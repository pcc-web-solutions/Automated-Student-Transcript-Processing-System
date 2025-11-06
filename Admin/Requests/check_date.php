<?php
	include("../../Database/config.php");

	$todays_date = date('Y-m-d');
	$todays_week_day = date('w', strtotime($todays_date));
	$posted_date = $_POST['date'];
	$posted_week_day = date('w', strtotime($posted_date));

	$select_enddate = $conn->query("SELECT * FROM terms");
	if($select_enddate->num_rows>0){
		while ($data=$select_enddate->fetch_array()) {
			$term = $data['term_name'];
			$start_date = $data['start_date'];
			$end_date = $data['end_date'];
		}
	}

	$selected_session_year = $conn->query("SELECT * FROM years");
	if($selected_session_year->num_rows>0){
		while ($row=$selected_session_year->fetch_array()) {
			$session_year = $row['year'];
		}
	}

	// Function to determine the day of the week in words
	function weekday_name($wkday){
		$errors = 0;
		switch ($wkday) {
			case 0:
				$day_name = "Sunday";
				break;
			case 1:
				$day_name = "Monday";
				break;
			case 2:
				$day_name = "Tuesday";
				break;
			case 3:
				$day_name = "Wednesday";
				break;
			case 4:
				$day_name = "Thursday";
				break;
			case 5:
				$day_name = "Friday";
				break;
			case 6:
				$day_name = "Saturday";
				break;
			
			default:
				$errors++;
				break;
		}
		if($errors<1){return $day_name;}
		else{return "Error";}
	}

	// Function to determine the tense
	function english_tense($curr_date, $custom_date){
		$errors = 0;
		$current_date = strtotime($curr_date); $date = strtotime($custom_date);
		if($current_date > $date){$tense = "was";}
		else if($current_date == $date){$tense = "is";}
		else if($current_date < $date){$tense = "will";}
		else{$errors++;}

		if($errors<1){return $tense;}
		else{return $errors. " found";}
	}

	
	$wkd_name = weekday_name($posted_week_day);
	$tense = english_tense($todays_date, $posted_date);
	
	if(strtotime($posted_date) < strtotime($start_date) || strtotime($posted_date) > strtotime($end_date)){
		echo "$posted_date $tense to be marked on another term session";
	}
	else{
		if($wkd_name == "Saturday" || $wkd_name == "Sunday"){
			echo "$wkd_name class attendance cannot be marked.";
		}
		else{
			echo "success";
		}
	}
?>