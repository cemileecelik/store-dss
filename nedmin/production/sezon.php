<?php
include 'header.php';
require_once '../netting/connection.php';

$sezonsor = $db->prepare("Select * From sezon");
$sezonsor->execute();
?>

<div class="right_col" role="main">
  <p style="color: black; font-size: 20px;"> Sezon ve Yıla Göre</p>

  <form action="" method="POST">
    <div class="form-group row">
      <label for="sezon" class="col-sm-1 col-form-label">
        <h4>Sezon Seçiniz :</h4>
      </label>
      <div class="col-sm-2">
        <select class="form-control" name="sezon" id="sezon">
          <?php
          while ($sezoncek = $sezonsor->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <option value=<?php echo $sezoncek['sezon_id']  ?>> <?php echo $sezoncek['sezon_ad']  ?></option>
          <?php  } ?>
        </select>
      </div>
      <label for="yil" class="col-sm-1 col-form-label">
        <h4>Yıl Seçiniz :</h4>
      </label>
      <div class="col-sm-2">
        <select class="form-control" name="yil" id="yil">

          <option value="2020">2020</option>
          <option value="2021">2021</option>

        </select>
      </div>
    </div>

    <button name="getSezonTable" type="submit" class="btn btn-primary btn-lg">Bul</button>

    <div class="x_content">
      <table id="datatable" class="table table-striped table-bordered">
        <thead>
          <?php



          if (isset($_POST['getSezonTable'])) {
            $stmt = $db->prepare("CALL `sezon_yila_gore_satis_aciklamasi`(?, ?)");
            $sezon = $_POST['sezon'];
            $yil = $_POST['yil'];
            $stmt->bindParam(1,  $sezon, PDO::PARAM_INT);
            $stmt->bindParam(2,  $yil, PDO::PARAM_INT);
            // call the stored procedure
            $stmt->execute();

            $return_value = $stmt->fetchAll();
            $stmt->closeCursor();
          }
          ?>

          <tr>
            <th>Ürün Adı</th>
            <th>Adet</th>
            <th>Detay</th>
          </tr>
        </thead>
        <?php
        if (isset($return_value) && $return_value !== null) {
          for ($i = 0; $i < count($return_value); $i++) {
            $adet = $return_value[$i]["adet"];
            $urun_ad = $return_value[$i]["urun_ad"];
            $detay = $return_value[$i]["detay"];
        ?>
            <tbody>

              <tr>
                <td> <?php print_r([$urun_ad][0]);   ?> </td>

                <td> <?php print_r([$adet][0]);   ?> </td>

                <td> <?php print_r([$detay][0]);   ?> </td>
              </tr>
          <?php
          }
        }
          ?>
            </tbody>
      </table>
    </div>
  
</div>

<?php
include 'footer.php';
?>