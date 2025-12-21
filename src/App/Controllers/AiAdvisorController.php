<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Models\AiAdvisorModel;
use App\Models\BalanceModel;
use App\Services\GeminiService;
use Exception;

class AiAdvisorController
{

    public function generateAdvice()
    {
        header('Content-Type: application/json');
        SessionHelper::start();

        // Sprawdź autoryzację
        if (!SessionHelper::has('user')) {
            echo json_encode([
                'success' => false,
                'error' => 'Musisz być zalogowany.'
            ]);
            exit();
        }

        $user = SessionHelper::get('user');
        $userId = $user['id'];

        $input = json_decode(file_get_contents('php://input'), true);
        $months = (int) ($input['months'] ?? 1);


        if (!in_array($months, [1, 3, 6, 12])) {
            echo json_encode([
                'success' => false,
                'error' => 'Nieprawidłowa liczba miesięcy.'
            ]);
            exit();
        }

        $aiModel = new AiAdvisorModel();

        // Sprawdź rate limit (8 godzin między generacjami)
        if (!$aiModel->canGenerateAdvice($userId)) {
            $hoursToWait = $aiModel->getHoursUntilNextGeneration($userId);
            $minutesToWait = round($hoursToWait * 60);

            echo json_encode([
                'success' => false,
                'error' => "Możesz wygenerować następną radę za {$hoursToWait} godz. (około {$minutesToWait} minut). Rate limit: 1 rada na 8 godzin.",
                'hoursToWait' => $hoursToWait
            ]);
            exit();
        }

        try {
            // Oblicz daty
            $startDate = date('Y-m-d', strtotime("-{$months} months"));
            $endDate = date('Y-m-d');

            // Użyj istniejącego BalanceModel zamiast duplikować kod
            $balanceModel = new BalanceModel();
            $incomes = $balanceModel->getIncomeCategories($userId, $startDate, $endDate);
            $expenses = $balanceModel->getExpenseCategories($userId, $startDate, $endDate);

            $totalIncome = array_sum(array_column($incomes, 'total_incomes'));
            $totalExpenses = array_sum(array_column($expenses, 'total_expenses'));
            $balance = $totalIncome - $totalExpenses;

            // Sprawdź czy użytkownik ma dane
            if ($totalIncome == 0 && $totalExpenses == 0) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Brak danych do analizy. Dodaj najpierw przychody i wydatki.'
                ]);
                exit();
            }

            // Przygotuj dane do analizy
            $balanceData = [
                'incomes' => $incomes,
                'expenses' => $expenses,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'balance' => $balance
            ];

            // Wygeneruj radę przez Gemini AI
            $geminiService = new GeminiService();
            $userName = $user['name'] ?? '';
            $adviceText = $geminiService->generateFinancialAdvice($balanceData, $months, $userName);

            // Zapisz radę w bazie
            $aiModel->saveAdvice($userId, $adviceText, $months, $balanceData);

            // Zwróć sukces
            echo json_encode([
                'success' => true,
                'advice' => $adviceText,
                'balanceData' => [
                    'totalIncome' => $totalIncome,
                    'totalExpenses' => $totalExpenses,
                    'balance' => $balance
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            error_log("Error in AiAdvisorController::generateAdvice: " . $e->getMessage());

            echo json_encode([
                'success' => false,
                'error' => 'Wystąpił błąd: ' . $e->getMessage()
            ]);
        }
    }

    public function getLatestAdvice()
    {
        header('Content-Type: application/json');
        SessionHelper::start();

        // Sprawdź autoryzację
        if (!SessionHelper::has('user')) {
            echo json_encode([
                'success' => false,
                'error' => 'Musisz być zalogowany.'
            ]);
            exit();
        }

        $user = SessionHelper::get('user');
        $userId = $user['id'];

        try {
            $aiModel = new AiAdvisorModel();
            $latestAdvice = $aiModel->getLatestAdvice($userId);

            if ($latestAdvice) {
                echo json_encode([
                    'success' => true,
                    'advice' => $latestAdvice['advice_text'],
                    'months' => $latestAdvice['months_analyzed'],
                    'balanceData' => $latestAdvice['balance_snapshot'],
                    'createdAt' => $latestAdvice['created_at']
                ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Brak zapisanych rad.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error in AiAdvisorController::getLatestAdvice: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => 'Wystąpił błąd podczas pobierania rady.'
            ]);
        }
    }
}
