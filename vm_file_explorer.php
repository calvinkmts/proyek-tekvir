<?php

session_start();
include("_layouts/header.php");
include("_layouts/navbar.php");
include("_layouts/sidebar.php");

if (empty($_SESSION['path'])) {
    $_SESSION['path'] = '/';
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">File Explorer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">File Explorer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Path: <?php echo $_SESSION["path"]; ?></h3>
            </div>
            <div class="card-body">
                <form method="POST" action="function.php">
                    <div class="input-group input-group">
                        <span class="input-group-prepend">
                            <button type="submit" class="btn btn-danger btn-flat" name="status" value="BACK">Back!</button>
                        </span>
                        <input type="text" class="form-control" value="" name="new_path" id="new_path">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat" name="status" value="GO">Go!</button>
                        </span>
                    </div>
                </form>
                <!-- /input-group -->
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Directory Management</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="function.php">
                    <div class="input-group input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">Path: <?php echo $_SESSION["path"]; ?></span>
                        </span>
                        <input type="text" class="form-control" value="" name="new_dir" id="new_dir">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat" name="status" value="MD">New</button>
                        </span>
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-danger btn-flat" name="status" value="RD">Delete</button>
                        </span>
                    </div>
                </form>
                <!-- /input-group -->
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">File Management</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <form method="POST" action="function.php">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Old File / Directory Name</label>
                                <input type="text" class="form-control" placeholder="Enter ...">
                            </div>
                            <div class="form-group">
                                <label>New File / Directory Name</label>
                                <input type="text" class="form-control" placeholder="Enter ...">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <form method="POST" action="function.php">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">File Explorer</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th style="width: 40px">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $myfile = fopen("temp/output.txt", "r") or die("Unable to open file!");

                        while (($line = fgets($myfile)) !== false) {
                            $pieces = explode(" ", $line);
                        ?>
                            <tr>
                                <td><?php echo $pieces[1] ?></td>
                                <td><?php echo $pieces[2] ?></td>
                                <td><?php echo $pieces[0] ?></td>
                            </tr>
                        <?php }
                        fclose($myfile); ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
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