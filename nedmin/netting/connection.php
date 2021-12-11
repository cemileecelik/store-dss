<?php

try {

    $db = new PDO("mysql:host=localhost;dbname=giyim_kds;charset=utf8", 'root', '');

} catch (PDOException $e) {

    echo $e->getMessage();
}
