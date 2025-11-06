<?php
require('../../Database/config.php');
if(isset($_POST['selected_trainer'])){
	if(isset($_POST['selected_course'])){
		if(isset($_POST['selected_class'])){
			$selected_course = $_POST["selected_course"];
			$selected_class = $_POST["selected_class"];
			$selected_trainer = $_POST["selected_trainer"];
			$unit = array();
			$unitsquery=$conn->query("SELECT unit_code, unit_name FROM units WHERE courses_code='$selected_course' ORDER BY unit_code");
		    if($unitsquery->num_rows > 0){
				$sn = 0;
				while($row = mysqli_fetch_assoc($unitsquery) ){
					$unit_code = $row['unit_code'];
					$unit_name = strtoupper($row['unit_name']);
					echo '
						<tr id="records">
							<td><center><input type="checkbox" name="unitchoice[]" value='.$unit_code.'></input></center></td>
							<td>'.++$sn.'</td>
							<td>'.$unit_code.'</td>
							<td>'.$unit_name.'</td>
						</tr> 
					';
				}
			}
			else{
				echo 'No units';
				exit();
			}
		}
		else{
			echo "No class selected";
		}
	}
	else{
		echo "Unable to submit selected course";
		exit();
	}
}
else{
	echo "Trainer not selected";
}
?>
