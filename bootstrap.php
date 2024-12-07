<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__, 'myconfig.env');
$dotenv->load();

use Kocas\Onlinestore\Db;


?>