<?php
	session_start();

	if(!$_SESSION['Admin']){
		header("location: ../../login-page.php");
		exit();
	}

	include("../../Database/config.php");

	$term = $_POST['term'];
	$year = $_POST['year'];
	$dept = $_POST['dept'];
	$course = $_POST['course'];
	$unit = $_POST['unit'];
	$class = $_POST['class'];
	
	$sql = $conn->query("SELECT cl_att_register.sn, date, cl_code, count(trainees.adm) AS total_attendance, trainers.first_name, trainers.last_name, timein, timeout, IF(cl_att_register.status = '1','Approved','Not approved') AS cl_status FROM cl_att_register INNER JOIN trainees ON trainees.adm = cl_att_register.adm INNER JOIN trainers ON trainers.trainer_id = cl_att_register.trainer INNER JOIN courses ON courses.code = cl_att_register.course INNER JOIN departments ON departments.department_code = courses.department_code WHERE year='$year' AND term = '$term' AND unit='$unit' AND courses.department_code = '$dept' GROUP BY date ORDER BY date DESC");
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<div class="modal fade" id="modal-pdf-report">
	      <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header bg-info">
	              <h6 class="modal-title" style="padding-top: 0px; padding-bottom: 0px;">PDF Report</h6>
	            </div>
	            <div class="modal-body">
	            	<div class="error_message"></div>
	            	<div id="report_area"></div>
	            </div>
	            <div class="modal-footer">
	            	<button type="button" style="color: red;" class="close" data-dismiss="modal" aria-label="Close">
	                  <span aria-hidden="true">&times;</span>
	              </button>
	            </div>
	        </div>
	        </div>
	    </div>

		<hr>
		<input type="text" id="search" class="form-control search form-control-sm" placeholder="Start typing to search" style="margin-bottom: 5px;" />
		<table>
			<thead>
				<tr>
					<th>SN</th>
					<th>DATE</th>
					<th>CLASS CODE</th>
					<th>ENTRY</th>
					<th>TRAINER</th>
					<th>TIME IN</th>
					<th>TIME OUT</th>
					<th>STATUS</th>
					<th colspan="2">ACTIONS</th>
				</tr>
			</thead>
			<tbody id="records_a">
				<?php
				$sn = 0;
				if($sql->num_rows > 0){
				while ($row=$sql->fetch_array()) { 
					++$sn;
					$date = $row['date'];
					$class_code = $row['cl_code'];
					$entry = $row['total_attendance'];
					$trainer = strtoupper($row['first_name']." ".$row['last_name']);
					$timein = $row['timein'];
					$timeout = $row['timeout'];
					$status = $row['cl_status'];
				?>
					
				<tr class="data" id=<?php echo $sn; ?> >
					<td><?php echo $sn; ?> </td>
					<td><?php echo $date; ?> </td>
					<td><?php echo $class_code; ?> </td>
					<td><?php echo $entry; ?> </td>
					<td><?php echo $trainer; ?> </td>
					<td><?php echo $timein; ?> </td>
					<td><?php echo $timeout; ?> </td>
					<td><?php echo $status; ?> </td>
					<td><button name="view" class="btn-info form-control form-control-sm" data-toggle="modal" data-target="#modal-pdf-report" id=<?php echo $row['sn']; ?> ><i class="fa fa-eye"></i>&nbsp View</button></td>
					<td><button name="download" class="btn-primary form-control form-control-sm" id=<?php echo $row['sn']; ?> ><i class="fa fa-download"></i>&nbsp Download</button></td>
				</tr>
			<?php }}
			else{ ?>
				<tr>
					<td colspan="10">No class attended </td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</body>

	<script type="text/javascript">
	$(document).ready(function(){

		$("#search").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#records tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			})
		})


	})
</script>
</html>