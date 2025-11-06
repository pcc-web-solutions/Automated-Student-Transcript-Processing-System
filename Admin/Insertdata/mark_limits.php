<?php 
	include('../../Database/config.php');
	
	$catmax = filter_input(INPUT_POST, 'catmax', FILTER_VALIDATE_INT);
	$exammax = filter_input(INPUT_POST, 'exammax', FILTER_VALIDATE_INT);
	$request = filter_input(INPUT_POST, 'request');

	$sql="SELECT * from years order by  year";
	$years_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($years_result)) {$year = $row['year'];}
	
	$sql="SELECT * from terms order by  term_name";
	$terms_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($terms_result)) {$term = $row['term_name'];}
	
	function save(){
		global $term, $year, $catmax, $exammax, $conn;
		$sql = $conn->query("SELECT max FROM mark_entry_limits WHERE exam ='cat' AND term = '$term' AND year = '$year'");
		if($sql->num_rows<1){
			$sql1 = $conn->query("INSERT INTO mark_entry_limits (term, year, exam, max) VALUES ('$term', '$year', 'cat', '$catmax') ");
			$sql = $conn->query("SELECT max FROM mark_entry_limits WHERE exam ='exam' AND term = '$term' AND year = '$year'");
			if($sql->num_rows<1){
				$sql2 = $conn->query("INSERT INTO mark_entry_limits (term, year, exam, max) VALUES ('$term', '$year', 'exam', '$exammax') ");
				if(!$sql1 || !$sql2){return "Server error"; exit();}
				else{return "save success"; exit();}
			}
			return "Exam limit already set"; exit();
		}
		return "Exam limit already set";
	}
	function update(){
		global $term, $year, $catmax, $exammax, $conn;
		$sql1 = $conn->query("UPDATE mark_entry_limits SET max = '$catmax' WHERE term='$term' AND year='$year' AND exam='cat'");
		$sql2 = $conn->query("UPDATE mark_entry_limits SET max = '$exammax' WHERE term='$term' AND year='$year' AND exam='exam'");
		if(!$sql1 || !$sql2){return "Server error"; exit();}
		else{return "update success"; exit();}
	}

	switch ($request) {
		case 'save':
			echo save();
			break;
		
		case 'update':
			echo update();
			break;
		
		default:
			echo "Unkown request to server";
			break;
	}		
?>