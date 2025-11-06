<?php
if(isset($_POST['deptid']))
{
	include('../../Database/config.php');
	
	$deptid=$_POST['deptid'];
	$deptname=$_POST['deptname'];
	$hod=$_POST['hod'];

	$sql="UPDATE departments SET  department_name = '$deptname' WHERE department_code =$deptid";
	$update_dept_hods = $conn->query("UPDATE department_hods SET hod = '$hod' WHERE department_code = '$deptid'");
	$results=mysqli_query($conn, $sql) or die("Wrong query expression"); 
	
	if($results)
	{
		echo "Record updated"; 
}
else
	
{
	echo "Problem updating record"; 
}	
}
?>