<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '1521');
define('DB_SERVICE_NAME', 'FREEPDB2');
define('DB_USERNAME', 'pdbadmin');
define('DB_PASSWORD', 'hikki25');
define('DB_CHARSET', 'AL32UTF8');

$connection_string = DB_HOST . ':' . DB_PORT . '/' . DB_SERVICE_NAME;

$conn = @oci_connect(
    DB_USERNAME,
    DB_PASSWORD,
    $connection_string,
    DB_CHARSET
);

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    die("Koneksi ke database Oracle gagal!");
}