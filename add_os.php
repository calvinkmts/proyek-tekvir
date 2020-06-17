<?php 
include ("_config/connect.php");
// menyimpan data kedalam variabel
$vm_name = $_POST['vm_name'];
$vm_path = $_POST['vm_path'];
$vm_username = $_POST['vm_username'];
$vm_password = $_POST['vm_password'];

$check = "SELECT COUNT(*) from virtual_machines where path = '$vm_path'";
$result = mysqli_query($koneksi, $check);
$row = mysqli_fetch_assoc($result);
if ($row['COUNT(*)'] == 0)
	{
		// query SQL untuk insert data
		$query="INSERT INTO virtual_machines VALUES (DEFAULT, '$vm_name', '$vm_path', '$vm_username', '$vm_password', 'OFF')";
		mysqli_query($koneksi, $query);
		echo "<script>
				alert('VM succesfully added')
				window.location.replace('vm_list.php');
    		  </script>";


    	
	}
	
else
	{
		echo "<script>
		alert('VM has been added before!')
		window.location.replace('vm_list.php');
		</script>";
			
	
	}

 ?>