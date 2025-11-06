<?php
	require_once "../../Database/config.php";
	require_once "../../Includes/rsignatory.php";

	//retrieve year from years table
	$sql="SELECT year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}

	//retrieve term from term table
	$sql="SELECT term_name from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_name'];}

	$sql = $conn->query("SELECT DISTINCT departments.department_code, departments.department_name FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN results_entry ON results_entry.course_code = courses.code WHERE results_entry.term = '$term' AND results_entry.exam_year = '$year' ORDER BY departments.department_name ASC;");
	if ($sql->num_rows<1) {
		echo "<strong>Error: </strong>No marks";
		exit();
	}
	$deptcodes = array();
	while($rows=mysqli_fetch_assoc($sql))
	{
		$deptcodes[] = $rows;
	}
	
	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);

	$pdf->SetAutoPageBreak(true, 5);
	
	$pdf->SetTitle('Passlist Report');
	$pdf->SetSubject('Passed Trainees');
	$pdf->SetAuthor('Paramount ICT Solutions');
	$pdf->SetCreator('Musee Abiud');

	foreach($deptcodes as $dept)
	{
		//Create a Page
		$pdf->AddPage();

		//Insert letter-head
		$pdf->Image('../../Images/letter_head.jpg',11,10,190);
		$pdf->Ln(35);

		//PDF Report title
		$pdf->Ln();
		$pdf->WriteHTMLCell(193,5,'','','<h2 style="text-align: center;" ><u>PASSLIST</u></h2>',0);
		$pdf->Ln(5);

		$deptcode = $dept['department_code'];
		$deptname = $dept['department_name'];

		// Get students in this department
		$sql = $conn->query("SELECT DISTINCT results_entry.adm, trainees.name, trainees.course_code AS course, trainees.class FROM results_entry INNER JOIN trainees on trainees.adm = results_entry.adm INNER JOIN courses ON courses.code = results_entry.course_code INNER JOIN departments ON departments.department_code = courses.department_code WHERE departments.department_code = '$deptcode' AND results_entry.exam_year='$year' AND term = '$term' ");
		if($sql->num_rows>0){
			$admissions = array();

			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'TERM:                    _________________________________________________________________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(160,5,' '.strtoupper($term.''.$year),0,0,'L');
			$pdf->Ln();
			$pdf->Ln(2);
			
			$pdf->SetFont('Times', 'B', 10);
			$pdf->Cell(30,5,'DEPARTMENT:    _________________________________________________________________________________________',0,0,'L');
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(160,5,' '.$deptname,0,0,'L');
			$pdf->Ln();
			$pdf->Ln(2);

			$pdf->Ln();	
			$pdf->SetFont("Times","B",10);
			$pdf->Cell(10,5,'SN',1,0);
			$pdf->Cell(23,5,'ADM NO',1,0);
			$pdf->Cell(60,5,'FULL NAME',1,0,'C');
			$pdf->Cell(25,5,'COURSE',1,0,'L');
			$pdf->Cell(25,5,'CLASS',1,0,'L');
			$pdf->Cell(27,5,'GRADE',1,0,'L');
			$pdf->Cell(20,5,'SIGN',1,0,'L');
			$pdf->Ln();
			$serial = 0;

			// Analysis for this student
			while ($rows = $sql->fetch_array()) {
				$adm = $rows['adm'];
				$name = $rows['name'];
				$course = $rows['course'];
				$class = $rows['class'];

				// Analyze results for this student
				$analysis = $conn->query("SELECT DISTINCT(results_entry.unit_code), units.unit_name, if(results_entry.cat<1, '-',results_entry.cat) as cat, 
				if(results_entry.exam<1, '-',results_entry.exam) as exam, 
				if(results_entry.exam ='-', '-', (results_entry.cat + results_entry.exam)) as total, if(cat = '-' AND exam = '-', 'X', grading.grade_value) as grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  as grade
				from (results_entry inner join units
		              on results_entry.unit_code = units.unit_code and results_entry.course_code = units.courses_code
		              inner join grading
		              on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark)
				where results_entry.adm='$adm' and exam_year='$year' and term='$term' order by unit_code");

				$marks_scored=array();
				
				while($row=mysqli_fetch_assoc($analysis))
				{ 
					$pdf->SetFont('Times', '', 10);
					//$sn = sprintf('%03d',$serial);
					$cat = $row['cat'];
					$exam = $row['exam'];
					if($row['total'] == '-'){$total = 0;}else{$total = $row['total'];}
					if($row['grade_value'] == 'X'){$grade = 'X';}else{$grade = $row['grade'];}
					
					$marks_scored[]=$total;
					$grades[]=$grade;
					
					$aggregate_grade='';
					$refer=0;
					$missing_mark=0;
					
					$avg_score=array_sum($marks_scored)/count($marks_scored);
					$avg_score=round($avg_score);
					
					
					for($i=0;$i< sizeof($marks_scored);$i++){
						if($marks_scored[$i]>=0 && $marks_scored[$i]<=39)
						{
							$refer++;
						}
						if($marks_scored[$i]=='-')
						{
							$missing_mark++;
						}
					}
					
					if($missing_mark!=0)
					{
						$aggregate_grade='C.R.N.M';
					}
					
					else if($refer>=1 && $refer<=2)
					{
						 $aggregate_grade='REFER';
						}
						
					else if($refer>2)
					{
						 $aggregate_grade='FAIL';
					}
					
					else if($avg_score >=40 && $avg_score <=59)
					{
						 $aggregate_grade='PASS';
					}
					else if($avg_score >=60 && $avg_score <=74)
					{
						 $aggregate_grade='CREDIT';
					}
					else if($avg_score >=75 && $avg_score <=100)
					{
						 $aggregate_grade='DISTINCTION';
					}
				}

				$passed = array("DISTINCTION", "CREDIT", "PASS");

				if (in_array($aggregate_grade, $passed)) {
					$serial++;
					$pdf->Cell(10,5,$serial,1,0);
					$pdf->Cell(23,5,$adm,1,0);
					$pdf->Cell(60,5,$name,1,0);
					$pdf->Cell(25,5,$course,1,0);
					$pdf->Cell(25,5,$class,1,0);
					$pdf->Cell(27,5,$aggregate_grade,1,0);
					$pdf->Cell(20,5,'',1,0);
					$pdf->Ln();
				}
			}
			if ($serial == 0){
				$pdf->Cell(190,5,'	No records found',1,0,'C'); $pdf->Ln();
			}
			$pdf->Ln();

			$pdf->Ln(20);

			// Add signatories
			$rptname = "Pass list";
			$rptorientation = "portrait";
			if(get_signatories($rptname,$rptorientation)['status'] == true){
				echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
			}
			
			$pdf->Ln();
			$pdf->SetFont('Times', '', 11);
			$pdf->Image('../../images/stamp-holder.jpg',120,250,70);
		}
		else{
			break;
		}
	}
	$pdf->Output('PASSLIST.pdf');
?>
