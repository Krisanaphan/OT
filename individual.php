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
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">User</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">สรุปประจำเดือน (รายบุคคล)</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-1">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    include "pages/connectdb.php";

                    $sql = "SELECT id_member, name, lastname, job FROM employee ORDER BY name";

                    $result = $conn->query($sql);
                    while($query=$result->fetch_array()) {
                      $id_mem = $query['id_member'];
                  ?>
                      <tr>
                        <td><a href="person.php?id_mem=<?= $id_mem; ?>" style="color: black; padding-left: 100px;"><?php echo $query["name"]."&nbsp;&nbsp;".$query["lastname"] ?></a></td>
                        <td><a href="person.php?id_mem=<?= $id_mem; ?>" style="color: #000;">ดูรายละเอียด</a></td>
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
        
  </div>
  <!-- ./wrapper -->

<?php
  include "pages/footer.php";
  include "pages/cradit.php";
?>

</body>
</html>
