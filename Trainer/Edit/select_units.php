<?php
require('config.php');

if(isset($_POST['selected_course'])){
	$selected_course = $_POST["selected_course"];
}

$unit = array();

   $sql="select unit_code, unit_name from units where courses_code='$selected_course' order by unit_name";
	$unit_results=mysqli_query($conn, $sql) or die('wrong qry');
	
   while($row = mysqli_fetch_assoc($unit_results) ){
      $unit_code = $row['unit_code'];
      $unit_name = strtoupper($row['unit_name']);

      $unit[] = array("unit_code" => $unit_code, "unit_name" => $unit_name);
   }
echo json_encode($unit);

?>
