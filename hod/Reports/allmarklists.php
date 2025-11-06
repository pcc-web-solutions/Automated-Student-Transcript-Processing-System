<?php
	session_start();
	$dept = $_SESSION['dept'];
	include('../../Database/config.php');
	include('../../Includes/rsignatory.php');

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

	$unit_codes = $conn->query("SELECT DISTINCT(units.unit_code), units.unit_name,courses.code, courses.course_name, if(CHAR_LENGTH(courses.code) >=5, substring_index(courses.code, '/', -1), 'N/A') AS module, departments.department_code, departments.department_name FROM results_entry INNER JOIN units ON units.unit_code = results_entry.unit_code INNER JOIN courses ON courses.code = units.courses_code INNER JOIN trainees ON trainees.course_code = courses.code INNER JOIN departments ON departments.department_code = courses.department_code WHERE departments.department_code = '$dept' AND results_entry.term = '$term' AND results_entry.exam_year = '$year' AND trainees.status = '1' ORDER BY courses.code, units.unit_code ASC");
	if($unit_codes->num_rows <= 0){
		echo '<div class="alert alert-warning"><h4>No marks</h4></div>';
		exit();
	}
	
	while($data=mysqli_fetch_array($unit_codes))
	{
		$units[] = $data;
	}
	

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetAutoPageBreak(true, 5);

	$pdf->SetTitle('Marksheets');
	$pdf->SetSubject('All Marksheets');
	$pdf->SetAuthor('Julius Okoth');
	$pdf->SetCreator('Musee Abiud');
	
	$pdf->SetFont('Times', '', 10);
	
	foreach($units as $array_data)
	{
		$unitcode = $array_data['unit_code'];
		$unitname = strtoupper($array_data['unit_name']);
		$coursecode = strtoupper($array_data['code']);
		$coursename = strtoupper($array_data['course_name']);
		$module = strtoupper($array_data['module']);
		$departmentcode = strtoupper($array_data['department_code']);
		$departmentname = strtoupper($array_data['department_name']);
		$date = strtoupper(date('d-M-Y'));
		
		$select_classes=$conn->query("SELECT DISTINCT(classes.class_name) FROM classes INNER JOIN trainees ON trainees.class = classes.class_name INNER JOIN courses ON courses.code = trainees.course_code WHERE courses.code = '$coursecode' AND trainees.status = '1' ORDER BY classes.class_name DESC");

		while($classes_data=mysqli_fetch_array($select_classes)){
			
			$classname = $classes_data['class_name'];
			$select_trainer = $conn->query("SELECT trainer_units.trainer_id, trainers.first_name, trainers.last_name FROM trainer_units INNER JOIN trainers ON trainers.trainer_id = trainer_units.trainer_id WHERE trainer_units.class_name = '$classname' AND trainer_units.unit_code = '$unitcode' ");
			if($select_trainer->num_rows>0){
				while($row=mysqli_fetch_assoc($select_trainer)){
					$trainername = strtoupper($row['first_name']." ".$row['last_name']);
				}
			}else{$trainername = "N/A";}

			//Create a Page
			$pdf->AddPage();
			
			//Insert letter-head
			$pdf->Image('../../Images/letter_head.jpg',11,7,190);
			$pdf->Ln(35);

			//PDF Report title
			$pdf->Ln();
			$pdf->SetFont('Times', 'B', 12);
			$pdf->WriteHTMLCell(0,0,'','','<u><h4 style="color: royalblue; text-align: center;">'.$year.' '.strtoupper($term).' MARKLIST FOR '.$classname." CLASS".'</h4></u>',0);
			$pdf->Ln();

			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'DEPARTMENT:    _________________________________________________________________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(160,5,' '.$departmentname,0,0,'L');
			$pdf->Ln();
			$pdf->Ln(2);
			
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'COURSE NAME:  _________________________________________________________________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(160,5,' '.$coursename.' - ('.$coursecode.')',0,0,'L');
			$pdf->Ln();
			$pdf->Ln(2);
			
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'SUBJECT NAME: __________________________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(100,5,' '.$unitname.' - ('.$unitcode.')',0,0,'L');
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'MODULE/STAGE: _______________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(30,5,'  '.$module,0,0,'C');
			$pdf->Ln();
			$pdf->Ln(2);

			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'TRAINER:          ________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(60,5,' '.$trainername,0,0,'L');
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(10,5,'DATE: ______________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(30,5,'   '.$date,0,0,'L');
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(10,5,'SIGN: __________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Ln();
			$pdf->Ln(5);

			$select_trainees = $conn->query("SELECT DISTINCT(trainees.adm), trainees.name FROM trainees INNER JOIN courses ON courses.code = trainees.course_code LEFT JOIN results_entry ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class WHERE courses.code = '$coursecode' AND trainees.class = '$classname' AND trainees.status = '1' ");
			$number_of_trainees = $select_trainees->num_rows;
			if($number_of_trainees > 0){
				
				$pdf->SetFont('Times', 'B', 12);
				$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: green;"><u>'."TRAINEES PERFOMANCE".'</u> </h4>',0);
				$pdf->Ln();
				$pdf->Ln(2);
				
				$pdf->SetFont('Times', 'B', 10);
				$pdf->Cell(25,5,'ADM. NO',1,0,'L');
				$pdf->Cell(65,5,'FULL NAME',1,0,'L');
				$pdf->Cell(25,5,'CAT (30%)',1,0,'L');
				$pdf->Cell(25,5,'EXAM (70%)',1,0,'L');
				$pdf->Cell(25,5,'TOT (100%)',1,0,'L');
				$pdf->Cell(25,5,'GRADE',1,0,'L');
				$pdf->Ln();
				
				while($trainee = mysqli_fetch_assoc($select_trainees)){
					
					$trainee_adm = strtoupper($trainee['adm']);
					$trainee_name = strtoupper($trainee['name']);
					
					//Display the subject performance
					$trainee_marks = $conn->query("SELECT IF(results_entry.cat<1, '-',results_entry.cat) AS cat, IF(results_entry.exam<1, '-',results_entry.exam) AS exam, IF(results_entry.exam ='-', '-', (results_entry.cat + results_entry.exam)) AS total, IF(cat = '-' AND exam = '-', 'Missing mark', grading.grade_value) AS grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  AS grade FROM trainees LEFT JOIN results_entry ON results_entry.adm = trainees.adm INNER JOIN units ON results_entry.unit_code = units.unit_code AND results_entry.course_code = units.courses_code INNER JOIN grading ON (results_entry.cat + results_entry.exam) BETWEEN grading.min_mark AND grading.max_mark WHERE trainees.adm = '$trainee_adm' AND results_entry.unit_code = '$unitcode' AND exam_year='$year' AND term='$term' ");
					
					while($marks=mysqli_fetch_assoc($trainee_marks)){
						$cat = $marks['cat'];
						$exam = $marks['exam'];
						$total = $marks['total'];
						if($marks['grade_value'] == 'Missing mark'){$grade = 'Missing mark';}else{$grade = $marks['grade'];}
						
						$pdf->SetFont('Times', '', 10);
						$pdf->Cell(25,5,$trainee_adm,1,0,'L');
						$pdf->Cell(65,5,$trainee_name,1,0,'L');
						$pdf->Cell(25,5,$cat,1,0,'L');
						$pdf->Cell(25,5,$exam,1,0,'L');
						$pdf->Cell(25,5,$total,1,0,'L');
						$pdf->Cell(25,5,$grade,1,0,'L');
						$pdf->Ln();
					}
					
					
				}
				for($i=0; $i<=2; $i++){
					$pdf->SetFont('Times', '', 10);
					$pdf->Cell(25,5,'',1,0,'L');
					$pdf->Cell(65,5,'',1,0,'L');
					$pdf->Cell(25,5,'',1,0,'L');
					$pdf->Cell(25,5,'',1,0,'L');
					$pdf->Cell(25,5,'',1,0,'L');
					$pdf->Cell(25,5,'',1,0,'L');
					$pdf->Ln();
				}
				$pdf->Ln(3);
			}
		}

		$pdf->Ln(15);
		// Add signatories
		$rptname = "marklists";
		$rptorientation = "portrait";
		if(get_signatories($rptname,$rptorientation)['status'] == true){
			echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
		}
		
		$pdf->Ln();
		$endnote = '
			<p class="endnote"><i><b>Note:</b> All marks are to be submitted before the deadline for transcript generation.</i> </p>
			<style>
			p{
				text-align: left;
				font-size: 10;
			}
			</style>
		';
		$pdf->Ln(10);
		$pdf->WriteHTMLCell(0,0,'','',$endnote,'',0);
		$pdf->Ln();
	}
	
	$pdf->Output($year.' '.strtoupper($term).' MARKLISTS.pdf');
	
?>
