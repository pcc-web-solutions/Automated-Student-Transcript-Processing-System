<?php
	session_start();
	$dept = $_SESSION['dept'];
	include("../../Database/config.php");

	$courses = $conn->query("SELECT code, course_name FROM courses INNER JOIN trainer_units ON trainer_units.course_code = courses.code INNER JOIN trainees ON trainees.course_code = courses.code WHERE courses.department_code = '$dept' AND trainees.status = '1' GROUP BY courses.code ORDER BY code");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<div class="card card-info card-outline">
		<div class="card-header">
			<h4 class="card-title">Mark class attendance</h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="course">Select Course</label>
					<select class="form-control form-control-sm" id="course">
						<option value="">--choose--</option>
						<?php
						if ($courses->num_rows > 0) {
							while ($row = $courses->fetch_array()) {
								$code = $row['code'];
								$course_name = $row['course_name'];
								echo "<option value=".$code.">".$course_name."</option>";
							}
						}
						?>
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
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="class_code">3-Digit Code</label>
					<input type="number" class="form-control form-control-sm" placeholder="e.g 123" id="class_code"></input>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="Action">Action</label>
					<button type="button" class="form-control form-control-sm btn-info float-right" id="get_trainees"><i class="fa fa-search"></i>&nbspSearch Trainees </button>
				</div>
			</div>
			<div id="trainees"></div>
		</div>
		<div id="footer_content">
			<div class="card-footer">
				<button type="button" class="btn btn-danger float-right" id="close"><i class="fa fa-times"></i>&nbspClose</button>
				<button type="button" class="btn btn-primary float-left" id="upload"><i class="fa fa-upload"></i>&nbspUpload</button>
			</div>
		</div>
	</div>
    <style>
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

		function course(){return $("#course").children("option:selected").val() }
		function unit(){return $("#unit").children("option:selected").val()}
		function cls(){return $("#class").children("option:selected").val()}
		function date(){return new $("#date").val()}
		function cls_code(){return $("#class_code").val()}
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

		function get_units(course){
			$.ajax({
				url: 'Requests/req_my_units.php',
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
				url: 'Requests/req_my_classes.php',
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

		function check_class_code(code){
			var len = code.length;
			if(len != 3){
				return "error";
			}
			else{
				return "ok";
			}
		}

		function validate_data(){
			var message;
			if(course() == ""){message = "Please select course"}
			else if(unit() == ""){message = "Please select unit"}
			else if(cls() == ""){message = "Please select class"}
			else if(date() == "" || date() == 'dd/mm/yyyy') {message = "Date cannot be blank"}
			else if(cls_code() == ""){message = "Class code cannot be empty"}
			else if(check_class_code(cls_code()) == "error"){message = "The value entered is not in 3 digits"}
			else{message = "success";}
			return message;
		}
	
		function load_trainees(course,unit,cls,date,code){
			$.ajax({
				url: 'Requests/trainees_in_class.php',
				method: 'post',
				data: {course:course, unit:unit, cls:cls, date:date, code:code},
				success: function(data){
					$("#trainees").html(data)
				}
			})
		}

		function trainees_table(){
			if(validate_data() != 'success'){toastr.error(validate_data())}
			else{
				load_trainees(course(),unit(),cls(),date(),cls_code())
			}
		}

		function submit_data(course,unit,cls,date,code,adms){
			$.ajax({
				url: 'Insertdata/attended_trainees.php',
				method: 'post',
				data: {course:course,unit:unit,class:cls,date:date,code:code,adms:adms},
				success: function(data){
					if(data != 'success'){
						toastr.info(data)
					}
					else{
						toastr.success("Data uploaded successfully")
						$('#trainees').html(load_trainees(course(),unit(),cls(),date(),cls_code()))
					}
				}
			})
		}

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

		$("#date").change(function(){
			check_date(date())
		})

		$("#get_trainees").on("click", function(){
			$.ajax({
				url: 'Requests/check_date.php',
				method: 'post',
				data: {date:date},
				success: function(data){
					if(data != 'success'){
						toastr.error(data)
					}
					else{
						trainees_table();
					}
				}
			})
		})

		$("#upload").on("click", function(){
			if(validate_data() != 'success'){
				toastr.error(validate_data())
			}
			else{
				submit_data(course(), unit(), cls(), date(), cls_code(), adm_numbers())
			}
		})

		$("#close").on("click", function(){
			location.reload()
		})
	})
</script>
</html>