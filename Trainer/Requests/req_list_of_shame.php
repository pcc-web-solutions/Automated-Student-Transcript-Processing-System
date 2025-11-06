<?php
	echo 'Yes';
	// session_start();
	
	// include('../../Database/config.php');

	// $trainer_id = $_SESSION['Trainer'];

	// //retrieve year from years table
	// $sql="select year from years";
	// $current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	// while($row=mysqli_fetch_assoc($current_year))
	// {$year=$row['year'];}

	// //retrieve term from term table
	// $sql="select term_code from terms";
	// $current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	// while($row=mysqli_fetch_assoc($current_term))
	// {$term=$row['term_code'];}


	// $course_codes=$conn->query("SELECT DISTINCT(results_entry.course_code) AS codes FROM results_entry INNER JOIN trainer_units ON trainer_units.course_code = results_entry.course_code WHERE trainer_units.trainer_id = '$trainer_id' AND term = '$term' AND exam_year = '$year'");
	// $num_rows = $course_codes->num_rows;
	
	// if($num_rows > 0){
	// 	while($rows=mysqli_fetch_assoc($course_codes))
	// 	{
	// 		$codes[]=$rows;
	// 	}
		
	// 	$all_marks_entered = 0;
	// 	$not_all_marks_entered = 0;
	// 	$course_count = 0;
	// 	foreach($codes as $code){
	// 		$code = $code['codes'];

	// 		//Getting all the classes for this course
	// 		$classes = $conn->query("SELECT DISTINCT(class_name) AS class FROM trainer_units INNER JOIN trainees ON trainees.class = trainer_units.class_name WHERE trainer_units.course_code = '$code' AND trainer_id = '$trainer_id' ");

	// 		if($classes->num_rows<1){
	// 			echo "You have no missing marks for this term session. All your marks were submitted successfully. Thank you.";
	// 			exit();
	// 		}
	// 		$courses=$conn->query("SELECT courses.code, courses.course_name, COUNT(units.unit_code) AS total_units_for_course FROM courses INNER JOIN units ON units.courses_code = courses.code WHERE code = '$code' ORDER BY courses.code ASC LIMIT 1"); 
		
	// 		$unitsinmarks=$conn->query("SELECT COUNT(DISTINCT results_entry.unit_code) AS units_in_marks FROM results_entry WHERE results_entry.course_code = '$code' AND term = '$term' AND exam_year = '$year'");
			
	// 		//Total units in results entry for this session
	// 		while($row=mysqli_fetch_assoc($unitsinmarks))
	// 		{
	// 			$units_in_marks = $row['units_in_marks'];	
	// 		}
			
	// 		while($row=mysqli_fetch_assoc($courses))
	// 		{
	// 			$total_units_for_this_course = $row['total_units_for_course'];	
	// 		}
			
	// 		if($total_units_for_this_course > $units_in_marks){
	// 			$status = "Success";	
	// 		}
	// 		elseif($total_units_for_this_course = $units_in_marks){
	// 			$status = "Success";
	// 		}
	// 		else{
	// 			$course_count++;
	// 			$status =  "All marks for the $course_count course entry attempts for this term session has been entered";
	// 		}
	// 	}
	// 	echo $status;
	// 	exit();
	// }
	// else{
	// 	echo 'No marks has been entered for this term session';
	// 	exit();
	// }

?>