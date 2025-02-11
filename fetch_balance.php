<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}
// Dane do bazy - naprawić by czytało z pliku konfiguracyjnego;
$host = 'localhost';
$db_name = 'budgetmanager';
$db_user = 'root';
$db_password = '';



$userId = $_SESSION['user']['id'];
$data = json_decode(file_get_contents('php://input'), true);

$startDate = $data['start_date'] ?? date('Y-m-01');
$endDate = $data['end_date'] ?? date('Y-m-t');

$connection = new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit();
}

$incomeQuery = "
    SELECT icatu.name AS income_category_name, 
           COALESCE(SUM(i.amount), 0) AS total_incomes
    FROM incomes_category_assigned_to_users icatu
    INNER JOIN incomes i 
           ON icatu.id = i.income_category_assigned_to_user_id
           AND i.date_of_income BETWEEN ? AND ?
           AND i.user_id = ?
    WHERE icatu.user_id = ?
    GROUP BY icatu.name
    ORDER BY icatu.name;
";

$stmt = $connection->prepare($incomeQuery);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error preparing income query.']);
    exit();
}
$stmt->bind_param('ssii', $startDate, $endDate, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$incomes = [];
$totalIncome = 0;

while ($row = $result->fetch_assoc()) {
    $incomes[] = $row;
    $totalIncome += floatval($row['total_incomes']);
}

$expensesQuery = "
    SELECT ecatu.name AS expense_category_name, 
           COALESCE(SUM(e.amount), 0) AS total_expenses
    FROM expenses_category_assigned_to_users ecatu
    INNER JOIN expenses e 
           ON ecatu.id = e.expense_category_assigned_to_user_id
           AND e.date_of_expense BETWEEN ? AND ?
           AND e.user_id = ?
    WHERE ecatu.user_id = ?
    GROUP BY ecatu.name
    ORDER BY ecatu.name;
";

$stmt = $connection->prepare($expensesQuery);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error preparing expense query.']);
    exit();
}
$stmt->bind_param('ssii', $startDate, $endDate, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$expenses = [];
$totalExpenses = 0;

while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
    $totalExpenses += floatval($row['total_expenses']);
}

$balance = $totalIncome - $totalExpenses;

echo json_encode([
    'success' => true,
    'totalBalance' => $balance,
    'incomes' => $incomes,
    'expenses' => $expenses,
]);

$stmt->close();
$connection->close();
