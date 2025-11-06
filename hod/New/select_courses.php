<?php
session_start();
require('../../Database/config.php');

if(isset($_POST['selected_department'])){
	$selected_department = $_POST["selected_department"];
	$_SESSION['dept'] = $selected_department;
	
	$courses = array();
	
	$course_options = $conn->query("SELECT courses.code, courses.course_name FROM courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$selected_department' ORDER BY code, course_name ASC");
	
	while($row = mysqli_fetch_assoc($course_options) ){
	  $course_code = $row['code'];
	  $course_name = $row['course_name'];

	  $courses[] = array("course_code" => $course_code, "course_name" => $course_name);
	}
	echo json_encode($courses);
	exit();
}
?>