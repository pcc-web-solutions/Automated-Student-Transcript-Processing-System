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
    $pdf = NEW TCPDF('L','mm','A4');
    
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $crs_sn = 0;
    foreach ($courses as $course) {
        
        $selected_course = $course['course_code'];

        $course_name = $course['course_name'];

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
        
        $pdf->SetFont('Times', 'B', 14);
        $pdf->WriteHTMLCell(0,0,'','','<h3 style="color: green; text-align: center;"><u> '.$year." ".strtoupper($term)." ANALYSIS SUMMARY PER COURSE PER UNIT".'</u> </h3>',0,'C');
        $pdf->Ln();

        //Watermark
        //$pdf->Image('../../Images/transcript-watermark.jpg',90,95,50);
        
        $pdf->WriteHTMLCell(280,0,'','','<h4 style="color: royalblue; text-align: left;"> '.$course_name." - (".$selected_course.")".' </h4>',0,'L');

        $select_classes=$conn->query("SELECT distinct(classes.class_name) FROM classes INNER JOIN trainees ON trainees.class = classes.class_name INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN results_entry ON results_entry.adm = trainees.adm WHERE courses.code='$selected_course' AND trainees.status = '1' ORDER BY classes.class_name DESC");

        $entered_classes=$select_classes->num_rows;
        if($entered_classes>0){
            while ($class=mysqli_fetch_assoc($select_classes)) {
                $classname = strtoupper($class['class_name']);

                 $select_units = $conn->query("SELECT results_entry.unit_code, units.unit_name, trainers.first_name, trainers.last_name, COUNT(DISTINCT results_entry.adm) AS entries FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class INNER JOIN units ON units.unit_code = results_entry.unit_code LEFT JOIN trainer_units ON trainer_units.unit_code = results_entry.unit_code AND trainer_units.class_name = '$classname' LEFT JOIN trainers ON trainers.trainer_id = trainer_units.trainer_id WHERE results_entry.course_code = '$selected_course' AND classes.class_name = '$classname' AND exam_year = '$year' AND term = '$term' GROUP BY results_entry.unit_code");

                $pdf->Ln();
                $pdf->SetFont('Times', 'B', 12);
                $pdf->WriteHTMLCell(280,0,'','','<h4 style="color: brown; text-align: left;"> '.$classname." CLASS".' </h4>',1,0);
                $pdf->Ln();
                $pdf->SetFont('Times', 'B', 11);
                // $pdf->Cell(10,5,'SN',1,0,'L');
                $pdf->Cell(20,5,'CODE',1,0,'L');
                $pdf->Cell(100,5,'UNIT NAME',1,0,'L');
                $pdf->Cell(20,5,'ENTRIES',1,0,'L');
                $pdf->Cell(30,5,'DISTINCTION',1,0,'L');
                $pdf->Cell(20,5,'CREDIT',1,0,'L');
                $pdf->Cell(16,5,'PASS',1,0,'L');
                $pdf->Cell(16,5,'FAIL',1,0,'L');
                $pdf->Cell(16,5,'%PASS',1,0,'L');
                $pdf->Cell(42,5,'TRAINER',1,0,'L');
                $pdf->Ln();

                $unit_sn = 0;
                while ($units=mysqli_fetch_assoc($select_units)) {

                    $sqladm = $conn->query("SELECT distinct(results_entry.adm) as adm from results_entry inner join trainees on trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class WHERE classes.class_name='$classname' AND results_entry.course_code='$selected_course' AND trainees.status = '1'");
                    $trainee_entries = $sqladm->num_rows;

                    $unit_code = strtoupper($units['unit_code']);
                    $unit_name = strtoupper($units['unit_name']);
                    $trainername = strtoupper($units['first_name']." ".$units['last_name']);
                    $fails = 0;
                    $passes = 0;
                    $credits = 0;
                    $distinctions = 0;

                    while($rows=mysqli_fetch_assoc($sqladm)){

                        $admission=$rows['adm'];

                        //Display the subject performance
                        $analysis = $conn->query("SELECT if(results_entry.cat<1, '-',results_entry.cat) as cat, if(results_entry.exam<1, '-',results_entry.exam) as exam, if(results_entry.exam ='-', '-', (results_entry.cat + results_entry.exam)) as total, if(cat = '-' AND exam = '-', 'X', grading.grade_value) as grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  as grade from (results_entry inner join units on results_entry.unit_code = units.unit_code and results_entry.course_code = units.courses_code inner join grading on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark)  where results_entry.adm='$admission' AND results_entry.unit_code='$unit_code' and exam_year='$year' and term='$term' order by results_entry.unit_code");

                        $marks_scored=array();

                        while($row=mysqli_fetch_assoc($analysis))
                        { 
                            $cat = $row['cat'];
                            $exam = $row['exam'];
                            if($row['total'] == '-'){$total = 0;}else{$total = $row['total'];}
                            $grade_value = $row['grade_value'];

                            if($grade_value == 'X'){$grade = 'X';}else{$grade = $row['grade'];}

                            $marks_scored[]=$total;

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

                        }

                        if($grade_value == 1 || $grade_value == 2){$distinctions++;}
                        elseif($grade_value == 3 || $grade_value == 4){$credits++;}
                        elseif($grade_value == 5 || $grade_value == 6){$passes++;}
                        elseif($grade_value == 7 || $grade_value == 8){$fails++;}

                        $passrate = ($distinctions+$credits+$passes)/$trainee_entries * 100;
                    }

                    $pdf->SetFont('Times', '', 10);
                    // $pdf->Cell(10,5,++$unit_sn,1,0,'L');
                    $pdf->Cell(20,5,$unit_code,1,0,'L');
                    $pdf->Cell(100,5,$unit_name,1,0,'L');
                    $pdf->Cell(20,5,$trainee_entries,1,0,'C');
                    $pdf->Cell(30,5,$distinctions,1,0,'C');
                    $pdf->Cell(20,5,$credits,1,0,'C');
                    $pdf->Cell(16,5,$passes,1,0,'C');
                    $pdf->Cell(16,5,$fails,1,0,'C');
                    $pdf->Cell(16,5,number_format($passrate,2)."%",1,0,'L');
                    $pdf->Cell(42,5,$trainername,1,0,'L');
                    $pdf->Ln();
                }
            }
        }

        $pdf->Ln(15);

        // Add signatories
        $rptname = "Course Analysis";
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
        $pdf->Ln(5);
        $pdf->WriteHTMLCell(0,0,'','',$endnote,'',0);
    }

    $pdf->Output($year.' '.strtoupper($term).' ANALYSIS SUMMARY PER COURSE PER UNIT.pdf');
    
?>
