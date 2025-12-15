<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager - Bilans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./img/favicon.svg" type="image/png">
</head>

<body>
    <main class="container">
        <?php include 'nav.php'; ?>


        <section class="my-5">
            <h2 class="text-center">Bilans</h2>

            <div class="text-center my-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#balanceModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-calendar mx-2" viewBox="0 0 16 16">
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 1 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>Wybierz zakres dat
                </button>
            </div>

            <div class="text-center my-4">
                <a id="goToHistoryButton" href="/history" class="btn btn-primary">Historia transakcji</a>
            </div>

            <div class="modal fade" id="balanceModal" tabindex="-1" aria-labelledby="balanceModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="balanceModalLabel">Wybierz zakres dat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">


                            <div class="mb-3">
                                <label for="dateRangeSelect" class="form-label">Wybierz zakres dat</label>
                                <select id="dateRangeSelect" class="form-select">
                                    <option value="">Wybierz zakres</option>
                                    <option value="thisMonth">Bieżący miesiąc</option>
                                    <option value="lastMonth">Poprzedni miesiąc</option>
                                    <option value="allTime">Wszystkie</option>
                                    <option value="custom">Niestandardowy</option>
                                </select>
                            </div>


                            <div class="mb-3" id="customStartDateRange" style="display: none;">
                                <label for="startDateInput" class="form-label">Data początkowa</label>
                                <input type="date" id="startDateInput" class="form-control">
                            </div>
                            <div class="mb-3" id="customEndDateRange" style="display: none;">
                                <label for="endDateInput" class="form-label">Data końcowa</label>
                                <input type="date" id="endDateInput" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="applyDateRange">Zastosuj</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center flex-column bordered">
                    <p class="fs-3">Twój bilans:</p>
                    <p class="fs-3"><span id="balance"
                            class="text-center"><?php echo number_format($balance, 2, ',', ' ') . " zł"; ?></span></p>
                    <br>
                    <p class="fs-3"><span id="text">
                            <?php echo $balance > 0
                                ? "Świetnie zarządzasz swoimi finansami!"
                                : ($balance < 0
                                    ? "Twój bilans jest na minusie: " . abs($balance) . " zł"
                                    : "Bilans wynosi zero."); ?>
                        </span></p>
                </div>

                <!-- Dochody -->
                <div class="col-md-6 d-flex align-items-center flex-column bordered">
                    <h4><br>Dochody</h4>
                    <table class="table table-bordered table-incomes">
                        <thead>
                            <tr>
                                <th scope="col">Kategoria</th>
                                <th scope="col">Kwota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incomeCategories as $category): ?>
                                <?php if ($category['total_incomes'] > 0): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['income_category_name']); ?></td>
                                        <td class="fw-bold">
                                            <?php echo number_format($category['total_incomes'], 2, ',', ' ') . " PLN"; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Wydatki -->
                <div class="col-md-6 d-flex align-items-center flex-column bordered">
                    <br>
                    <h4>Wydatki</h4>
                    <table class="table table-bordered table-expenses">
                        <thead>
                            <tr>
                                <th scope="col">Kategoria</th>
                                <th scope="col">Kwota</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($expenseCategories as $category): ?>
                                <?php if ($category['total_expenses'] > 0): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['expense_category_name']); ?></td>
                                        <td class="fw-bold">
                                            <?php echo number_format($category['total_expenses'], 2, ',', ' ') . " PLN"; ?>
                                        </td>
                                    </tr>
                                    <?php if (isset($category['spending_limit']) && $category['spending_limit'] > 0): ?>
                                        <tr>
                                            <td colspan="2">
                                                <?php
                                                $spent = $category['total_expenses'];
                                                $limit = $category['spending_limit'];
                                                $percentage = $limit > 0 ? min(100, ($spent / $limit) * 100) : 0;
                                                $progressColor = $percentage >= 100 ? 'bg-danger' : ($percentage >= 80 ? 'bg-warning' : 'bg-success');
                                                ?>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar <?= $progressColor; ?>" role="progressbar"
                                                        style="width: <?= $percentage; ?>%"
                                                        aria-valuenow="<?= $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= number_format($spent, 2); ?> / <?= number_format($limit, 2); ?> PLN (<?= round($percentage); ?>%)
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/balance-script.js"></script>

</body>

</html>