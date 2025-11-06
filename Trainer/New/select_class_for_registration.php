<?php
session_start();

$trainer_id = $_SESSION['Trainer'];

require('../../Database/config.php');

if(isset($_POST['selected_course'])){
	$selected_course = $_POST["selected_course"];
	
	$classes = array();
	
	$class_options = $conn->query("SELECT classes.class_name FROM classes INNER JOIN intakes ON classes.intake = intakes.int_abrev INNER JOIN trainer_units ON trainer_units.class_name = classes.class_name INNER JOIN courses ON courses.code = trainer_units.course_code WHERE trainer_units.trainer_id = '$trainer_id' GROUP BY classes.class_name ORDER BY classes.class_name ASC");
	
	while($row = mysqli_fetch_assoc($class_options) ){
	  $class_name = $row['class_name'];

	  $classes[] = array("class_name" => $class_name);
	}
	echo json_encode($classes);
	exit();
}
?>