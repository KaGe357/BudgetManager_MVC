<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeBudget - Zarządzaj swoimi finansami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" href="./img/favicon.svg" type="image/png">

</head>

<body>
    <main>

        <section class="hero-section bg-light py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <img src="./img/logo budget manager.svg" class="rounded mb-3" width="120" alt="HomeBudget Logo">
                    <h1 class="display-5 fw-bold mb-3">HomeBudget</h1>
                    <p class="lead text-muted mb-4">
                        Pełna kontrola nad domowymi finansami - prosto, szybko, za darmo!
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="/login" class="btn btn-light btn-lg px-4">
                            Zaloguj się
                        </a>
                        <a href="/register" class="btn btn-warning btn-lg px-4 fw-semibold">
                            Zacznij za darmo→
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <section class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Główne korzyści</h2>
                <p class="text-muted">Kliknij na kafelek, aby zobaczyć podgląd</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm feature-card" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/dashboard.jpg" data-bs-title="Dashboard z wykresami">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="feature-icon mb-3">📊</div>
                            <h5 class="card-title fw-semibold mb-2">Wykresy finansowe</h5>
                            <p class="card-text text-muted small mb-0">Wizualizuj swoje wydatki</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm feature-card" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/bilans.jpg" data-bs-title="Bilans z limitami">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="feature-icon mb-3">💰</div>
                            <h5 class="card-title fw-semibold mb-2">Limity wydatków</h5>
                            <p class="card-text text-muted small mb-0">Kontroluj budżet automatycznie</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm feature-card" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/historia.jpg" data-bs-title="Historia transakcji">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="feature-icon mb-3">📜</div>
                            <h5 class="card-title fw-semibold mb-2">Historia transakcji</h5>
                            <p class="card-text text-muted small mb-0">Wszystko w jednym miejscu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm feature-card" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/kategorie.jpg" data-bs-title="Kategorie i ustawienia">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="feature-icon mb-3">⚙️</div>
                            <h5 class="card-title fw-semibold mb-2">Personalizacja</h5>
                            <p class="card-text text-muted small mb-0">Twoje kategorie, Twoje reguły</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal ze screenshotami -->
        <div class="modal fade" id="screenshotModal" tabindex="-1" aria-labelledby="screenshotModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="screenshotModalLabel">Screenshot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" id="modalImage" class="img-fluid" alt="Screenshot">
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Dynamiczne ładowanie obrazka do modala
        const modal = document.getElementById('screenshotModal');
        modal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const image = button.getAttribute('data-bs-image');
            const title = button.getAttribute('data-bs-title');

            modal.querySelector('#modalImage').src = image;
            modal.querySelector('.modal-title').textContent = title;
        });
    </script>
</body>

</html>