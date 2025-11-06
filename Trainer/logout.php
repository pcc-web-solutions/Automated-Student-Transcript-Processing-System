<?php
	session_start();

	$_SESSION['Trainer'] = NULL;

	header("location: ../login-page.php?success=Successfully logged out!");
?>