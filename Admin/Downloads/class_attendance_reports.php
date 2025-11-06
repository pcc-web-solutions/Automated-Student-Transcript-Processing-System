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
				$('.custom_session').html('Term: <select id="term" name="term" class="cust_session"><option value="">--choose--</option><?php if($sql_cust_term->num_rows>0){while ($row=$sql_cust_term->fetch_array()) {$cust_term = $row['term']; echo '<option value="'.$cust_term.'">'.$cust_term.'</option>';}}else{echo '<option value="">No other session</option>';}?> </select> Year: <input type="number" id="year" class="cust_session" max="4" min="4"></input> ')	
			}
			else{
				$('.custom_session').empty()
			}
		}
	</script>
</head>
<body>

	<div class="card card-info card-outline">
		<div class="card-header">
			<h4 class="card-title"><b>Class attendance reports</b></h4>&nbsp
			<span style="font-weight: bold; color: seagreen;" class="float-right">
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
				<div class="col-lg-4 col-md-4 col-sm-4">
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
				<!-- <div class="col-lg-2 col-md-2 col-sm-2">
					<label for="dateattended">Date</label>
					<input type="date" id="date" class="form-control form-control-sm" />
				</div> -->
				
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="Action">Action</label>
					<button type="button" class="form-control form-control-sm btn-success float-right" id="load_available_reports"><i class="fa fa-search"></i>&nbspLoad Reports </button>
				</div>
			</div>
			<div id="available_reports"></div>
		</div>
		<div class="card-footer">
			<marquee behaviour="alternate" direction="right"><small><strong>Please ensure all the fields are filled properly before loading available reports</strong></small></marquee>
		</div>

	</div>
    <style>
    	body{
    		font-style: times;
    	}
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
		font-family: sans-serif;
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

		function validate_data(){
			var message;
			if(term() == ""){message = "Term session not specified"}
			else if(year() == ""){message = "Session year not specified"}
			else if(dept() == ""){message = "Please select department"}
			else if(course() == ""){message = "Please select course"}
			else if(unit() == ""){message = "Please select unit"}
			else if(cls() == ""){message = "Please select class"}
			else{message = "success";}
			return message;
		}
	
		function available_reports(term,year,dept,course,unit,cls){
			$.ajax({
				url: 'Requests/available_cl_att_reports.php',
				method: 'post',
				data: {term:term,year:year,dept:dept,course:course, unit:unit, class:cls},
				success: function(data){
					$("#available_reports").html(data)
				}
			})
		}

		function report_contents(){
			if(validate_data() != 'success'){toastr.error(validate_data())}
			else{
				available_reports(term(),year(),dept(),course(),unit(),cls())
			}
		}


		function load_report(term,year,dept,course,unit,cls){
			$.ajax({
				url: 'Reports/rpt_class_attendance.php',
				method: 'post',
				data: {term:term,year:year,dept:dept,course:course, unit:unit, cls:cls},
				success: function(data){
					$("#report_area").html(data)
				}
			})
		}

		$('#dept').change(function(){
			$('#course').empty()
			$('#available_reports').empty()
			$('#unit').empty()
			$('#class').empty()
			get_courses(dept())
		})
		$("#course").change(function(){
			$('#available_reports').empty()
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
			report_contents()
		})

		$("#load_available_reports").on("click", function(){
			report_contents();
		})

		$("#download").click(function(){
			if(validate_data() != 'success'){
				$('.error_message').html("<p class='callout callout-danger' style='color: red;'><strong>Error:</strong>&nbsp"+validate_data()+"</p>");
			}
			else{
				load_report(term(),year(),dept(),course(),unit(),cls())
			}
		})
		
		$("#btn_delete").on("click", function(){
			alert("Delete button is clicked")			
		})

	})
</script>
</html>