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

  <?php
    date_default_timezone_set("Asia/Bangkok");
    function DateThai($strDate) {
      $strYear = date("Y",strtotime($strDate))+543;
      $strMonth= date("n",strtotime($strDate));
      $strDay= date("d",strtotime($strDate));
      $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
      
      $strMonthThai=$strMonthCut[$strMonth];

      return "$strDay"."&nbsp;"."$strMonthThai"."&nbsp;"."$strYear";
  }
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">สรุปประจำเดือน</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  
                  <th>Date</th>
                  <th>In</th>
                  <th>Out</th>
                  <!-- <th>Edit</th> -->
                </tr>
                </thead>
                <tbody>
                  <?php
                    include "pages/connectdb.php";

                    $sql = "SELECT time.id, employee.name, employee.lastname, time.date, time.in, time.out FROM time JOIN employee ON employee.id_member = time.id_member ORDER BY name";

                    $result = $conn->query($sql);
                    while($query=$result->fetch_array()) {
                      $date = $query["date"];
                  ?>
                      <tr>
                        <td><?php echo $query["name"]."&nbsp;&nbsp;".$query["lastname"] ?></td>
                        
                        <td><?php echo DateThai($date) ?></td>
                        <td><?php echo $query["in"] ?></td>
                        <td><?php echo $query["out"] ?></td>
                        <!-- <td><a style="color: #e85d9e;" href="edit.php?id=<?php echo $query["id"]; ?>" onclick="return confirm('คุณต้องการแก้ข้อมูลคุณ <?=$query["name"] ."&nbsp;". $query["lastname"] ."&nbsp;วันที่&nbsp;". DateThai($date);?> ใช่หรือไม่?')">Edit</a></td> -->
                      </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

  </div>
  <!-- ./wrapper -->

<?php
  include "pages/footer.php";
  include "pages/cradit.php";
?>

</body>
</html>
