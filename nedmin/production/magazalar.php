<?php
include 'header.php';
require_once '../netting/connection.php';

$sezonsor = $db->prepare("Select * From sezon");
$sezonsor->execute();

$urunsor = $db->prepare("Select * From urunler");
$urunsor->execute();
?>

<div class="right_col" role="main">
    <p style="color: black; font-size: 20px;"> Filtreleri Seçiniz:</p>

    <form action="" method="POST">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    <label for="urunler">
                        <h4>Ürün Seçiniz:</h4>
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="urunler" id="urunler">
                        <?php
                        while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value=<?php echo $uruncek['urun_id']  ?>> <?php echo $uruncek['urun_ad']  ?></option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="sezon">
                        <h4>Sezon Seçiniz:</h4>
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="sezon" id="sezon">
                        <?php
                        while ($sezoncek = $sezonsor->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value=<?php echo $sezoncek['sezon_id']  ?>> <?php echo $sezoncek['sezon_ad']  ?></option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="yil" class="col-form-label">
                        <h2>Yıl Seçiniz:</h2>
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="yil" id="yil">
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                    </select>
                </div>

                <div class="col-sm-2">
                    <label for="magaza" class="col-form-label">
                        <h2>Mağaza Seçiniz:</h2>
                    </label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="magaza" id="magaza">
                        <option value="1">Ankara</option>
                        <option value="2">izmir</option>

                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <label for="maks" class="col-form-label">
                        <h4>Max Satış:</h4>
                    </label>
                </div>
                <div class="col-sm-4">
                    <input name="maks" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                </div>

                <div class="col-sm-2">
                    <label for="minimum" class="col-form-label">
                        <h4>Min Satış:</h4>
                    </label>
                </div>
                <div class="col-sm-4">
                    <input name="minimum" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                </div>
            </div>
        </div>

        <button name="getDataTable" type="submit" class="btn btn-primary btn-lg float-right">Bul</button>
        <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <?php

                    if (isset($_POST['getDataTable'])) {
                        $stmt = $db->prepare("CALL `magaza_sezon_yıla_gore_satislar`(?,?,?,?,?,?)");
                        $sezon = $_POST['sezon'];
                        $yil = $_POST['yil'];
                        $magaza = $_POST['magaza'];
                        $maks = $_POST['maks'];
                        $minimum = $_POST['minimum'];
                        $urun = $_POST['urunler'];

                        $stmt->bindParam(1,  $sezon, PDO::PARAM_INT);
                        $stmt->bindParam(2,  $yil, PDO::PARAM_INT);
                        $stmt->bindParam(3,  $magaza, PDO::PARAM_INT);
                        $stmt->bindParam(4,  $maks, PDO::PARAM_INT);
                        $stmt->bindParam(5,  $minimum, PDO::PARAM_INT);
                        $stmt->bindParam(6,  $urun, PDO::PARAM_INT);
                        $stmt->execute();

                        $return_value = $stmt->fetchAll();
                        $stmt->closeCursor();
                    }
                    ?>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Mağaza</th>
                        <th>Adet</th>
                        <th>Detay</th>


                    </tr>
                </thead>
                <?php
                if (isset($return_value) && $return_value !== null) {

                    for ($i = 0; $i < count($return_value); $i++) {
                        $adet = $return_value[$i]["adet"];
                        $magaza = $return_value[$i]["magaza"];
                        $urun_ad = $return_value[$i]["urun"];
                        $detay = $return_value[$i]["detay"];
                    }
                }
                ?>
                <tbody>

                    <tr>
                        <td> <?php print_r(isset($urun_ad) ? [$urun_ad][0] : null); ?> </td>
                        <td> <?php print_r(isset($magaza) ? [$magaza][0] : null); ?> </td>
                        <td> <?php print_r(isset($adet) ? [$adet][0] : null); ?> </td>
                        <td> <?php print_r(isset($detay) ? [$detay][0] : null); ?> </td>

                    </tr>
                </tbody>
            </table>
        </div>
      
</div>

<?php

include 'footer.php';

?>