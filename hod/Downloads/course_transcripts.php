<?php
session_start();
$dept = $_SESSION['dept'];
include('../../Database/config.php');

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

$courses=$conn->query("SELECT DISTINCT(results_entry.course_code),courses.course_name,COUNT(DISTINCT results_entry.adm) AS total_trainees FROM results_entry INNER JOIN trainees ON trainees.course_code = results_entry.course_code INNER JOIN courses ON results_entry.course_code = courses.code WHERE courses.department_code = '$dept' AND trainees.status = '1' AND results_entry.term = '$term' AND results_entry.exam_year = '$year' GROUP BY courses.code ORDER BY courses.code");
?>

<!DOCTYPE />
<html>
	<head>
		<style>
			td button{
				margin: 0px;
				padding: 0px;
				width: 100%;
			}
			.search:focus{
				width: 100%;
			}
			.search {
				width: 170px;
				transition: width 0.4s ease-in-out;
				margin-bottom:0px
			}
			table{
				width: 100%;
				border-collapse: collapse;
			}
			thead tr th{
				background-color: cyan;
				border: 1px solid grey;
			}
			tbody tr td{
				border: 1px solid grey;
			}
			tbody tr:nth-child(even){
				background-color: lightcyan;
			}
			button.form-control-sm{
				padding-top: 0px;
			}
		</style>
	</head>
	<body>
		<div class="card card-warning">
			<div class="card-header">
				<h5 class="card-title">COURSE TRANSCRIPTS</h5>	
			</div>
			<div class="card-body">
				<div class="row" style="margin-top: 0px; margin-bottom: 0px;">
					<div class="col-sm-10"style="margin-bottom: 10px;">
						<input  type="Text" id="search" class="form-control search" placeholder="Type to search..." ></input>
					</div>
					<div class="col-sm-2"style="margin-bottom: 10px;">
						<button type="button" class="btn btn-info btn-block float-right" id="download_all"><i class="fa fa-download"></i>&nbsp Download All</button>
					</div>
				</div>
				<form class="table-responsive">
					<table class="table-striped text-nowrap">
						<thead>
							<tr>
								<th>SN</th>
								<th>CODE</th>
								<th>COURSE NAME</th>
								<th colspan=2>PDF FILE</th>
							</tr>
						</thead>
						<tbody id=records>
						<?php 
							IF($courses->num_rows > 0){
							$record_count = 0;
							while($data=mysqli_fetch_assoc($courses)){?>
							<tr>
								<td><?php echo ++$record_count; ?></td>
								<td><?php echo strtoupper($data['course_code']); ?></td>
								<td><?php echo strtoupper($data['course_name']); ?></td>
								<td><button name="view" class="btn btn-info form-control-sm" id=<?php echo $data['course_code']; ?> ><i class="fa fa-eye"></i>&nbsp View</button></td>
								<td><button name="download" class="btn btn-primary form-control-sm" id=<?php echo $data['course_code']; ?> ><i class="fa fa-download"></i>&nbsp Download</button></td>
							</tr>
							<?php } 
							}
							else{ ?>
							<tr>
								<td colspan=5><h6><center><?php echo strtoupper("No mark entries for ".$year." TERM ".$term);?></center></h6></td>
							</tr>
							<?php }?>							
						</tbody>
					</table>
				</form>	
			</div>
			<div class="card-footer">
			</div>
		</div>
		
	</body>
</html>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#search").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		
		$("#records tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		})
	})
	
	$('#download_all').click(function(e){
		e.preventDefault();
		alert('This action will download all course transcripts');
	});

	$('button[name=view]').click(function(e){
		e.preventDefault();
		var selected_course = $(this).attr('id');
		var download_status = 'true';
		
		$.ajax({
			url: 'Sessions/sessions.php',
			method: 'post',
			data: {selected_course:selected_course,download_status:download_status},
			success: function(data){
				if(data == 'Success'){
					$('#container').html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
					$("#ifrm").attr("src", "Reports/tcpdf-transcript.php");
				}
				else{
					alert('Problem getting the course identity code');
				}
			}
		})
		
	});
	
	$('button[name=download]').click(function(e){
		e.preventDefault();
		var selected_course = $(this).attr('id');
		var download_status = 'true';
		
		// var new_id = ('#'+selected_course+'');
		// alert(''+new_id+'');
		// document.querySelector('button[id='+selected_course+']').style.visibility="hidden";
		$.ajax({
			url: 'Sessions/sessions.php',
			method: 'post',
			data: {selected_course:selected_course,download_status:download_status},
			success: function(data){
				if(data == 'Success'){
					location.replace("Reports/tcpdf-transcript-d.php");
				}
				else{
					alert('Problem getting the course identity code');
				}
			}
		})
		
	});
});

</script>