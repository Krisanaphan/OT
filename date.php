<!DOCTYPE html>
<html lang="en">

<?php
  include "pages/head.php";
  include "dist/css/style.css";
?>

<body class="hold-transition">
  <div class="wrapper"> <!-- แถบเลื่อน -->
    <?php
      include "pages/nav.php";
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">PDF</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">สร้าง PDF</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

    <!-- /.card-header -->
    <div class="row">
      <div class="col-md-2">
      
      </div>
      <div class="col-md-8">
        <div class="card card-in">
          <div class="card-header">
            <h3 class="card-title">ระบุเดือนและปี</h3>
          </div>
          <form method="POST" action="report75.php">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>เดือน</label>
                    <select class="form-control select2" style="width: 100%;" name="txt_month">
                      <option selected="selected" value="">เดือน</option>
                        <?php
                          $month = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
                          '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', 
                          '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน ', 
                          '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');

                          $txtMonth = isset($_POST['txt_month']) && $_POST['txt_month'] != '' ? $_POST['txt_month'] : date('m');
              
                          foreach($month as $i=>$mName) {
                            $selected = '';
                              if($txtMonth == $i) $selected = 'selected="selected"';
                                echo '<option value="'.$i.'" '.$selected.'>'. $mName .'</option>'."\n";
                          }
                        ?>
                    </select>
                  </div>
                  <!-- /.form-group -->
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ปี</label>
                    <select class="form-control select2" style="width: 100%;" name="txt_year">
                      <option selected="selected" value="">ปี</option>
                        <?php
                          $txtYear = (isset($_POST['txt_year']) && $_POST['txt_year'] != '') ? $_POST['txt_year'] : date('Y');
                          $yearStart = date('Y');
                          $yearEnd = $txtYear-3;

                          for($year=$yearStart;$year > $yearEnd;$year--){
                            $selected = '';
                              if($txtYear == $year) $selected = 'selected="selected"';
                                echo '<option value="'.$year.'" '.$selected.'>'. ($year+543) .'</option>'."\n";
                          }
                        ?>
                    </select>
                  </div>
                  <!-- /.form-group -->
                </div>
              </div>
            </div>  
            <!-- /.card-footer -->
            <div class="card-footer">
              <button type="submit" name="submit" class="btn btn-block btn-outline-info" onclick="return confirm('คุณต้องการข้อมูลในรูปแบบ PDF')">ตกลง</button>
            </div>
          </form>
        </div>  
      </div>
      <div class="col-md-2">

      </div>
    </div>
  
  </div>
  <!-- ./wrapper -->

  <?php
    include "pages/footer.php";
    include "pages/cradit.php";
  ?>

  </body>
</html>
