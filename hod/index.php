<?php
	session_start();
	
	if(!$_SESSION['hod']){
		header("location: ../login-page.php");	
	}
	else{
		require("../Database/config.php");

		header("location: home.php");
	}
?>
