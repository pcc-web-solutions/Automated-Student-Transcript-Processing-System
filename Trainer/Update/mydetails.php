<?php
session_start();

if(!$_SESSION["Trainer"])
{
    header('location: ../../login-page.php');		
}

if (isset($_POST['click'])) {
	// code...
	include "../../Database/config.php";

	//Logged in user session
	$loggedin = $_SESSION["Trainer"];

	$id = $_SESSION["Trainer"];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$usertype = $_POST['usertype'];

	$sql="UPDATE users SET FirstName = '$fname', LastName = '$lname', Phone_No = '$phone', username = '$username', password = '$password', usertype='$usertype' where user_id='$id'";

	$results=mysqli_query($conn, $sql);

	if($results){echo 'Data updated successfully';}
	else{echo 'Error updating data';}
}