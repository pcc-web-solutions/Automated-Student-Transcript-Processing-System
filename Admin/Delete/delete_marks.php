<?php
if(isset($_POST['sn']))
{
	$sn = $_POST['sn'];
	
	require('../../Database/config.php');
	
	$sql="DELETE FROM results_entry WHERE sn = '$sn'";
	
	$results=mysqli_query($conn, $sql);
	
	if($results)
	{
		echo "Marks deleted";
	}
	else{
		echo "Error deleting marks.";
	}	
}
?>