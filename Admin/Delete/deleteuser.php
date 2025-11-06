<?php
require('../../Database/config.php');

if(isset($_POST['record_no']))
{
	$record_no=$_POST['record_no'];
	
	$sql="DELETE FROM users where user_id = '$record_no'";
	$results=mysqli_query($conn, $sql);

	if($results)
	{
		echo "User with Indentity number $record_no has been removed successfully";
	}	
	else 
	{
		echo "Unable to delete the user with Indentity number $record_no. Please try again.";
	}
}
?>