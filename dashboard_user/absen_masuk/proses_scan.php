<?php

session_start();
include "dashboard_enter_code.html";
include "../../service/database.php";
// include "../../dashboard/dashboard_qr/dashboard_qr_be.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $_SESSION['username'];
$id_karyawan = $_SESSION['id_karyawan']; 
$date = date('Y-m-d');

function generateAbsenCode() {
    $today = date('Y-m-d');
    $seed = crc32($today);
    mt_srand($seed);
    
    $numbers = [];
    while (count($numbers) < 6) {
        $number = mt_rand(0, 9);
        if (!in_array($number, $numbers)) {
            $numbers[] = $number;
        }
    }
    
    return implode('', $numbers);
}


$kode = generateAbsenCode();

if (isset($_POST['button_qr']) || isset($_POST['button_code'])) {

    if (isset($_POST['button_qr'])) {
        $kode_input_qr = $_POST['hasil_scan'];

        if ($kode == $kode_input_qr) {
            $sql = "INSERT INTO absensi (id_karyawan, nama_karyawan, tanggal_masuk) 
            values ('$id_karyawan', '$username', '$date')";
            $result = $db->query($sql);
            
            include("popup_success.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
            
        } else {
            include("popup_failed.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
        }
    } else if (isset($_POST["button_code"])) {
        $kode_input_manual = $_POST['enter_code'];
        if ($kode == $kode_input_manual) {
            $sql = "INSERT INTO absensi (id_karyawan, nama_karyawan, tanggal_masuk) 
            values ('$id_karyawan', '$username', '$date')";
            $result = $db->query($sql);
            
            include("popup_success.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
            
        } else {
            include("popup_failed.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
            
        }
    } 
}
?>
