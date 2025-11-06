<?php	
	include("../../Database/config.php");

	if(isset($_POST['dept'])){
		$dept = $_POST['dept'];
		$trainers = array();
		$get_trainers = $conn->query("SELECT trainer_id, first_name, last_name, phone_no FROM trainers INNER JOIN departments ON departments.department_code = trainers.department_id WHERE departments.department_code = '$dept' ORDER BY trainer_id ASC");
		while ($row = $get_trainers->fetch_array()) {
			$trainer_id = $row['trainer_id'];
			$firstname =  strtoupper($row['first_name']); 
			$lastname = strtoupper($row['last_name']);
			$trainers[] = array("trainer_code" => $trainer_id, "trainer_name" => strtoupper($firstname." ".$lastname));
		}	
		echo json_encode($trainers);
		exit();
	}
?>	