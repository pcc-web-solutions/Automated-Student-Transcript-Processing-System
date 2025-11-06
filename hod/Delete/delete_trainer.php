<?php
session_start();
if (!$_SESSION['dept']) {
    header("location: ../login-page.php?error=Department not ready!");
    exit();
}
if(isset($_POST['record_no']))
{
	$id = $_POST['record_no'];
	
	require('../../Database/config.php');
	
	$deletefromtrainers=$conn->query("DELETE FROM trainers WHERE trainer_id = '$id'");
	$deletefromusers=$conn->query("DELETE FROM users WHERE user_id = '$id'");
	
	if($deletefromtrainers AND $deletefromusers)
	{
		echo "Trainer deleted";
	}
	else{
		echo "Error deleting trainer.";
	}	
}
?>