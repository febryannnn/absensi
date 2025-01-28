<?php

$hostname = "localhost";
$username ="root";
$password = "";
$database_name = "data_karyawan";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
echo "error woy";
die("error woyy");
}
?>