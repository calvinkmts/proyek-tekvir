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
                    <h1 class="m-0 text-dark">Terminal</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Terminal</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Run Program in Guests</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="function.php">
                    <!-- text input -->
                    <div class="form-group">
                        <label>First Arguments (Program)</label>
                        <input type="text" class="form-control" placeholder="Enter ..." value="" name="arg1">
                    </div>
                    <div class="form-group">
                        <label>Seccond Arguments (Program Arguments)</label>
                        <input type="text" class="form-control" placeholder="Enter ..." value="" name="arg2">
                    </div>
                    <button type="submit" class="btn btn-warning" name="status" value="PROGRAM">RUN</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Run Script in Guests</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="function.php" enctype="multipart/form-data">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Shell</label>
                        <select class="form-control" name="arg1">
                            <option value="/bin/bash">Bash</option>
                            <option value="/bin/python3">Python 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fileToUpload">File input</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload">
                                <label class="custom-file-label" for="fileToUpload">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning" name="status" value="SCRIPT">RUN</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">File Explorer</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label>Textarea</label>
                    <textarea class="form-control" rows="20" placeholder="" readonly>
                        <?php
                        $file = fopen("temp/outfile.txt", "r");
                        $data = "";

                        while (($line = fgets($file)) !== false) {
                            echo $line;
                        }

                        fclose($file);
                        ?>
                    </textarea>
                </div>
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
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
</body>

</html>