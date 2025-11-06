<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../Css/assign_units.css">
	</head>
	<body>
		<?php
		require('../../Database/config.php');

		if(isset($_POST['selected_course'])){
			if(isset($_POST['selected_class'])){
				$selected_course = $_POST["selected_course"];
				$selected_class = $_POST["selected_class"];
				$selected_trainer = $_POST["selected_trainer"];

				$unit = array();
				$unitsquery=$conn->query("SELECT trainer_units.unit_code, units.unit_name FROM trainer_units INNER JOIN units ON units.unit_code = trainer_units.unit_code WHERE courses_code='$selected_course' AND trainer_units.class_name = '$selected_class' AND trainer_units.trainer_id = '$selected_trainer' ORDER BY trainer_units.unit_code");
			    if($unitsquery->num_rows > 0){
					$sn = 0;
					?>
					<table class="table-striped text-nowrap">
						<thead>
							<tr> 
								<th><center>#</center></th> 
								<th>SN</th>	
								<th>UNIT CODE</th> 
								<th>UNIT NAME</th> 
							</tr>
						</thead>
						<tbody>
							<?php
							while($row = mysqli_fetch_assoc($unitsquery) ){
								$unit_code = $row['unit_code'];
								$unit_name = strtoupper($row['unit_name']); ?>
									<tr id="records">
										<td><center><input type="checkbox" class="unitchoice" name="unitchoice" id=<?php echo $unit_code; ?> checked></input></center></td>
										<td><?php echo ++$sn; ?></td>
										<td><?php echo $unit_code; ?></td>
										<td><?php echo $unit_name; ?></td>
									</tr> 
							<?php } ?>
						</tbody>
					</table>
				<?php	
				exit();
				}
				exit();
			}
			else{
				echo "No class selected";
			}
		}
		?>
		<!-- jQuery -->
	    <script src="../../Assets/plugins/jquery/jquery.min.js"></script>
	    <script src="../../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
	    <script src="../../Assets/plugins/toastr/toastr.min.js"></script>
	</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[type="checkbox"]').click(function(){
			var selected_trainer = $('#trainer').children("option:selected").val();
			var selected_course = $('#course').children("option:selected").val();
			var selected_class = $('#class').children("option:selected").val();
			var selected_unit = $(this).attr('id');

			if($(this).prop("checked") == true){
				$.ajax({
		            url: 'Insertdata/inserttrainer_units_new.php',
		            method: 'post',
		            data: {trainers:selected_trainer,courses:selected_course,classes:selected_class,unitchoice:selected_unit},
		            success:function(data){
		            	if(data == 'Success'){
		            		toastr.success(selected_unit+' assigned successfully');
		            	}else{
		            		toastr.error(data);
		            	}
		            } 
		        });
			}
			else if($(this).prop("checked") == false){
				$.ajax({
		            url: 'Delete/delete_trainer_unit_new.php',
		            method: 'post',
		            data: {trainers:selected_trainer,courses:selected_course,classes:selected_class,unitchoice:selected_unit},
		            success:function(data){
		            	if(data == 'Success'){
		            		toastr.success(selected_unit+' unassigned successfully');
		            	}else{
		            		toastr.error(data);
		            	}
		            } 
		        });
			}
		})
	})
</script>