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
	
	// $trainees = $conn->query("SELECT trainees.sn,  trainees.adm, trainees.name from ((((trainees inner join courses	on courses.code=trainees.course_code) inner join units	on courses.code=units.courses_code) inner join terms) inner join years) where units.unit_code= '$unit' and courses.code='$course' and class='$class' AND trainees.status = '1' AND trainees.adm IN (SELECT DISTINCT adm FROM cl_att_register WHERE date='$date' AND year='$year' AND term = '$term' AND unit='$unit' AND courses.department_code = '$dept')");

	$trainees = $conn->query("SELECT DISTINCT cl_att_register.sn, cl_att_register.adm, trainees.name, cl_code, course FROM cl_att_register INNER JOIN trainees ON trainees.adm = cl_att_register.adm INNER JOIN courses ON cl_att_register.course = courses.code INNER JOIN departments ON courses.department_code = departments.department_code WHERE year='$year' AND term = '$term' AND date='$date' AND cl_att_register.course = '$course' AND cl_att_register.class='$class' AND unit='$unit' AND courses.department_code = '$dept'")
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<hr>
		<div class="row" style="margin: 0px 0px 0px 0px;">
			<div class="col-lg-8 col-md-8 col-sm-8">
				<input type="text" id="search_1" class="form-control search form-control-sm" placeholder="Start typing to search" style="margin-bottom: 5px;" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<button type="button" class="form-control form-control-sm btn-primary float-right" data-toggle="modal" data-target="#modal-pdf-report" style="display: initial;" id="download"><i class="fas fa-download"></i> Download report</button>
			</div>
		</div>
			
		<table>
			<thead>
				<tr>
					<th><input type="checkbox" class="checkall_1" id="checkall_1" checked /> Select All</th>
					<th>SN</th>
					<th>ADM NO</th>
					<th>FULL NAME</th>
					<th>CLASS CODE</th>
				</tr>
			</thead>
			<tbody id="records_1">
				<?php
				$sn = 0;
				if($trainees->num_rows > 0){
				while ($row=$trainees->fetch_array()) { 
					++$sn;
					$sno = $row['sn'];
					$adm = $row['adm'];
					$name = $row['name'];
					$class_code = $row['cl_code'];
				?>
					
				<tr>
					<td><input type="checkbox" name="additional[]" class="checkboxes_1" value="<?php echo $adm; ?>" checked/> </td>
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

		$("#search_1").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#records_1 tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			})
		})

		function adm_numbers_1(){
			var adms = [];
			$('.checkboxes_1').each(function(){
				var self = $(this)
				if(self.is(':checked')){
					adms.push(self.attr('value'));
				}
			});
			return adms;
		}

		function checked_boxes(){
			var totalchecked = 0;
			$('.checkboxes_1').each(function(){
				if($(this).is(':checked')){
					totalchecked++;
				}
			})
			if(totalchecked < 1){
				$('.checkall_1').prop('checked', false)
			}
			else{
				$('.checkall_1').prop('checked', true)
			}
			return totalchecked;
		}

		$('.checkboxes_1').click(function(){
			if($(this).is(':checked')){
				$(this).prop('checked',true)
			}
			else{
				$(this).prop('checked',false)
			}
			checked_boxes()
		})

		$('#checkall_1').click(function(){
			var adms = Array();
			if($('.checkall_1').is(":checked")){
				$('.checkboxes_1').each(function(){
					$(this).prop("checked", true);
				});
			}
			else{
				$('.checkboxes_1').each(function(){
					$(this).prop("checked", false)
				});
			}
		})

	})
</script>
</html>