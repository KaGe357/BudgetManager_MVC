<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager - Ustawienia</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

    <main class="container">
        <?php include __DIR__ . '/nav.php'; ?>
        <?php if (App\Helpers\SessionHelper::has('success')): ?>
            <div class="alert alert-success"><?= App\Helpers\SessionHelper::get('success'); ?></div>
        <?php endif; ?>

        <?php if (App\Helpers\SessionHelper::has('error')): ?>
            <div class="alert alert-danger"><?= App\Helpers\SessionHelper::get('error'); ?></div>
        <?php endif; ?>


        <header>
            <h1 class="text-center my-4">Ustawienia</h1>
        </header>
        <section>
            <h2>Zarządzanie kategoriami</h2>
            <div class="row">
                <!-- Dochody -->
                <div class="col-md-6">
                    <h3>Dochody</h3>

                    <form method="POST" action="/settings/addIncomeCategory" class="mb-3">
                        <input type="text" name="income_category_name" class="form-control mb-2" placeholder="Nowa kategoria" required>
                        <button type="submit" class="btn btn-primary">Dodaj kategorię</button>
                    </form>

                    <ul class="list-group">
                        <?php
                        $newCategoryId = App\Helpers\SessionHelper::get('new_category_id');
                        foreach ($incomeCategories as $category):
                            $isNew = ($newCategoryId && $category['id'] == $newCategoryId);
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center <?= $isNew ? 'bg-success bg-opacity-25' : '' ?>">
                                <?= htmlspecialchars($category['name']); ?>
                                <form method="POST" action="/settings/removeIncomeCategory" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tę kategorię?');">
                                    <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>


                <!-- Wydatki -->
                <div class="col-md-6">
                    <h3>Wydatki</h3>

                    <form method="POST" action="/settings/addExpenseCategory" class="mb-3">
                        <input type="text" name="expense_category_name" class="form-control mb-2" placeholder="Nowa kategoria" required>
                        <button type="submit" class="btn btn-primary">Dodaj kategorię</button>
                    </form>

                    <ul class="list-group">
                        <?php
                        foreach ($expenseCategories as $category):
                            $isNew = ($newCategoryId && $category['id'] == $newCategoryId);
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center <?= $isNew ? 'bg-success bg-opacity-25' : '' ?>">
                                <span><?= htmlspecialchars($category['name']); ?></span>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($category['spending_limit']) && $category['spending_limit'] > 0): ?>
                                        <small class="text-muted">Limit: <?= number_format($category['spending_limit'], 2, ',', ' '); ?> zł</small>
                                    <?php endif; ?>
                                    <button class="btn btn-warning btn-sm limit-btn" data-bs-toggle="modal" data-bs-target="#limitModal"
                                        data-category-id="<?= $category['id']; ?>"
                                        data-category-name="<?= htmlspecialchars($category['name']); ?>">
                                        Limit
                                    </button>
                                    <form method="POST" action="/settings/removeExpenseCategory" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tę kategorię?');">
                                        <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <?php
        // Wyczyść ID nowej kategorii po wyświetleniu
        if (App\Helpers\SessionHelper::has('new_category_id')) {
            App\Helpers\SessionHelper::remove('new_category_id');
        }
        ?>

        <hr />

    </main>

    <!-- Modal ustawiania limitu -->
    <div class="modal fade" id="limitModal" tabindex="-1" aria-labelledby="limitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="limitModalLabel">Ustaw limit dla: <span id="categoryName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="/settings/updateCategoryLimit">
                    <div class="modal-body">
                        <input type="hidden" name="category_id" id="categoryId">

                        <label class="form-label">Miesięczny limit wydatków</label>
                        <div class="input-group mb-3">
                            <input type="number" name="limit" id="limitInput" class="form-control"
                                placeholder="np. 500" step="0.01" min="0">
                            <span class="input-group-text">zł</span>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-lightbulb"></i> Otrzymasz ostrzeżenie gdy przekroczysz ten limit przy dodawaniu wydatku.
                                Zostaw puste aby usunąć limit.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-warning">Zapisz limit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/limit.js"></script>
    <script>
        // Obsługa modala - pobieranie limitu z API
        const limitModal = document.getElementById('limitModal');
        limitModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            handleLimitButtonClick(button);
        });

        function validatePassword() {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            const errorDiv = document.getElementById("passwordError");

            if (newPassword !== confirmPassword) {
                errorDiv.style.display = "block";
                return false; // Zatrzymanie wysyłania formularza
            }

            errorDiv.style.display = "none";
            return true; // Pozwala na wysłanie formularza
        }
    </script>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</html>