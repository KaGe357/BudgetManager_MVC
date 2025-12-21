<?php

namespace App\Services;

use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use GuzzleHttp\Client as GuzzleClient;
use Exception;

class GeminiService
{
    private Client $client;
    private string $model;

    public function __construct()
    {

        $apiKey = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY');

        if (empty($apiKey)) {
            throw new Exception('GEMINI_API_KEY nie jest ustawiony w pliku .env');
        }

        // Konfiguracja klienta HTTP
        $httpClient = new GuzzleClient([
            'verify' => true, // Weryfikacja certyfikatów SSL (produkcja)
            'timeout' => 60
        ]);

        $this->client = new Client($apiKey, $httpClient);
        $this->model = 'gemini-2.5-flash-lite';
    }


    public function generateFinancialAdvice(array $balanceData, int $months, string $userName = ''): string
    {
        $prompt = $this->buildPrompt($balanceData, $months, $userName);

        try {
            $response = $this->client
                ->generativeModel($this->model)
                ->generateContent(new TextPart($prompt));

            return $response->text();
        } catch (Exception $e) {
            error_log("Gemini API Error: " . $e->getMessage());
            throw new Exception('Nie udało się wygenerować rady. Spróbuj ponownie później.');
        }
    }


    private function buildPrompt(array $balanceData, int $months, string $userName = ''): string
    {
        $incomes = $balanceData['incomes'];
        $expenses = $balanceData['expenses'];
        $totalIncome = $balanceData['totalIncome'];
        $totalExpenses = $balanceData['totalExpenses'];
        $balance = $balanceData['balance'];

        $greeting = !empty($userName) ? "Drogi/a {$userName}" : "Drogi Użytkowniku";
        $monthsLabel = ($months === 1) ? "miesiąca" : "miesięcy";

        // Buduj sekcję przychodów
        $incomesSection = '';
        if (!empty($incomes)) {
            $incomesSection .= "PRZYCHODY (według kategorii):\n";
            foreach ($incomes as $income) {
                $incomesSection .= "- {$income['income_category_name']}: " . number_format($income['total_incomes'], 2, ',', ' ') . " PLN\n";
            }
            $incomesSection .= "\n";
        }

        // Buduj sekcję wydatków
        $expensesSection = '';
        if (!empty($expenses)) {
            $expensesSection .= "WYDATKI (według kategorii):\n";
            foreach ($expenses as $expense) {
                $percent = ($totalExpenses > 0) ? ($expense['total_expenses'] / $totalExpenses * 100) : 0;
                $expensesSection .= "- {$expense['expense_category_name']}: " . number_format($expense['total_expenses'], 2, ',', ' ') . " PLN";
                $expensesSection .= " (" . number_format($percent, 1) . "%)\n";
            }
            $expensesSection .= "\n";
        }

        // Wczytaj szablon z pliku
        $templatePath = __DIR__ . '/../../../prompts/financial_advice.txt';
        if (!file_exists($templatePath)) {
            throw new Exception("Nie znaleziono pliku szablonu promptu: {$templatePath}");
        }

        $template = file_get_contents($templatePath);

        // Podstaw wartości do szablonu
        $prompt = str_replace(
            ['{months}', '{months_label}', '{total_income}', '{total_expenses}', '{balance}', '{incomes_section}', '{expenses_section}', '{greeting}'],
            [
                $months,
                $monthsLabel,
                number_format($totalIncome, 2, ',', ' '),
                number_format($totalExpenses, 2, ',', ' '),
                number_format($balance, 2, ',', ' '),
                $incomesSection,
                $expensesSection,
                $greeting
            ],
            $template
        );

        return $prompt;
    }


    public function chat(string $userMessage, array $history = []): string
    {
        try {
            $chatSession = $this->client
                ->generativeModel($this->model)
                ->startChat();

            // Jeśli jest historia, dodaj ją
            if (!empty($history)) {
                foreach ($history as $message) {
                    $chatSession->sendMessage(new TextPart($message['content']));
                }
            }

            $response = $chatSession->sendMessage(new TextPart($userMessage));
            return $response->text();
        } catch (Exception $e) {
            error_log("Gemini Chat Error: " . $e->getMessage());
            throw new Exception('Nie udało się przetworzyć wiadomości.');
        }
    }
}
