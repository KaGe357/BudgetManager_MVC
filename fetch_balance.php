<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user']['id'];


$configPath = __DIR__ . '/config/config.php';
if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'error' => "Błąd: Plik konfiguracyjny nie istnieje."]);
    exit();
}

$config = require $configPath;
if (!is_array($config) || !isset($config['host'], $config['user'], $config['password'], $config['dbname'])) {
    echo json_encode(['success' => false, 'error' => "Błąd: Plik konfiguracyjny nie zawiera wymaganych kluczy."]);
    exit();
}


$host = $config['host'];
$user = $config['user'];
$password = $config['password'];
$dbname = $config['dbname'];


$data = json_decode(file_get_contents('php://input'), true);
$startDate = $data['start_date'] ?? date('Y-m-01');
$endDate = $data['end_date'] ?? date('Y-m-t');


$connection = new mysqli($host, $user, $password, $dbname);
if ($connection->connect_errno) {
    error_log("Błąd połączenia z bazą danych: " . $connection->connect_error);
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit();
}


$incomeQuery = "
    SELECT icatu.name AS income_category_name, 
           COALESCE(SUM(i.amount), 0) AS total_incomes
    FROM incomes_category_assigned_to_users icatu
    LEFT JOIN incomes i 
           ON icatu.id = i.income_category_assigned_to_user_id
           AND i.user_id = ?
           AND i.date_of_income BETWEEN ? AND ?
    WHERE icatu.user_id = ?
    GROUP BY icatu.id, icatu.name
    ORDER BY icatu.name;
";

$stmt = $connection->prepare($incomeQuery);
if (!$stmt) {
    error_log("Błąd w zapytaniu dochodów: " . $connection->error);
    echo json_encode(['success' => false, 'error' => 'Error preparing income query.']);
    exit();
}
$stmt->bind_param('issi', $userId, $startDate, $endDate, $userId);
$stmt->execute();
$result = $stmt->get_result();
$incomes = $result->fetch_all(MYSQLI_ASSOC);
$totalIncome = array_sum(array_column($incomes, 'total_incomes'));
$stmt->close();


$expensesQuery = "
    SELECT ecatu.name AS expense_category_name, 
           COALESCE(SUM(e.amount), 0) AS total_expenses
    FROM expenses_category_assigned_to_users ecatu
    LEFT JOIN expenses e 
           ON ecatu.id = e.expense_category_assigned_to_user_id
           AND e.user_id = ?
           AND e.date_of_expense BETWEEN ? AND ?
    WHERE ecatu.user_id = ?
    GROUP BY ecatu.id, ecatu.name
    ORDER BY ecatu.name;
";

$stmt = $connection->prepare($expensesQuery);
if (!$stmt) {
    error_log("Błąd w zapytaniu wydatków: " . $connection->error);
    echo json_encode(['success' => false, 'error' => 'Error preparing expense query.']);
    exit();
}
$stmt->bind_param('issi', $userId, $startDate, $endDate, $userId);
$stmt->execute();
$result = $stmt->get_result();
$expenses = $result->fetch_all(MYSQLI_ASSOC);
$totalExpenses = array_sum(array_column($expenses, 'total_expenses'));
$stmt->close();


$balance = $totalIncome - $totalExpenses;
$response = [
    'success' => true,
    'totalBalance' => $balance,
    'incomes' => $incomes,
    'expenses' => $expenses,
];

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$connection->close();
