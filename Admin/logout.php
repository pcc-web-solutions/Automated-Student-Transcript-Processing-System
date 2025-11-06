<?php
	session_start();

	$_SESSION['Admin'] = NULL;

	header("location: ../login-page.php?success=Successfully logged out!");
?>