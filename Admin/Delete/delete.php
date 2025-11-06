<?php 
require_once "../../Database/config.php";
// $conn = new DBController();

$response = array();

function deleterecord($conn, $table, $column, $value){
	$sql = "DELETE FROM ".$table. " WHERE ".$column." = '".$value."' ";
	
	if(!$conn->query($sql)){$response = array("status"=>"error", "message"=>"Unable to delete record");}
	else{$response = array("status"=>"success", "message"=>"Record delete successfully");}
	return json_encode($response);
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$table = $_POST['table'];
	$column = $_POST['column'];
	$value = $_POST['value'];
	echo deleterecord($conn, $table, $column, $value);
}
else{
	$response = array("status"=>"error", "message"=>"Request sent with unknown method");
	echo json_encode($response);
}

?>