<?php
if(isset($_POST['trainer_id']))
{
	require('../../Database/config.php');
	
	$trainer_id=$_POST['trainer_id'];
	$tr_fname=$_POST['first_name'];
	$tr_lname=$_POST['last_name'];
	$trainer_phone=$_POST['trainer_phone'];
	$department_id=$_POST['department_id'];
	$date = date('Y-m-d');
	$password = md5('trainer2023');

	$updatetrainers=$conn->query("update trainers set  first_name='$tr_fname', last_name='$tr_lname', phone_no='$trainer_phone', department_id='$department_id'where trainer_id= '$trainer_id' ");
	
	//Pick initial phone number as the initial username
	$selecttrainer=$conn->query("SELECT user_id FROM users WHERE user_id = '$trainer_id'");
	if ($selecttrainer->num_rows>0) {
		
		while($user = mysqli_fetch_assoc($selecttrainer)){$user_id = $user['user_id'];}
		
		$updateusers = $conn->query("UPDATE users SET FirstName = '$tr_fname', LastName = '$tr_lname', Phone_No = '$trainer_phone', username = '$trainer_phone' WHERE user_id = '$user_id'");
	}
	else{
		$inserttrainer = $conn->query("INSERT INTO users (user_id,FirstName,LastName,Phone_No,username,password,usertype,Date_Registered,Attempts) 
		VALUES ('$trainer_id','$tr_fname','$tr_lname','$trainer_phone','$trainer_phone','$password','Trainer','$date','4')");
	}
	if($updatetrainers)
	{
		echo "Record updated"; 
	}
	else
	{
		echo "Problem updating record"; 
	}	
}
?>