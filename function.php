<?php 

session_start();
include '_config/connect.php';

$status = $_POST['status'];

if (!empty($_POST['id'])) {

    $id = $_POST['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM virtual_machines WHERE id='$id'");

    while ($row = $result->fetch_assoc()) {
        $vmx = $row['path'];
        $vmx_username = $row['username'];
        $vmx_password = $row['password'];

        $vmx = escapeshellarg($vmx);
    }

}
else if (!empty($_SESSION['id'])) {

    $id = $_SESSION['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM virtual_machines WHERE id='$id'");

    while ($row = $result->fetch_assoc()) {
        $vmx = $row['path'];
        $vmx_username = $row['username'];
        $vmx_password = $row['password'];

        $vmx = escapeshellarg($vmx);
    }
}

$prog = "C:\\xampp\\htdocs\\proyek-tekvir\\ProyekTekvir.exe";

function alert($msg, $redirect) {
    echo "<script>alert('$msg')
    window.location.replace('$redirect');
    </script>";
}

if ($status == "START") {
    alert('You click Power On', 'vm_list.php');
    $fungsi = "START";
    $result = shell_exec("$prog $vmx $vmx_username $vmx_password $fungsi");
    $query = "UPDATE virtual_machines SET status='ON' WHERE id='$id'";
    
    $_SESSION['id'] = $id;
    $_SESSION['path'] = '/';
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
    mysqli_query($koneksi, $query);
}

if ($status == "STOP") {
    alert('You click Shutdown', 'vm_list.php');
    $fungsi = "STOP";
    $result = shell_exec("$prog $vmx $vmx_username $vmx_password $fungsi");
    $query = "UPDATE virtual_machines SET status='OFF' WHERE id='$id'";
    session_destroy();
    mysqli_query($koneksi, $query);
}

if ($status == "CSSHOT") {
    if (!empty($_POST['csnap_name'])) {
        $csnap_name = $_POST['csnap_name'];
    }
    alert('You create a new Snapshot ' .$csnap_name, 'vm_snapshot_list.php');
    $fungsi = "CSSHOT";
    exec("$prog $fungsi $vmx $csnap_name");
}

if ($status == "RSSHOT") {
    if (!empty($_POST['rsnap_name'])) {
        $csnap_name = $_POST['rsnap_name'];
    }
    alert('You revert to Snapshot ' .$rsnap_name, 'vm_snapshot_list.php');
    $fungsi = "RSSHOT";
    exec("$prog $fungsi $vmx $rsnap_name");
}

if ($status == "GO") {
    if (!empty($_POST['new_path'])) {
        $new_path = $_POST['new_path'];
    }
    alert('Your working directory has moved', 'vm_file_explorer.php');
    $fungsi = "LS";

    if ($_SESSION['path'] == "/") {
        $pwd = $_SESSION['path'] . $new_path;
    }
    else {
        $pwd = $_SESSION['path'] . '/' . $new_path;
    }

    $_SESSION['path'] = $pwd;
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $pwd");
}

if ($status == "BACK") {
    alert('Your working directory has moved', 'vm_file_explorer.php');
    $fungsi = "LS";
    $pwd = $_SESSION['path'];
    if (strrpos($pwd, '/') == 0) {
        $pwd = substr($pwd, 0, strrpos($pwd, '/') + 1);
    }
    else {
        $pwd = substr($pwd, 0, strrpos($pwd, '/'));
    }
    echo $pwd;
    $_SESSION['path'] = $pwd;
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $pwd");
}

if ($status == "MD") {

}

if ($status == "RD") {

}

if ($status == "MV") {

}

if ($status == "RM") {

}

if ($status == "TG") {

}

if ($status == "TH") {

}

?>