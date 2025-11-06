<?php
require('../../Database/config.php');


$code = strip_tags($_POST['code']);
$course_name = strip_tags($_POST['coursename']);
$course_abrev = strip_tags($_POST['abrev']);
$department = strip_tags($_POST['department']);

$query="select code from courses where code = '$code'";
$query_result=mysqli_query($conn,$query);
if(mysqli_num_rows($query_result)>0)
{ 
	echo 'Course already exist';
}

else
{
	$insertcourse=$conn->query("INSERT into courses(code, course_name,course_abrev, department_code) values ('$code', '$course_name', '$course_abrev', '$department')");
	if(!$insertcourse){echo "Problem submitting information";}
	else{echo 'Saved';}
	exit();
}
?>