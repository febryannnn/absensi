<?php

session_start();
include "../service/database.php";

// print_r($_SESSION);
// print_r($_COOKIE);

if (isset($_COOKIE["cookie_token"])) {
    $cookie_token = $_COOKIE["cookie_token"];
    $sql_select = "SELECT * FROM admin where remember_token = '$cookie_token'";
    $result = mysqli_query($db, $sql_select);

    if ($result->num_rows > 0) {
        $data = mysqli_fetch_array($result);
        $_SESSION["username"] = $data["username_admin"];
        // echo "berhasil";
    }
    // $_SESSION["username"] = $_COOKIE["cookie_username"];
    // echo "cookie ada";
} else {
    // echo "cookie ga ada";
}

if ($_SESSION['username'] != "admin") {
    header("Location:../dashboard_user/dashboard_user.php");
}

if (!isset($_SESSION["username"])) {
    // echo "akun belum masuk";
    header("Location:../login_page/login.php");
} else {
    // echo "akun ada";
}

if (isset($_POST['logout'])) {
    session_destroy();
    $token_value = "";
    $cookie_time = time() - (60 * 60 * 24 * 30);
    setcookie("cookie_token", $token_value, $cookie_time, "/");

    header("Location:../login_page/login.php");
}

include "dashboard_final.html";
// include "dashboard.html";

?>



