<?php

require_once '../vendor/autoload.php';
require_once '../routes/web.php';

use App\Controllers\PageController;

$pageController = new PageController();
$pageController->index();
