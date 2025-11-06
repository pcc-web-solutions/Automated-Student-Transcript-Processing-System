<?php
	session_start();
	include("Database/config.php");
	$sql = "SELECT COUNT(DISTINCT User_id) AS users FROM users";
	$runquery = mysqli_query($conn, $sql);
	while($count = mysqli_fetch_assoc($runquery)){
		$availableusers = $count['users'];
	}

	if($availableusers < 1){
		header("location: login/FirstTimeLogin.php");
		exit();
	}
	// SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
	else{
		header("location: login-page.php");
		exit();
	}
?>