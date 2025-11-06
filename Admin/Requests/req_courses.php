<?php
	session_start();
	$dept = $_POST['dept'];
	include("../../Database/config.php");
	if (isset($_POST['dept'])) {
		$courses = array();
		$get_courses = $conn->query("SELECT code, course_name FROM courses INNER JOIN units ON units.courses_code = courses.code INNER JOIN classes ON classes.course_abrev = courses.course_abrev WHERE department_code = '$dept' GROUP BY courses.code ORDER BY code ASC, course_name ASC");
		if ($get_courses->num_rows>0) {
			while ($row = mysqli_fetch_array($get_courses)) {
				$code = $row['code'];
				$course_name = $row['course_name'];
				$courses[] = array("code" => $code, "course_name" => $course_name);
			}
			echo json_encode($courses);
		}
	}
?>