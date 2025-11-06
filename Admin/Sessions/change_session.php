<?php
require('../../Database/config.php');

$year = $_POST['year'];
$term = $_POST['term'];
if($term == ""){ echo "Term not selected"; }
elseif ($year == "") { echo "Year not selected";}
else{
	if($term == "I"){$term_name="Jan - March"; $start_date = "$year-01-01"; $end_date="$year-04-30";}
	elseif($term == "II"){$term_name="May - July"; $start_date="$year-05-01"; $end_date="$year-08-30";}
	elseif($term == "III"){$term_name="Sep - Nov"; $start_date="$year-09-01"; $end_date="$year-12-31";}
	else{$term_name = "";}

	$query1 = $conn->query("UPDATE years SET year='$year'");

	$query2 = $conn->query("UPDATE terms SET term_code='$term', term_name='$term_name', start_date='$start_date', end_date='$end_date'");

	if(!$query1 AND !$query2){echo 'Error updating session';}
	else{echo "success";}
}
?>