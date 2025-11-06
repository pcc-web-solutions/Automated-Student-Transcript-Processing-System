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
	$date = $_POST['date'];
	
	$trainees = $conn->query("SELECT DISTINCT adm, trainees.name, cl_code FROM cl_att_register INNER JOIN trainees ON trainees ON trainees.adm = cl_att_register.adm WHERE date='$date' AND year='$year' AND term = '$term' AND unit='$unit' AND courses.department_code = '$dept'")
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<hr>
		<div class="row">
			<div class="col-lg-8 col-md-8 col-sm-8">
				<input type="text" id="search" class="form-control search form-control-sm" placeholder="Start typing to search" style="margin-bottom: 5px;" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<button type="button" class="form-control form-control-sm btn-primary float-right" data-toggle="modal" data-target="#modal-pdf-report" id="download"><i class="fas fa-download"></i> Download report</button>
			</div>
		</div>
			
		<table>
			<thead>
				<tr>
					<th><input type="checkbox" class="checkall" id="checkall" />Select All</th>
					<th>SN</th>
					<th>ADM NO</th>
					<th>FULL NAME</th>
					<th>CLASS CODE</th>
				</tr>
			</thead>
			<tbody id="records">
				<?php
				$sn = 0;
				if($trainees->num_rows > 0){
				while ($row=$trainees->fetch_array()) { 
					++$sn;
					$adm = $row['adm'];
					$name = $row['name'];
					$class_code = $row['cl_code'];
				?>
					
				<tr class="data" id=<?php echo $sn; ?> >
					<td><input type="checkbox" class="checkboxes" value=<?php echo $adm; ?> /> </td>
					<td><?php echo $sn; ?> </td>
					<td><?php echo $adm; ?> </td>
					<td><?php echo $name; ?> </td>
					<td><?php echo $class_code; ?> </td>
				</tr>
			<?php }}
			else{ ?>
				<tr>
					<td colspan="5">No trainees found </td>
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

		function checked_boxes(){
			var totalchecked = 0;
			$('.checkboxes').each(function(){
				if($(this).is(':checked')){
					totalchecked++;
				}
			})
			if(totalchecked < 1){
				$('.checkall').prop('checked', false)
			}
			else{
				$('.checkall').prop('checked', true)
			}
			return totalchecked;
		}

		$('.checkboxes').click(function(){
			if($(this).is(':checked')){
				$(this).prop('checked',true)
			}
			else{
				$(this).prop('checked',false)
			}
			checked_boxes()
		})

		$('#checkall').click(function(){
			if($('.checkall').is(":checked")){
				$('.checkboxes').each(function(){
					$(this).prop("checked", true);
				});
			}
			else{
				$('.checkboxes').each(function(){
					$(this).prop("checked", false)
				});
			}
		})

	})
</script>
</html>