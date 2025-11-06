<?php
	require_once "../../Database/config.php";
	require_once "../../Includes/rsignatory.php";

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

	$course_codes=$conn->query("SELECT DISTINCT(results_entry.course_code) AS codes FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm WHERE term = '$term' AND exam_year = '$year' AND trainees.status = '1' ORDER BY results_entry.course_code ASC");
	
	while($rows=mysqli_fetch_assoc($course_codes))
	{
		$codes[]=$rows;
	}

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetAutoPageBreak(true, 5);
	
	$pdf->SetAuthor('Musee Abiud');
	$pdf->SetTitle('Missing Marks');
	$pdf->SetFont('Times', '', 10);
	
	//Create a Page
	$pdf->AddPage();

	//Insert letter-head
	$pdf->Image('../../Images/letter_head.jpg',11,10,190);
	$pdf->Ln(35);

	//PDF Report title
	$pdf->Ln();
	$pdf->WriteHTMLCell(193,5,'','','<h2 style="text-align: center;" ><u>'.$term.' '.$year.' UNSUBMITTED MARKS </u></h2>',0);
	$pdf->Ln(10);
	
	$sno = 0;	
	foreach($codes as $code)
	{
		$code=$code['codes'];

		$courses=$conn->query("SELECT courses.code, courses.course_name, COUNT(units.unit_code) AS total_units_for_course FROM courses INNER JOIN units ON units.courses_code = courses.code WHERE code = '$code' ORDER BY courses.code ASC LIMIT 1");

			while($row=mysqli_fetch_assoc($courses))
			{
				++$sno;
				$course_name = html_entity_decode(strtoupper($row['course_name']));
				$total_units_for_this_course = $row['total_units_for_course'];	
			}

			$pdf->SetFont('Times', '', 11);
			$pdf->WriteHTMLCell(0,0,'','','<h4>'.$sno.". ".$course_name." (".$code.")".'</h4><style>h4{text-align: left; color: royalblue;}</style>',1,0);
			$pdf->Ln();
			
			//Getting all the classes for this course
			$classes = $conn->query("SELECT DISTINCT(classes.class_name) AS class FROM classes INNER JOIN courses ON classes.course_abrev = courses.course_abrev WHERE courses.code = '$code'");

			while($row=mysqli_fetch_assoc($classes)){
				$class = $row['class'];

				$units=$conn->query("SELECT DISTINCT(units.unit_code), units.unit_name, trainers.first_name, trainers.last_name FROM units LEFT JOIN trainer_units ON trainer_units.unit_code = units.unit_code AND trainer_units.class_name = '$class' LEFT JOIN trainers ON trainers.trainer_id = trainer_units.trainer_id INNER JOIN courses ON courses.code = units.courses_code INNER JOIN trainees ON trainees.course_code = courses.code INNER JOIN classes ON classes.class_name = trainees.class WHERE units.courses_code = '$code' AND classes.class_name = '$class' AND trainees.status = '1' AND units.unit_code NOT IN (SELECT DISTINCT(results_entry.unit_code) FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class WHERE results_entry.course_code = '$code' AND classes.class_name = '$class' AND term = '$term' AND exam_year = '$year' AND trainees.status = '1') GROUP BY units.unit_code ORDER BY units.unit_code ASC");
				
				if($units->num_rows>0){

					$unitsinmarks = $conn->query("SELECT COUNT(DISTINCT results_entry.unit_code) AS units_in_marks FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class WHERE results_entry.course_code = '$code' AND classes.class_name = '$class' AND term = '$term' AND exam_year = '$year'");

					//Total units in results entry for this session
					while($row=mysqli_fetch_assoc($unitsinmarks))
					{
						$units_in_marks = $row['units_in_marks'];	
					}

					
					if($total_units_for_this_course > $units_in_marks){
					//Display class name
					$pdf->SetFont('Times', '', 11);
					$pdf->WriteHTMLCell(0,0,'','','<h5 style="color: green;">'.$class.'</h5>',1,0);
					$pdf->Ln();

					//Display units
					$pdf->SetFont('Times', 'B', 10);		
					$pdf->Cell(10,5,'SN',1,0);
					$pdf->Cell(25,5,'UNIT CODE',1,0);
					$pdf->Cell(115,5,'UNIT NAME',1,0,'L');
					$pdf->Cell(40,5,'TRAINER NAME',1,0,'L');
					$pdf->Ln();
					$serial = 0;
					while($row=mysqli_fetch_assoc($units))
					{
						$pdf->SetFont('Times', '', 10);		
						$pdf->Cell(10,5,++$serial,1,0);
						$pdf->Cell(25,5,strtoupper($row['unit_code']),1,0);
						$pdf->Cell(115,5,strtoupper($row['unit_name']),1,0);
						$pdf->Cell(40,5,strtoupper($row['first_name']?? ""),1,0);
						$pdf->Ln();
					}
				}
			}
		}
	}
	
	$pdf->Ln(15);

	// Add signatories
	$rptname = "unsubmitted marks";
	$rptorientation = "portrait";
	if(get_signatories($rptname,$rptorientation)['status'] == true){
		echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
	}
	
	$pdf->Ln();
	$pdf->Image('../../images/stamp-holder.jpg',120,250,70);
	
	$pdf->Output($year." ".strtoupper($term)." UNSUBMITTED MARKS.pdf");
	
?>
