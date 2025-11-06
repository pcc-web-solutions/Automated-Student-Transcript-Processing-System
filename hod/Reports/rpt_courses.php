<?php
session_start();
if (!$_SESSION['dept']) {
    header("location: ../login-page.php?error=Department not ready!");
    exit();
}
$dept = $_SESSION['dept'];

if(!isset($_SESSION['hod'])){
	echo "<h3>You do not have permission to access this report. Kindly login first</h3>";
}else{
	include("../../Database/config.php");

	$get_courses = $conn->query("SELECT DISTINCT(courses.code), courses.course_name, courses.course_abrev, courses.department_code FROM courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE departments.department_code = '$dept' ORDER BY courses.code, courses.course_name ASC");
	if($get_courses->num_rows>0){

		//Start PDF page
		require_once "../../Tcpdf/tcpdf.php";
		$pdf = NEW TCPDF('P','mm','A5');
		
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		
		//Create a Page
		$pdf->AddPage();

		$pdf->SetAutoPageBreak(true, 5);
		
		$pdf->SetTitle('Courses Report');
		$pdf->SetSubject('Registered Courses');
		$pdf->SetAuthor('Joseph Kieti');
		$pdf->SetCreator('Musee Abiud');
		
		$pdf->SetFont('Times', '', 10);
		$pdf->SetMargins(10,10,10,true);

		//Insert letter-head
		$pdf->Image('../../Images/letter_head.jpg',11,10,130);
		$pdf->Ln(30);
		
		//PDF Report title
		$pdf->Ln();
		$pdf->WriteHTMLCell(132,0,9,'','<h4 style="text-align: center;" ><u>REGISTERED COURSES REPORT</u></h4>',0,0);
		$pdf->Ln();

		//Getting all the courses  into an array
		$courses_data = array();
		$crs_sn = 0;
		while ($rows = mysqli_fetch_array($get_courses)) {
			// $courses_data[] = $rows; 
			$course_code = $rows['code'];
			$course_name = strtoupper($rows['course_name']);
			$pdf->Ln(2);
			$pdf->WriteHTMLCell(0,0,'','','<b>'.++$crs_sn.'. '.$course_code.' - '.$course_name.'</b>',0,0);
			$pdf->Ln();

			$units_data = array();
			$get_units = $conn->query("SELECT DISTINCT(units.unit_code), units.unit_name FROM units INNER JOIN courses ON units.courses_code = courses.code WHERE courses.code = '$course_code' ORDER BY units.unit_code, units.unit_name ASC");
			if ($get_units->num_rows>0) {
				// code...

				$pdf->SetFont('Times','B',9);
				$pdf->Cell(10,5,'SN',1,0,'L');
				$pdf->Cell(20,5,'UNIT CODE',1,0,'L');
				$pdf->Cell(100,5,'UNIT NAME',1,0,'L');
				$pdf->ln();
				$unt_sn = 0;
				while ($unit_rows = mysqli_fetch_array($get_units)) {
					// code...
					$pdf->SetFont('Times','',9);
					$unit_sn = ++$unt_sn;
					$unit_code = strtoupper($unit_rows['unit_code']);
					$unit_name = strtoupper($unit_rows['unit_name']);

					$pdf->Cell(10,5,$unit_sn,1,0,'L');
					$pdf->Cell(20,5,$unit_code,1,0,'L');
					$pdf->Cell(100,5,$unit_name,1,0,'L');
					$pdf->ln();
				}
				$pdf->ln(5);
			}
			else{
				$pdf->SetFont('Times','B',9);
				$pdf->Cell(10,5,'SN',1,0,'L');
				$pdf->Cell(20,5,'UNIT CODE',1,0,'L');
				$pdf->Cell(100,5,'UNIT NAME',1,0,'L');
				$pdf->ln();
				$pdf->Cell(130,5,"No units",1,0,'L');
				$pdf->ln(5);
			}
		}

		$pdf->Output('COURSE REPORT.pdf');
	}
}
?>