<?php

if(isset($_POST['id']))
{
	require('../../Database/config.php');
	
	$id = $_POST['id'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$usertype = $_POST['usertype'];
	
	// $selectuser = $conn->query("SELECT * FROM users WHERE username = '$username'");
	// if($selectuser->num_rows<1){
		$update=$conn->query("UPDATE users SET FirstName = '$fname', LastName = '$lname', Phone_No = '$phone', username = '$username', password = '$password', usertype='$usertype' where user_id='$id'");
		if($update){echo 'Updated successfully';}
		else{echo 'Error updating';}
	// }else{echo "A user with the supplied username already exists. Kindly enter a new username"; exit();}
}