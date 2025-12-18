<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager - Ustawienia</title>
    <link rel="stylesheet" href="/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

    <main class="container">
        <?php include __DIR__ . '/nav.php'; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <hr />
        <section>
            <h2>Ustawienia konta</h2>
            <form id="accountSettingsForm" style="max-width: 250px;">
                <div class="mb-3">
                    <button type="button" id="changeUserName" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeUserNameModal">
                        Zmiana nazwy użytkownika
                    </button>
                </div>

                <div class="mb-3">
                    <button type="button" id="changePassword" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        Zmiana hasła
                    </button>
                </div>

                <div class="mb-3">
                    <button type="button" id="deleteAccount" class="btn btn-danger">Usuń konto</button>
                </div>
            </form>
        </section>

        <!-- Modal: Zmiana nazwy użytkownika -->
        <div class="modal fade" id="changeUserNameModal" tabindex="-1" aria-labelledby="changeUserNameModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeUserNameModalLabel">Zmiana nazwy użytkownika</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/settings/changeUserName">
                        <div class="modal-body">
                            <?= \App\Helpers\CsrfHelper::getTokenField(); ?>
                            <label for="newUserName" class="form-label">Nowa nazwa użytkownika</label>
                            <input type="text" name="newUserName" id="newUserName" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Zmiana hasła -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Zmiana hasła</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/settings/changePassword" onsubmit="return validatePassword()">
                        <div class="modal-body">
                            <?= \App\Helpers\CsrfHelper::getTokenField(); ?>
                            <label for="currentPassword" class="form-label">Obecne hasło</label>
                            <input type="password" name="currentPassword" id="currentPassword" class="form-control" required>

                            <label for="newPassword" class="form-label mt-3">Nowe hasło</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control" required>

                            <label for="confirmPassword" class="form-label mt-3">Potwierdź nowe hasło</label>
                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>

                            <div id="passwordError" class="text-danger mt-2" style="display: none;">Hasła nie są identyczne!</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Zmień hasło</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal: Usunięcie konta -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAccountModalLabel">Potwierdzenie usunięcia konta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Czy na pewno chcesz usunąć swoje konto? Tej operacji nie można cofnąć!</p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="/settings/deleteAccount">
                            <?= \App\Helpers\CsrfHelper::getTokenField(); ?>
                            <button type="submit" class="btn btn-danger">Usuń</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    </div>
                </div>
            </div>
        </div>

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

        document.getElementById("deleteAccount").addEventListener("click", function() {
            let deleteModal = new bootstrap.Modal(document.getElementById("deleteAccountModal"));
            deleteModal.show();
        });
    </script>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</html>