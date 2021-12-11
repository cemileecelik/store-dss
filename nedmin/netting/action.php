<?php
ob_start();
session_start();

include 'connection.php';

if (isset($_POST['adminlogin'])) {

    echo $admin_mail = $_POST['admin_mail'];
    echo $admin_password = md5($_POST['admin_password']);

    if ($admin_mail == "deneme@d.com" && $admin_password == "25d55ad283aa400af464c76d713c07ad") {

        header("Location:../production/index.php");
    } else {
        header("Location:../production/login.php?durum=no");
    }
}
