<?php

define('DB_HOST', 'localhost');
define('DB_PORT', '1521');
define('DB_SERVICE_NAME', 'FREEPDB1');
define('DB_USERNAME', 'SINERGI');
define('DB_PASSWORD', 'arif123');
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


// $db_host = "localhost";
// $db_user = "root";
// $db_pass = "";
// $db_name = "pegawai";

// $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// if (mysqli_connect_errno()) {
//     echo "Koneksi database gagal : " . mysqli_connect_error();
// }
