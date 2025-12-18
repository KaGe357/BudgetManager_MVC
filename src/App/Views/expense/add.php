<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager - Dodaj Wydatek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="../../../../img/favicon.svg" type="image/png">
</head>

<body>
    <main>
        <div class="container">
            <?php include __DIR__ . '/../nav.php'; ?>

            <?php require_once __DIR__ . '/../alerts.php'; ?>

            <div class="d-flex justify-content-center border py-4 position-relative">
                <form action="/expense/save" method="POST" id="expenseForm">
                    <?= \App\Helpers\CsrfHelper::getTokenField(); ?>
                    <section>
                        <div class="mb-3 pt-4">
                            <label for="amountInput" class="form-label">Kwota</label>
                            <input type="number" class="form-control resize" id="amountInput" name="amount" step="0.01" placeholder="Wprowadź kwotę" required />
                        </div>
                        <div class="mb-3">
                            <label for="dateInput" class="form-label">Wybierz datę</label>
                            <input type="date" class="form-control resize" id="dateInput" name="date" value="<?= date('Y-m-d'); ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="categorySelect" class="form-label">Wybierz kategorię</label>
                            <select class="form-select resize" id="categorySelect" name="category_id" required>
                                <option value="" disabled selected>Wybierz...</option>
                                <?php foreach ($categories as $category): ?>
                                    <option
                                        value="<?= htmlspecialchars($category['id']); ?>"
                                        data-name="<?= htmlspecialchars($category['name']); ?>">
                                        <?= htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="methodSelect" class="form-label">Metoda płatności</label>
                            <select class="form-select resize" id="methodSelect" name="payment_method_id" required>
                                <option value="" disabled selected>Wybierz...</option>
                                <?php foreach ($paymentMethods as $method): ?>
                                    <option value="<?= htmlspecialchars($method['id']); ?>"><?= htmlspecialchars($method['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="commentTextarea" class="form-label">Komentarz (opcjonalnie)</label>
                            <textarea class="form-control resize" id="commentTextarea" name="comment" rows="3" placeholder="Wpisz swój komentarz tutaj..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Dodaj</button>
                            <a href="/home" class="btn btn-danger">Anuluj</a>
                        </div>
                    </section>
                </form>

                <div id="limitInfoCards" class="d-none position-absolute top-0 start-0 end-0 p-2" style="z-index: 999;">
                    <div class="d-flex gap-2">
                        <div id="limitCard" class="card text-center p-2 border-danger shadow-sm" style="width: 33.33%; visibility: hidden;">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <small class="text-muted mb-0">Limit:</small>
                                <strong class="text-danger mb-0" id="limitAmount">0.00 zł</strong>
                            </div>
                        </div>
                        <div id="afterCard" class="card text-center p-2 border-success shadow-sm" style="width: 33.33%;">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <small class="text-muted mb-0">Po dodaniu:</small>
                                <strong class="text-success mb-0" id="afterAmount">0.00 zł</strong>
                            </div>
                        </div>
                        <div id="exceededCard" class="card text-center p-2 border-danger shadow-sm" style="width: 33.33%; visibility: hidden;">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <small class="text-muted mb-0">Przekroczenie:</small>
                                <strong class="text-danger mb-0" id="exceededAmount">0.00 zł</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/js/limit.js"></script>
    <script>
        const amountInput = document.getElementById('amountInput');
        const categorySelect = document.getElementById('categorySelect');
        const limitInfoCards = document.getElementById('limitInfoCards');
        const limitAmount = document.getElementById('limitAmount');
        const afterAmount = document.getElementById('afterAmount');
        const exceededAmount = document.getElementById('exceededAmount');

        let currentLimit = 0;
        let currentSpent = 0;

        async function fetchLimitData() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                currentLimit = 0;
                currentSpent = 0;
                return;
            }

            const categoryName = selectedOption.getAttribute('data-name');
            const data = await getLimitForCategory(categoryName);

            if (data) {
                currentLimit = data.limit || 0;
                currentSpent = data.spent || 0;
            }

            checkLimit();
        }

        function checkLimit() {
            const amount = parseFloat(amountInput.value) || 0;
            const afterCard = document.getElementById('afterCard');
            const limitCard = document.getElementById('limitCard');
            const exceededCard = document.getElementById('exceededCard');

            const total = currentSpent + amount;

            // Zawsze pokazuj kafelek "Po dodaniu"
            afterAmount.textContent = `${total.toFixed(2)} zł`;

            if (currentLimit === 0) {
                // Brak limitu - ukryj kafelki, ale zachowaj ich miejsce
                limitCard.style.visibility = 'hidden';
                exceededCard.style.visibility = 'hidden';

                afterCard.classList.remove('border-warning');
                afterCard.classList.add('border-success');
                afterAmount.classList.remove('text-warning');
                afterAmount.classList.add('text-success');

                limitInfoCards.classList.remove('d-none');
                return;
            }

            // Kategoria ma limit
            limitCard.style.visibility = 'visible';
            const exceeded = total - currentLimit;

            limitAmount.textContent = `${currentLimit.toFixed(2)} zł`;

            if (exceeded > 0) {
                exceededAmount.textContent = `${exceeded.toFixed(2)} zł`;
                exceededCard.style.visibility = 'visible';

                afterCard.classList.remove('border-success');
                afterCard.classList.add('border-warning');
                afterAmount.classList.remove('text-success');
                afterAmount.classList.add('text-warning');
            } else {
                exceededCard.style.visibility = 'hidden';

                afterCard.classList.remove('border-warning');
                afterCard.classList.add('border-success');
                afterAmount.classList.remove('text-warning');
                afterAmount.classList.add('text-success');
            }

            limitInfoCards.classList.remove('d-none');
        }

        amountInput.addEventListener('input', checkLimit);
        categorySelect.addEventListener('change', fetchLimitData);
    </script>
</body>

</html>