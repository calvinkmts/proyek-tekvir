<?php
function alert($msg)
{
    echo "<script>alert('$msg')
    window.location.replace('index.php');
    </script>";
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $result = mysqli_query($koneksi, "SELECT name FROM virtual_machines WHERE id='$id'");
    while ($row = $result->fetch_assoc()) {
        $vm_name = $row['name'];
    }
    alert('You have removed ' . $name);
    $query = "DELETE FROM virtual_machines WHERE id='$id'";
    mysqli_query($koneksi, $query);
}

include("_layouts/header.php");
include("_layouts/navbar.php");
include("_layouts/sidebar.php");

include("_config/connect.php");

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">VM List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">VM List v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of available virtual machines</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>VM Name</th>
                            <th>Path</th>
                            <th>Status</th>
                            <th>Turn On</th>
                            <th>Turn Off</th>
                            <th>Clone</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM virtual_machines";
                        $os_results = mysqli_query($koneksi, $query);
                        $os_options = "";

                        while ($row_tipe = mysqli_fetch_array($os_results)) {
                        ?>
                            <tr>
                                <td><?php echo $row_tipe['id'] ?></td>
                                <td><?php echo $row_tipe['name'] ?></td>
                                <td><?php echo $row_tipe['path'] ?></td>
                                <td><?php echo $row_tipe['status'] ?></td>
                                <td>
                                    <form method="POST" action="function.php">
                                        <input type="hidden" value="<?php echo $row_tipe['id']; ?>" name="id" id="id">
                                        <button class="btn btn-block btn-success btn-sm" type="submit" name="status" value="START">On</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="function.php">
                                        <input type="hidden" value="<?php echo $row_tipe['id']; ?>" name="id" id="id">
                                        <button class="btn btn-block btn-secondary btn-sm" type="submit" name="status" value="STOP">Off</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="function.php">
                                        <input type="hidden" value="<?php echo $row_tipe['id']; ?>" name="id" id="id">
                                        <input type="name" id="clone_path" class="form-control" placeholder="Clone Path" name="clone_path" required autofocus>
                                        <button class="btn btn-block btn-info btn-sm" type="submit" name="status" value="CLONE">Clone</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="function_delete_os.php">
                                        <input type="hidden" value="<?php echo $row_tipe['id']; ?>" name="id" id="id">
                                        <button type="submit" class="btn btn-block btn-danger btn-sm" name="delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Add VM</h3>
            </div>
            <div class="card-body">
                <form action="function_add_os.php" method="POST" role="form">
                    <div class="form-group">
                        <label for="exampleInputEmail1">VM Name</label>
                        <input class="form-control" id="vm_name" type="text" placeholder="VM Name" name="vm_name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">VM Path</label>
                        <input class="form-control" id="vm_path" type="text" placeholder="VM Path" name="vm_path">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">VM Username</label>
                                <input class="form-control" id="vm_username" type="text" placeholder="VM Username" name="vm_username">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">VM Password</label>
                                <input class="form-control" id="vm_password" type="password" placeholder="VM Password" name="vm_password">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-warning text-uppercase" type="submit" value="simpan">Add VM</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include("_layouts/footer.php"); ?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>

</html>