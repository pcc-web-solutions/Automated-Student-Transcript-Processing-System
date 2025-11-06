<div class="row" id="small-boxes">
  <div class="col-12 col-sm-6 col-md-3" style="margin-top:10px;">
	<div class="info-box">
	  <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

	  <div class="info-box-content">
		<span class="info-box-text">Active Trainees</span>
		<span class="info-box-number">
		  <?php
				while($row =mysqli_fetch_assoc($trainees_count)){
					echo number_format($row['trainees']);
				}
			?> 
		</span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3" style="margin-top:10px;">
	<div class="info-box mb-3">
	  <span class="info-box-icon bg-pink elevation-1"><i class="fa fa-user-circle"></i></span>

	  <div class="info-box-content">
		<span class="info-box-text">Trainers</span>
		<span class="info-box-number">
		<?php 
				while($row =mysqli_fetch_assoc($trainers_count)){
					echo number_format($row['trainers']);
				}
			?>  
		</span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3" style="margin-top:10px;">
	<div class="info-box mb-3">
	  <span class="info-box-icon bg-success elevation-1"><i class="fa fa-bars"></i></span>

	  <div class="info-box-content">
		<span class="info-box-text">Courses</span>
		<span class="info-box-number">
		<?php
				while($row =mysqli_fetch_assoc($courses_count)){
					echo number_format($row['courses']);
				}
			?> 
		</span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3" style="margin-top:10px;">
	<div class="info-box mb-3">
	  <span class="info-box-icon bg-yellow elevation-1"><i class="far fas fa-school"></i></span>

	  <div class="info-box-content">
		<span class="info-box-text">Classes</span>
		<span class="info-box-number">
		<?php
				while($row =mysqli_fetch_assoc($classes_count)){
					echo number_format($row['classes']);
				}
	   ?>
	   </span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->