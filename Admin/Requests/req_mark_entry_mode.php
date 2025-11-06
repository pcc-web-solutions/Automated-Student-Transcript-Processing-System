<?php
include("../../Database/config.php");
if(isset($_POST['sn'])){
	$sn = $_POST['sn'];

	$sql = $conn->query("SELECT * FROM mark_entry_dates WHERE sn = '$sn'");
	while ($row = mysqli_fetch_assoc($sql)) {
		$status = $row['status'];
		$year = $row['year'];
		$term = $row['term'];
		$start_date = $row['start_date'];
		$end_date = $row['end_date'];
		if($term == ""){$term_code = "I";}
		elseif($term == ""){$term_code = "II";}
		else{$term_code = "III";}
	}

	$update_terms = "UPDATE terms SET term_code = '$term_code', term_name = '$term', start_date = '$start_date', end_date = '$end_date'";
	$update_year = "UPDATE years SET year = '$year'";

	if($status == "Open"){
		$lock = $conn->query("UPDATE mark_entry_dates SET status = 'Closed' WHERE sn = '$sn'");
		if (!$lock) {
			echo "Unable to lock this session"; exit();
		}else{echo "lock_success";exit();}
	}
	if ($status == "Closed") {
		$unlock = $conn->query("UPDATE mark_entry_dates SET status = 'Open' WHERE sn = '$sn'");
		$lockrest = $conn->query("UPDATE mark_entry_dates SET status = 'Closed' WHERE sn != '$sn'");
		mysqli_query($conn, $update_terms);
		mysqli_query($conn, $update_year);
		if (!$unlock) {
			echo "Unable to unlock this session"; exit();
		}else{echo "unlock_success";exit();}
	}
}
?>