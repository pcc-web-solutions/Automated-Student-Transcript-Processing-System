<?php 
if (isset($_POST['request'])) {
	include("../../Database/config.php");
	$request = $_POST['request'];
	$usertype = $_POST['usertype'];
	$userid = $_POST['userid'];

	if($request == "Trainers"){
		$trainers = array();
		$get_trainers = $conn->query("SELECT trainer_id, first_name, last_name, phone_no FROM trainers WHERE trainer_id NOT IN (SELECT user_id FROM users WHERE usertype = '$usertype') ORDER BY trainer_id ASC");
		while ($row = $get_trainers->fetch_array()) {
			$trainer_id = $row['trainer_id'];
			$firstname =  strtoupper($row['first_name']); 
			$lastname = strtoupper($row['last_name']);
			$trainer_phone = strtoupper($row['phone_no']);
			$trainers[] = array("trainer_id" => $trainer_id, "first_name" => $firstname, "last_name" => $lastname, "phone_no" => $trainer_phone);
		}	
		echo json_encode($trainers);
		exit();
	}
	else if ($request == "hods") {
		$departments = array();

		$get_hods = $conn->query("SELECT department_hods.hod, trainers.first_name, trainers.last_name, trainers.phone_no FROM department_hods INNER JOIN departments ON department_hods.department_code = departments.department_code LEFT JOIN trainers ON trainers.trainer_id = department_hods.hod WHERE trainers.trainer_id NOT IN (SELECT user_id FROM users WHERE usertype = '$usertype') ORDER BY departments.department_code ASC");
		while ($row = $get_hods->fetch_array()) {
			$hod_id = $row['hod'];
			$hod_firstname =  strtoupper($row['first_name']); 
			$hod_lastname = strtoupper($row['last_name']);
			$hod_phone = strtoupper($row['phone_no']);
			$departments[] = array("hod_id" => $hod_id, "hod_first_name" => $hod_firstname, "hod_last_name" => $hod_lastname, "hod_phone" => $hod_phone);
		}	
		echo json_encode($departments);
		exit();
	}
	else if ($request == "biodata"){
		$biodata = array();

		$get_details = $conn->query("SELECT first_name, last_name, phone_no FROM trainers WHERE trainer_id = '$userid'");
		while ($trainer = mysqli_fetch_assoc($get_details)) {
			$fname = strtoupper($trainer['first_name']);
			$lname = strtoupper($trainer['last_name']);
			$phone = strtoupper($trainer['phone_no']);
			$biodata = array("fname" => $fname, "lname" => $lname, "phone" => $phone);
		}
		echo json_encode($biodata);
		exit();
	}
}

?>