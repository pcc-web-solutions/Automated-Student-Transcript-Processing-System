<?php 

require('../../Database/config.php');

if(!empty($_REQUEST['ids']))
{
	foreach($_REQUEST['ids'] as $key=>$ids)
	{
		$id = $ids;
		$sn = substr(str_shuffle('0123456789'),0,2);
		$adm = $_REQUEST['adm'][$key];
		$course_code=$_REQUEST['code'][$key];
		$unit_code=$_REQUEST['unit_code'][$key];
		$cat = $_REQUEST['cat'][$key];
		$exam = $_REQUEST['exa'][$key];
		
		$term = $_REQUEST['term'][$key];
		$year = $_REQUEST['year'][$key];

		$sql="select adm, course_code, unit_code, exam_year, term from results_entry where adm='$adm' and course_code='$course_code' and unit_code='$unit_code' and exam_year='$year' and term='$term'";
		
		$rslt=mysqli_query($conn, $sql) or die ('Error in query');
		
		$rows_affected=0;
		if(mysqli_num_rows($rslt)==0)
		{

			$sql="INSERT into results_entry(adm, course_code, unit_code, cat, exam, term, exam_year) values('$adm','$course_code', '$unit_code', '$cat','$exam', '$term', '$year')";
			$results=mysqli_query($conn, $sql) or die('error on query');
			
			$rows_affected++;

		}
		
		
	}
	
	if($rows_affected !=0)
	{
	echo "Data saved";
	}
	else {echo "Marks for that unit has been entered. You can only update!"; }
}
?>