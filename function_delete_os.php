<?php 
include ("_config/connect.php");
// menyimpan data kedalam variabel
$vm_id = $_POST['id'];

$delete = "DELETE FROM `virtual_machines` WHERE `virtual_machines`.`id` = $vm_id";
$result = mysqli_query($koneksi, $delete);

echo "<script>
	alert('VM has been deleted!')
	window.location.replace('vm_list.php');
	</script>";
