<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" href="./img/favicon.svg" type="image/png">

</head>

<body>
    <main>
        <header>
            <div class="container-fluid fs-4">
                <div class="main-page">
                    <h1 class="text-center oxanium p-1"><img src="./img/logo budget manager.svg" id="img" class="logo" width="100" alt="Logo">
                        Kontroluj swoje finanse z łatwością - Planuj, oszczędzaj, osiągaj cele!</h1>
                </div>
        </header>

        <section class="text-center pt-2">
            <a href="/login" class="btn btn-secondary mx-1">
                Zaloguj
            </a>
            <a href="/register" class="btn btn-secondary mx-1">
                Zarejestruj
            </a>
        </section>

        <section class="container mt-5">
            <h2 class="text-center mb-2">Główne funkcje</h2>
            <p class="text-center text-muted mb-4"><small>Kliknij na kafelek, aby zobaczyć podgląd</small></p>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="p-3 feature-tile" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/dashboard.jpg" data-bs-title="Dashboard z wykresami">
                        <h5>📊 Wykresy</h5>
                        <p class="text-muted small">Wizualizacja finansów</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 feature-tile" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/bilans.jpg" data-bs-title="Bilans z limitami">
                        <h5>💰 Limity</h5>
                        <p class="text-muted small">Kontrola wydatków</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 feature-tile" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/historia.jpg" data-bs-title="Historia transakcji">
                        <h5>📜 Historia</h5>
                        <p class="text-muted small">Paginowane transakcje</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 feature-tile" role="button" data-bs-toggle="modal" data-bs-target="#screenshotModal" data-bs-image="./img/kategorie.jpg" data-bs-title="Kategorie i ustawienia">
                        <h5>⚙️ Kategorie</h5>
                        <p class="text-muted small">Personalizacja ustawień</p>
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