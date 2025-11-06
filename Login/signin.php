<?php
	session_start();
	include("../Database/config.php");
	//retrieve year from years table
	$sql="select year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}

	//retrieve term from term table
	$sql="select term_name from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_name'];}

	$check_mark_entry_status = $conn->query("SELECT * FROM mark_entry_dates WHERE year = '$year' AND term = '$term'");
    if($check_mark_entry_status->num_rows>0){
      while ($data = mysqli_fetch_assoc($check_mark_entry_status)) {
        $_SESSION['mark_entry_status'] = $data['status'];
      }
    }

	if(isset($_POST['username']) && isset($_POST['password'])){
		function validate($data){
			$data=trim($data);
			$data=stripslashes($data);
			$data=htmlspecialchars($data);
			return $data;
		}
		$username=validate($_POST['username']);
		$password=md5(validate($_POST['password']));

		if(empty($username)){
			header("location: ../login-page.php?error=Please enter username.");
			exit();
		}
		if(empty($password)){
			header("location: ../login-page.php?error=Please enter Password.");
			exit();
		}
		
		//Count of users with similar userid
		$countfounduser = "SELECT COUNT(DISTINCT username) AS foundusers FROM users WHERE username = '$username'";
		$runquery = mysqli_query($conn, $countfounduser);
		while($recordsfound = mysqli_fetch_array($runquery)){
			$numberfound = $recordsfound['foundusers'];
		}
		
		//Check if the userid exists
		if($numberfound <= 0){
			header("location: ../login-page.php?error=Incorrect username.");
			exit();
		}
		
		else{
			//selecting user records where the username 
			$selectuser = "SELECT * FROM users WHERE username = '$username'";
			$runquery = mysqli_query($conn, $selectuser);
			while($selecteduser = mysqli_fetch_assoc($runquery)){
				$loginattempts = $selecteduser['Attempts'];
				$userpassword = $selecteduser['password'];
				$usertype = $selecteduser['usertype'];
				$userid = $selecteduser['user_id'];
				
				if($loginattempts < 0 || $loginattempts == 0){
					header("location: ../login-page.php?error=Sorry! This account is blocked.");
					exit();
				}
				//check if the password is correct
				else if($password === $userpassword){
					
					if($usertype === "Admin"){
						$_SESSION['Admin'] = $userid;
						header("location: ../Admin/");
						$updatesql = "UPDATE users SET Attempts = '4' WHERE username = '$username' ";
						$runsql = mysqli_query($conn, $updatesql);
						exit();
					}
					elseif($usertype === "Exam Officer"){
						
						// header("location: ../login-page.php?error=Oops!! Exam Officers is Page still under maintenance");
						$_SESSION['ExamOfficer'] = $userid;
						header("location: ../Examoffice/");
						$updatesql = "UPDATE users SET Attempts = '4' WHERE username = '$username' ";
						$runsql = mysqli_query($conn, $updatesql);
						exit();
					}
					elseif($usertype === "HOD"){
						
						// header("location: ../login-page.php?error=Oops!! H.O.Ds panel is still under maintenance");
						$get_department = $conn->query("SELECT department_code FROM department_hods WHERE hod = '$userid'");
						if($get_department->num_rows>0){
							while ($record = mysqli_fetch_assoc($get_department)) {
								$dept = $record['department_code'];
							}
							$_SESSION['dept'] = $dept;
						}
						$_SESSION['hod'] = $userid;
						header("location: ../hod/");
						$updatesql = "UPDATE users SET Attempts = '4' WHERE username = '$username' ";
						$runsql = mysqli_query($conn, $updatesql);
						exit();
					}
					elseif($usertype === "Trainer"){
						// header("location: ../login-page.php?error=Oops!! Trainers Page is still under maintenance");
						$_SESSION['Trainer'] = $userid;
						header("location: ../Trainer/");
						$updatesql = "UPDATE users SET Attempts = '4' WHERE username = '$username' ";
						$runsql = mysqli_query($conn, $updatesql);
						exit();
					}
					else{
						header("location: ../login-page.php?error=Problem identifying your usertype.");
					}
					exit();
				}
				else{
					$loginattempts--;
					if($loginattempts < 1){
						header("location: ../login-page.php?error=Sorry! This account is blocked.");
						exit();
					}else{
					$updatesql = "UPDATE users SET Attempts = '$loginattempts' WHERE username = '$username' ";
					$runsql = mysqli_query($conn, $updatesql);
					header("location: ../login-page.php?error=Incorrect Password. $loginattempts attempt(s) remaining"); exit();}
				}
			}	
		}
	}
	else{
		header("location: ../login-page.php");
	}		
?>