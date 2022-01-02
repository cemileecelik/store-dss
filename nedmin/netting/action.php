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

if (isset($_POST['getMagazaPie'])) {
    $stmt = $db->prepare("CALL `toplam_magaza_satıs_adedi`(?)");
    $urun = $_POST['urunler'];
    $stmt->bindParam(1,  $urun, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);

    // call the stored procedure
    $stmt->execute();
    $return_value = $stmt->fetchAll();
    $stmt->closeCursor();
    for ($i = 0; $i < count($return_value); $i++) {
        $magaza = $return_value[$i]["magaza"];
        $adet = strval($return_value[$i]["adet"]);
        print_r("['$magaza', $adet],");
    }
}
