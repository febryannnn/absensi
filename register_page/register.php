<?php
 include "register.html";
//  include "register_final.html";
 include "../service/database.php";
 
 if (isset($_POST['btn'])) {
     $username = $_POST['username'];
     $password = $_POST['password'];
     $id_karyawan = $_POST['id_karyawan'];
     
     $sql = "SELECT username_admin FROM admin WHERE username_admin = '$username'";
     $result = mysqli_query($db, $sql);
     if (mysqli_num_rows($result) > 0) { 
        include "../popup/popup_akun_ada.html";
        // $data = mysqli_fetch_array($result);
        // echo "akun ada";
        echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
    } else {

        if ($_POST['password'] == $_POST['re-password']) {
            $sql_insert = "INSERT INTO admin (id_admin, username_admin, password_admin) VALUES ('$id_karyawan','$username','$password')";
            $sql_insert2 = "INSERT INTO karyawan (id_karyawan, nama_karyawan) VALUES ('$id_karyawan','$username')";
            $result_insert = mysqli_query($db, $sql_insert);
            $result_insert2 = mysqli_query($db, $sql_insert2);
            include "popup_login.html";
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";

        } else {
            include "popup.html";
            echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
        }

    }
 }

?>