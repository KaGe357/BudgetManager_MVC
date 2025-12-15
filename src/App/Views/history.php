<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Transakcji - Budget Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="mb-4">Historia Transakcji</h2>

                <?php require_once __DIR__ . '/alerts.php'; ?>

                <?php if (empty($transactions)): ?>
                    <div class="alert alert-info">
                        Brak transakcji do wyświetlenia.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Kategoria</th>
                                    <th>Kwota</th>
                                    <th>Typ</th>
                                    <th>Komentarz</th>
                                    <th>Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                                        <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                                        <td class="<?php echo $transaction['type'] === 'income' ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo $transaction['type'] === 'income' ? '+' : '-'; ?>
                                            <?php echo number_format($transaction['amount'], 2, ',', ' '); ?> zł
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $transaction['type'] === 'income' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $transaction['type'] === 'income' ? 'Przychód' : 'Wydatek'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($transaction['comment'] ?: '-'); ?></td>
                                        <td>
                                            <?php if ($transaction['type'] === 'expense'): ?>
                                                <form method="POST" action="/history/expense/delete" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wydatek?');">
                                                    <input type="hidden" name="expense_id" value="<?php echo (int)$transaction['id']; ?>">
                                                    <input type="hidden" name="page" value="<?php echo (int)$page; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Usuń</button>
                                                </form>
                                            <?php else: ?>
                                                <form method="POST" action="/history/income/delete" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przychód?');">
                                                    <input type="hidden" name="income_id" value="<?php echo (int)$transaction['id']; ?>">
                                                    <input type="hidden" name="page" value="<?php echo (int)$page; ?>">
                                                    <button type="submit" class="btn btn-outline-success btn-sm">Usuń</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginacja -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Nawigacja stronicowania">
                            <ul class="pagination justify-content-center">
                                <!-- Przycisk Poprzednia -->
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Poprzednia</a>
                                </li>

                                <!-- Numery stron -->
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);

                                if ($startPage > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                                <?php endif; ?>

                                <!-- Przycisk Następna -->
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Następna</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="/balance" class="btn btn-secondary">Powrót do Bilansu</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>