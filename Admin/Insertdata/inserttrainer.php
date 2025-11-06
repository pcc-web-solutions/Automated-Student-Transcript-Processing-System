<?php
	include("../../Database/config.php");

	function validate($data){
		$data=trim($data);
		$data=stripslashes($data);
		$data=htmlspecialchars($data);
		return $data;
	}

	// Count number of head teachers and inreement by 1
	
	$set = '0123456789';
	$tr_id = substr(str_shuffle($set),0,3);
	$tr_fname = validate($_POST['tr_fname']);
	$tr_lname = validate($_POST['tr_lname']);
	$phone = validate($_POST['phone']);
	$deptid = validate($_POST['department']);
	$password = md5('12345678');
	$regdate = date('Y-m-d');

	if(empty($tr_fname)){echo "Please enter the trainer's first name";}
	elseif(empty($tr_lname)){echo "Please enter the trainer's first name";}
	elseif(empty($phone)){echo "Phone number field cannot be blank";}
	elseif(empty($deptid)){echo "Please select department";}
	else{
		$sql = "SELECT * FROM trainers WHERE first_name = '$tr_fname' AND last_name = '$tr_lname' OR phone_no = '$phone' LIMIT 1";
		$run = mysqli_query($conn, $sql);
		if(mysqli_num_rows($run)>0){
			echo "This trainer is already registered";
		}
		else{
			$intotrainers = $conn->query("INSERT INTO trainers (trainer_id,first_name,last_name,phone_no,department_id) VALUES ('$tr_id','$tr_fname','$tr_lname','$phone','$deptid')");
			
			//
			$check = $conn->query("SELECT * FROM users WHERE FirstName = '$tr_fname' AND LastName = '$tr_lname' OR Phone_No = '$phone'");
			if($check->num_rows < 1){
				$intousers = $conn->query("INSERT INTO users (user_id, FirstName, LastName, Phone_No, username, password, usertype, Date_Registered,Attempts) VALUES ('$tr_id','$tr_fname','$tr_lname','$phone','$phone','$password','Trainer','$regdate','4')");
				
				if($intousers || $intotrainers){echo "Success";}
			}
		}
	}

?>