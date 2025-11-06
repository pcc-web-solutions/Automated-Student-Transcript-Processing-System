<?php
	session_start();
	
	if(!$_SESSION['Trainer']){
		header("location: ../../login-page.php");
	}
	else{
		include("../../Database/config.php");
	
		$trainer_id = $_SESSION['Trainer'];
		$course = $_POST['course_code'];
		$classes = array();

		$sql = $conn->query("SELECT DISTINCT(trainer_units.class_name) FROM trainer_units INNER JOIN courses ON courses.code = trainer_units.course_code INNER JOIN trainees ON trainees.class = trainer_units.class_name WHERE courses.code = '$course' AND trainer_units.trainer_id = '$trainer_id' ORDER BY trainer_units.class_name ASC");
		if($sql->num_rows > 0){
			while($row=mysqli_fetch_array($sql)) {
				$class_name = $row['class_name'];

				$classes[] = array("class_name"=>$class_name);
			}
			echo json_encode($classes);
		}
	}
	
?>