<?php
	session_start();
	$dept = $_SESSION['Admin'];
	include("../../Database/config.php");

	
	$sql="SELECT * from years order by  year";
	$years_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($years_result)) {$year = $row['year'];}
	
	$sql="SELECT * from terms order by  term_name";
	$terms_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($terms_result)) {$term = $row['term_name'];}

	$sql_cust_term = $conn->query("SELECT DISTINCT term FROM cl_att_register WHERE term != '$term' ORDER BY term ASC");

	$department = $conn->query("SELECT departments.department_code, department_name FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN trainees ON trainees.course_code = courses.code WHERE trainees.status = '1' GROUP BY departments.department_code ORDER BY departments.department_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<script type="text/javascript">
		function customize_session(condition){
			if(condition == true){
				$('.custom_session').html('Term: <select id="term" name="term" class="cust_session"><?php if($sql_cust_term->num_rows>0){?> <option value="">--choose--</option> <?php while ($row=$sql_cust_term->fetch_array()) {$cust_term = $row['term']; echo '<option value="'.$cust_term.'">'.$cust_term.'</option>';}}else{echo '<option value="">No other session</option>';}?> </select> Year: <input type="number" id="year" class="cust_session" max="4" min="4"></input> ')	
			}
			else{
				$('.custom_session').empty()
			}
		}

	</script>
</head>
<body>
	<div class="modal fade" id="modal-add-trainees">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
              <h6 class="modal-title" style="padding-top: 0px; padding-bottom: 0px;">Append trainees</h6>
              <button type="button" style="color: red;" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            	<div class="error_message"></div>
            	<div id="list_trainees"></div>
            </div>
            <div class="modal-footer">
            	<input type="submit" id="append" class="btn btn-success float-right" value="Append" />
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pdf-report">
    	<div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header bg-info">
	              <h6 class="modal-title" style="padding-top: 0px; padding-bottom: 0px;">Attendance report</h6>
	            </div>
	            <div class="modal-body">
	            	<div class="error_message_1"></div>
	            	<div id="report_area">
	            		<!-- <iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;" src="Reports/rpt_class_attendance.php"></iframe> -->
	            	</div>
	            </div>
	            <div class="modal-footer">
	            	<button type="button" style="color: red;" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	            	</button>
	            </div>
	        </div>
        </div>
    </div>

	<div class="card card-info card-outline">
		<div class="card-header">
			<h4 class="card-title"><b>Update class attendance</b></h4>&nbsp
			<span style="font-weight: bold; color: orange;" class="float-right">
				<input type="radio" name="term_session" class="<?php echo $year; ?>" id="<?php echo $term; ?>" value="default" checked> For current term</input> 
				<input type="radio" name="term_session" class="custom_year" id="custom_term" value="custom"> For custom term session</input>
				<span class="custom_session"></span>
			</span>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="dept">Select Department</label>
					<select class="form-control form-control-sm" id="dept">
						<option value = "">--choose--</option>
						<?php
						if ($department->num_rows > 0) {
							while ($row = $department->fetch_array()) {
								$code = $row['department_code'];
								$name = $row['department_name'];
								echo "<option value=".$code.">".$name."</option>";
							}
						}
						?>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<label for="course">Select Course</label>
					<select class="form-control form-control-sm" id="course">
						<option value="">--choose--</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="course">Select Unit</label>
					<select class="form-control form-control-sm" id="unit">
						<option value = "">--choose--</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="course">Select Class</label>
					<select class="form-control form-control-sm" id="class">
						<option value = "">--choose--</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="dateattended">Date</label>
					<input type="date" id="date" class="form-control form-control-sm" />
				</div>
				
				<div class="col-lg-1 col-md-1 col-sm-1">
					<label for="Action">Action</label>
					<button type="button" class="form-control form-control-sm btn-info float-right" id="get_trainees"><i class="fa fa-search"></i>&nbspSearch Trainees </button>
				</div>
			</div>
			<div id="trainees"></div>
		</div>
		<div id="footer_content">
			<div class="card-footer">
				<button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#modal-add-trainees" id="btn_add"><i class="fa fa-plus"></i>&nbspAdd trainees</button>
				<button type="button" class="btn btn-danger float-left" id="btn_delete"><i class="fa fa-trash"></i>&nbspDelete</button>
			</div>
		</div>

	</div>
    <style>
    	.cust_session, .cust_session:focus{
    		border: 1px hairline silver;
    		border-radius: 4px;
    		border-color: silver;
    		color: gray;
    		max-height: 30px;
    		margin: 0px 0px 0px 0px;
    	}
    	.term{
    		font-weight: bold; 
    		color: orange;
    	}
        table{
		border-collapse: collapse;
		border-radius: 5px;
		width: 100%;
		border: 2px solid silver;
		font-size: 14px;
		margin-bottom: 10px;
        }
        tr td{
            line-height:17px;
            min-height:17px;
            height:17px;
        }
        .col-sm-6,.col-sm-6, .col-sm-4, .col-md-4{
            margin-bottom: 10px;
        }
        tbody tr,td{
            border: 1.5px solid silver;
        }
        tbody tr:nth-child(even){
            background-color: lightcyan;
        }
        tr th{
            line-height:17px;
            min-height:17px;
            height:17px;
            background-color: lightgray;
            border: 1px solid silver;
            color: black;
        }
        td input.form-control, td input.form-control-sm, td input.form-control:focus, td input.form-control-sm:focus{
            border-radius: 0px;
            border-color: transparent;
            background-color: transparent;
        }
        #new_break{
            padding-top: 0px;
            padding-bottom: 0px;
            border-radius: 0px;
            border-color: transparent;
            background-color: transparent;
            color: brown;
        }
        #new_break:hover{
            color: white;
            background-color: grey;
        }
    </style>
</body>

<script type="text/javascript">
	$(document).ready(function(){

		function session_choice(){return $('input[name=term_session]:checked').attr('value')}

		$('input[name=term_session]').click(function(){
			if(session_choice() == 'custom'){
				customize_session(true)
			}
			else{
				customize_session(false)
			}
		})

		function term(){
			var term;
			if(session_choice() == 'custom'){
				term = $('#term').children("option:selected").val()
			}
			else{
				term = $('input[name=term_session]:checked').attr('id')
			}
			return term
		}
		function year(){
			var year;
			if(session_choice() == 'custom'){
				year = $("#year").val()
			}
			else{
				year = $('input[name="term_session"]:checked').attr('class')
			}
			return year
		}

		function dept(){return $("#dept").children("option:selected").val()}
		function course(){return $("#course").children("option:selected").val()}
		function unit(){return $("#unit").children("option:selected").val()}
		function cls(){return $("#class").children("option:selected").val()}
		function date(){return new $("#date").val()}
		function adm_numbers(){
			var adms = [];
			$('.checkboxes').each(function(){
				var self = $(this)
				if(self.is(':checked')){
					adms.push(self.attr('value'));
				}
			});
			return adms;
		}

		function adm_numbers_1(){
			/*var adms_1 = [];
			$('.checkboxes_1').each(function(){
				var self = $(this)
				if(self.is(':checked')){
					adms_1.push(self.attr('value'));
				}
			});
			return adms_1;*/
			return $('#search_1').val()
		}


		function get_courses(dept){
			$.ajax({
				url: 'Requests/req_courses.php',
				method: 'post',
				data: {dept:dept},
				dataType: 'json',
				success: function(response){
					var resp_len = response.length
					if(resp_len < 1){
						$('#course').empty()
						$('#course').append("<option value=''>No courses</option>")
					}
					else{
						$('#course').empty()
						$("#course").append("<option value=''>--choose--</option>");
						for(var i = 0; i < resp_len; i++){
							var code = response[i]['code'];
							var name = response[i]['course_name'];
							$('#course').append("<option value="+code+">"+name+"</option>")
						}
					}
				}
			})
		}

		function get_units(course){
			$.ajax({
				url: 'Requests/req_units.php',
				type: 'post',
				data: {course_code:course},
				dataType: 'json',
				success: function(response){
					var len = response.length;
					
					if (len<1) {
						$('#unit').empty();
						$("#unit").append("<option value=''>No unit found</option>");
					}
					else{
						$('#unit').empty();
						$("#unit").append("<option value=''>--choose--</option>");
						for (var i = 0; i < len; i++) {
							var unit_code = response[i]['unit_code'];
							var unit_name = response[i]['unit_name'];
							$('#unit').append("<option value="+unit_code+">"+unit_name+"</option>");
						}
					}
				}
			})
		}

		function get_classes(course){
			$.ajax({
				url: 'Requests/req_classes.php',
				type: 'post',
				data: {course_code:course},
				dataType: 'json',
				success: function(response){
					var len = response.length;
					
					if (len<1) {
						$('#class').empty();
						$("#class").append("<option value=''>No classes</option>");
					}
					else{
						$('#class').empty();
						$("#class").append("<option value=''>--choose--</option>");
						for (var i = 0; i < len; i++) {
							var class_name = response[i]['class_name'];
							$('#class').append("<option value="+class_name+">"+class_name+"</option>");
						}
					}
				}
			})
		}

		function check_date(date){
			$.ajax({
				url: 'Requests/check_date.php',
				method: 'post',
				data: {date:date},
				success: function(data){
					if(data != 'success'){
						toastr.error(data)
					}
				}
			})
		}
		
		function validate_data(){
			var message;
			if(term() == ""){message = "Term session not specified"}
			else if(year() == ""){message = "Session year not specified"}
			else if(dept() == ""){message = "Please select department"}
			else if(course() == ""){message = "Please select course"}
			else if(unit() == ""){message = "Please select unit"}
			else if(cls() == ""){message = "Please select class"}
			else if(date() == "" || date() == 'dd/mm/yyyy') {message = "Date cannot be blank"}
			else{message = "success";}
			return message;
		}
		
		function load_report(){
			$.ajax({
				url: 'Reports/rpt_class_attendance.php',
				method: 'post',
				data: {term:term(),year:year(),dept:dept(),course:course(), unit:unit(), cls:cls(), date:date()},
				success: function(data){
					$("#report_area").append('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
					$("#ifrm").attr("src", "Reports/rpt_course_analysis.php");
				}
			})
		}
		function list_trainees(term,year,dept,course,unit,cls,date){
			$.ajax({
				url: 'Requests/trainees_in_class.php',
				method: 'post',
				data: {term:term,year:year,dept:dept,course:course, unit:unit, cls:cls, date:date},
				success: function(data){
					$("#list_trainees").html(data)
				}
			})
		}

		function load_trainees(term,year,dept,course,unit,cls,date){
			$.ajax({
				url: 'Requests/trainees_marked_present.php',
				method: 'post',
				data: {term:term,year:year,dept:dept,course:course, unit:unit, class:cls, date:date},
				success: function(data){
					$("#trainees").html(data)
				}
			})
		}

		function trainees_table(){
			if(validate_data() != 'success'){toastr.error(validate_data())}
			else{
				load_trainees(term(),year(),dept(),course(),unit(),cls(),date())
			}
		}

		function request_data(term,year,dept,course,unit,cls,date,adms){
			$.ajax({
				url: 'View/view_attended_trainees.php',
				method: 'post',
				data: {term:term,year:year,dept:dept,course:course,unit:unit,class:cls,date:date,adms:adms},
				success: function(data){
					if(data != 'success'){
						toastr.info(data)
					}
					else{
						toastr.success("Data uploaded successfully")
						$('#trainees').html(load_trainees(dept(),course(),unit(),cls(),date(),cls_code()))
					}
				}
			})
		}

		function submit_data(){
			alert(adm_numbers_1())
			/*$.ajax({
				url: 'Insertdata/attended_trainees.php',
				method: 'post',
				data: {dept:dept(),course:course(),unit:unit(),class:cls(),date:date(),code:cls_code(),adms:adm_numbers_1()},
				success: function(data){
					if(data != 'success'){
						toastr.info(data)
					}
					else{
						toastr.success("Data uploaded successfully")
						// $('#trainees').html(load_trainees(dept(),course(),unit(),cls(),date(),cls_code() ) )
					}
				}
			})*/
		}

		$('#dept').change(function(){
			$('#course').empty()
			$('#trainees').empty()
			$('#unit').empty()
			$('#class').empty()
			get_courses(dept())
		})
		$("#course").change(function(){
			$('#trainees').empty()
			$('#unit').empty()
			$('#class').empty()
			if(course() != ""){
				get_units(course())
				get_classes(course())
			}
		})

		$("#unit").change(function(){
			get_classes(course())
		})

		$("#class").change(function(){
			trainees_table()
		})

		$("#get_trainees").on("click", function(){
			trainees_table();
		})

		/*$("#download").click(function(){
			$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
			$("#ifrm").attr("src", "Reports/rpt_course_analysis.php");
		})
		*/
		function delete_marked_absent(sn){
			$.ajax({
				url: 'Delete/marked_absent.php',
				method: 'post',
				data: {sn:sn},
				success: function(data){
					if(data != 'success'){
						toastr.info(data)
					}
					else{
						delete_marked_absent(adm_numbers_1())
						// toastr.success("Data uploaded successfully")
						$('#trainees').html(load_trainees(dept(),course(),unit(),cls(),date(),cls_code()))
					}
				}
			})			
		}
		$("#btn_delete").on("click", function(){
			
		})

		$("#btn_add").on("click", function(){
			if(validate_data() != 'success'){
				$('#list_trainees').empty()
				$('.error_message').html("<p class='callout callout-danger' style='color: red;'><strong>Error:</strong>&nbsp"+validate_data()+"</p>");
			}
			else{
				$('.error_message').empty()
				list_trainees(term(),year(),dept(),course(),unit(),cls(),date())
			}
		})

		$("#download").click(function(){
			if(validate_data() != 'success'){
				$('#report_area').empty()
				$('.error_message_1').html("<p class='callout callout-danger' style='color: red;'><strong>Error:</strong>&nbsp"+validate_data()+"</p>");
			}
			else{
				$('.error_message_1').empty()
				load_report()
			}
		})

		$("#append").click(function(){
			if(validate_data() == 'success'){
				submit_data()
			}
			else{}
		})
	})
</script>
</html>