<?php
session_start(); // Upewnij się, że sesja jest uruchomiona
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetManager - załóż darmowe konto!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto justify-content-center">
                        <li class="nav-item"><a href="./" class="nav-link active px-1" aria-current="page">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house mx-2" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                                </svg>Strona główna</a></li>
                        <li class="nav-item"><a href="./login" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-door-open mx-2" viewBox="0 0 16 16">
                                    <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                                    <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5M4 1.934V15h6V1.077z" />
                                </svg>Zaloguj</a></li>
                        <li class="nav-item"><a href="./register" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square mx-2" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>Rejestracja</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="form-signin w-100 m-auto center">
        <div class="container bordered p-5">
            <h1 class="fs-4">Zarejestruj się</h1>
            <form action="/register" method="POST">
                Imię: <br> <input type="text" class="form-control" id="name" value="<?php
                                                                                    if (isset($_SESSION['fr_name'])) {
                                                                                        echo $_SESSION['fr_name'];
                                                                                        unset($_SESSION['fr_name']);
                                                                                    }
                                                                                    ?>" name="name">

                <?php
                if (isset($_SESSION['e_name'])) {
                    echo '<div class="error">' . $_SESSION['e_name'] . '</div>';
                    unset($_SESSION['e_name']);
                }
                ?>

                E-mail: <br> <input type="email" class="form-control" id="email" value="<?php
                                                                                        if (isset($_SESSION['fr_email'])) {
                                                                                            echo $_SESSION['fr_email'];
                                                                                            unset($_SESSION['fr_email']);
                                                                                        }
                                                                                        ?>" name="email">
                <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                    unset($_SESSION['e_email']);
                }
                ?>

                Hasło: <br> <input type="password" class="form-control" id="haslo1" value="<?php
                                                                                            if (isset($_SESSION['fr_haslo1'])) {
                                                                                                echo $_SESSION['fr_haslo1'];
                                                                                                unset($_SESSION['fr_haslo1']);
                                                                                            }
                                                                                            ?>" name="haslo1">
                <?php
                if (isset($_SESSION['e_haslo'])) {
                    echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
                    unset($_SESSION['e_haslo']);
                }
                ?>

                Powtórz hasło: <br> <input type="password" class="form-control" id="haslo2" value="<?php
                                                                                                    if (isset($_SESSION['fr_haslo2'])) {
                                                                                                        echo $_SESSION['fr_haslo2'];
                                                                                                        unset($_SESSION['fr_haslo2']);
                                                                                                    }
                                                                                                    ?>" name="haslo2">

                <label>
                    <input type="checkbox" name="regulamin" <?php
                                                            if (isset($_SESSION['fr_regulamin'])) {
                                                                echo "checked";
                                                                unset($_SESSION["fr_regulamin"]);
                                                            }
                                                            ?>> Akceptuję regulamin
                </label><br>
                <?php if (isset($_SESSION['e_regulamin'])): ?>
                    <div class="error"><?php echo $_SESSION['e_regulamin']; ?></div>
                    <?php unset($_SESSION['e_regulamin']); ?>
                <?php endif; ?>




                <input type="submit" value="Zarejestruj się">
            </form>
        </div>

    </main>
    <!-- <script src="./register.js"></script> -->
</body>

</html>