<?php
	session_start();
	if(!$_SESSION['dept']){
		header("location: ../../login-page.php");
		exit();
	}

	include("../../Database/config.php");
	$dept = $_SESSION['dept'];
	$current_session_year = $conn->query("SELECT * FROM years");
	if($current_session_year->num_rows>0){
		while ($row=$current_session_year->fetch_array()) {
			$session_year = $row['year'];
		}
	}

	$current_session_term = $conn->query("SELECT term_name FROM terms");
	if($current_session_term->num_rows>0){
		while ($row=$current_session_term->fetch_array()) {
			$session_term = $row['term_name'];
		}
	}

	$course = $_POST['course'];
	$unit = $_POST['unit'];
	$class = $_POST['class'];
	$date = $_POST['date'];
	$code = $_POST['code'];

	if(!empty($_POST['adms'])){$adms = $_POST['adms'];}

	if(empty($adms)){echo "Please mark at least one trainee to upload"; exit();}
	
	// select a trainer for the unit in that class
	$sql = $conn->query("SELECT trainer_id FROM trainer_units WHERE course_code = '$course' AND unit_code = '$unit' AND class_name = '$class' LIMIT 1");
	if($sql->num_rows>0){
		while ($row = $sql->fetch_array()) {
			$trainer = $row['trainer_id'];
		}
	}
	else{
		$trainer = 'xxx';
	}

	//Get total attended lessons for this term
	$sql = $conn->query("SELECT COUNT(DISTINCT unit) AS attended_lessons FROM cl_att_register WHERE class = '$class' AND term='$session_term' AND year='$session_year'");
	while ($row=$sql->fetch_array()) {
		$lessons_marked = $row['attended_lessons'];
	}

	function check_unit_info($unit_code, $lessons_marked, $max_lessons){
		$sql = $conn->query("SELECT * FROM units WHERE unit_code='$code'");
		if($sql->num_rows>0){
			while ($info=$sql->fetch_array()) {
				$weekly_lessons = $info['weekly_hours'];
				$lesson_duration = $info['hourly_lessons'];
				$unitname = $info['unit_name'];
			}
		}
	}

	$trainees = array(); $sql = null;
	$sql = $conn->query("SELECT * FROM cl_att_register INNER JOIN trainers ON trainers.trainer_id = cl_att_register.trainer WHERE date = '$date'  AND term = '$session_term' AND year = '$session_year' AND unit = '$unit' AND class = '$class' ");
	if($sql->num_rows>0){
		while ($row = $sql->fetch_array()) {
			$mrked_code = $row['cl_code'];
			$marked_by = $row['first_name']." ".$row['last_name'];
		}
		echo "This class attendance has been already marked by $marked_by with code $mrked_code";
		exit();
	}
	else{
		foreach ($adms as $adm_no) {
			$adm = $adm_no;
			$sql = $conn->query("INSERT INTO cl_att_register (adm,cl_code,date,year,term,course,unit,class,trainer) VALUES ('$adm','$code','$date','$session_year','$session_term','$course','$unit','$class','$trainer')");
		}
		if($sql){echo "success";}
		else{echo "Error uploading data";}
	}

?>