<?php
require('../../Database/config.php');

$unit_code = $_POST['unit_code'];
$unit_name = $_POST['unit_name'];
$course = $_POST['course'];

$check_if_unit_exists = $conn->query("SELECT * FROM units WHERE unit_code = '$unit_code' AND courses_code = '$course'");
if($check_if_unit_exists->num_rows<1){
	$insert=$conn->query("insert into units(unit_code, unit_name, courses_code)values('$unit_code', '$unit_name', '$course')");
	if(!$insert){echo "Problem adding the unit";}else{echo 'Success';}
}else{
	echo "$unit_name alreay exists";
}
?>