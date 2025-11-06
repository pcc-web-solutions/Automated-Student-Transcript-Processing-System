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
 		$delete_unit = $conn->query("DELETE FROM trainer_units WHERE unit_code = '$unit' AND trainer_id = '$trainer' AND course_code = '$course' AND class_name = '$class'");
 		if(!$delete_unit){echo "Problem unassigning the unit.";}
 		else{echo "Success";}
 	}
?>