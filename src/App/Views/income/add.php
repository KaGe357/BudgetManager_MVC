<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Budget Manager - Dodaj dochód</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/style.css" />
    <link rel="icon" href="./img/favicon.svg" type="image/png">
</head>

<body>
    <main>
        <div class="container">
            <?php include __DIR__ . '/../nav.php'; ?>

            <div class="d-flex justify-content-center border py-4">
                <form action="/income/save" method="POST">
                    <section>
                        <div class="mb-3">
                            <label for="amountInput" class="form-label">Kwota</label>
                            <input
                                type="number"
                                class="form-control resize"
                                id="amountInput"
                                name="amount"
                                step="0.01"
                                placeholder="Wprowadź kwotę"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="dateInput" class="form-label">Wybierz datę</label>
                            <input
                                type="date"
                                class="form-control resize"
                                id="dateInput"
                                name="date"
                                value="<?= date('Y-m-d'); ?>"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="categorySelect" class="form-label">Wybierz kategorię</label>
                            <select class="form-select resize" id="categorySelect" name="category_id" required>
                                <option value="" disabled selected>Wybierz...</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="commentTextarea" class="form-label">Komentarz (opcjonalnie)</label>
                            <textarea
                                class="form-control resize"
                                id="commentTextarea"
                                name="comment"
                                rows="3"
                                placeholder="Wpisz swój komentarz tutaj..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Dodaj</button>
                            <a href="/home" class="btn btn-danger">Anuluj</a>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>