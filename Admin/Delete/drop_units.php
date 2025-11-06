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

 		$deleted_units = 0;
	 	foreach ($selected_units as $unit_choice) {
	 		$delete_unit = $conn->query("DELETE FROM trainer_units WHERE trainer_id = '$trainer' AND course_code = '$course' AND class_name = '$class' AND unit_code = '$unit_choice' ");
	 		$check_if_unit_exists = $conn->query("SELECT DISTINCT(unit_code) FROM trainer_units WHERE unit_code = '$unit_choice' AND trainer_id = '$trainer' AND course_code = '$course' AND class_name = '$class'");
	 		if ($check_if_unit_exists->num_rows<1) {
	 			$deleted_units++;
	 		}
	 	}
	 	if($deleted_units < 1){
	 		echo "Problem unassigning some of the selected units";
	 		exit();
	 	}
	 	else{
	 		echo "Units unassigned successfully";
	 	}
 	}
 	
?>