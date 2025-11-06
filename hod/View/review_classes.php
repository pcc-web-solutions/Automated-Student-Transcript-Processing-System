<?php
session_start();
$dept = $_SESSION['dept'];

include('../../Database/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>
    

            <?php  
             $classes=$conn->query("SELECT DISTINCT(trainees.class), classes.class_id, classes.course_abrev, classes.academic_year, intakes.int_name, classes.status FROM trainees INNER JOIN classes ON classes.class_name = trainees.class INNER JOIN courses ON courses.course_abrev = classes.course_abrev INNER JOIN intakes ON classes.intake = intakes.int_abrev WHERE  courses.department_code = '$dept' AND trainees.status = '1' ORDER BY course_abrev ASC, academic_year DESC, intakes.int_name DESC, classes.class_name ASC");
            if(mysqli_num_rows($classes)>0)
            {?>
   <div class="card card-info">
   <div class="card-header">
   <h3 class="card-title">Review Classes</h3>
   </div>
   <div class="card-body ">
  
<div class=cont>
<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>

<div class=table-responsive>
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
            <tr class="bg-info">
                            <th>SN</th>
                            <th>Identity Code</th>
                            <th>Course Abreviation</th>
                            <th>Academic Year</th>
                            <th>Intake</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <?php $sn=0; 
                        while ($row=mysqli_fetch_assoc($classes)){ 
                        if($row['status']=="Active"){$icon = "lock-open"; $color = "orange";}else{$icon = "lock"; $color = "brown";}
                        ?>
                        <tr style="font-size: 12px;">
                            <td><?php echo ++$sn; ?></td>
                            <td><?php echo $row['class_id']; ?></td>
                            <td><?php echo strtoupper($row['course_abrev']); ?></td>
                            <td><?php echo strtoupper($row['academic_year']); ?></td>
                            <td><?php echo strtoupper($row['int_name']); ?></td>
                            <td><?php echo strtoupper($row['class']); ?></td>
                            <td><?php echo strtoupper($row['status']); ?></td>
                            <td><a href="#" class="action" id=<?php echo $row['class_id']; ?> style="color:<?php echo $color; ?>;"><strong><i id="icon" class="fas fa-<?php echo $icon; ?> fa-lg"></i></strong></a></td>
                            <td><a href="#" class="delete" id=<?php echo $row['class_id']; ?> style="color:red"><strong><i id="icon" class="fas fa-<?php echo "trash"; ?> fa-lg center"></i></strong></a></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php } else{?>
                
                    <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Identity Code</th>
                            <th>Course Abreviation</th>
                            <th>Academic Year</th>
                            <th>Intake</th>
                            <th>Class</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <tr>
                        <td colspan=7 style="text-align: center;"><?php echo '<h6>There are no registered classes<h6>'; ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php } ?>    
        </div>
    </div>
<style>
  .row{
    margin-bottom: 10px;
  }
</style>
    <!-- jQuery -->
    <script src="../../Assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="../../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="../../Assets/plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../Assets/dist/js/adminlte.min.js"></script>
</body>

<style>
    
    .card{
        width: 100%;
    }
    button:hover{
        cursor: pointer;
    }
    .search {
    width: 200px;
    transition: width 0.4s ease-in-out;
    margin-bottom:10px
    }
    
    tr{
    line-height:8px;
    min-height:8px;
    height:8px;
    
    }
    tr th{
    line-height:8px;
    min-height:8px;
    height:8px;
    
    }
    
    .search:focus {
        width: 100%;
    }
.search {
 width: 150px;
 transition: width 0.4s ease-in-out;
 margin-bottom:0px
  }
    
</style>

</html>
<script>
$(document).ready(function () {

    $("#search").on("keyup", function(){
        var value = $(this).val().toLowerCase();
        $("#records tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        })
    })
    
    $('.delete').click(function(event){ 
        event.preventDefault();
        var record_no=$(this).attr('id');
        
        if(confirm('Are you sure you want to remove this class?'))
        {
          $.ajax({url:'Delete/delete_class.php',
          method:'post',
          data:{record_no},
          
            success:function(data)
            {
                alert(data);
                $('.container').load('View/review_classes.php');
            }
            
          })
          $(this).parents('tr').remove(); 
        }
    })

    $('.action').click(function(){
        var id = $(this).attr('id')
        $.ajax({
            url:'Requests/req_manage_class.php',
            method:'post',
            data:{id:id},
            success:function(data)
            {
                if(data == 'lock_success'){
                    toastr.info("Deactivated successfully");
                    $("#container").load("View/review_classes.php");
                }
                else if(data == 'unlock_success'){
                    toastr.success("Activated successfully");
                    $("#container").load("View/review_classes.php");
                }
                else{
                    toastr.error(data)
                }
            }
        })
    })
});
</script>