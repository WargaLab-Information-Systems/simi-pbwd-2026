<?php
$host = "localhost";
$user = "root";
$pass = "260607";
$db_name = "db_simi";
$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>