<?php
require('../../Database/config.php');

$unit_code = $_POST['unit_code'];
$unit_name = $_POST['unit_name'];
$course = $_POST['course'];
$hrly_lessons = $_POST['hourly_lessons'];
$wkly_hours = $_POST['weekly_hours'];
$check_if_unit_exists = $conn->query("SELECT * FROM units WHERE unit_code = '$unit_code' AND courses_code = '$course'");
if($check_if_unit_exists->num_rows<1){
	$insert=$conn->query("insert into units(unit_code, unit_name, courses_code, hourly_lessons, weekly_hours)values('$unit_code', '$unit_name', '$course', '$hrly_lessons', '$wkly_hours')");
	if(!$insert){echo "Problem adding the unit";}else{echo 'Success';}
}else{
	echo "$unit_name alreay exists";
}
?>