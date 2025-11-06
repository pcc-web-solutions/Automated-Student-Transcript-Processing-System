<?php
	require_once "../../Database/config.php";
	require_once "../../Includes/rsignatory.php";

	$term = $_POST['term'];
	$year = $_POST['year'];
	$dept = $_POST['dept'];
	$course = $_POST['course'];
	$unit = $_POST['unit'];
	$class = $_POST['cls'];
	$date = $_POST['date'];
	
	$sql = $conn->query("SELECT cl_code, DISTINCT trainees.adm, trainers.first_name, trainers.last_name, timein, timeout, IF(cl_att_register.status = '1','Approved','Not approved') AS cl_status FROM cl_att_register INNER JOIN trainees ON trainees.adm = cl_att_register.adm INNER JOIN trainers ON trainers.trainer_id = cl_att_register.trainer INNER JOIN courses ON courses.code = cl_att_register.course INNER JOIN departments ON departments.department_code = courses.department_code WHERE year='$year' AND term = '$term' AND unit='$unit' AND courses.department_code = '$dept' GROUP BY date ORDER BY date DESC");

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetAutoPageBreak(true, 5);
	
	$pdf->SetTitle('Attendance');
	$pdf->SetSubject('Class Attendance');
	$pdf->SetAuthor('Julius Okoth');
	$pdf->SetCreator('Musee Abiud');
	
	$pdf->SetFont('Times', '', 10);
	
	//Create a Page
	$pdf->AddPage();
	
	//Insert letter-head
	$pdf->Image('../../Images/letter_head.jpg',11,10,190);
	$pdf->Ln(35);

	//PDF Report title
	$pdf->Ln();
	$pdf->WriteHTMLCell(193,5,'','','<h2 style="text-align: center;" ><u>CLASS ATTENDANCE REPORT</u></h2>',0);
	$pdf->Ln(10);
	
	/*while($trainee = mysqli_fetch_assoc($select_trainees)){

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(30,5,strtoupper($trainee['adm']),1,0,'L');
		$pdf->Cell(60,5,strtoupper($trainee['name']),1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Ln();
	}
	for($i=0; $i<=2; $i++){
		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(30,5,'',1,0,'L');
		$pdf->Cell(60,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Cell(25,5,'',1,0,'L');
		$pdf->Ln();
	}*/

	$pdf->Ln(15);
	// Add signatories
	$rptname = "class attendance sheet";
	$rptorientation = "portrait";
	if(get_signatories($rptname,$rptorientation)['status'] == true){
		echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
	}
			
	$pdf->Ln();
	$endnote = '
		<p class="endnote"><i><b>Note:</b> This report is system generated. any alterations on the content will render it invalid</i> </p>
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
	$pdf->Output('CLASS ATTENDANCE REPORT.pdf');
	
?>