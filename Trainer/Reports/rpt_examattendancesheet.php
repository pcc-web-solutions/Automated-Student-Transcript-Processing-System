<?php

	session_start();	
	require_once "../../Database/config.php";
	require_once "../../Includes/rsignatory.php";

	$trainer_id = $_SESSION['Trainer'];

	$choice = $_SESSION['choice'];

	$course = $_SESSION['course'];

	$class = $_SESSION['class'];

	$unit = $_SESSION['unit'];

	

	$trainername = $conn->query("SELECT * FROM trainers WHERE trainer_id = '$trainer_id'");

	while($row = $trainername->fetch_assoc()){

		$supervisor = strtoupper($row['first_name']." ".$row['last_name']);	

	}

	$exam_date = $_SESSION['ed'];

	$no_of_records = $_SESSION['not'];



	//retrieve year from years table

	$sql="select year from years";

	$current_year=mysqli_query($conn, $sql);

	while($row=mysqli_fetch_assoc($current_year))

	{$year=$row['year'];}



	//retrieve term from term table

	$sql="select term_name from terms";

	$current_term=mysqli_query($conn, $sql);

	while($row=mysqli_fetch_assoc($current_term))

	{$term=$row['term_name'];}



	$get_unit_data = $conn->query("SELECT DISTINCT(units.unit_code), units.unit_name, courses.code, courses.course_name, if(CHAR_LENGTH(courses.code) >=5, substring_index(courses.code, '/', -1), 'N/A') AS module, departments.department_code, departments.department_name FROM units INNER JOIN courses ON courses.code = units.courses_code LEFT JOIN classes ON classes.course_abrev = courses.course_abrev INNER JOIN departments ON departments.department_code = courses.department_code INNER JOIN trainees ON trainees.course_code = courses.code WHERE units.unit_code = '$unit' ORDER BY courses.code, units.unit_code ASC");

	

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

	

	while($array_data=$get_unit_data->fetch_array())

	{

		$unitname = strtoupper($array_data['unit_name']);

		$coursename = strtoupper($array_data['course_name']);

		$module = strtoupper($array_data['module']);

		$departmentcode = strtoupper($array_data['department_code']);

		$departmentname = strtoupper($array_data['department_name']);

		$date = strtoupper(date('d-M-Y'));

		



		$select_trainer = $conn->query("SELECT trainer_units.trainer_id, trainers.first_name, trainers.last_name FROM trainer_units INNER JOIN trainers ON trainers.trainer_id = trainer_units.trainer_id WHERE trainer_units.class_name = '$class' ");



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

		$pdf->WriteHTMLCell(0,0,'','','<u><h4 style="color: royalblue; text-align: center;">'.$class.' EXAM ATTENDANCE SHEET FOR '.$year." ".strtoupper($term).'</h4></u>',0);

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

		$pdf->Cell(160,5,' '.$coursename.' - ('.$course.')',0,0,'L');

		$pdf->Ln();

		$pdf->Ln(2);

		

		$pdf->SetFont('Times', 'B', 10);

		$pdf->Cell(30,5,'SUBJECT NAME: __________________________________________________',0,0,'L');

		$pdf->SetFont('Times', '', 10);

		$pdf->Cell(100,5,' '.$unitname.' - ('.$unit.')',0,0,'L');

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

		$pdf->Cell(30,5,'       '.$exam_date,0,0,'L');

		$pdf->SetFont('Times', 'B', 10);

		$pdf->Cell(10,5,'SIGN: __________________________',0,0,'L');

		$pdf->SetFont('Times', '', 10);

		$pdf->Ln();

		$pdf->Ln(5);

	

		$select_trainees = $conn->query("SELECT DISTINCT(trainees.adm), trainees.name FROM trainees INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN classes ON classes.class_name = trainees.class WHERE courses.code = '$course' AND trainees.class = '$class'");

		$number_of_trainees = $select_trainees->num_rows;

		if($number_of_trainees > 0){

			

			$pdf->SetFont('Times', 'B', 12);

			$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: green;"><u>'."ACTIVE TRAINEES".'</u> </h4>',0);

			$pdf->Ln();

			$pdf->Ln(2);

			

			$pdf->SetFont('Times', 'B', 10);

			$pdf->Cell(15,5,'Sr.NO',1,0,'C');

			$pdf->Cell(30,5,'ADM. NO',1,0,'L');

			$pdf->Cell(75,5,'FULL NAME',1,0,'L');

			$pdf->Cell(40,5,'PHONE NO',1,0,'L');

			$pdf->Cell(30,5,'SIGNATURE',1,0,'L');

			$pdf->Ln();

			

			if($choice == 'autofilled'){

				$srn = 0;

				while($trainee = mysqli_fetch_assoc($select_trainees)){

					$pdf->SetFont('Times', '', 10);

					$pdf->Cell(15,5,++$srn.'.',1,0,'C');

					$pdf->Cell(30,5,strtoupper($trainee['adm']),1,0,'L');

					$pdf->Cell(75,5,strtoupper($trainee['name']),1,0,'L');

					$pdf->Cell(40,5,'',1,0,'L');

					$pdf->Cell(30,5,'',1,0,'L');

					$pdf->Ln();

				}

				for($i=0; $i<=2; $i++){

					$pdf->SetFont('Times', '', 10);

					$pdf->Cell(15,5,'',1,0,'C');

					$pdf->Cell(30,5,'',1,0,'L');

					$pdf->Cell(75,5,'',1,0,'L');

					$pdf->Cell(40,5,'',1,0,'L');

					$pdf->Cell(30,5,'',1,0,'L');

					$pdf->Ln();

				}

				$pdf->Ln(5);

			}else{

				if($choice > 0){

					$srn = 0;

					for($i=0; $i<$no_of_records; $i++){

						$pdf->SetFont('Times', '', 10);

						$pdf->Cell(15,5,++$srn.'.',1,0,'C');

						$pdf->Cell(30,5,'',1,0,'L');

						$pdf->Cell(75,5,'',1,0,'L');

						$pdf->Cell(40,5,'',1,0,'L');

						$pdf->Cell(30,5,'',1,0,'L');

						$pdf->Ln();

					}

					$pdf->Ln(5);

				}

			}



			// Designation

			$pdf->Ln(10);

			$pdf->SetFont('Times','B',10);

			$pdf->Ln();

			$pdf->Cell(190,5,'SUPERVISOR:',0,0);

			$pdf->Ln(5);

			$pdf->SetFont('Times','',10);

			$pdf->Cell(15,5,'NAME:    _________________________________',0,0);

			$pdf->Cell(70,5,'   '.$supervisor,0,0);

			$pdf->Cell(60,5,'SIGN: ____________________',0,0);

			$pdf->Cell(15,5,'DATE:    _____________',0,0);

			$pdf->Cell(25,5,strtoupper($exam_date),0,0);

			$pdf->Ln(7);
			// Add signatories
			$rptname = "marklists";
			$rptorientation = "portrait";
			if(get_signatories($rptname,$rptorientation)['status'] == true){
				echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
			}
			$pdf->Ln();

			$endnote = '

				<p class="endnote"><i><b>Note:</b> This attendance sheet is computer generated.</i> </p>

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

	$pdf->Output(strtoupper($term).' '.$year.' EXAM ATTENDANCE SHEET.pdf');

	



	$_SESSION['choice'] = NULL;

	$_SESSION['course'] = NULL;

	$_SESSION['class'] = NULL;

	$_SESSION['unit'] = NULL;

	$_SESSION['supervisor'] = NULL;

	$_SESSION['ed'] = NULL;
?>