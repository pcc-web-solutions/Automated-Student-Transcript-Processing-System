<?php

	session_start();

	require_once("../../Database/dbcontroller.php");

	$db_handle = new DBController();

	

	include('../../Database/config.php');



	$trainer_id = $_SESSION['Trainer'];



	$sql="SELECT DISTINCT(courses.code), courses.course_name FROM courses INNER JOIN trainer_units ON trainer_units.course_code = courses.code INNER JOIN trainees ON trainees.course_code = courses.code WHERE trainer_units.trainer_id = '$trainer_id' ORDER BY course_name ASC";

	$courses_result=mysqli_query($conn, $sql) or die("Problem running query");



?>



<html>

<head>

<script src="../../Assets/plugins/jquery/jquery.min.js"></script>

<script src="../../Assets/jquery-ui.min.js"></script>



<style>

.row{margin-bottom:5px}

.card-body{

	padding-left:9px;

	padding-right:9px;

}

</style>

</head>



<body>

<div class="card card-info">

<div class="card-header">

<h5 class="card-title">Mark entry</h5>

</div>

<div class="card-body">   



<form class="form-horizontal" id=dialog>



<div class=row>

	<div class=col-sm-4 style="margin-bottom: 10px;">

		<select class="form-control" name=course id=course>

			<option value="">--Select course--</option>

			<?php

			while($row=mysqli_fetch_assoc($courses_result)) { ?>

			<option value="<?php echo $row['code'];?>"><?php echo $row['course_name'];?></option>

			<?php } ?>

		</select>

	</div>



	<div class=col-sm-2 style="margin-bottom: 10px;">

		<select class="form-control" name=class_name  id=class_name>

			<option value="">--Select class--</option>

		</select>

	</div>



	<div class=col-sm-4 style="margin-bottom: 10px;">

		<select class="form-control" name=unit_name  id=unit_name>

			<option value="">--Select unit--</option>

		</select>

	</div>



	<div class="col-md-2" style="margin-bottom: 10px;">

		<button type="button" class="btn btn-primary float-right" id="load-trainees"><i class="fa fa-search"></i>&nbsp Get Trainees</button>

	</div>

</div>

</form>



<hr>

<div class=tbl>



</div>

</div>

</div>





</body>



<script>

$(document).ready(function()

{	

	$('#load-trainees').click(function()

	{

		var selected_course=$('#course').children("option:selected").val();

		var selected_unit=$('#unit_name').children("option:selected").val();

		var selected_class=$('#class_name').children("option:selected").val();

		

		if ( selected_course == "" )

		{

			alert('Please select course');

		}

		else if ( selected_class == "" )

		{

			alert('Please select class');

		}

		else if ( selected_unit == "" )

		{

			alert('Please select unit');

		}

		else{

			$.ajax({

				url:'New/mark_entry_sheet.php',

				method:'post',

				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},



				success:function(data)

				{

					$('.tbl').html(data);	

				}

			})

		}

})

})

</script>



<script type="text/javascript">

$(document).ready(function(){

	

    $("#course").change(function(){

        var selected_course = $('#course').children("option:selected").val();

        var selected_unit=$('#unit_name').children("option:selected").val();

		var selected_class=$('#class_name').children("option:selected").val();

		

		if(selected_course == ''){

			$('.tbl').html('<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>');

		}

		else{



			$.ajax({

				url: 'New/select_class.php',

				type: 'post',

				data: {selected_course:selected_course},

				dataType: 'json',

				success:function(response){



					var len = response.length;



					$("#class_name").empty();



					if(len<1){

						$("#class_name").append("<option value=''>No Classes Found</option>");

						document.querySelector('#load-trainees').style.visibility="hidden";

					}

					else{

						document.querySelector('#load-trainees').style.visibility="visible";

						$("#class_name").append("<option value=''>--Select class--</option>");

						for( var i = 0; i < len; i++){

							var class_name = response[i]['class_name'];
							$("#class_name").append("<option value="+class_name+">"+class_name+"</option>");

						}

					}

				} 

			});



			$("#unit_name").empty();

			$("#unit_name").append("<option value=''>--Select unit--</option>");

			$('.tbl').empty();

		}

    });

});





</script>





<script type="text/javascript">

	$(document).ready(function(){

		$('#unit_name').change(function(){

			var selected_course=$('#course').children("option:selected").val();

			var selected_unit=$('#unit_name').children("option:selected").val();

			var selected_class=$('#class_name').children("option:selected").val();

			$.ajax({

				url:'New/mark_entry_sheet.php',

				method:'post',

				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},



				success:function(data)

				{

					$('.tbl').html(data);	

				}

			})

			

		});

		

		$('#class_name').change(function(){

			var selected_course=$('#course').children("option:selected").val();

			var selected_unit=$('#unit_name').children("option:selected").val();

			var selected_class=$('#class_name').val();



			$.ajax({

				url: 'New/select_units.php',

				type: 'post',

				data: {selected_course:selected_course, selected_class:selected_class},

				dataType: 'json',

				success:function(response){



					var len = response.length;



					$("#unit_name").empty();

					if(len<1){

						$("#unit_name").append("<option value=''>No units Found</option>");

						document.querySelector('#load-trainees').style.visibility="hidden";

					}

					else{

						document.querySelector('#load-trainees').style.visibility="visible";

						for( var i = 0; i < len; i++){

							var unit_code = response[i]['unit_code'];

							var unit_name = response[i]['unit_name'];

							

							$("#unit_name").append("<option value='"+unit_code+"'>"+unit_name+"</option>");

						}

					}

				} 

			});

			

			$('.tbl').empty();



		});



	})

</script>



</html>