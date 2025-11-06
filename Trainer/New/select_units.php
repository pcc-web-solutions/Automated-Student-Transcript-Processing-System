<?php
session_start();
include('../../Database/config.php');
$trainer_id = $_SESSION['Trainer'];

if(isset($_POST['selected_course']) && isset($_POST['selected_class'])){
	$selected_course = $_POST["selected_course"];
	$selected_class = $_POST['selected_class'];

	$unit = array();
	$sql="SELECT trainer_units.unit_code, units.unit_name FROM trainer_units INNER JOIN units ON units.unit_code = trainer_units.unit_code WHERE trainer_units.course_code='$selected_course' AND trainer_units.class_name = '$selected_class' AND trainer_units.trainer_id = '$trainer_id' ORDER BY trainer_units.unit_code";
	$unit_results=mysqli_query($conn, $sql) or die('wrong qry');

	while($row = mysqli_fetch_assoc($unit_results) ){
	  $unit_code = $row['unit_code'];
	  $unit_name = strtoupper($row['unit_name']);

	  $unit[] = array("unit_code" => $unit_code, "unit_name" => $unit_name);
	}
	echo json_encode($unit);
	exit();
}
?>
