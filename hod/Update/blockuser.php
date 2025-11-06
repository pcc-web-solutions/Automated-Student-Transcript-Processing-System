<?php
require('../../Database/config.php');

if(isset($_POST['record_no']))
{
	$record_no=$_POST['record_no'];
	
	$sql="SELECT * FROM users WHERE user_id = '$record_no'"; 	
	$results=mysqli_query($conn, $sql) or die("Problem fetching users from database");
	if($gotten=mysqli_fetch_assoc($results))
	{
		$attempts = $gotten['Attempts'];
	}
	if($attempts<=0){
		$sql="UPDATE users SET Attempts = 4 WHERE user_id = '$record_no'";
		$results=mysqli_query($conn, $sql);

		if($results)
		{
			echo "Account for Indentity number $record_no has been reset successfully";
		}	
		else 
		{
			echo "Unable to reset the account for user Indentity number $record_no. Please try again.";
		}
	}
	else{
		$sql="UPDATE users SET Attempts = 0 WHERE user_id = '$record_no'";
		$results=mysqli_query($conn, $sql);

		if($results)
		{
			echo "The user with Indentity number $record_no has been blocked successfully";
		}	
		else 
		{
			echo "Unable to block the user with Indentity number $record_no. Please try again.";
		}
	}
}
?>