<?php

include 'header.php';
require_once '../netting/connection.php';


$urunsor = $db->prepare("Select * From urunler");
$urunsor->execute();
?>

<div class="right_col" role="main">
  <div>
    <p style="color: red; font-size: 20px;"> Lütfen Ürün Seçiniz.</p>

    <form action="" method="POST">
      <div class="form-group row">
        <label for="urunler" class="col-sm-1 col-form-label"><h4>Ürün Seçiniz :</h4></label>
        <div class="col-sm-6">
          <select class="form-control" name="urunler" id="urunler">
            <?php
            while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <option value=<?php echo $uruncek['urun_id']  ?>> <?php echo $uruncek['urun_ad']  ?></option>
            <?php  } ?>
          </select>
        </div>
      </div>
      <button name="getMagazaPie" type="submit" class="btn btn-primary btn-lg">Bul</button>
      <div id="piechart" style="width: 900px; height: 500px;"></div>
      <div id="chart_div" style="max-width: 1600px; height: 500px;"></div>

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        <?php
        if (isset($_POST['getMagazaPie'])) {
          $stmt = $db->prepare("CALL `toplam_magaza_satıs_adedi`(?)");
          $urun = $_POST['urunler'];
          $stmt->bindParam(1,  $urun, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);

          // call the stored procedure
          $stmt->execute();
          $return_value = $stmt->fetchAll();
          $stmt->closeCursor();
          
        ?>
          google.charts.load('current', {
            'packages': ['corechart']
          });
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Mağaza', 'Adet'],
              <?php
              for ($i = 0; $i < count($return_value); $i++) {
                $urun = $return_value[$i]["urun"];
                $magaza = $return_value[$i]["magaza"];
                $adet = strval($return_value[$i]["adet"]);
                print_r("['$magaza', $adet],");
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
      <script type="text/javascript">
        google.charts.load('current', {
          'packages': ['corechart', 'bar']
        });
        google.charts.setOnLoadCallback(drawStuff);
        <?php
        $toplamcek = $db->prepare("CALL `urun_toplam_urun_satis`()");
        $toplamcek->execute();
        $toplamSatis = $toplamcek->fetchAll();
        $toplamcek->closeCursor();
        ?>

        function drawStuff() {
          var chartDiv = document.getElementById('chart_div');
          var data = google.visualization.arrayToDataTable([
            ['Ürün', 'Ankara', 'İzmir'],
            <?php
            for ($i = 0; $i <= count($toplamSatis) / 2 - 1; $i++) {
              $urun = $toplamSatis[$i]["urun"];
              $ankara = strval($toplamSatis[$i]["adet"]);
              $izmir = strval($toplamSatis[$i + count($toplamSatis) / 2]["adet"]);

              print_r("['$urun', $ankara, $izmir],");
            }
            ?>

          ]);

          var materialOptions = {
            backgroundColor: "#f7f7f7",
            chart: {
              title: 'Mağazalara Göre Ürünler',
            },
            chartArea: {
              backgroundColor: "#f7f7f7",
            }
          };

          var materialChart = new google.charts.Bar(chartDiv);
          materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
          drawMaterialChart();
        };
      </script>
  </div>
</div>




<?php
include 'footer.php';

?>