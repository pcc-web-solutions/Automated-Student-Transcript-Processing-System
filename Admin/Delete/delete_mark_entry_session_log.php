<?php
	include("../../Database/config.php");

	$id = $_POST['sn'];

	$delete = $conn->query("DELETE FROM mark_entry_dates WHERE sn = '$id'");
	if(!$delete){echo "Error deleting";}
	else{echo "success";}
?>