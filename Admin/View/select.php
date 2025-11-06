<?php 
session_start();
require_once "../../Database/dbcontroller.php";
$conn = new DBController();

function no_request(){
	return json_encode(array("status"=>"error", "message"=>"No request sent to server!"));
}
function unknown_request(){
	return json_encode(array("status"=>"error", "message"=>"Unknown request sent to server!"));
}

function getSignatories(){
	global $conn; $response = array(); $signatories = array();
	$sql = "SELECT DISTINCT signatory.sn, designation.description, sname, trainers.trainer_id, trainers.first_name, trainers.last_name, trainers.phone_no, rankpos FROM signatory INNER JOIN designation ON designation.code = signatory.designation INNER JOIN trainers ON trainers.trainer_id = signatory.sname ORDER BY rankpos ASC";
	$numrows = $conn->numRows($sql);
	if ($numrows > 0) {
		$rows = $conn->readData_array($sql);
		foreach($rows AS $row){
			$signatories[] = array(
				"sn"=>$row['sn'],
				"designation"=>$row['description'],
				"trcode"=>$row['trainer_id'],
				"name"=>$row['first_name']." ".$row['last_name'],
				"phone"=>$row['phone_no'],
				"rankpos"=>$row['rankpos']
			);
		}
		$response = array("status"=>"success", "data"=>$signatories, "rows"=>$numrows);
	}
	else{
		$response = array("status"=>"error", "message"=>"no data");
	}
	return json_encode($response);
}

function getReports(){
	global $conn; $response = array(); $data = array();
	$sql = "SELECT DISTINCT sn, description, min_nos, max_nos FROM reports ORDER BY description ASC";
	$numrows = $conn->numRows($sql);
	if ($numrows > 0) {
		$rows = $conn->readData_array($sql);
		foreach($rows AS $row){
			$data[] = array(
				"sn"=>$row['sn'],
				"description"=>$row['description'],
				"min_nos"=>$row['min_nos'],
				"max_nos"=>$row['max_nos']
			);
		}
		$response = array("status"=>"success", "data"=>$data, "rows"=>$numrows);
	}
	else{
		$response = array("status"=>"error", "message"=>"no data");
	}
	return json_encode($response);
}

function getDesignations(){
	global $conn; $response = array(); $data = array();
	$sql = "SELECT DISTINCT sn, code, description FROM designation ORDER BY description ASC";
	$numrows = $conn->numRows($sql);
	if ($numrows > 0) {
		$rows = $conn->readData_array($sql);
		foreach($rows AS $row){
			$data[] = array(
				"sn"=>$row['sn'],
				"code"=>$row['code'],
				"description"=>$row['description']
			);
		}
		$response = array("status"=>"success", "data"=>$data, "rows"=>$numrows);
	}
	else{
		$response = array("status"=>"error", "message"=>"no data");
	}
	return json_encode($response);
}

function getReport_set_designations(){
	global $conn; $response = array(); $data = array();
	!empty($_REQUEST['rptoption'])?$rptoption = $conn->cleanData($_REQUEST['rptoption']):$rptoption = null;
	$sql = "SELECT DISTINCT designation.code, designation.description, rankpos FROM signatory INNER JOIN designation ON designation.code = signatory.designation ORDER BY rankpos ASC";

	$sql2 = "SELECT min_nos, max_nos FROM reports WHERE description = '$rptoption'";
	$rows2 = $conn->readData_array($sql2);
	$min_nos = $rows2[0]['min_nos'];
	$max_nos = $rows2[0]['max_nos'];
	
	$numrows = $conn->numRows($sql);
	if ($numrows > 0) {
		$rows = $conn->readData_array($sql);
		foreach($rows AS $row){
			
			$code = $row['code'];
			$designation = $row['description'];
			$signatories = '';

			// checking for the existence of the signatory to mark them set already
			$check = "SELECT designation.description, rpt_signatory.sname FROM rpt_signatory INNER JOIN designation ON designation.code = rpt_signatory.designation WHERE designation = '$code' AND rpt_signatory.report = '$rptoption'";
			if($conn->numRows($check)>0){$status = "checked";}else{$status="";}
			
			$sql = "SELECT DISTINCT sname, first_name, last_name FROM trainers INNER JOIN signatory ON trainers.trainer_id = signatory.sname WHERE signatory.designation = '$code'";
			$numrows = $conn->numRows($sql);
			if ($numrows > 0) {
				$rows = $conn->readData_array($sql);

				$signatories .='<select class="form-control form-control-sm signfor" name="signfor[]" onchange="toggle_signatory(this)">';
				foreach($rows AS $row){
					$sigselected="";
					$scode = $row['sname'];
					$sname = $row['first_name']." ".$row['last_name'];

					if($status == "checked"){
						$chkStatus = $conn->readData_array($check);
						$sigselected = $chkStatus[0]['sname'];
					}

					if($sigselected == $scode){$state = "selected";}else{$state = "";}

					$signatories .='
						<option value='.$scode.' '.$state.'>'.$sname.'</option>';
				}
				$signatories .='<option value="new">--Add New--</option>';
				$signatories .='</select>';
			}

			$data[] = array(
				"code"=>$code,
				"description"=>$code." - ".$designation,
				"signatory"=>$signatories,
				"status"=>$status
			);
		}
		$response = array("status"=>"success", "data"=>$data, "rows"=>$numrows, "minnos"=>$min_nos, "maxnos"=>$max_nos);
	}
	else{
		$response = array("status"=>"error", "message"=>"no data");
	}
	return json_encode($response);
}

function handle_requests($request){
	if(!empty($request)){
		switch ($request) {
			case 'signatories':
				echo getSignatories();
				break;
			case 'designations':
				echo getDesignations();
				break;
			case 'reports':
				echo getReports();
				break;
			case 'report_set_designations':
				echo getReport_set_designations();
				break;

			default:
				echo unknown_request();
				break;
		}
	}
	else{
		echo no_request();
	}
}
handle_requests($_POST['request']);
?>