<?php
	session_start();
	
	if(!$_SESSION['dept']){
		header("location: ../../login-page.php");
	}
	else{
		include("../../Database/config.php");
	
		$dept = $_SESSION['dept'];
		$course = $_POST['course_code'];
		$classes = array();

		$sql = $conn->query("SELECT DISTINCT(trainer_units.class_name) FROM trainer_units INNER JOIN courses ON courses.code = trainer_units.course_code INNER JOIN trainees ON trainees.class = trainer_units.class_name WHERE courses.code = '$course' ORDER BY trainer_units.class_name ASC");
		if($sql->num_rows > 0){
			while($row=mysqli_fetch_array($sql)) {
				$class_name = $row['class_name'];

				$classes[] = array("class_name"=>$class_name);
			}
			echo json_encode($classes);
		}
	}
	
?>