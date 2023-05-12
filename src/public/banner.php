<?php

use app\Handlers\BannerHandler;

require dirname(__DIR__) . '/init.php';
$config = require dirname(__DIR__) . '/config.php';

$app = new \app\App($config);
$app->setHandler(new BannerHandler())
    ->handle();




