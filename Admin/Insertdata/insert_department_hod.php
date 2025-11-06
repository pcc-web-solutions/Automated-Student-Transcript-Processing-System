<?php
	require_once("../../Database/dbcontroller.php");
	include("../../Database/config.php");
	$db_handle = new DBController();
	$department = "";
	$trainer = "";

	if(!empty($_POST["department"])) {
		$department = $db_handle->cleanData($_POST["department"]);
	}
	
	if(!empty($_POST["trainer"])) {
		$trainer = $db_handle->cleanData($_POST["trainer"]);
	}
	
	$check_dept = $conn->query("SELECT department_code, hod FROM department_hods WHERE department_code = '$department'");
	$affectedrows = $check_dept->num_rows;
	
	if($affectedrows==0){

		$sql = "INSERT INTO department_hods (department_code, hod) VALUES ('$department','$trainer')";
		$sn = $db_handle->executeInsert($sql);  
		
		if(!empty($sn)) {
			$sql = "SELECT * FROM department_hods INNER JOIN departments ON departments.department_code = department_hods.department_code INNER JOIN trainers ON trainers.trainer_id = department_hods.hod WHERE department_hods.sn = '$sn' ORDER BY department_hods.sn DESC";
			$Result = $db_handle->readData_array($sql);
		}

		if(!empty($Result)) { 
		?>
			<tr>
				<td style="width:5%"><?php echo $Result[0]["department_code"]; ?></td>
				<td style="width:20%"><?php echo strtoupper($Result[0]["department_name"]); ?></td>
				<td style="width:40%"><?php echo strtoupper($Result[0]["first_name"]." ".$Result[0]["last_name"]); ?></td>
			</tr>
		<?php
		}
	}
	else{echo "Error"; exit();}
?>	
