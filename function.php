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
    
    $_SESSION['id'] = $id;
    $_SESSION['path'] = '/';
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
    $query = "UPDATE virtual_machines SET status='ON' WHERE id='$id'";
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

if($status == "CLONE"){
    if (!empty($_POST['clone_path'])){
        $clone_path = escapeshellarg(htmlentities($_POST['clone_path']));
    }
    alert('You Clone to '.$clone_path, 'vm_list.php');
    $fungsi = "CLONE";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $clone_path");
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
    if (!empty($_POST['new_dir'])) {
        $new_dir = $_POST['new_dir'];
    }

    $pwd = $_SESSION['path'];
    if (substr($pwd, -1) != '/') {
        $new_dir_path = $pwd . '/' . $new_dir;
    }
    alert('New directory has created', 'vm_file_explorer.php');
    $fungsi = "MD";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $new_dir_path");

    $_SESSION['id'] = $id;
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
    $query = "UPDATE virtual_machines SET status='ON' WHERE id='$id'";
    mysqli_query($koneksi, $query);
}

if ($status == "RD") {
    if (!empty($_POST['new_dir'])) {
        $new_dir = $_POST['new_dir'];
    }

    $pwd = $_SESSION['path'];
    if (substr($pwd, -1) != '/') {
        $new_dir_path = $pwd . '/' . $new_dir;
    }

    alert('Directory has been deleted', 'vm_file_explorer.php');
    $fungsi = "RD";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $new_dir_path");

    $_SESSION['id'] = $id;
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
}

if ($status == "MV") {

    $old_name = $_POST['old_name'];
    $new_name = $_POST['new_name'];

    $pwd = $_SESSION['path'];
    if (substr($pwd, -1) != '/') {
        $old_path = $pwd . '/' . $old_name;
        $new_path = $pwd . '/' . $new_name;
    }

    alert('Your file/directory has been changed', 'vm_file_explorer.php');
    $fungsi = "MV";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $old_path $new_path");

    $_SESSION['id'] = $id;
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
}

if ($status == "RM") {

    $delete_file = $_POST['delete_file'];

    $pwd = $_SESSION['path'];
    if (substr($pwd, -1) != '/') {
        $file_path = $pwd . '/' . $delete_file;
    }

    alert('Your file has been deleted', 'vm_file_explorer.php');
    $fungsi = "RM";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $file_path");

    $_SESSION['id'] = $id;
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
}

if ($status == "TG") {

    $target_dir = "temp/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

    $pwd = $_SESSION['path'];
    if (substr($pwd, -1) != '/') {
        $file_path = $pwd . '/' . basename($_FILES["fileToUpload"]["name"]);
    }

    alert('Your file has been uploaded', 'vm_file_explorer.php');
    $fungsi = "TG";
    exec("$prog $vmx $vmx_username $vmx_password $fungsi $target_file $file_path");

    $_SESSION['id'] = $id;
    $fungsi2 = "LS";
    $pwd = $_SESSION['path'];
    exec("$prog $vmx $vmx_username $vmx_password $fungsi2 $pwd");
}

if ($status == "PROGRAM") {

    $arg1 = $_POST['arg1'];
    $arg2 = escapeshellarg($_POST['arg2']);

    echo $arg1 . "<br>";
    echo $arg2 . "<br>";

    alert('Your file has been run', 'vm_terminal.php');
    $fungsi = "PROGRAM";
    shell_exec("$prog $vmx $vmx_username $vmx_password $fungsi $arg1 $arg2");
}

if ($status == "SCRIPT") {

    $arg1 = $_POST['arg1'];

    $target_dir = "temp/scripts/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

    alert('Your file has been run', 'vm_terminal.php');
    $fungsi = "SCRIPT";
    shell_exec("$prog $vmx $vmx_username $vmx_password $fungsi $arg1 $target_file");
}

if ($status == "TH") {


}

?>