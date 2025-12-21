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

                    <!-- Przycisk AI Advisor -->
                    <div class="mt-3 mb-2">
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#aiAdvisorModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16">
                                <path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z" />
                            </svg>
                            Poproś e-doradcę o analizę
                        </button>
                    </div>
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

    <!-- Modal AI Advisor -->
    <div class="modal fade" id="aiAdvisorModal" tabindex="-1" aria-labelledby="aiAdvisorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="aiAdvisorModalLabel">
                        💡 Doradca Finansowy AI
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Wybór okresu + przycisk generowania -->
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-7">
                            <label for="aiMonthsSelect" class="form-label">Analizuj dane za ostatnie:</label>
                            <select id="aiMonthsSelect" class="form-select">
                                <option value="1">1 miesiąc</option>
                                <option value="3" selected>3 miesiące</option>
                                <option value="6">6 miesięcy</option>
                                <option value="12">12 miesięcy</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <button type="button" id="generateAiAdvice" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16">
                                    <path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z" />
                                </svg>
                                <span id="generateBtnText">Wygeneruj radę</span>
                            </button>
                        </div>
                    </div>

                    <!-- Error -->
                    <div id="aiError" class="alert alert-danger alert-dismissible d-none mb-3" role="alert">
                        <span id="aiErrorText"></span>
                        <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('aiError').classList.add('d-none')"></button>
                    </div>

                    <!-- Loading dots -->
                    <div id="aiLoading" class="text-center d-none my-4">
                        <div class="ai-loading">
                            <span class="ai-loading-icon">💼</span>
                            <div class="ai-loading-text">
                                AI analizuje Twoje finanse
                                <span class="ai-dot">.</span>
                                <span class="ai-dot">.</span>
                                <span class="ai-dot">.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Odpowiedź AI -->
                    <div id="aiResponse" class="d-none">
                        <div class="alert alert-info">
                            <h6>📊 Podsumowanie:</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <strong>Przychody:</strong><br>
                                    <span id="aiIncome">-</span> zł
                                </div>
                                <div class="col-4">
                                    <strong>Wydatki:</strong><br>
                                    <span id="aiExpenses">-</span> zł
                                </div>
                                <div class="col-4">
                                    <strong>Bilans:</strong><br>
                                    <span id="aiBalance">-</span> zł
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">🤖 Rada od AI:</h6>
                                <div id="aiAdviceText" style="white-space: pre-wrap; line-height: 1.6;"></div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3 mb-0">
                            <small><strong>⚠️ Uwaga:</strong> To rada AI. Nie zastępuje konsultacji z doradcą finansowym.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/balance-script.js"></script>

    <script>
        // Formatowanie markdown - bold, lista, nowe linie
        function formatMarkdown(text) {
            return text
                // Bold: **text** -> <strong>text</strong>
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                // Italic: *text* -> <em>text</em>
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                // Lista: "* " na początku linii -> <li>
                .split('\n').map(line => {
                    if (line.trim().startsWith('* ')) {
                        return '<li>' + line.trim().substring(2) + '</li>';
                    }
                    return line;
                }).join('\n')
                // Wrap listy w <ul>
                .replace(/((<li>.*<\/li>\n?)+)/g, '<ul class="mt-2 mb-2">$1</ul>')
                // Nowe linie -> <br>
                .replace(/\n/g, '<br>');
        }

        // AI Advisor - AJAX
        document.getElementById('generateAiAdvice').addEventListener('click', function() {
            const btn = this;
            const months = document.getElementById('aiMonthsSelect').value;
            const loading = document.getElementById('aiLoading');
            const response = document.getElementById('aiResponse');
            const error = document.getElementById('aiError');

            // Reset błędu (ale NIE rady - może już być wyświetlona)
            error.classList.add('d-none');
            loading.classList.remove('d-none');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generowanie...';

            // AJAX request
            fetch('/api/ai/advice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        months: parseInt(months)
                    })
                })
                .then(res => res.json())
                .then(data => {
                    loading.classList.add('d-none');
                    btn.disabled = false;

                    if (data.success) {
                        // Zmień przycisk na "Odśwież radę"
                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16"><path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z"/></svg> <span id="generateBtnText">Odśwież radę</span>';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');

                        // Pokaż odpowiedź
                        response.classList.remove('d-none');
                        document.getElementById('aiIncome').textContent = data.balanceData.totalIncome.toLocaleString('pl-PL', {
                            minimumFractionDigits: 2
                        });
                        document.getElementById('aiExpenses').textContent = data.balanceData.totalExpenses.toLocaleString('pl-PL', {
                            minimumFractionDigits: 2
                        });
                        document.getElementById('aiBalance').textContent = data.balanceData.balance.toLocaleString('pl-PL', {
                            minimumFractionDigits: 2
                        });

                        // Formatuj markdown w radzie AI
                        document.getElementById('aiAdviceText').innerHTML = formatMarkdown(data.advice);
                    } else {
                        // Błąd - przywróć oryginalny tekst przycisku
                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16"><path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z"/></svg> <span id="generateBtnText">Wygeneruj radę</span>';

                        // Pokaż błąd (rada nadal widoczna jeśli była)
                        error.classList.remove('d-none');
                        document.getElementById('aiErrorText').textContent = data.error || 'Wystąpił nieznany błąd';

                        // Auto-hide po 8 sekundach
                        setTimeout(() => {
                            error.classList.add('d-none');
                        }, 8000);
                    }
                })
                .catch(err => {
                    loading.classList.add('d-none');
                    error.classList.remove('d-none');
                    document.getElementById('aiErrorText').textContent = 'Błąd połączenia z serwerem: ' + err.message;
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16"><path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.0 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z"/></svg> Wygeneruj radę';

                    // Auto-hide po 8 sekundach
                    setTimeout(() => {
                        error.classList.add('d-none');
                    }, 8000);
                });
        });

        // Załaduj ostatnią radę przy otwarciu modalu
        const aiModal = document.getElementById('aiAdvisorModal');
        aiModal.addEventListener('shown.bs.modal', function() {
            // Sprawdź czy rada już nie jest wyświetlona
            const adviceText = document.getElementById('aiAdviceText');
            if (adviceText.innerHTML.trim() === '') {
                // Załaduj ostatnią radę z bazy
                fetch('/api/ai/advice/latest')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const response = document.getElementById('aiResponse');
                            response.classList.remove('d-none');

                            document.getElementById('aiIncome').textContent = data.balanceData.totalIncome.toLocaleString('pl-PL', {
                                minimumFractionDigits: 2
                            });
                            document.getElementById('aiExpenses').textContent = data.balanceData.totalExpenses.toLocaleString('pl-PL', {
                                minimumFractionDigits: 2
                            });
                            document.getElementById('aiBalance').textContent = data.balanceData.balance.toLocaleString('pl-PL', {
                                minimumFractionDigits: 2
                            });

                            // Formatuj markdown
                            document.getElementById('aiAdviceText').innerHTML = formatMarkdown(data.advice);

                            // Ustaw odpowiedni okres w select
                            document.getElementById('aiMonthsSelect').value = data.months;

                            // Zmień tekst przycisku na "Odśwież radę"
                            const btn = document.getElementById('generateAiAdvice');
                            const btnText = document.getElementById('generateBtnText');
                            btnText.textContent = 'Odśwież radę';
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-outline-primary');
                        }
                    })
                    .catch(err => {
                        console.log('Brak zapisanej rady lub błąd:', err);
                    });
            }
        });
    </script>

</body>

</html>