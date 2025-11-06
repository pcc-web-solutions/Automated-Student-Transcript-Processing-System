<?php
 require_once("../../Database/config.php");

 $adm = $_POST['adm'];
 $curr_status = $_POST['current_status'];

 if($curr_status == 1){
 	$set_to_0 = $conn->query("UPDATE trainees SET status = 0 WHERE adm = '$adm'");
 	if(!$set_to_0){echo "error";}else{echo "success";}
 	exit();
 }else if($curr_status == 0){
 	$set_to_1 = $conn->query("UPDATE trainees SET status = 1 WHERE adm = '$adm'");
 	if(!$set_to_1){echo "error";}else{echo "success";}
 	exit();
 }
 else{echo 'Error retrieving current status';}
 // echo 'Adm Number: '.$adm.'  Current status: '.$curr_status;
?>