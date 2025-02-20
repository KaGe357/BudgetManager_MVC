<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager - Ustawienia</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
                    <ul class="list-group">
                        <?php foreach ($incomeCategories as $category): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($category['name']); ?>
                                <form method="POST" action="/settings/removeIncomeCategory" style="display:inline;">
                                    <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <form method="POST" action="/settings/addIncomeCategory" class="mt-3">
                        <input type="text" name="income_category_name" class="form-control mb-2" placeholder="Nowa kategoria" required>
                        <button type="submit" class="btn btn-secondary">Dodaj kategorię</button>
                    </form>
                </div>

                <!-- Wydatki -->
                <div class="col-md-6">
                    <h3>Wydatki</h3>
                    <ul class="list-group">
                        <?php foreach ($expenseCategories as $category): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($category['name']); ?>
                                <form method="POST" action="/settings/removeExpenseCategory" style="display:inline;">
                                    <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <form method="POST" action="/settings/addExpenseCategory" class="mt-3">
                        <input type="text" name="expense_category_name" class="form-control mb-2" placeholder="Nowa kategoria" required>
                        <button type="submit" class="btn btn-secondary">Dodaj kategorię</button>
                    </form>
                </div>
            </div>
        </section>

        <hr />


    </main>

    <script>
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