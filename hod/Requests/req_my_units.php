<?php
	session_start();
	
	if(!$_SESSION['dept']){
		header("location: ../../login-page.php");
	}
	else{
		include("../../Database/config.php");
	
		$course = $_POST['course_code'];
		$units = array();

		$sql = $conn->query("SELECT units.unit_code, units.unit_name FROM units INNER JOIN trainer_units ON trainer_units.unit_code = units.unit_code WHERE units.courses_code = '$course' GROUP BY units.unit_code ORDER BY units.courses_code, units.unit_code");
		if($sql->num_rows > 0){
			while($row=mysqli_fetch_array($sql)){
				$unit_code = $row['unit_code'];
				$unit_name = $row['unit_name'];

				$units[] = array("unit_code"=>$unit_code, "unit_name"=>$unit_name);
			}
			echo json_encode($units);
		}
	}
	
?>