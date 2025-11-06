<?php
	include("../../Database/config.php");
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$update_terms = "UPDATE terms SET start_date = '$start_date', end_date = '$end_date'";

	$get_current_term = $conn->query("SELECT * FROM terms");
	$get_current_year= $conn->query("SELECT * from years limit 1");
	while($row=mysqli_fetch_assoc($get_current_year)) {$curr_year = $row['year'];}

	if ($get_current_term->num_rows>0) {
		while ($data = mysqli_fetch_assoc($get_current_term)) {
			$term_code = $data['term_code'];
			$term_name = $data['term_name'];
			$prev_start_date = $data['start_date'];
			$pre_end_date = $data['end_date'];
		}
		$check_if_exists = $conn->query("SELECT sn FROM mark_entry_dates WHERE term = '$term_name' AND year = '$curr_year'");
		if ($check_if_exists->num_rows>0){
			while ($sn = mysqli_fetch_assoc($check_if_exists)){$sno = $sn['sn'];}
			// update terms
			mysqli_query($conn,$update_terms);

			// update mark entry dates
			$sql2 = $conn->query("UPDATE mark_entry_dates SET start_date = '$start_date', end_date = '$end_date', status = 'Open' WHERE sn = '$sno' ");
		}
		else{
			// update terms
			mysqli_query($conn,$update_terms);

			// insert into mark entry dates
			$sql2 = $conn->query("INSERT INTO mark_entry_dates (year, term, start_date, end_date, status) VALUES ('$curr_year', '$term_name', '$prev_start_date', '$pre_end_date', 'Open')");
			// update mark entry dates
			$sql3 = $conn->query("UPDATE mark_entry_dates SET status = 'Closed' WHERE sn != '$sno' ");
		}
		if(!$update_terms AND !$sql2){echo "Problem setting deadline"; exit();}
			else{echo "success"; exit();}
	}
	else{
		echo "Problem retrieving current mark entry session.";
	}
?>