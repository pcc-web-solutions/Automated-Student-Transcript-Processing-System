<?php
if(isset($_POST['request'])){
	session_start();
	
	include('../../Database/config.php');

	$count_departments = $conn->query("SELECT COUNT(DISTINCT department_code) AS total_departments FROM departments");
	$results=$count_departments->num_rows;
	if($results > 0){
		$response = "Success";
	}
	else{
		$response = "Error";
	}
	echo $response;
}
else{
	echo "Error sending your request to the server. Kindly try again later";
}
?>