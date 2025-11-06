<?php
	include("../../Database/config.php");

	function validate($data){
		$data=trim($data);
		$data=stripslashes($data);
		$data=htmlspecialchars($data);
		return $data;
	}
	$usertype = validate($_POST['usertype']);
	$user_id = validate($_POST['user_id']);
	$fname = validate($_POST['first_name']);
	$lname = validate($_POST['last_name']);
	$phone = validate($_POST['phone_no']);
	$username = validate($_POST['username']);
	$npassword = md5(validate($_POST['npassword']));
	$cpassword = md5(validate($_POST['cpassword']));
	
	$date = date('Y-m-d');

	if(strlen($npassword) < 6){echo "Password must be atleast 6 characters"; }
	
	else{
		if($usertype == 'Trainer'){
			$inserttrainer = $conn->query("INSERT INTO users (user_id, FirstName, LastName, Phone_No, username, password, usertype, Date_Registered, Attempts) VALUES ('$user_id', '$fname','$lname','$phone','$username','$npassword','$usertype','$date','4')");
		}
		elseif($usertype == 'Department Head') {
			$sql = $conn->query("INSERT INTO users (user_id, FirstName, LastName, Phone_No, username, password, usertype, Date_Registered, Attempts) VALUES ('$user_id','$fname','$lname','$phone','$username','$npassword','$usertype','$date','4')");
			if($sql){echo "The user $user_id has been added successfully.";exit();}
			else{echo "Problem submitting information.";}
			exit();
		}
	}

?>