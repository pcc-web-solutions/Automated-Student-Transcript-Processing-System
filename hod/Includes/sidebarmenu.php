<?php 
  // session_start();
  include("../Database/config.php");
  $status = $_SESSION['mark_entry_status'];
  
?>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

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
          
          <li class="nav-item">
          <a href="#" class="nav-link" id=trainees_report>
            <i class="far fa-circle nav-icon"></i>
            <p>Trainees Report</p>
          </a>
          </li>
        </ul>
      </li>
        
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-user-circle"></i>
          <p>
          Trainers
          <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
        
        <li class="nav-item">
          <a href="#" class="nav-link" id=add_trainer>
            <i class="far fa-circle nav-icon"></i>
            <p>Add trainers</p>
          </a>
          </li>
          
          <li class="nav-item">
          <a href="#" class="nav-link" id=review_trainers>
            <i class="far fa-circle nav-icon"></i>
            <p>Review trainers</p>
          </a>
          </li>
          
          <li class="nav-item">
            <a href="#" class="nav-link" id=assign_units>
              <i class="far fa-circle nav-icon"></i>
              <p>Assign Units</p>
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

              <li class="nav-item">
                <a href="#" class="nav-link" id=courses_report>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Courses Report </p>
                </a>
              </li>
			  
			   </ul>
          
			</li>
			               
           	 
		  <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
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
              <p>Review unit </p>
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
		 
		  <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-poll"></i>
              <p>
                Marks
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

            <?php
            if ($status == "Open") { ?>
			      <li class="nav-item">
                <a href="#" class="nav-link" id=mark_entry>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mark Entry</p>
                </a>
              </li>
                
				      <li class="nav-item">
                <a href="#" class="nav-link" id=review_marks_dialog>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Review marks</p>
                </a>
              </li>
            <?php 
            }
            ?>
              <li class="nav-item">
                <a href="#" class="nav-link" id=list_of_shame>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Missing Marks</p>
                </a>
              </li>  
              
              <li class="nav-item">
                <a href="#" class="nav-link" id=mark_lists>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mark Lists</p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="#" class="nav-link" id=mark_sheets>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mark Sheets</p>
                </a>
              </li>
                

            </ul>
			</li>
 
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
                <a href="#" class="nav-link" id=course_transcripts>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Course Transcripts</p>
                </a>
              </li>
			  
			  <li class="nav-item">
                <a href="#" class="nav-link" id=individual_transcripts>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Individual Transcripts</p>
                </a>
              </li>
			  
        <li class="nav-item">
                <a href="#" class="nav-link" id=general_analysis>
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Analysis</p>
                </a>
              </li>
        
        <li class="nav-item">
                <a href="#" class="nav-link" id=course_analysis>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Course Analysis</p>
                </a>
              </li>
 
			 </ul>
			 </li>
		  
      <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
        
        <li class="nav-item">
                <a href="#" class="nav-link" id=add_user>
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p>New User </p>
                </a>
              </li>
        
        <li class="nav-item">
                <a href="#" class="nav-link" id=review_users>
                  <i class="far fa-user nav-icon"></i>
                  <p>Review Users </p>
                </a>
              </li>
         </ul>  
       </li>

      </ul>
 </nav>
      <!-- /.sidebar-menu -->