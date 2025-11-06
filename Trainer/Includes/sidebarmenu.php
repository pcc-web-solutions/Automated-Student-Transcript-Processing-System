<?php 
  // session_start();
  include("../Database/config.php");
  $status = $_SESSION['mark_entry_status'];
?>
      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

		 <li class="nav-item has-treeview">

            <a href="#" class="nav-link">

              <i class="nav-icon fas fa-users"></i>

              <p>

                Trainees

                <i class="right fas fa-angle-left"></i>

              </p>

            </a>

            <ul class="nav nav-treeview">

			

			<li class="nav-item">

                <a href="#" class="nav-link" id=add_trainee>

                  <i class="far fa-circle nav-icon"></i>

                  <p>Add trainees</p>

                </a>

              </li>

              <li class="nav-item">

                <a href="#" class="nav-link" id=review_trainees>

                  <i class="far fa-circle nav-icon"></i>

                  <p>Review trainee</p>

                </a>

              </li>  

            </ul>

          </li>

          

		  <li class="nav-item has-treeview">

            <a href="#" class="nav-link">

              <i class="nav-icon fas fa-bars"></i>

              <p>

                Courses

                <i class="fas fa-angle-left right"></i>

              </p>

            </a>

            <ul class="nav nav-treeview">

              <li class="nav-item">

                <a href="#" class="nav-link" id=add_course>

                  <i class="far fa-circle nav-icon"></i>

                  <p>Add course </p>

                </a>

              </li>

			  

			   <li class="nav-item">

                <a href="#" class="nav-link" id=review_courses>

                  <i class="far fa-circle nav-icon"></i>

                  <p>Review course </p>

                </a>

              </li>

			  

			   </ul>

          

			  </li>

			               

         

       <li class="nav-item has-treeview">

          <a href="#" class="nav-link">

            <i class="nav-icon fas fa-book"></i>

            <p>

              Units

              <i class="fas fa-angle-left right"></i>

            </p>

          </a>

          <ul class="nav nav-treeview">

              

          <li class="nav-item">

            <a href="#" class="nav-link" id=add_unit>

              <i class="far fa-circle nav-icon"></i>

              <p>Add unit </p>

            </a>

          </li>

    

          <li class="nav-item">

            <a href="#" class="nav-link" id=review_unit>

              <i class="far fa-circle nav-icon"></i>

              <p>Review units </p>

            </a>

          </li>

        </ul>

      </li>

       

      <li class="nav-item has-treeview">

        <a href="#" class="nav-link">

          <i class="nav-icon fas fa-school"></i>

          <p>

            Classes

            <i class="fas fa-angle-left right"></i>

          </p>

        </a>

        <ul class="nav nav-treeview">

              

        <li class="nav-item">

          <a href="#" class="nav-link" id=add_class>

            <i class="far fa-circle nav-icon"></i>

            <p>New Class </p>

          </a>

        </li>

        

        <li class="nav-item">

          <a href="#" class="nav-link" id=review_classes>

            <i class="far fa-circle nav-icon"></i>

            <p>Review Classes </p>

          </a>

        </li>

      </ul>

      </li>

      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-folder"></i>
          <p> Attendance </p>
          <i class="right fas fa-angle-left"></i>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-file"></i>
              <p>Class Attendance </p>
              <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link" id="mark_class_register">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mark Register</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" id="update_class_register">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Update Register</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" id="class_attendance_report">
                  <i class="far fa-circle nav-icon"></i>
                  <p>PDF Report</p>
                </a>
              </li>
            </ul>
          </li>
        </ul> 
        <ul class="nav nav-treeview">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-file"></i>
              <p>Exam Attendance </p>
              <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link" id="mark_exam_register">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mark Register</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" id="update_exam_register">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Update Register</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" id="exam_attendance_report">
                  <i class="far fa-circle nav-icon"></i>
                  <p>PDF Report</p>
                </a>
              </li>
            </ul>
          </li>
        </ul> 
      </li>

        <?php
        if ($status == "Open") { ?>
	      <li class="nav-item has-treeview">

          <a href="#" class="nav-link">

            <i class="nav-icon fas fa-poll"></i>

            <p>

              Marks

              <i class="fas fa-angle-left right"></i>

            </p>

          </a>

          <ul class="nav nav-treeview">

		       <li class="nav-item">

              <a href="#" class="nav-link" id=mark_entry>

                <i class="far fa-circle nav-icon"></i>

                <p>Enter marks</p>

              </a>

            </li>

              

			      <li class="nav-item">

              <a href="#" class="nav-link" id=review_marks_dialog>

                <i class="far fa-circle nav-icon"></i>

                <p>Edit marks</p>

              </a>

            </li>	



            <li class="nav-item">

              <a href="#" class="nav-link" id=list_of_shame>

                <i class="far fa-circle nav-icon"></i>

                <p>Missing Marks</p>

              </a>

            </li>	

        </ul>

			</li>

    <?php } ?>

      <li class="nav-item has-treeview">

        <a href="#" class="nav-link">

          <i class="nav-icon fas fa-book-open"></i>

          <p>

            Reports

            <i class="right fas fa-angle-left"></i>

          </p>

        </a>

        <ul class="nav nav-treeview">

          

          <li class="nav-item">

            <a href="#" class="nav-link" id=mark_lists>

              <i class="far fa-circle nav-icon"></i>

              <p>Mark lists</p>

            </a>

          </li>



          <li class="nav-item">

            <a href="#" class="nav-link" id=mark_sheets>

              <i class="far fa-circle nav-icon"></i>

              <p>Mark sheets</p>

            </a>

          </li>

        </ul>

      </li>
    </ul>

  </nav>

<!-- /.sidebar-menu -->