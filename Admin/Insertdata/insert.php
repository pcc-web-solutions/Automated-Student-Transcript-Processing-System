<?php 
session_start();
require_once "../../Database/dbcontroller.php";
$conn = new DBController();

$response = array();

function no_request(){
	echo json_encode(array("status"=>"error", "message"=>"No request sent to server!"));
}
function unknown_request(){
	echo json_encode(array("status"=>"error", "message"=>"Unknown request sent to server!"));
}

function save_report($conn){
	global $response, $session_id;
	!empty($_REQUEST['rname'])?$rname = $conn->cleanData($_REQUEST['rname']):$rname = NULL;
	!empty($_REQUEST['minnos'])?$minnos = $conn->cleanData($_REQUEST['minnos']):$minnos = NULL;
	!empty($_REQUEST['maxnos'])?$maxnos = $conn->cleanData($_REQUEST['maxnos']):$maxnos = NULL;

	$sql = "SELECT DISTINCT description FROM reports WHERE description = '$rname'";
	if($conn->numRows($sql) < 1){
		$sql = "INSERT INTO reports (description, min_nos, max_nos) VALUES ('$rname','$minnos','$maxnos')";
		$insertId = $conn->executeInsert($sql);
		if($insertId !== ""){
			$response = array("status"=>"success", "message"=>"Report successfully added");
		}
		else{
			$response = array("status"=>"error", "message"=>"Problem adding the report");
		}
	}
	else{
		$response = array("status"=>"error", "message"=>"$rname already exists");
	}
	echo json_encode($response);
}

function save_designation($conn){
	global $response, $session_id;
	!empty($_REQUEST['rptdesignation'])?$rptdesignation = $conn->cleanData($_REQUEST['rptdesignation']):$rptdesignation = NULL;
	$id = substr(str_shuffle('0123456789'), 0, 3);

	$sql = "SELECT DISTINCT code FROM designation WHERE description = '$rptdesignation'";
	if($conn->numRows($sql) < 1){
		$sql = "INSERT INTO designation (code, description) VALUES ('$id','$rptdesignation')";
		$insertId = $conn->executeInsert($sql);
		if($insertId !== ""){
			$response = array("status"=>"success", "message"=>"Successfully added");
		}
		else{
			$response = array("status"=>"error", "message"=>"Problem adding designation");
		}
	}
	else{
		$response = array("status"=>"error", "message"=>"$rptdesignation already exists");
	}
	echo json_encode($response);
}

function save_signatory($conn){
	global $response, $session_id;
	!empty($_REQUEST['sigdesignation'])?$sigdesignation = $conn->cleanData($_REQUEST['sigdesignation']):$sigdesignation = NULL;
	!empty($_REQUEST['sigtitle'])?$sigtitle = $conn->cleanData($_REQUEST['sigtitle']):$sigtitle = NULL;

	$sql = "SELECT DISTINCT designation FROM signatory WHERE designation = '$sigdesignation'";
	// if($conn->numRows($sql) < 1){
		$sql = "INSERT INTO signatory (designation, sname, rankpos) VALUES ('$sigdesignation','$sigtitle','0')";
		$insertId = $conn->executeInsert($sql);
		if($insertId !== ""){
			$response = array("status"=>"success", "message"=>"Registration successful");
		}
		else{
			$response = array("status"=>"error", "message"=>"Could not register signatory");
		}
	// }
	// else{
	// 	$response = array("status"=>"error", "message"=>"$sigdesignation already registered");
	// }
	echo json_encode($response);
}

function set_signatory($conn){
	global $response, $session_id;
	!empty($_REQUEST['report'])?$report = $conn->cleanData($_REQUEST['report']):$report = NULL;
	!empty($_REQUEST['signatories'])?$signatories = $_REQUEST['signatories']:$signatories = NULL;
	!empty($_REQUEST['designations'])?$designations = $_REQUEST['designations']:$designations = NULL;
	
	if($report !== null && $signatories !== null && $designations !== null){
		// Delete current set signatories for the selected report
		$sql = $conn->executeDelete("DELETE FROM rpt_signatory WHERE report = '$report'");

		// Initialize count, total failures and success queries
		$cnt = 0; $fails=0; $success=0;

		// Set signatories for every designation posted
		foreach ($designations as $designation) {
			$signatory = $signatories[$cnt];
			$sql = "INSERT INTO rpt_signatory (report, designation, sname) VALUES ('$report','$designation','$signatory')";
			$insertId = $conn->executeInsert($sql);
			if($insertId !== ""){
				$success++;
			}
			else{$fails++;}
			$cnt++;
		}
		if($success>0){$response = array("status"=>"success", "message"=>"Report signatories set successfully");}
		else{$response = array("status"=>"error", "message"=>"Could not set signatory(s)");}
	}
	else{
		$response = array("status"=>"error", "message"=>"No report or signatories selected");
	}
	echo json_encode($response);
}

function handle($request){
	global $conn;
	switch ($request) {
		case 'saveReport':
			save_report($conn);
			break;

		case 'saveDesignation':
			save_designation($conn);
			break;

		case 'saveSignatory':
			save_signatory($conn);
			break;

		case 'setSignatory':
			set_signatory($conn);
			break;

		default:
			unknown_request();
			break;
	}
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_POST['request'])) {
 		$request = $_POST['request']; handle($request);
 	}
}
else{
	unknown_request();
}
?>