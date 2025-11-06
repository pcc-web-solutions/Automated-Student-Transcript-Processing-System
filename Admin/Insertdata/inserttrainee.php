<?php

	include("../../Database/config.php");

    $fname = strip_tags($_POST['fname']);
	$lname = strip_tags($_POST['lname']);
	$gender = strip_tags($_POST['gender']);
	$adm = strip_tags($_POST['adm']);
	$class = strip_tags($_POST['class']);
	$course = strip_tags($_POST['course']);
	$fullname = $fname.' '.$lname;
    
	if(empty($fname[$i])){echo "The first name field is blank";exit();}
	
	if(empty($lname[$i])){echo "The last name field is blank";exit();}
	
	if(empty($gender[$i])){echo "Please select gender";exit();}
	
	if(empty($adm[$i])){echo "Admission number is blank";exit();}
	
	if(empty($class[$i])){echo "Class field is blank";exit();}
	
	if(empty($course[$i])){echo "Please select course";exit();}

	$sqlselect = $conn->query("SELECT * FROM trainees WHERE adm = '$adm' LIMIT 1");
	$numrows = $sqlselect->num_rows;
	
	if($numrows>=1){
		echo "The trainee already exists.";
		exit();
	}else {
		$sql = "INSERT INTO trainees (adm,name,course_code,class,gender) 
		VALUES ('$adm','$fullname','$course','$class','$gender')";
		$runsql = mysqli_query($conn, $sql);
	}
	
	if($runsql){echo "Data saved successfully.";}
    else{echo "Unable to save records";}
?>