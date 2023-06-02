<?php

use App\Helpers\AppHelper;

define('ROOT_DIR', __DIR__);

require_once __DIR__ . '/autoloader.php';

AppHelper::getInstance()->enableDebugMode();

AppHelper::getInstance()->sessionStart();

require_once 'routers/main.php';