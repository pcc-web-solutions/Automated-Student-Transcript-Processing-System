<?php
if(isset($_POST['record_no']))
{
	$id = $_POST['record_no'];
	
	require('../../Database/config.php');
	
	$sql="DELETE FROM departments WHERE department_code = '$id'";
	
	$results=mysqli_query($conn, $sql);
	
	if($results)
	{
		echo "Department deleted successfully";
	}
	else{
		echo "Error deleting department.";
	}	
}
?>