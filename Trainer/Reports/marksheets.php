<?php

	if(!session_start()){
		session_start();
	}

	include('../../Database/config.php');



	$trainer_id = $_SESSION['Trainer'];



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

	$unit_codes = $conn->query("SELECT DISTINCT(trainer_units.unit_code), units.unit_name, courses.code, courses.course_name, IF(CHAR_LENGTH(courses.code) >= 5 , SUBSTRING_INDEX(courses.code, '/', -1),'N/A' ) AS module, departments.department_code, departments.department_name, department_hods.hod AS hod_code, trainers.first_name, trainers.last_name FROM trainer_units INNER JOIN units ON units.unit_code = trainer_units.unit_code INNER JOIN courses ON courses.code = units.courses_code INNER JOIN departments ON departments.department_code = courses.department_code LEFT JOIN department_hods ON department_hods.department_code = departments.department_code LEFT JOIN trainers ON trainers.trainer_id = department_hods.hod WHERE trainer_units.trainer_id = '$trainer_id' ORDER BY unit_code ASC");

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

		

		$select_classes=$conn->query("SELECT DISTINCT(trainer_units.class_name) FROM trainer_units INNER JOIN trainees ON trainees.class = trainer_units.class_name INNER JOIN units ON trainer_units.unit_code = units.unit_code WHERE trainer_units.unit_code = '$unitcode' AND trainer_units.trainer_id = '$trainer_id' ORDER BY trainer_units.class_name DESC");



		while($classes_data=mysqli_fetch_array($select_classes)){

			

			$classname = $classes_data['class_name'];



			$select_trainer = $conn->query("SELECT trainer_units.trainer_id, trainers.first_name, trainers.last_name FROM trainer_units INNER JOIN trainers ON trainers.trainer_id = trainer_units.trainer_id WHERE trainer_units.trainer_id = '$trainer_id' ");

			if($select_trainer->num_rows>0){

				while($row=mysqli_fetch_assoc($select_trainer)){

					$trainername = strtoupper($row['first_name']." ".$row['last_name']);

				}

			}else{$trainername = "N/A";}



			//Create a Page

			$pdf->AddPage();

			

			//Insert letter-head

			$pdf->Image('../../Images/letter_head.jpg',11,8,190);

			$pdf->Ln(35);


			//PDF Report title

			$pdf->Ln();

			$pdf->SetFont('Times', 'B', 12);

			$pdf->WriteHTMLCell(0,0,'','','<u><h4 style="color: royalblue; text-align: center;">'.$year.' '.strtoupper($term).' MARKSHEET FOR '.$classname." CLASS".'</h4></u>',0);

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

			$pdf->Cell(30,5,'SUBJECT NAME: _______________________________________________________',0,0,'L');

			$pdf->SetFont('Times', '', 10);

			$pdf->Cell(100,5,' '.$unitname,0,0,'L');

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

			$pdf->Cell(30,5,'',0,0,'L');

			$pdf->SetFont('Times', 'B', 10);

			$pdf->Cell(10,5,'SIGN: __________________________',0,0,'L');

			$pdf->SetFont('Times', '', 10);

			$pdf->Ln();

			$pdf->Ln(5);

		

			$select_trainees = $conn->query("SELECT DISTINCT(trainees.adm), trainees.name FROM trainees INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN classes ON classes.class_name = trainees.class WHERE courses.code = '$coursecode' AND trainees.class = '$classname' AND trainees.status = '1' AND trainees.deleted_by = ''");

			$number_of_trainees = $select_trainees->num_rows;

			if($number_of_trainees > 0){

				

				$pdf->SetFont('Times', 'B', 12);

				$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: green;"><u>'."ACTIVE TRAINEES".'</u> </h4>',0);

				$pdf->Ln();

				$pdf->Ln(2);

				

				$pdf->SetFont('Times', 'B', 10);

				$pdf->Cell(25,5,'ADM. NO',1,0,'L');

				$pdf->Cell(65,5,'FULL NAME',1,0,'L');

				$pdf->Cell(25,5,'CAT (30%)',1,0,'L');

				$pdf->Cell(25,5,'EXAM (70%)',1,0,'L');

				$pdf->Cell(25,5,'TOT. (%AGE)',1,0,'L');

				$pdf->Cell(25,5,'ATT. (100%)',1,0,'L');

				$pdf->Ln();

				

				while($trainee = mysqli_fetch_assoc($select_trainees)){



					$pdf->SetFont('Times', '', 10);

					$pdf->Cell(25,5,strtoupper($trainee['adm']),1,0,'L');

					$pdf->Cell(65,5,strtoupper($trainee['name']),1,0,'L');

					$pdf->Cell(25,5,'',1,0,'L');

					$pdf->Cell(25,5,'',1,0,'L');

					$pdf->Cell(25,5,'',1,0,'L');

					$pdf->Cell(25,5,'',1,0,'L');

					$pdf->Ln();

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

				$pdf->Ln(5);



				$pdf->Ln();

				$endnote = '

					<p class="endnote"><i><b>Note:</b> All marks are to be submitted in time.</i> </p>

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

		}

	}

	$pdf->Output($year.' TERM '.$term.' MARKSHEETS.pdf');
?>