<?php
include("../../Database/config.php");
$dept_id = $_POST['dept_code'];
$courses = array();

$get_courses = $conn->query("SELECT DISTINCT(trainees.course_code), courses.course_name FROM trainees INNER JOIN courses ON trainees.course_code = courses.code INNER JOIN departments ON departments.department_code = courses.department_code WHERE trainees.status = '1' AND departments.department_code = '$dept_id' ORDER BY trainees.course_code ASC, courses.course_name ASC");

$num_rows = $get_courses->num_rows;

if($num_rows > 0){
	while($data = mysqli_fetch_assoc($get_courses)){
		$code = $data['course_code'];
		$course_name = $data['course_name'];

		$courses[] = array("course_code" => $code, "course_name" => $course_name);
	}
	echo json_encode($courses);
	exit();
}
else{
	echo "Problem retrieving courses";
}

?>