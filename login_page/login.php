<?php

session_start();

include "../service/database.php";
include "login.html";

if (isset($_COOKIE['cookie_token'])) {
  $cookie_token = $_COOKIE['cookie_token'];

  $sql = "SELECT * FROM admin WHERE remember_token = '$cookie_token'";
  $result = $db->query($sql);
  
  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $_SESSION["username"] = $data["username_admin"];
    $_SESSION["is_login"] = true;
    $_SESSION["id_karyawan"] = $data["id_admin"];
  }
}

if (isset($_SESSION["username"])) {
  header("Location:../dashboard/dashboard.php");
  exit();
}

if (isset($_POST['btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? $_POST['remember_me'] : 0;
    
    $sql_name = "SELECT * FROM admin WHERE username_admin = '$username'";

    $result_name = $db->query($sql_name);

    if ($result_name->num_rows > 0) {
      $data = $result_name->fetch_assoc();
      
      if ($password == $data['password_admin']) {
        $_SESSION["username"] = $data["username_admin"];
        $_SESSION["is_login"] = true;
        if ($remember_me == 1) {
          $token_value = bin2hex(random_bytes(16));
          $cookie_time = time() + (60 * 60 * 24 * 30);
          setcookie("cookie_token", $token_value, $cookie_time, "/");

          $sql_update = "UPDATE admin SET remember_token = '$token_value' WHERE username_admin = '$username'";
          $db->query($sql_update);
        }

        if ($username != "admin") {
          header("Location:../dashboard_user/dashboard_user.php");
          exit();
        } else {
          header("Location:../dashboard/dashboard.php");
          exit();
        }

      } else {
        include("../popup/popup_password_salah.html");
        echo "<script>document.getElementById('popupModal').style.display = 'inline-block';</script>";
        // echo"pass salah";
      }
      
    } else {
      include("../popup/popup.html");
      echo "<script>document.getElementById('popupModal').style.display = 'block';</script>";
    }
}
?>