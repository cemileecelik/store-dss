<?php
include 'header.php';
require_once '../netting/connection.php';

$urunsor = $db->prepare("Select * From urunler");
$urunsor->execute();
?>



<!-- page content -->
<div class="right_col" role="main">
  <div class="">


    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Yönetici Paneli <small>Hoşgeldiniz!</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>

              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div class="x_content">
              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Fiyat</th>
                  </tr>
         </thead>

                <tbody>
                  
                <?php   
                while ($uruncek = $urunsor->fetch(PDO::FETCH_ASSOC)) {
                  
                ?>
                        <tr>
                          <td><?php echo $uruncek['urun_ad'] ?></td>
                          <td><?php echo $uruncek['kategori_id'] ?></td>
                          <td><?php echo $uruncek['fiyat'] ?></td>
                        </tr>
                </tbody>

<?php } ?>

              </table>
            </div>
          </div>
        </div>

        <!-- Bitiyor -->




      </div>
    </div>
  </div>
  <!-- /page content -->

  <?php
  include 'footer.php';
  ?>