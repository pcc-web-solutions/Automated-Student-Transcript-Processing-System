<?php
require('../../Database/config.php');

if(isset($_POST['selected_course'])){
	$selected_course = $_POST["selected_course"];
	
	$trainers = array();
	$select_trainer=$conn->query("SELECT trainer_id, first_name, last_name FROM trainers INNER JOIN departments ON departments.department_code = trainers.department_id INNER JOIN courses ON courses.department_code = trainers.department_id WHERE courses.code='$selected_course' ORDER BY trainer_id");

	while($row = mysqli_fetch_assoc($select_trainer) ){
	  $trainer_id = $row['trainer_id'];
	  $trainer_name = strtoupper($row['first_name'])." ".strtoupper($row['last_name']);

	  $trainers[] = array("trainer_code" => $trainer_id, "trainer_name" => $trainer_name);
	}
	echo json_encode($trainers);
	exit();
}
else{
	echo "Error";
}
?>
