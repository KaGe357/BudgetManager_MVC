<?php

namespace App\Models;

use App\Database\DatabaseConnection;
use PDO;
use PDOException;
use Exception;

class AiAdvisorModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    // Sprawdzić czy użytkownik może wygenerować radę (rate limit: 3 generacje/24h)

    public function canGenerateAdvice(int $userId): bool
    {
        try {
            // Sprawdź ostatnią radę
            $stmt = $this->db->prepare("
                SELECT updated_at 
                FROM ai_advices 
                WHERE user_id = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            $lastAdvice = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lastAdvice) {
                // Brak rad - można wygenerować
                return true;
            }

            // Sprawdź ile czasu minęło od ostatniej generacji
            $lastUpdate = new \DateTime($lastAdvice['updated_at']);
            $now = new \DateTime();
            $hoursSinceUpdate = ($now->getTimestamp() - $lastUpdate->getTimestamp()) / 3600;

            // Rate limit: minimum 8 godzin między generacjami (3 razy dziennie)
            return $hoursSinceUpdate >= 8;
        } catch (PDOException $e) {
            error_log("Error in canGenerateAdvice: " . $e->getMessage());
            return false;
        }
    }

    // Pobiera czas do następnej możliwej generacji (w godzinach)

    public function getHoursUntilNextGeneration(int $userId): float
    {
        try {
            $stmt = $this->db->prepare("
                SELECT updated_at 
                FROM ai_advices 
                WHERE user_id = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            $lastAdvice = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lastAdvice) {
                return 0;
            }

            $lastUpdate = new \DateTime($lastAdvice['updated_at']);
            $now = new \DateTime();
            $hoursSinceUpdate = ($now->getTimestamp() - $lastUpdate->getTimestamp()) / 3600;

            $waitTime = max(0, 8 - $hoursSinceUpdate);
            return round($waitTime, 1);
        } catch (PDOException $e) {
            error_log("Error in getHoursUntilNextGeneration: " . $e->getMessage());
            return 0;
        }
    }


    // Zapisuje lub aktualizuje radę AI dla użytkownika

    public function saveAdvice(int $userId, string $adviceText, int $months, array $balanceSnapshot): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO ai_advices 
                    (user_id, advice_text, months_analyzed, balance_snapshot, created_at, updated_at)
                VALUES 
                    (:userId, :adviceText, :months, :balanceSnapshot, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    advice_text = VALUES(advice_text),
                    months_analyzed = VALUES(months_analyzed),
                    balance_snapshot = VALUES(balance_snapshot),
                    updated_at = NOW()
            ");

            return $stmt->execute([
                ':userId' => $userId,
                ':adviceText' => $adviceText,
                ':months' => $months,
                ':balanceSnapshot' => json_encode($balanceSnapshot, JSON_UNESCAPED_UNICODE)
            ]);
        } catch (PDOException $e) {
            error_log("Database error in saveAdvice: " . $e->getMessage());
            throw new Exception("Nie udało się zapisać rady.");
        }
    }


    // Pobiera ostatnią radę użytkownika

    public function getLatestAdvice(int $userId): ?array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id,
                    advice_text,
                    months_analyzed,
                    balance_snapshot,
                    created_at,
                    updated_at
                FROM ai_advices
                WHERE user_id = :userId
                LIMIT 1
            ");

            $stmt->execute([':userId' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $result['balance_snapshot'] = json_decode($result['balance_snapshot'], true);
                return $result;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Database error in getLatestAdvice: " . $e->getMessage());
            return null;
        }
    }
}
