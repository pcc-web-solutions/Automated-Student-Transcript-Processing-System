<?php
session_start();

if(!$_SESSION["Admin"])
{
    header('location: ../../login-page.php');		
}

if (isset($_POST['click'])) {
	// code...
	include "../../Database/config.php";

	//Logged in user session
	$loggedin = $_SESSION["Admin"];

	$id = $_SESSION["Admin"];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$username = $_POST['username'];
	$npassword = $_POST['npassword'];
	$password = md5($npassword);
	$usertype = $_POST['usertype'];

	if(strlen($npassword)<6){echo "You must enter a password of not less than 6 characters"; exit();}
	$selectuser = $conn->query("SELECT * FROM users WHERE username = '$username'");
	if($selectuser->num_rows<1){
		$update=$conn->query("UPDATE users SET FirstName = '$fname', LastName = '$lname', Phone_No = '$phone', username = '$username', password = '$password', usertype='$usertype' where user_id='$id'");
		if($update){echo 'success';}
		else{echo 'Error updating your profile';}
	}
	else{
		echo "A user with the supplied username already exists. Kindly enter a new username"; 
	}
}