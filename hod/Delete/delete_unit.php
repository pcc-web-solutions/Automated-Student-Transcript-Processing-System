<?php
	if(isset($_POST['sn']))
	{
		$sn=$_POST['sn'];
		
		require('../../Database/config.php');
		
		$sql="delete from units where sn=$sn";
		
		$results=mysqli_query($conn, $sql);
		
		if($results)
		{
			echo "Record deleted";
		}	
	}
?>