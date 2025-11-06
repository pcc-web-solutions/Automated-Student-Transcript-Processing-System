<?php
	require('../../Database/config.php');
	
	$deptid = substr(str_shuffle("0123456789"),0,3);
	$department_name=$_POST['department_name'];
	
	if(empty($department_name)){echo "Please enter department name"; exit();}
	else{
		$sql = "insert into departments(department_code, department_name) values('$deptid', '$department_name')";
		
		$results=mysqli_query($conn, $sql);
		if($results)
		{echo "Department saved";}
		else{echo "Error saving";}
	}
?> 


