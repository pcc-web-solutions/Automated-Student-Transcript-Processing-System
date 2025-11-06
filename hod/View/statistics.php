<?php
session_start();
include('../../Database/config.php');

//retrieve year from years table
$sql="SELECT year FROM years";
$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
while($row=mysqli_fetch_assoc($current_year))
{$year=$row['year'];}

//retrieve term from term table
$sql="SELECT term_name FROM terms";
$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
while($row=mysqli_fetch_assoc($current_term))
{$term=$row['term_name'];}

$dept = $_SESSION['dept'];

function getTraineesPerCourse(){
    global $conn, $dept; 
    $courses = array();
    $sql = $conn->query("SELECT DISTINCT courses.code, courses.course_abrev, courses.course_name, COUNT(DISTINCT trainees.adm) AS trainees FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN trainees ON trainees.course_code = courses.code WHERE departments.department_code = '$dept' GROUP BY courses.code ORDER BY departments.department_name ASC");
    if($sql->num_rows>0){
        $data = array();
        while($row=$sql->fetch_array()){
            $course_code = $row['code'];
            $abbreviation = $row['course_abrev'];
            $course_name = $row['course_name'];
            $trainees = $row['trainees'];
            $data[] = array("course_code"=>$course_code, "abbreviation"=>$abbreviation, "course_name"=>$course_name,"trainees"=>$trainees);
        }
        $response = array("status"=>"success","data"=>$data);
    }else{
        $response = array("status"=>"error","message"=>"No courses found");  
    }
    return json_encode($response);  
}

function getGeneralPassRate(){
    global $conn, $term, $year;

}

function getThisPerformanceSummary(){
    global $conn, $term, $year, $dept;
    $response = array();
    $select_courses = $conn->query("SELECT DISTINCT(results_entry.course_code), courses.course_name FROM results_entry INNER JOIN courses ON courses.code = results_entry.course_code INNER JOIN departments on departments.department_code = courses.department_code INNER JOIN trainees ON trainees.adm = results_entry.adm WHERE trainees.status = '1' AND exam_year = '$year' AND term = '$term' AND departments.department_code = '$dept' ORDER BY courses.code, courses.course_name");
    if ($select_courses->num_rows > 0){
        while ($rows = mysqli_fetch_array($select_courses)) { $courses[] = $rows; }

        $tDistinctions = 0; 
        $tCredits = 0; 
        $tPasses = 0; 
        $tFails = 0; 
        $tReffers = 0; 
        $tCrnm = 0;
        
        $totalcourses = 0;

        $contestants = 0;
        $analysis_data = array();
        $gradeTotals = array();
        $passrates = 0;
        $failrates = 0;

        foreach ($courses AS $course){
            $course_code = $course['course_code'];

            $selected_course = $course['course_code'];
            $course_name = strtoupper($course['course_name']);

            $sqladm = $conn->query("SELECT distinct(results_entry.adm) as adm from results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm where trainees.status = '1' AND results_entry.course_code='$selected_course'");
            $totalentries = $sqladm->num_rows; $contestants += $totalentries;
            $admission = "";
            $crnm = 0;
            $fails = 0;
            $reffers = 0;
            $passes = 0;
            $credits = 0;
            $distinctions = 0;

            while($rows=mysqli_fetch_assoc($sqladm)){
                $admission=$rows['adm'];

                //Display the subject performance
                $analysis = $conn->query("SELECT DISTINCT(results_entry.unit_code), units.unit_name, if(results_entry.cat<1, '-',results_entry.cat) as cat, 
                if(results_entry.exam<1, '-',results_entry.exam) as exam, 
                if(results_entry.exam = '-', '-', (results_entry.cat + results_entry.exam)) as total, if(cat = '-' AND exam = '-', 'X', grading.grade_value) as grade_value, CONCAT(grading.grade, ' ', '(', grading.grade_value, ')')  as grade
                from (results_entry inner join units
                      on results_entry.unit_code = units.unit_code and results_entry.course_code = units.courses_code
                      inner join grading
                      on (results_entry.cat + results_entry.exam) between grading.min_mark and grading.max_mark)
                where results_entry.adm='$admission' and exam_year='$year' and term='$term' order by unit_code");

                $marks_scored=[];
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

                if($tr_grade == 'DISTINCTION'){$distinctions++; }
                elseif($tr_grade == 'CREDIT'){$credits++; }
                elseif($tr_grade == 'PASS'){$passes++; }
                elseif($tr_grade == 'REFER'){$reffers++; }
                elseif($tr_grade == 'FAIL'){$fails++; }
                elseif($tr_grade == 'X'){$crnm++; }

                $passrate = ($distinctions+$credits+$passes)/$totalentries * 100;
                $failrate = ($fails+$crnm)/$totalentries*100;
            }
            
            // Add student grades to the total grades for this course
            $tDistinctions+=$distinctions;
            $tCredits+=$credits;
            $tPasses+=$passes;
            $tReffers+=$reffers;
            $tFails += $fails;
            $tCrnm+=$crnm;

            $totalcourses++;
            $passrates += number_format($passrate,2);
            $failrates += number_format($failrate,2);

            // PERFORMANCE ANALYSED FOR THIS COURSE
            $analysis_data[] = array(
                "course_code"=>$selected_course,
                "course_name"=>$course_name,
                "entries"=>$totalentries,
                "distinctions"=>$distinctions,
                "credits"=>$credits,
                "passes"=>$passes,
                "reffers"=>$reffers,
                "fails"=>$fails,
                "crnm"=>$crnm,
                "passrate"=>number_format($passrate,2),
                "failrate"=>number_format($failrate,2)
            );
        }

        // TOTAL GRADES FOR ALL THE COURSES
        $gradeTotals[] = array(
            "distinctions"=>number_format($tDistinctions),
            "credits"=>number_format($tCredits),
            "passes"=>number_format($tPasses),
            "reffers"=>number_format($tReffers),
            "fails"=>number_format($tFails),
            "crnms"=>number_format($tCrnm)
        );

        // GENERAL PASSRATE AND FAILRATE FOR THIS TERM AND YEAR
        $general_passrate = array( "year"=>$year,"term"=>$term, "totalentries"=>$totalentries, "totalcourses"=>$totalcourses, "passrate"=>$passrates/($totalcourses));
        $general_failrate = array( "year"=>$year,"term"=>$term, "totalentries"=>$totalentries, "totalcourses"=>$totalcourses, "failrate"=>100-$passrates/($totalcourses));

        $response = array("status"=>"success", "analysis_data"=>$analysis_data, "gradeTotals"=>$gradeTotals, "gpassrate"=>$general_passrate, "gfailrate"=>$general_failrate);
    }
    else{
        $response = array("status"=>"error", "message"=>"No marks entered");
    }
    return json_encode($response);
}

// echo getThisPerformanceSummary();
// echo getTraineesPerDepartment();

if(isset($_POST['req'])) {
    if ($_REQUEST['req'] == 'course_data') {
        echo getTraineesPerCourse();
    }

    if ($_REQUEST['req']  == 'performance_analysis') {
        echo getThisPerformanceSummary();
    }
} 
else{
    $response = array("status"=>"error", "message"=>"Unknown request sent to server");
    echo json_encode($response);
}
?>