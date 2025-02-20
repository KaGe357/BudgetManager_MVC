<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="/img/favicon.svg" type="image/png">
    <title>Budget Manager</title>
</head>

<body>
    <div class="container">
        <?php include __DIR__ . '/nav.php'; ?>

        <section class="text-center">
            <div class="loggin-success"></div>
            <img src="/img/logo budget manager.svg" class="py-5" id="img" width="100" alt="">
            <h2>Witaj, <?php echo htmlspecialchars($userName); ?>!</h2>
            <p>Co chcesz teraz zrobić?</p>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </div>
</body>

</html>