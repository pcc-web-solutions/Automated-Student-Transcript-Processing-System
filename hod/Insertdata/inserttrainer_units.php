<?php
	include("../../Database/config.php");
 	$trainer = $_POST['trainers'];
 	$course = $_POST['courses'];
 	$class = $_POST['classes'];

 	if(empty($_POST['unitchoice'])){
 		echo("No unit choice selected");
 		exit();
 	}
 	else{
 		$selected_units = $_POST['unitchoice'];

 		$registered_units = array();
	 	foreach ($selected_units as $unit_choice) {
	 		$check_if_unit_exists = $conn->query("SELECT DISTINCT(unit_code) FROM trainer_units WHERE unit_code = '$unit_choice' AND trainer_id = '$trainer' AND course_code = '$course' AND class_name = '$class'");
	 		if ($check_if_unit_exists->num_rows<1) {
	 			$insert_records = $conn->query("INSERT INTO trainer_units (trainer_id, course_code, unit_code, class_name) VALUES ('$trainer','$course','$unit_choice','$class')");
	 		}
	 		else{
	 			$registered_units[] = $unit_choice;
	 			echo "Some of the selected units are already saved";
	 			exit();
	 		}
	 	}
	 	if(!$insert_records){
	 		echo "Problem saving data";
	 		exit();
	 	}
	 	else{
	 		echo "Choices saved successfully";
	 	}
 	}
 	
?>