<?php
session_start();

$adm_code = $_SESSION['Admin'];

if(isset($_POST['sn']))
{
	$sn=$_POST['sn'];
	
	require('../../Database/config.php');
	
	// $sql="delete from trainees where adm='$sn'";
	
	$update = $conn->query("UPDATE trainees SET deleted_by = '$adm_code' WHERE sn = '$sn' ");
	
	// $results=mysqli_query($conn, $sql);
	
	if($update)
	{
		echo "Record deleted";
}
	
}
?>