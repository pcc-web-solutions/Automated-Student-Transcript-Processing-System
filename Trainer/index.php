<?php
	session_start();
	
	if(!$_SESSION['Trainer']){
		header("location: ../login-page.php?error=Access Denied! You've to login first.");	
	}
	else{
		require("../Database/config.php");

		header("location: home.php");
	}
?>
