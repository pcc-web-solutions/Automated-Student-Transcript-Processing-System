<?php 
session_start();
require_once "../../Database/dbcontroller.php";

$conn = new DBController();

function no_request(){ echo "false"; }

function unknown_request(){ echo "false"; }

function editSignatory($conn, $column, $value, $sn,$table){
    $sql = "UPDATE ".$table." SET " . $column . "='" . $value . "' WHERE sn = " .$sn ;
    echo $conn->executeUpdate($sql);
}

function editReport($conn, $column, $value, $sn){
    $sql = "UPDATE reports SET ".$column."='".$value."' WHERE sn = ".$sn ;
    echo $conn->executeUpdate($sql);
}

function editDesignation($conn, $column, $value, $sn){
    $sql = "UPDATE designation SET ".$column."='".$value."' WHERE sn = ".$sn ;
    echo $conn->executeUpdate($sql);
}

function handle($request){
    global $conn;
    !empty($_REQUEST['column'])?$column = $conn->cleanData($_REQUEST['column']):$column = NULL;
    !empty($_REQUEST['value'])?$value = $conn->cleanData($_REQUEST['value']):$value = NULL;
    !empty($_REQUEST['sn'])?$sn = $conn->cleanData($_REQUEST['sn']):$sn = NULL;
    !empty($_REQUEST['table'])?$table = $conn->cleanData($_REQUEST['table']):$table = NULL;

    switch ($_POST['request']) {
        case 'editSignatory':
            editSignatory($conn, $column, $value, $sn, $table);
            break;
        case 'editReport':
            editReport($conn, $column, $value, $sn);
            break;
        case 'editDesignation':
            editDesignation($conn, $column, $value, $sn);
            break;
        default:
            unknown_request();
            break;
    }
}

if($_POST['request']){
    handle($_POST['request']);
}
else{
    no_request();
}
?>