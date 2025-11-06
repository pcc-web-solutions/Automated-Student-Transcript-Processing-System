<?php
include("../../Database/config.php");
if(isset($_POST['id'])){
	$id = $_POST['id'];
	$adm = array();
	$sql = $conn->query("SELECT DISTINCT(trainees.adm), trainees.status AS trainee_status, classes.status AS class_status FROM classes LEFT JOIN trainees ON trainees.class = classes.class_name WHERE class_id = '$id'");
	while ($row = mysqli_fetch_assoc($sql)) {
		$trainee_status = $row['trainee_status'];
		$status = $row['class_status'];
		$adms[] = $row;
	}

	if($status == "Active"){
		$lock = $conn->query("UPDATE classes SET status = 'Inactive' WHERE class_id = '$id'");
		foreach ($adms as $adm) {
			$trainee = $adm['adm'];	
			$mark_inactive = $conn->query("UPDATE trainees SET status = '0' WHERE adm = '$trainee'");
		}
		if (!$lock) {
			echo "Unable to deactivate this class"; exit();
		}else{echo "lock_success";exit();}
	}
	elseif ($status == "Inactive") {
		$unlock = $conn->query("UPDATE classes SET status = 'Active' WHERE class_id = '$id'");
		foreach ($adms as $adm) {
			$trainee = $adm['adm'];	
			$mark_active = $conn->query("UPDATE trainees SET status = '1' WHERE adm = '$trainee'");
		}
		if (!$unlock) {
			echo "Unable to activate this class"; exit();
		}else{echo "unlock_success";exit();}
	}
	else{
		echo 'Error identifying the class status';
	}
}
?>