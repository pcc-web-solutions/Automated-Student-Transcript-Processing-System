<?php
	include("../../Database/config.php");
 	$trainer = $_POST['trainers'];
 	$course = $_POST['courses'];
 	$class = $_POST['classes'];
 	$unit = $_POST['unitchoice'];

 	if (empty($_POST['trainers'])) {
 		echo("Problem sending the selected trainer");
 		exit();
 	}
 	if (empty($_POST['courses'])) {
 		echo("Problem sending the selected code");
 		exit();
 	}
 	if (empty($_POST['classes'])) {
 		echo("Problem sending the selected class");
 		exit();
 	}
 	elseif(empty($_POST['unitchoice'])){
 		echo("No unit choice selected");
 		exit();
 	}
 	else{

 		$check_if_unit_exists = $conn->query("SELECT DISTINCT(unit_code) FROM trainer_units WHERE unit_code = '$unit' AND trainer_id = '$trainer' AND course_code = '$course' AND class_name = '$class'");
 		if ($check_if_unit_exists->num_rows<1) {
 			$insert_records = $conn->query("INSERT INTO trainer_units (trainer_id, course_code, unit_code, class_name) VALUES ('$trainer','$course','$unit','$class')");
 			if(!$insert_records){
		 		echo "Problem saving data";
		 		exit();
		 	}
		 	else{
		 		echo "Success";
		 		exit();
		 	}
		 	exit();
 		}
 		else{
 			echo "Unit already assigned";
 			exit();
 		}
 	}
?>