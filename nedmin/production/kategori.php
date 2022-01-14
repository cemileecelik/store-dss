<?php

include 'header.php';
require_once '../netting/connection.php';


$urunsor = $db->prepare("Select * From kategoriler");
$urunsor->execute();
?>
<div class="right_col" role="main">
  <div>
    <p style="color: red; font-size: 20px;"></p>

    <form action="" method="POST">
    <p style="color: black; font-size: 20px;"> Kategorilere Ait Ürünlerin Yüzdelikleri</p>

      <div class="form-group row">
        <label for="kategoriler" class="col-sm-1 col-form-label"><h4>Ürün Seçiniz :</h4></label>
        <div class="col-sm-6">
          <select class="form-control" name="kategoriler" id="kategoriler">
            <?php
            while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <option value=<?php echo $uruncek['kategori_id']  ?>> <?php echo $uruncek['kategori_ad']  ?></option>
            <?php  } ?>
          </select>
        </div>
      </div>
      <button name="getKategoriPie" type="submit" class="btn btn-primary btn-lg">Bul</button>
      <div id="piechart" style="width: 900px; height: 500px;"></div>
      <div id="chart_div" style="max-width: 1600px; height: 500px;"></div>

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        <?php
        if (isset($_POST['getKategoriPie'])) {
          $stmt = $db->prepare("CALL `kategorilere_gore_satislar`(?)");
          $urun = $_POST['kategoriler'];
          $stmt->bindParam(1,  $urun, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);

          $stmt->execute();
          $return_value = $stmt->fetchAll();
          $stmt->closeCursor();
          //var_dump($return_value);
        ?>
          google.charts.load('current', {
            'packages': ['corechart']
          });
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Kategori', 'Adet'],
              <?php
              for ($i = 0; $i < count($return_value); $i++) {
                $urun = $return_value[$i]["urun"];
                $kategori = $return_value[$i]["kategori"];
                $adet = strval($return_value[$i]["adet"]);
                print_r("['$urun', $adet],");
              }
              ?>
            ]);

            var options = {
              title: <?php print("'$urun'"); ?>,
              backgroundColor: "#f7f7f7",
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
          }

        <?php
        }
        ?>
      </script>

<div id="chart_div"></div>
    </div>
</div>




<?php
include 'footer.php';

?>