<?php
session_start();
if(!$_SESSION['Trainer']){header("location: ../../login-page.php");}
else{
	include "../../Database/config.php";
	$context = filter_input(INPUT_POST, 'context');
	$action = filter_input(INPUT_POST, 'action');
	$adm = filter_input(INPUT_POST, 'adm');
	$course = filter_input(INPUT_POST, 'course');
	$unit = filter_input(INPUT_POST, 'unit');
	$cat = filter_input(INPUT_POST, 'cat', FILTER_VALIDATE_INT);
	$exam = filter_input(INPUT_POST, 'exam', FILTER_VALIDATE_INT);
	$term = filter_input(INPUT_POST, 'term');
	$year = filter_input(INPUT_POST, 'year');

	$response = "";

	function save(){
		global $conn, $adm, $unit, $course, $cat, $exam, $year, $term, $response;
		$sql="SELECT adm, course_code, unit_code, exam_year, term from results_entry where adm='$adm' and course_code='$course' and unit_code='$unit' and exam_year='$year' and term='$term'";
		$rslt=mysqli_query($conn, $sql);
		if(mysqli_num_rows($rslt)==0){
		$sql="INSERT INTO results_entry(adm, course_code, unit_code, cat, exam, term, exam_year) VALUES('$adm','$course', '$unit', '$cat','$exam', '$term', '$year')";
		$results=mysqli_query($conn, $sql);
		if($results){$response = "success";}else{$response = "<strong>Error:</strong> problem saving record";}}
		else {$response = "Marks has already been entered. You can only update!"; }
		return $response;
	}

	function update(){
		global $conn, $adm, $unit, $course, $cat, $exam, $year, $term, $response;
		$sql = "UPDATE results_entry SET cat = '$cat', exam = '$exam' WHERE adm = '$adm' AND unit_code = '$unit' AND term = '$term' AND exam_year = '$year'";
		$result=mysqli_query($conn, $sql);
		if($result){$response = "success";}else{$response = "<strong>Error:</strong> problem saving record";}
		return $response;
	}

	function handle(){
		global $response, $action;
		if($action === "save"){$response = save();}
		else if($action === "update"){$response = update();}
		else{$response = "<strong>Error:</strong> Unknown action";}
		return $response;
	}

	function handleAll(){
		global $conn;
		if(!empty($_REQUEST['ids'])){
			$rows_inserted = 0;
			$rows_updated = 0;
			foreach($_REQUEST['ids'] as $key=>$ids){
				$id = $ids;
				$sn = substr(str_shuffle('0123456789'),0,2);
				$adm = $_REQUEST['adm'][$key];
				$course_code=$_REQUEST['code'][$key];
				$unit_code=$_REQUEST['unit_code'][$key];
				$cat = $_REQUEST['cat'][$key];
				$exam = $_REQUEST['exa'][$key];
				
				$term = $_REQUEST['term'][$key];
				$year = $_REQUEST['year'][$key];

				$sql="SELECT adm, course_code, unit_code, exam_year, term FROM results_entry WHERE adm='$adm' AND course_code='$course_code' AND unit_code='$unit_code' AND exam_year='$year' AND term='$term'";
				$records=mysqli_query($conn, $sql) or die ('Error fetching a record');
				
				$rows_affected=0;
				if(mysqli_num_rows($records)==0){
					$sql="INSERT INTO results_entry(adm, course_code, unit_code, cat, exam, term, exam_year) VALUES('$adm','$course_code', '$unit_code', '$cat','$exam', '$term', '$year')";
					$results=mysqli_query($conn, $sql) or die('Error inserting a record');
					$rows_affected++;
					$rows_inserted++;
				}
				else{
					$sql="UPDATE results_entry SET cat='$cat' AND exam='$exam' WHERE adm='$adm' AND course_code='$course_code' AND unit_code='$unit_code' AND exam_year='$year' AND term='$term'";
					$results=mysqli_query($conn, $sql) or die('Error updating a record');
					$rows_affected++;
					$rows_updated++;
				}	
			}
			if($rows_affected !=0){echo "$rows_inserted Row(s) uploaded"."<br>"."$rows_updated Row(s) updated";}
			else {echo "Error"; }
		}
		else{echo "Error";}
	}

	switch ($context) {
		case 'all':
			handleAll();
			break;
		case 'single':
			echo handle();
			break;
		
		default:
			echo "<strong>Error:</strong> Unknown request";
			break;
	}
	
}
?>