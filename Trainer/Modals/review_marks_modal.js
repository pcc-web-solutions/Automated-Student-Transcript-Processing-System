$(document).ready(function()
{	
	document.querySelector('#general').style.visibility="hidden";

	$("#search").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#marks-table tbody tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		})
	})
	$('#courses').change(function()
	{
		var selected_course=$('#courses').children("option:selected").val();
		var selected_class=$('#classes').children("option:selected").val();
		var selected_unit=$('#units').children("option:selected").val();

		if(selected_course == ''){
			$('.tbl').html('<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>');
		}
		else{

			$('#units').empty();
			$('#units').append("<option value = ''>--Select unit--</option>");

			$.ajax({
				url: 'New/select_class.php',
				type: 'post',
				data: {selected_course:selected_course},
				dataType: 'json',
				success:function(response){

					var len = response.length;
					
					$("#classes").empty();
					if(len==0){
						$('.success_message').empty();
						$('.tbl').empty();
						$("#classes").append("<option value=''>No class found</option>");
						$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no classes</div>");
					}
					else{
						$('.error_message').empty();
						$("#classes").append("<option value=''>--Select class--</option>");
						for( var i = 0; i < len; i++){
							var class_name = response[i]['class_name'];
							
							$("#classes").append("<option value='"+class_name+"'>"+class_name+"</option>");
						}
					}
				} 
			});

		}
	});

	$('#load-marks').click(function()
	{
		var selected_course=$('#courses').children("option:selected").val();
		var selected_class=$('#classes').children("option:selected").val();
		var selected_unit=$('#units').children("option:selected").val();
		
		if (selected_course == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a course first</div>");
		}
		else if (selected_class == '' && selected_unit == ''){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a class and a unit for "+selected_course+" first</div>");
		}
		else if (selected_class == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a class first</div>");
		}
		else if (selected_unit == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a unit first</div>");
		}
		else{
			$.ajax({
				url:'View/review_marks_new.php',
				method:'post',
				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

				success:function(data)
				{
					$('.tbl').html(data);	
				}
			});
		}
	});

	
	$('#view_all').click(function(event)
	{	
		event.preventDefault();
		$('.success_message').empty();
		$('.error_message').empty();
		$('#specific').empty();
		document.querySelector('#general').style.visibility="visible";

	  	var request="View all marks";

		$.ajax({
			url:'Requests/review_marks_requests.php',
			method:'post',
			data:{request:request},
			success:function(data)
			{
				$('.tbl').html(data);
			}
		});
	});

	$('#delete_all_specific').click(function(event)
	{	
	  	var request="Delete all marks";
	  	var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();
		
		if(confirm("Are you sure you want to delete all "+selected_unit+" marks for "+selected_class+" class?"))
		{
			$.ajax({
				url:'Requests/review_marks_requests.php',
				method:'post',
				data:{request:request, selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},
				success:function(data)
				{
					$('.tbl').html(data);
				}
			});
		}else{
			event.preventDefault();
		}	
	});

	$('#delete_all').click(function(event){
		var request="Delete this session marks";
		
		if(confirm("Are you sure you want to delete all marks for this term session?"))
		{
			$.ajax({
				url:'Requests/review_marks_requests.php',
				method:'post',
				data:{request:request},
				success:function(data)
				{
					$('.tbl').html(data);
				}
			});
		}else{
			event.preventDefault();
		}	
	});	

	$('#units').change(function(){
		var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();
		$.ajax({
			url:'View/review_marks_new.php',
			method:'post',
			data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

			success:function(data)
			{
				$('.tbl').html(data);	
			}
		})
		
	});
	
	$('#classes').change(function(){
		var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();

		$.ajax({
			url: 'New/select_units.php',
			type: 'post',
			data: {selected_course:selected_course, selected_class:selected_class},
			dataType: 'json',
			success:function(response){

				var len = response.length;

				$("#units").empty();

				if(len==0){
					$('.success_message').empty();
					$('.tbl').empty();
					$("#units").append("<option value=''>No unit found</option>");
					$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no units</div>");
				}
				else{
					for( var i = 0; i < len; i++){
						var unit_code = response[i]['unit_code'];
						var unit_name = response[i]['unit_name'];
						
						$("#units").append("<option value='"+unit_code+"'>"+unit_name+"</option>");
					}
				}
			} 
		});
		$('.tbl').empty();
	});
});