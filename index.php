<?php
require 'vendor/autoload.php';
require 'routes/web.php';
if (!file_exists('routes/web.php')) {
    die('Plik routes/web.php nie istnieje');
}
