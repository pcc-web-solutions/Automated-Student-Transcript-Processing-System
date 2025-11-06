<?php
	session_start();
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

    $select_courses = $conn->query("SELECT DISTINCT(results_entry.course_code), courses.course_name FROM results_entry INNER JOIN courses ON courses.code = results_entry.course_code INNER JOIN trainees ON trainees.adm = results_entry.adm WHERE trainees.status = '1' AND exam_year = '$year' AND term = '$term' ORDER BY courses.code, courses.course_name");
    if ($select_courses->num_rows <= 0) {
        echo "No marks for $year term $term";
        exit();
    }
    while ($rows = mysqli_fetch_array($select_courses)) {
        $courses[] = $rows;
    }

	//Start PDF page
	require_once "../../Tcpdf/tcpdf.php";
	$pdf = NEW TCPDF('L','mm','Letter');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetAutoPageBreak(true, 5);

	$pdf->SetTitle('Analysis');
	$pdf->SetSubject('General Analysis');
	$pdf->SetAuthor('Julius Okoth');
	$pdf->SetCreator('Musee Abiud');
	
	$pdf->SetFont('Times', '', 10);

    //Create a Page
    $pdf->AddPage();
    
    //Insert letter-head
    $pdf->Image('../../Images/letter_head.jpg',15,5,270);
    $pdf->Ln(45);
    
    //Watermark
    //$pdf->Image('../../Images/transcript-watermark.jpg',90,95,50);
    
    $pdf->Ln();
    $pdf->SetFont('Times', 'B', 12);
    $pdf->WriteHTMLCell(0,0,'','','<h3 style="color: green; text-align: center;"><u> '.$year." ".strtoupper($term)." GENERAL PERFORMANCE SUMMARY REPORT".'</u> </h3>',0,'C');
    $pdf->Ln();

    $pdf->SetFont('Times', 'B', 10);
    $pdf->Cell(15,5,'CODE',1,0,'L');
    $pdf->Cell(160,5,'COURSE NAME',1,0,'L');
    $pdf->Cell(14,5,'ENTRY',1,0,'L');
    $pdf->Cell(13,5,'DST.',1,0,'L');
    $pdf->Cell(13,5,'CRDT',1,0,'L');
    $pdf->Cell(13,5,'PASS',1,0,'L');
    $pdf->Cell(13,5,'REF.',1,0,'L');
    $pdf->Cell(13,5,'FAIL',1,0,'L');
    $pdf->Cell(13,5,'CRNM',1,0,'L');
    $pdf->Cell(15,5,'%PASS',1,0,'L');
    $pdf->Ln();


    foreach ($courses as $course) {
        $course_code = $course['course_code'];

        $selected_course = $course['course_code'];
        $course_name = strtoupper($course['course_name']);

        $sqladm = $conn->query("SELECT distinct(results_entry.adm) as adm from results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm where trainees.status = '1' AND results_entry.course_code='$selected_course'");
        $totalentries = $sqladm->num_rows;
        
        $crnm = 0;
        $fails = 0;
        $reffers = 0;
        $passes = 0;
        $credits = 0;
        $distinctions = 0;

        while($rows=mysqli_fetch_assoc($sqladm))
        {
            $admission=$rows['adm'];

            //Display the subject performance
            $analysis = $conn->query("SELECT DISTINCT(results_entry.unit_code), units.unit_name, if(results_entry.cat<1, '-',results_entry.cat) as cat, 
            if(results_entry.exam<1, '-',results_entry.exam) as exam, 
            if(results_entry.exam ='-', '-', (results_entry.cat + results_entry.exam)) as total, if(cat = '-' AND exam = '-', 'X', grading.grade_value) as grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  as grade
            from (results_entry inner join units
                  on results_entry.unit_code = units.unit_code and results_entry.course_code = units.courses_code
                  inner join grading
                  on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark)
            where results_entry.adm='$admission' and exam_year='$year' and term='$term' order by unit_code");

            $marks_scored=array();
            $aggregate_grade='';
            while($row=mysqli_fetch_assoc($analysis))
            { 
                $unit_code = strtoupper($row['unit_code']);
                $unitname = strtoupper($row['unit_name']);
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
                    $aggregate_grade='X';
                }
                
                else if($refer>=1 && $refer<=2)
                {
                     $aggregate_grade='REFER';
                }
                    
                else if($refer>2)
                {
                     $aggregate_grade='FAIL';
                }
                
                else if($avg_score >=40 && $avg_score <= 59)
                {
                     $aggregate_grade='PASS';
                }
                else if($avg_score >=60 && $avg_score <= 79)
                {
                     $aggregate_grade='CREDIT';
                }
                else if($avg_score >=80 && $avg_score <= 100)
                {
                     $aggregate_grade='DISTINCTION';
                }
            }

            $tr_grade = $aggregate_grade;

            if($tr_grade == 'DISTINCTION'){$distinctions++;}
            elseif($tr_grade == 'CREDIT'){$credits++;}
            elseif($tr_grade == 'PASS'){$passes++;}
            elseif($tr_grade == 'REFER'){$reffers++;}
            elseif($tr_grade == 'FAIL'){$fails++;}
            elseif($tr_grade == 'X'){$crnm++;}

            $passrate = ($distinctions+$credits+$passes)/$totalentries * 100;
        }

        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(15,5,$selected_course,1,0,'L');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(160,5,$course_name,1,0,'L');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(14,5,$totalentries,1,0,'C');
        $pdf->Cell(13,5,$distinctions,1,0,'C');
        $pdf->Cell(13,5,$credits,1,0,'C');
        $pdf->Cell(13,5,$passes,1,0,'C');
        $pdf->Cell(13,5,$reffers,1,0,'C');
        $pdf->Cell(13,5,$fails,1,0,'C');
        $pdf->Cell(13,5,$crnm,1,0,'C');
        $pdf->Cell(15,5,number_format($passrate,2)."%",1,0,'L');
        $pdf->Ln();
    }

    $pdf->Ln(15);
    // Add signatories
    $rptname = "General Analysis";
    $rptorientation = "";
    if(get_signatories($rptname,$rptorientation)['status'] == true){
        echo display_signatories(get_signatories($rptname,$rptorientation), $rptorientation);
    }
    
    $pdf->Ln();
    $endnote = '
        <p class="endnote"><i><b>Note:</b> This analysis report is system generated.</i> </p>
        <style>
        p{
        	text-align: center;
        	font-size: 12;
        }
        </style>
    ';
    $pdf->Ln(10);
    $pdf->WriteHTMLCell(0,0,'','',$endnote,'',0);

	$pdf->Output($year.' '.strtoupper($term).' GENERAL PERFORMANCE SUMMARY.pdf');
	
?>
