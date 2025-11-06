<?php
if(isset($_POST['sn']))
{
	$sn=$_POST['sn'];
	
	require('../../Database/config.php');
	
	$sql="delete from courses where code =$sn";
	
	$results=mysqli_query($conn, $sql);
	
	if($results)
	{
		echo "Course deleted";
}


	
}
?>