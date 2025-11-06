<?php
require('../../Database/config.php');


$classid = substr(str_shuffle('0123456789'),0,3);
$course = strip_tags($_POST['course']);
$year = strip_tags($_POST['year']);
$intake = strip_tags($_POST['intake']);
$classname = $course.'/'.$year.$intake;

$selectduplicates = $conn->query("select * from classes where class_name = '$classname'");
$duplicates = $selectduplicates->num_rows;
if($duplicates > 0){
	echo 'Error';
}
else
{
	$insertdata = $conn->query("INSERT INTO classes (class_id, course_abrev, academic_year, intake, class_name) VALUES ('$classid','$course', '$year', '$intake', '$classname')");

	echo 'Success';
}
?>