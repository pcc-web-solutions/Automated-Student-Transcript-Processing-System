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
	$sql="select term_code from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");

	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_code'];}

	$course_codes = $conn->query("SELECT DISTINCT(trainees.course_code) AS code FROM trainees INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept' AND trainees.status = '1' ORDER BY courses.code");
	
	while($rows=mysqli_fetch_assoc($course_codes))
	{
		$courses[] = $rows;
	}

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	//Create a Page
	$pdf->AddPage();

	$pdf->SetAutoPageBreak(true, 5);
	
	$pdf->SetTitle('Trainees Report');
	$pdf->SetSubject('Registered Trainees');
	$pdf->SetAuthor('Julius Okoth');
	$pdf->SetCreator('Musee Abiud');
	
	$pdf->SetFont('Times', '', 10);

	//Insert letter-head
	$pdf->Image('../../Images/letter_head.jpg',11,7,190);
	$pdf->Ln(35);

	//PDF Report title
	$pdf->Ln();
	$pdf->WriteHTMLCell(193,5,'','','<h2 style="text-align: center;" ><u>TRAINEES REPORT PER COURSE</u></h2>',0);
	$pdf->Ln(10);
	
	$srn = 0;	
	foreach($courses as $course)
	{
		$course_code = $course['code'];
		
		$courseinfo = $conn->query("SELECT course_name FROM courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.code = '$course_code' LIMIT 1");
		
		$trainees = $conn->query("SELECT adm, name, courses.code, class FROM trainees INNER JOIN courses ON courses.code = trainees.course_code WHERE courses.code = '$course_code' AND trainees.status = '1' ");
		
		$countoftitc = $conn->query("SELECT COUNT(adm) AS trainees_in_this_course FROM trainees WHERE trainees.course_code = '$course_code' AND trainees.status = '1'");
		
		$srn++;
		while($row=mysqli_fetch_assoc($courseinfo))
		{
			$coursename = strtoupper($row['course_name']);	
		}
		
		while($row=mysqli_fetch_assoc($countoftitc))
		{
			$trainees_in_this_course = $row['trainees_in_this_course'];	
		}
		
		//$crs_info = "$srn. $course_code - $coursename ($trainees_in_this_course Trainees)";
		$pdf->SetFont('Times', 'B', 11);
		$pdf->Cell(190,5,$srn.'.   '.$course_code.' - '.$coursename.' ('.$trainees_in_this_course.') Trainees',0,0);
		$pdf->Ln(2);
		
		$pdf->Ln();
		$pdf->SetFont('Times', 'B', 11);		
		$pdf->Cell(15,5,'SN',1,0);
		$pdf->Cell(35,5,'ADM NO',1,0);
		$pdf->Cell(75,5,'FULL NAME',1,0,'C');
		$pdf->Cell(30,5,'COURSE CODE',1,0,'C');
		$pdf->Cell(35,5,'CLASS',1,0,'L');
		$pdf->Ln();
		$serial = 1;
		while($row=mysqli_fetch_assoc($trainees))
		{ 
			$pdf->SetFont('Times', '', 10);
			
			$sn = $serial;
			$admno = strtoupper($row['adm']);
			$name = strtoupper($row['name']);
			$course = strtoupper($row['code']);
			$class = strtoupper($row['class']);
			$serial++;
			
			$pdf->SetFont('Times', '', 11);
			$pdf->Cell(15,5,$sn,1,0,'L');
			$pdf->Cell(35,5,$admno,1,0,'L');
			$pdf->Cell(75,5,$name,1,0,'L');
			$pdf->Cell(30,5,$course,1,0,'C');
			$pdf->Cell(35,5,$class,1,0,'L');
			$pdf->Ln();
		}
		
		$pdf->Ln();

	}
	
	$pdf->Ln(15);
	// Add signatories
	$rptname = "trainees";
	$rptorientation = "portrait";
	if(get_signatories($rptname,$rptorientation)['status'] == true){
		echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
	}

	$pdf->Ln();
	$pdf->SetFont('Times', '', 11);
	//$pdf->Image('../../Images/stamp-holder.jpg',120,250,70);

	$pdf->Output('TRAINEES REPORT.pdf');
	
?>
