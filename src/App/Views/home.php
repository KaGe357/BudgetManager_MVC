<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="/img/favicon.svg" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Budget Manager</title>
</head>

<body>
    <div class="container">
        <?php include __DIR__ . '/nav.php'; ?>

        <section class="text-center my-4">
            <h2>Witaj, <?php echo htmlspecialchars($userName); ?>!</h2>
            <p class="text-muted">Twój przegląd finansowy za <?php
                                                                $months = ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'];
                                                                echo $months[date('n') - 1] . ' ' . date('Y');
                                                                ?></p>
        </section>

        <!-- Alerty o przekroczonych limitach -->
        <?php if (!empty($exceededLimits)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>⚠️ Uwaga!</strong> Przekroczyłeś limity w kategoriach:
                <ul class="mb-0 mt-2">
                    <?php foreach ($exceededLimits as $limit): ?>
                        <li><strong><?= htmlspecialchars($limit['name']); ?></strong>:
                            <?= number_format($limit['total_spent'], 2); ?> / <?= number_format($limit['spending_limit'], 2); ?> zł
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Karty z podsumowaniem -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <h6 class="card-title text-muted mb-2">Bilans</h6>
                        <h4 class="mb-0 <?= $balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?= number_format($balance, 2); ?> zł
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <h6 class="card-title text-muted mb-2">Przychody</h6>
                        <h4 class="mb-0 text-success">
                            +<?= number_format($totalIncomes, 2); ?> zł
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <h6 class="card-title text-muted mb-2">Wydatki</h6>
                        <h4 class="mb-0 text-danger">
                            -<?= number_format($totalExpenses, 2); ?> zł
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wykresy -->
        <div class="row g-4 mb-4 justify-content-center">
            <!-- Wykres porównawczy -->
            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Przychody vs Wydatki</h5>
                        <div style="max-width: 300px; margin: 0 auto;">
                            <canvas id="compareChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wykres wydatków po kategoriach -->
            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Wydatki po kategoriach</h5>
                        <div style="max-width: 300px; margin: 0 auto;">
                            <canvas id="expensesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Wykres porównawczy Przychody vs Wydatki
            const compareCtx = document.getElementById('compareChart').getContext('2d');
            new Chart(compareCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Przychody', 'Wydatki'],
                    datasets: [{
                        data: [<?= $totalIncomes; ?>, <?= $totalExpenses; ?>],
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Wykres wydatków po kategoriach
            <?php if (!empty($expenseCategories)): ?>
                const expensesCtx = document.getElementById('expensesChart').getContext('2d');
                new Chart(expensesCtx, {
                    type: 'pie',
                    data: {
                        labels: <?= json_encode(array_column($expenseCategories, 'category_name')); ?>,
                        datasets: [{
                            data: <?= json_encode(array_column($expenseCategories, 'total')); ?>,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            <?php else: ?>
                document.getElementById('expensesChart').parentElement.innerHTML = '<p class="text-center text-muted">Brak wydatków w tym miesiącu</p>';
            <?php endif; ?>
        </script>
    </div>
</body>

</html>