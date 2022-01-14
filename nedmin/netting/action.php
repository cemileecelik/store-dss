<?php
ob_start();
session_start();

include 'connection.php';

if (isset($_POST['adminlogin'])) {

    // Kullanıcından gelen bilgieleri değişkene alma 
    $admin_mail = htmlspecialchars($_POST['admin_mail']);
    $admin_password = md5($_POST['admin_password']);

    $kullanicisor = $db->prepare("select * from admin where admin_mail=:admin_mail and admin_password=:admin_password");
    $kullanicisor->execute(array(
        'admin_mail' => $admin_mail,
        'admin_password' => $admin_password,
    ));

    $say = $kullanicisor->rowCount();
    if ($say == 1) {

        echo $_SESSION['admin_mail'] = $admin_mail;
        header("Location:../production/index.php");
        exit;
    } else {
        header("Location:../../?durum=basarisizgiris");
       
    }
}

if (isset($_POST['adminregister'])) {

    $admin_adsoyad = htmlspecialchars($_POST['admin_adsoyad']);
    $admin_mail = htmlspecialchars($_POST['admin_mail']);
    $admin_password = $_POST['admin_password'];

    $kullanicisor = $db->prepare("select * from admin where admin_mail=:admin_mail");
    $kullanicisor->execute(array(
        'admin_mail' => $admin_mail
    ));

    $say = $kullanicisor->rowCount();

    if ($say == 0) {

        $password = md5($admin_password);

        $kullanicikaydet = $db->prepare("INSERT INTO admin(admin_adsoyad,admin_mail,admin_password) VALUES(:admin_adsoyad,:admin_mail,:admin_password)");

        $insert = $kullanicikaydet->execute(array(
            'admin_adsoyad' => $admin_adsoyad,
            'admin_mail' => $admin_mail,
            'admin_password' => $password,
        ));

        if ($insert) {


            header("Location:../production/login.php?durum=kayitbasarili");
        } else {


            header("Location:../../register.php?durum=basarisiz");
        }
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
