<?php
	session_start();

	$_SESSION['hod'] = NULL;

	header("location: ../login-page.php?success=Successfully logged out!");
?>