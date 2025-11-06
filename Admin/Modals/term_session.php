<?php
	require('../../Database/config.php');
	$sql="SELECT * from years order by  year";
	$years_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($years_result)) {$year = $row['year'];}
	
	$term_options = array();
	$term_options[0] = array("id"=>"I", "name"=>"Jan - March", "start"=>"", "end"=>"");
	$term_options[1] = array("id"=>"II", "name"=>"May - July", "start"=>"", "end"=>"");
	$term_options[2] = array("id"=>"III", "name"=>"Sep - Nov", "start"=>"", "end"=>"");

	$sql="SELECT * from terms order by  term_name";
	$terms_result=mysqli_query($conn, $sql);
	$i = 0;
	while($row=mysqli_fetch_assoc($terms_result)) {
		$term_code = $row['term_code']; 
		$term_name = $row['term_name'];
		$start_date = $row['start_date'];
		$end_date = $row['end_date'];
	}

	$get_years = $conn->query("SELECT DISTINCT(exam_year) FROM results_entry ORDER BY exam_year DESC");

?>
<html>
<head>
	<style>
		.row{margin-bottom:10px}
		.card{
			width: 100%;
		}
	</style>
</head>
<body>
	<div class=row>
		<div class="mx-auto">
			<div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Set current term session</h3>
        </div>
        <form class="form-horizontal" id=dialog>
          <div class="card-body">

						<div class=row>
            	<div class=col-sm-5 style="margin-bottom: 10px;">
	              <label for="exampleInputEmail1">Year:</label>
            	</div>
            	<div class=col-sm-7 style="margin-bottom: 10px;">
	              <select class="form-control form-control-sm" name="year" id="year">
									<!-- <option value=<?php echo "$year";?> ><?php echo "$year";?></option> -->
									<?php
									$date=date('Y');

                    for($i=$date-4; $i<=$date-1; $i++){
                        $p = $i+1;
                        if($p == $year){$state = "selected";}else{$state = "";}
                        echo "<option value=".$p." ".$state.">".$p."</option>";
                    }
										?>
								</select>
            	</div>
						</div>

		 				<div class=row>
		 					<div class=col-sm-5 style="margin-bottom: 10px;">
	              <label for="exampleInputEmail1">Term:</label>
            	</div>
							<div class=col-sm-7 style="margin-bottom: 10px;">
								<select class="form-control form-control-sm" name="term" id="term" placeholder="Term" >
									<?php for($i=0; $i<sizeof($term_options); $i++){$id=$term_options[$i]['id'];$name=$term_options[$i]['name']; if($id == $term_code){$opt_state="selected";}else{$opt_state="";}echo '<option value='.$id.' '.$opt_state.'>'.$name.'</option>';}?>
								</select>                       
				      </div>
            </div>

            <!-- <div class="row">
            	<div class="col-sm-5"  style="margin-bottom: 10px;">
            		<label for="exampleInputEmail1">Start Date</label>
            	</div>
            	<div class="col-sm-7"  style="margin-bottom: 10px;">
            		<input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="<?php echo $start_date; ?>" ></input>
            	</div>
            </div>

            <div class="row">
            	<div class="col-sm-5"  style="margin-bottom: 10px;">
            		<label for="exampleInputEmail1">End Date</label>
            	</div>
            	<div class="col-sm-7"  style="margin-bottom: 10px;">
            		<input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="<?php echo $end_date; ?>" ></input>
            	</div>
            </div> -->

					</div>
          <div class="card-footer">
          	<button type="button" class="btn btn-danger float-left" id="close" >Close</button>
            <button type="button" class="btn btn-info float-right" id="update" >Update</button>
          </div>
        </form>
      </div>
		</div>
	</div>		
</body>	
<script>
	$(document).ready(function(){
		function term(){return $("#term").val();}
		function year(){return $("#year").val();}

		/*function todays_date(){return new Date(Date());}
		function start_date(){return new Date($("#start_date").val());}
		function end_date(){return new Date($("#end_date").val());}
		
		var millsecs = end_date.getTime() - start_date.getTime();
		var days = Math.round(Math.abs(millsecs/(1000*3600*24)));

		function check_start_date(){
			if (start_date == "Invalid date"){alert("Start date not set")}
			else{
				alert(todays_date)
			}
		}
		function check_end_date(){
			
		}

		$('#update').click(function(){
			check_start_date()
		})*/

		$('#update').click(function(){
			$.ajax({
				url:'Sessions/change_session.php',
				method:'post',
				data:{year:year(), term:term()},
				success:function(data)
				{
					if(data == 'success'){
						alert("Session updated successfully");
						location.reload();
					}
					else{
						toastr.error(data)
					}
				}
			})
		})

		$('#close').click(function(){
			location.reload();
		})
	})
</script>
</html>