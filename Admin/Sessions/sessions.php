<?php
session_start();
include('../../Database/config.php');

if(isset($_POST['adm_no'])){
	if($_POST['adm_no'] == ""){echo "Error"; exit();}
	else{
		$_SESSION["adm_no"]=$_POST["adm_no"];
		echo "Success";
	}
	exit();
}
elseif(isset($_POST['selected_course'])){
	
	if($_POST['selected_course'] == ""){echo "Error"; exit();}
	else{
		$_SESSION["selected_course"]=$_POST["selected_course"];
		echo "Success";
	}
	exit();
}
else{ 
	echo "Error"; 
}	
?>