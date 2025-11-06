<?php
	session_start();
	$selected_course = strip_tags($_SESSION["selected_course"]);

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

	$sqladm = $conn->query("SELECT distinct(results_entry.adm) as adm from results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm where trainees.status = '1' AND results_entry.course_code = '$selected_course'");
	
	while($rows=mysqli_fetch_assoc($sqladm))
	{
		$admissions[] = $rows;
	}

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('P','mm','A4');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetAutoPageBreak(true, 5);
	$pdf->SetTitle('Academic Transcript');
	$pdf->SetSubject('Transcript');
	$pdf->SetAuthor('Julius Okoth');
	$pdf->SetCreator('Musee Abiud');
	$pdf->SetFont('Times', '', 10);
	
	foreach($admissions as $admission)
	{
		$admission=$admission['adm'];
		
		$traineeinfo = $conn->query("select trainees.name, trainees.adm, trainees.class, trainees.course_code, results_entry.term, results_entry.exam_year, 
		results_entry.cat, results_entry.exam, (results_entry.cat + results_entry.exam) as total, grading.grade_value, grading.grade
		, units.unit_code, units.unit_name, courses.course_name, departments.department_name, 
		if(CHAR_LENGTH(trainees.course_code) >=5, substring_index(trainees.course_code, 
		'/', -1), 'N/A') as module
		from ((((trainees
		inner join results_entry
		on trainees.adm=results_entry.adm)
		inner join grading
		on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark
		inner join units
		on results_entry.unit_code=units.unit_code and results_entry.course_code=units.courses_code) 
		inner join courses
		on trainees.course_code =courses.code)
		inner join departments
		on courses.department_code = departments.department_code) where trainees.course_code='$selected_course' and trainees.adm='$admission' and exam_year='$year' and term='$term' LIMIT 1");
	
		//Create a Page
		$pdf->AddPage();
		
		//Insert letter-head
		$pdf->Image('../../images/letter_head.jpg',11,7,190);
		$pdf->Ln(35);
		
		//Watermark
		$pdf->Image('../../images/transcript-watermark.jpg',90,95,50);
		
		//PDF Report title
		$pdf->Ln();
		$pdf->WriteHTMLCell(193,5,'','','<h2 style="text-align: center;" ><u>ACADEMIC TRANSCRIPT</u></h2>',0);
		$pdf->Ln(10);
		
		//Display trainee details
		$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: red;"><u>TRAINEE DETAILS</u></h4>',0);
		$pdf->Ln(7);
		while($row=mysqli_fetch_assoc($traineeinfo))
		{
			$coursename = strtoupper($row['course_name']);
			$deptname = strtoupper($row['department_name']);
			
			$tr_table = '
				<table>
					<tbody style="font: 10px";>
						<tr>
							<td style = "width: 90px;"><b>NAME:</b></td>
							<td style = "width: 220px;">'.strtoupper($row['name']).'</td>
							<td><b>REGISTRATION NO:</b></td>
							<td>'.strtoupper($row['adm']).'</td>
						</tr>
						<tr>
							<td style = "width: 90px;"><b>DEPARTMENT:</b></td>
							<td style = "width: 220px;">'.strtoupper($row['department_name']).'</td>
							<td><b>CLASS:</b></td>
							<td>'.strtoupper($row['class']).'</td>
						</tr>
						<tr>
							<td style = "width: 90px;"><b>COURSE:</b></td>
							<td style = "width: 220px;">'.strtoupper($row['course_name'])." (".strtoupper($row['course_code']).")".'</td>
							<td><b>TERM:</b></td>
							<td>'.strtoupper($row['term']).'</td>
						</tr>
						<tr>
							<td style = "width: 90px;"><b>STAGE:</b></td>
							<td style = "width: 220px;">'.strtoupper($row['module']).'</td>
							<td><b>ACADEMIC YEAR:</b></td>
							<td>'.$row['exam_year'].'</td>
						</tr>
					</tbody>
				</table>
			';
			$pdf->WriteHTMLCell(0,0,'','',$tr_table,0);
			$pdf->Ln();
		}
		
		//Subject perfomance title
		$pdf->Ln(5);
		$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: red;"><u>SUBJECT PERFORMANCE</u></h4>',0,0);
		$pdf->Ln(7);
		
		//Display the subject performance
		$analysis = $conn->query("SELECT DISTINCT(results_entry.unit_code), units.unit_name, if(results_entry.cat<1, '-',results_entry.cat) as cat, 
		if(results_entry.exam<1, '-',results_entry.exam) as exam, 
		if(results_entry.exam ='-', '-', (results_entry.cat + results_entry.exam)) as total, if(cat = '-' AND exam = '-', 'Missed Exam', if (exam = '-' ,'Missed Exam',grading.grade_value)) as grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  as grade
		from (results_entry inner join units
              on results_entry.unit_code = units.unit_code and results_entry.course_code = units.courses_code
              inner join grading
              on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark)
		where results_entry.adm='$admission' and exam_year='$year' and term='$term' order by unit_code");

		$marks_scored=array();
		$serial=1;
		
		$pdf->SetFont('Times', 'B', 11);		
		$pdf->Cell(20,5,'CODE',1,0);
		$pdf->Cell(95,5,'UNIT NAME',1,0);
		$pdf->Cell(15,5,'CAT',1,0,'C');
		$pdf->Cell(15,5,'EXAM',1,0,'C');
		$pdf->Cell(15,5,'TOTAL',1,0,'C');
		$pdf->Cell(30,5,'GRADE',1,0);
		$pdf->Ln();
		$serial = 1; $aggregate_grade = "";
		while($row=mysqli_fetch_assoc($analysis))
		{ 
			$pdf->SetFont('Times', '', 10);
			//$sn = sprintf('%03d',$serial);
			$unit_code = strtoupper($row['unit_code']);
			$unitname = strtoupper($row['unit_name']);
			$cat = $row['cat'];
			$exam = $row['exam'];
			if($row['total'] == '-'){$total = 0;}else{$total = $row['total'];}
			if($row['grade_value'] == 'Missed Exam'){$grade = 'Missed Exam';}else{$grade = $row['grade'];}
			$serial++;
			
			$pdf->Cell(20,5,$unit_code,1,0);
			$pdf->Cell(95,5,$unitname,1,0);
			$pdf->Cell(15,5,$cat,1,0,'C');
			$pdf->Cell(15,5,$exam,1,0,'C');
			$pdf->Cell(15,5,$total,1,0,'C');
			$pdf->Cell(30,5,$grade,1,0);
			$pdf->Ln();
			
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
				$aggregate_grade='C.R.N.M.';
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
			else if($avg_score >=60 && $avg_score <=79)
			{
				 $aggregate_grade='CREDIT';
			}
			else if($avg_score >=80 && $avg_score <=100)
			{
				 $aggregate_grade='DISTINCTION';
			}
		}
		
		$pdf->Ln();

		//Aggregate Grade
		$pdf->Ln(5);
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(40,5,'AGGREGATE GRADE: ',0,0,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(100,5,$aggregate_grade,0,0,'L');
		$pdf->Ln();
		
		//Key to Grading
		$pdf->Ln(5);
		$pdf->SetFont('Times','',10);
		$pdf->WriteHTMLCell(0,0,'','','<h4 style="color: red;"><u>KEY TO GRADING</u></h4>',0);
		$pdf->Ln(7);
		
		$pdf->Cell(47.5,5,'80 - 100 DISTINCTION (1)',1,0);
		$pdf->Cell(47.5,5,'75 - 79 DISTINCTION (2)',1,0);
		$pdf->Cell(47.5,5,'70 - 74 CREDIT (3)',1,0);
		$pdf->Cell(47.5,5,'60 - 69 CREDIT (4)',1,0);
		$pdf->Ln();
		$pdf->Cell(47.5,5,'50 - 59 PASS (5)',1,0);
		$pdf->Cell(47.5,5,'40 - 49 PASS (6)',1,0);
		$pdf->Cell(47.5,5,'30 - 39 FAIL (7)',1,0);
		$pdf->Cell(47.5,5,'0 - 29 FAIL (8)',1,0);
		$pdf->Ln();
		
		$pdf->Ln(15);
		// Add signatories
		$rptname = "transcripts";
		$rptorientation = "portrait";
		if(get_signatories($rptname,$rptorientation)['status'] == true){
			echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
		}

		$pdf->Ln();
		
		$endnote = '
			<p class="endnote"><i><b>Note:</b> This transcript is issued without any erasures or alterations.</i> </p>
			<style>
			p{
				text-align: left;
				font-size: 10;
			}
			</style>
		';
		$pdf->Ln(10);
		$pdf->WriteHTMLCell(0,0,'','',$endnote,'',0);
		$pdf->Image('../../images/stamp-holder.jpg',120,250,70);
	
	}
	
	$pdf->Output($coursename.' '.strtoupper($term).' TRANSCRIPTS.pdf');
	
?>
