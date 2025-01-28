<?php

session_start();
include "../../service/database.php";
include "dashboard_izin.html";


if (isset($_COOKIE["cookie_token"])) {
    $cookie_token = $_COOKIE["cookie_token"];
    $sql = "SELECT * FROM admin WHERE remember_token = '$cookie_token'";
    $result = $db->query($sql);
    $data = $result->fetch_assoc();

    $_SESSION['id_karyawan'] = $data['id_admin'];
}

if (isset($_POST['submit'])) {

        $izin = $_POST['jenis_izin'];
        if ($izin == "sakit") {
            
            $username = $_SESSION['username'];
            $date = $_POST['date'];
            $id_karyawan = $_SESSION['id_karyawan'];
            $jenis_izin = "sakit";
            
            include("popup.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";

            
        } else if ($izin == "izin_lainnya") {
            $username = $_SESSION['username'];
            $date = $_POST['date'];
            $id_karyawan = $_SESSION['id_karyawan'];
            $jenis_izin = "izin lainnya";

            include("popup.html");
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
        }

        $sql = "INSERT INTO izin (id_karyawan, nama_karyawan, jenis_izin, tanggal_masuk) values
        ('$id_karyawan', '$username', '$jenis_izin', '$date')";
        $result = $db->query($sql);
}


?>