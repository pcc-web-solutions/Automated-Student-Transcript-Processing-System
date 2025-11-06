<?php
session_start();
include('../../Database/config.php');
$trainer_id = $_SESSION['Trainer'];

if(isset($_POST['selected_course'])){
	$selected_course = $_POST["selected_course"];
	
	$classes = array();
	
	$class_options = $conn->query("SELECT DISTINCT(trainer_units.class_name) FROM trainer_units INNER JOIN courses ON courses.code = trainer_units.course_code INNER JOIN trainees ON trainees.class = trainer_units.class_name WHERE courses.code = '$selected_course' AND trainer_units.trainer_id = '$trainer_id' ORDER BY trainer_units.class_name ASC");
	
	while($row = mysqli_fetch_assoc($class_options) ){
	  $class_name = $row['class_name'];

	  $classes[] = array("class_name" => $class_name);
	}
	echo json_encode($classes);
	exit();
}
?>