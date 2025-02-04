<?php

require_once '../vendor/autoload.php';

use App\Helpers\SessionHelper;

SessionHelper::start();

require_once '../routes/web.php';
