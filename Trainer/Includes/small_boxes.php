<?php
	$trainer_id = $_SESSION['Trainer'];

	include('../Database/config.php');

	$get_courses = $conn->query("SELECT COUNT(DISTINCT course_code) AS courses FROM trainer_units WHERE trainer_id = '$trainer_id' ");
	while ($rows = mysqli_fetch_array($get_courses)) {
		$courses_count = $rows['courses'];
	}

	$get_units = $conn->query("SELECT COUNT(DISTINCT unit_code) AS units FROM trainer_units WHERE trainer_id = '$trainer_id' ");
	while ($rows = mysqli_fetch_array($get_units)) {
		$units_count = $rows['units'];
	}

	$get_classes = $conn->query("SELECT COUNT(DISTINCT class_name) AS classes FROM trainer_units WHERE trainer_id = '$trainer_id' ");
	while ($rows = mysqli_fetch_array($get_classes)) {
	$classes_count = $rows['classes'];
	}

	$get_trainees = $conn->query("SELECT count(DISTINCT trainees.adm) AS trainees FROM trainees INNER JOIN classes ON classes.class_name = trainees.class INNER JOIN trainer_units ON trainer_units.class_name = trainees.class WHERE trainer_units.trainer_id = '$trainer_id' AND trainees.deleted_by = ''");
	while ($rows = mysqli_fetch_array($get_trainees)) {
		$trainees_count = $rows['trainees'];
	}
?>
	<div class="row">
	  <div class="col-12 ">
		<!-- Default box -->
			<!-- cards -->
			<div class="row noprint "  style="margin-top: 10px; margin-left: 2px; padding-left: 0px;"> 
			  <div class="col-md-3 col-sm-6 col-12 noprint " >
				<div class="info-box ">
				  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>

				  <div class="info-box-content">
					<span class="info-box-text">Trainees</span>
					<span class="info-box-number"><?php	echo number_format($trainees_count);?></span>
				  </div>
				  <!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			  </div>
			  <!-- /.col -->
			  <div class="col-md-3 col-sm-6 col-12 noprint">
				<div class="info-box">
				  <span class="info-box-icon bg-success"><i class="fas fa-school"></i></span>

				  <div class="info-box-content noprint">
					<span class="info-box-text">Classes</span>
					<span class="info-box-number"><?php echo number_format($classes_count,0);?></span>
				  </div>
				  <!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			  </div>
			  <!-- /.col -->
			  <div class="col-md-3 col-sm-6 col-12">
				<div class="info-box">
				  <span class="info-box-icon bg-warning"><i class="far fas fa-list"></i></span>

				  <div class="info-box-content">
					<span class="info-box-text">Units</span>
					<span class="info-box-number"><?php echo number_format($units_count,0);?></span>
				  </div>
				  <!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			  </div>
			  <!-- /.col -->
			  <div class="col-md-3 col-sm-6 col-12">
				<div class="info-box">
				  <span class="info-box-icon bg-grey"><i class="far fas fa-bars"></i></span>

				  <div class="info-box-content">
					<span class="info-box-text">Courses</span>
					<span class="info-box-number"><?php echo number_format($courses_count,0); ?></span>
				  </div>
				  <!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			  </div>
			  <!-- /.col -->
			</div>
		<!-- /.card -->
	  </div>
	</div>
		<style>
			
		</style>