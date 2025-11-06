<?php
session_start();
$dept = $_SESSION['dept'];
require('../../Database/config.php');

if(isset($_POST['selected_course'])){
	$selected_course = $_POST["selected_course"];
	
	$classes = array();
	
	$class_options = $conn->query("SELECT classes.class_name FROM classes INNER JOIN courses ON courses.course_abrev = classes.course_abrev INNER JOIN trainees ON trainees.course_code = courses.code WHERE courses.code = '$selected_course' GROUP BY class_name ORDER BY class_name ASC");
	
	while($row = mysqli_fetch_assoc($class_options) ){
	  $class_name = $row['class_name'];

	  $classes[] = array("class_name" => $class_name);
	}
	echo json_encode($classes);
	exit();
}
?>