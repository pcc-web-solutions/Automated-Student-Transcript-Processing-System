<?php
function display_signatories($response, $orientation){
	global $pdf; $date = date('d-M-Y');
	$total_signatories = 0;
	$total_signatories = sizeof($response['data']);
	if ($total_signatories > 0) {
		switch ($orientation) {
			case 'portrait':
			for ($i=0; $i < $total_signatories; $i++) { 
				$pdf->SetFont('Times','B',10);
				$pdf->Cell(190,5,$response['data'][$i]['designation'].':',0,0);
				$pdf->Ln(5);
				$pdf->SetFont('Times','',10);
				$pdf->Cell(15,5,'NAME:    _________________________________',0,0);
				$pdf->Cell(70,5,'   '.$response['data'][$i]['signame'],0,0);
				$pdf->Cell(60,5,'SIGN: ____________________',0,0);
				$pdf->Cell(15,5,'DATE:    _____________',0,0);
				$pdf->Cell(25,5,strtoupper($date),0,0);
				$pdf->Ln(7);
			}	
			break;
			
			default:
			for ($i=0; $i < $total_signatories; $i++) { 
				$pdf->SetFont('Times','B',10);
			    $pdf->Cell(270,5,$response['data'][$i]['designation'].':',0,0);
			    $pdf->Ln(5);
			    $pdf->SetFont('Times','',10);
			    $pdf->Cell(15,5,'NAME:    _________________________________',0,0);
			    $pdf->Cell(105,5,$response['data'][$i]['signame'],0,0);
			    $pdf->Cell(105,5,'SIGN: ____________________',0,0);
			    $pdf->Cell(15,5,'DATE:    _____________',0,0);
			    $pdf->Cell(30,5,strtoupper($date),0,0);
			    $pdf->Ln(7);
			}
			break;
		}			
	}
}

function get_signatories($report, $orientation){
	global $conn; $response = array();
	$sql = "SELECT designation.description, trainers.first_name, trainers.last_name FROM rpt_signatory INNER JOIN reports ON reports.description = rpt_signatory.report INNER JOIN designation ON designation.code = rpt_signatory.designation INNER JOIN signatory ON signatory.sname = rpt_signatory.sname INNER JOIN trainers ON trainers.trainer_id = rpt_signatory.sname WHERE rpt_signatory.report = '$report' ORDER BY signatory.rankpos ASC";
	$results = mysqli_query($conn, $sql);

	if(mysqli_num_rows($results)>0){
		$signatories = array();
		foreach ($results as $result) {
			$designation = $result['description'];
			$signame = $result['first_name']." ".$result['last_name'];
			$signatories[] = array(
				"designation"=>$designation,
				"signame"=>$signame
			);
		}
		$response = array("status"=>true, "data"=>$signatories);
	}
	else{
		$response = array("status"=>false);
	}
	return $response;
}		
?>