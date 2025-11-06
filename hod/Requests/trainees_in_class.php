<?php
	session_start();

	include("../../Database/config.php");

	$dept = $_SESSION['dept'];

	$get_term = $conn->query("SELECT term_code, term_name FROM terms");
	if($get_term->num_rows>0){
		while($data = $get_term->fetch_assoc()){
			$term = $data['term_name'];
		}
	}
	
	$get_year = $conn->query("SELECT year FROM years");
	if($get_year->num_rows>0){
		while($data = $get_year->fetch_assoc()){
			$year = $data['year'];
		}
	}

	$course = $_POST['course'];
	$unit = $_POST['unit'];
	$class = $_POST['cls'];
	$date = $_POST['date'];
	$code = $_POST['code'];

	$trainees = $conn->query("SELECT trainees.sn,  trainees.adm, trainees.name from ((((trainees inner join courses	on courses.code=trainees.course_code) inner join units	on courses.code=units.courses_code) inner join terms) inner join years) where units.unit_code= '$unit' and courses.code='$course' and class='$class' AND trainees.status = '1' AND trainees.adm NOT IN (SELECT DISTINCT adm FROM cl_att_register WHERE date='$date' AND year='$year' AND term = '$term' AND unit='$unit' AND courses.department_code = '$dept')");
 ?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<hr>
		<!-- <div class="row">
			<div class="col-lg-8 col-md-8 col-sm-8">
				
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<button type="button" class="form-control form-control-sm btn-primary float-right" style="display: initial;" id="download"><i class="fas fa-download"></i> Download report</button>
			</div>
		</div> -->
		<input type="text" id="search" class="form-control search form-control-sm" placeholder="Start typing to search" style="margin-bottom: 5px;" />	
		<table>
			<thead>
				<tr>
					<th><input type="checkbox" class="checkall" id="checkall" />Select All</th>
					<th>SN</th>
					<th>ADM NO</th>
					<th>FULL NAME</th>
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
					?>
					
				<tr>
					<td><input type="checkbox" class="checkboxes" value=<?php echo $adm; ?> /> </td>
					<td><?php echo $sn; ?> </td>
					<td><?php echo $adm; ?> </td>
					<td><?php echo $name; ?> </td>
				</tr>
			<?php }}
			else{ ?>
				<tr>
					<td colspan="4">No trainees found </td>
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
			var adms = Array();
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

		$("#download").click(function(){
			alert("downloading ...")
		})
	})
</script>
</html>