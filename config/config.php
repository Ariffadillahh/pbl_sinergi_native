<?php
require_once __DIR__ . '/vendor/autoload.php';

define('BASEURL', 'http://localhost/sinergi');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// define('BASEURL', 'https://798vjd5k-80.asse.devtunnels.ms/sinergi');