<?php
	session_start(); 

	if(isset($_POST['request'])){

		include("../../Database/config.php");

		$dept = $_SESSION['dept'];
		//retrieve year from years table
		$sql="select year from years";
		$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
		while($row=mysqli_fetch_assoc($current_year))
		{$year=$row['year'];}

		//retrieve term from term table
		$sql="select term_name from terms";
		$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
		while($row=mysqli_fetch_assoc($current_term))
		{$term=$row['term_name'];}

		$request = $_POST['request'];
		
		if($request == "View all marks"){
			$select_all_marks = $conn->query("SELECT results_entry.sn, results_entry.adm, trainees.name, results_entry.course_code, classes.class_name, results_entry.unit_code, units.unit_name, cat, exam, exam_year, term FROM results_entry INNER JOIN units ON units.unit_code = results_entry.unit_code INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class INNER JOIN courses ON courses.code = results_entry.course_code INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept' AND term = '$term' AND exam_year = '$year' AND trainees.status= '1' ORDER BY results_entry.course_code, classes.class_name, results_entry.adm DESC");
			if($select_all_marks->num_rows>0)
			{?>
				<form id=service_form method=post>	
					<div class="table-responsive">
						<div class="marks-table">
							<table class="table-striped text-nowrap" id="loaded_marks">
								<thead>
									<tr>
										<th>SN</th>
										<th>ADM NO</th>
										<th>NAME</th>
										<th>COURSE</th>
										<th>CLASS</th>
										<th>UNIT</th>
										<th>CAT</th>
										<th>EXAM</th>
										<th>TERM</th>
										<th>YEAR</th>
										<th style="text-align: center;">ACTION</th>
									</tr>
								</thead>
								<tbody id="marks-table">
								<?php
								$count = 1;
								while ($row=mysqli_fetch_array($select_all_marks)) {
									echo '
										<tr>					
											<td>'.$count.'</td>
											<td>'.strtoupper($row['adm']).'</td>
											<td>'.strtoupper($row['name']).'</td>
											<td>'.$row['course_code'].'</td>
											<td>'.$row['class_name'].'</td>
											<td>'.strtoupper($row['unit_name']).'</td>
											<td contentEditable="true" class="cats" id="'.$row['sn'].'">'.$row['cat'].'</td>
											<td contentEditable="true" class="exams" id="'.$row['sn'].'">'.$row['exam'].'</td>
											<td>'.$row['term'].'</td>
											<td>'.$row['exam_year'].'</td>
											<td><a href="#" class="delete" id='.$row['sn'].' style="color:red">Delete</a></td>
										
						             	</tr>'; 
									$count++; 
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</form>	

				<script src="js/jquery-3.3.1.js"></script>
				<script src="../jquery-ui.min.js"></script>
				<script type="text/javascript">	</script>
				<script type="text/javascript" src="script/functions.js"></script>

				<script>  
				function changeBackground(obj) {
				        $(obj).removeClass("bg-success");
				        $(obj).addClass("bg-info");
				    }

				    function saveData(obj, sn, column) {
				        var customer = {
				            sn: sn,
				            column: column,
				            value: obj.innerHTML
				        }
				        $.ajax({
				            type: "POST",
				            url: "Edit/change_course.php",
				            data: customer,
				            dataType: 'json',
				            success: function(data){
				                if (data) {
				                    $(obj).removeClass("bg-danger");
				                    $(obj).addClass("bg-success");
				                }
				            }
				       });
				    }
				</script>

				<script>
					
					$(document).ready(function()
					{
					$('[contenteditable]').keypress(function(e){ return e.which != 13; });
					
				  $(".search").on("keyup", function() {
				    var value = $(this).val().toLowerCase();
				    $("#marks-table tr").filter(function() {
				      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				    })
				  })
				  
				  
					$('.delete').click(function(e)
					{
						e.preventDefault();
					var sn=$(this).attr('id');		
					
				  	if(confirm('Are you sure you want to delete this record?'))
					{
					  $.ajax({url:'Delete/delete_marks.php',
					  method:'post',
					  data:{sn},
					  
					  	success:function(data)
						{
							alert(data); 
						}
					  });
					 $(this).parents('tr').remove(); 
					
						}
				    })
					
				  
					})
				  
				 	
				</script>

				<script>	
					$(document).ready(function()
					{
						$(".cats").blur(function(){
						var id = this.id;
						var value = $(this).text();
						
					
						$.ajax({
						url: 'Update/update_cats.php',
						type: 'post',
						data: {value:value, id:id },
						success:function(response){
						console.log(response); 
						}
						})
						  
						})
					})
				</script>

				<script>	
					$(document).ready(function()
					{
						$(".exams").blur(function(){
						var id = this.id;
						var value = $(this).text();
						
					
						$.ajax({
						url: 'Update/update_exams.php',
						type: 'post',
						data: {value:value, id:id },
						success:function(response){
						console.log(response); 
						}
						})
						  
						})
					})
				</script>
			<?php
			}
			else{
				echo '<div class="alert alert-warning"><strong>Sorry!</strong> There are no mark entries for this term session.</div>';
			}
			exit();
		}
		elseif ($request == "Delete all marks") {
			$course = $_POST['selected_course'];
			$class = $_POST['selected_class'];
			$unit = $_POST['selected_unit'];

			// Get the trainee admission numbers
			$adms = array();
			$select_adm_no = $conn->query("SELECT DISTINCT(results_entry.adm) FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN classes ON classes.class_name = trainees.class WHERE trainees.course_code = '$course' AND classes.class_name = '$class' AND results_entry.unit_code = '$unit' AND exam_year = '$year' AND term = '$term' AND trainees.status= '1'");
			if($select_adm_no->num_rows>0){
				while($rows=mysqli_fetch_array($select_adm_no)){
					$adms[] = $rows;
				}
				foreach ($adms as $adm) {
					$adm_no = $adm['adm'];
					$delete_marks = $conn->query("DELETE FROM results_entry WHERE adm = '$adm_no' AND unit_code = '$unit' AND exam_year = '$year' AND term = '$term'");
				}
				echo '<div class="alert alert-success"><strong>Success!</strong> Marks deleted successfully.</div>';
				exit();
			}
			else{
				echo '<div class="alert alert-warning"><strong>Sorry!</strong> Problem retrieving trainees in that class. Please try again.</div>';
				exit();
			}
		}
		else if($request == "Delete this session marks"){
			// Get the trainee admission numbers
			$adms = array();
			$select_adm_no = $conn->query("SELECT DISTINCT(results_entry.adm) FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN departments ON departments.department_code=courses.department_code WHERE departments.department_code='$dept' AND exam_year = '$year' AND term = '$term' AND trainees.status= '1'");
			if($select_adm_no->num_rows>0){
				while($rows=mysqli_fetch_array($select_adm_no)){
					$adms[] = $rows;
				}
				foreach ($adms as $adm) {
					$adm_no = $adm['adm'];
					$delete_marks = $conn->query("DELETE FROM results_entry WHERE adm = '$adm_no' AND exam_year = '$year' AND term = '$term'");
				}
				echo '<div class="alert alert-success"><strong>Success!</strong> Marks deleted successfully.</div>';
				exit();
			}
			else{
				echo '<div class="alert alert-warning"><strong>Sorry!</strong> Problem retrieving trainees of this department. Please try again.</div>';
				exit();
			}
		}
		echo $request;
	
	}
	else{
		echo '<div class="alert alert-warning"><strong>Sorry!</strong> The system was unable to process your request. Please try again.</div>';
	}	
?>