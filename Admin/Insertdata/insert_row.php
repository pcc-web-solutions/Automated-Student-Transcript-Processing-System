<?php
	include("../../Database/dbcontroller.php");
	$db_handle = new DBController();
	$name = "";
	$adm = "";
	$course= "";
	$class = "";
	$gender = "";
    
	if(!empty($_POST["name"])) {
		$name = $db_handle->cleanData($_POST["name"]);
	}
	
	if(!empty($_POST["adm"])) {
		$adm = $db_handle->cleanData($_POST["adm"]);
	}
	if(!empty($_POST["course"])) {
		$course= $db_handle->cleanData($_POST["course"]);
	}
	if(!empty($_POST["classname"])) {
		$class = $db_handle->cleanData($_POST["classname"]);
	}
	if(!empty($_POST["gender"])) {
		$gender = $db_handle->cleanData($_POST["gender"]);
	}
	
	$check_adm = "SELECT adm FROM trainees WHERE adm = '$adm'";
	$affectedrows = $db_handle->numRows($check_adm);
	
	if($affectedrows==0){
		//echo "Trainee already registered";
			$sql = "INSERT INTO trainees (adm,name,course_code, class, gender) VALUES ('$adm','$name','$course','$class','$gender')";
			$sn = $db_handle->executeInsert($sql);  
			
			if(!empty($sn)) {
				$sql = "SELECT * from trainees WHERE sn = '$sn' ORDER BY sn DESC";
				$Result = $db_handle->readData_array($sql);
			}
			
		?>
		<?php 
			if(!empty($Result)) { 
		?>
			<tr>
				<td style="width:5%"><?php echo $Result[0]["sn"]; ?></td>
				<td style="width:20%"><?php echo $Result[0]["adm"]; ?></td>
				<td style="width:40%"><?php echo $Result[0]["name"]; ?></td>
				<td style="width:20%"><?php echo $Result[0]["gender"]; ?></td>
				<td style="width:15%"><?php echo $Result[0]["class"]; ?></td>
				<td style="width:15%"><?php echo $Result[0]["course_code"]; ?></td>
			</tr>
		<?php
			}
	}
	
?>	
