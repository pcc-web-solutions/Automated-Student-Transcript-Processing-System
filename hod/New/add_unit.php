<?php
    session_start();
    $dept = $_SESSION['dept'];
    include("../../Database/config.php");
    $sql="SELECT * from courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept' ORDER by course_name asc";
    $results=mysqli_query($conn, $sql)or die("Wrong query expression");
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="#" id="form" class="form">
            <div class="card card-info card-outline">
                    <h5 class="card-header">Add Unit</h5>
                <div class="card-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Course Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control form-control-sm" id=course name=course>
                            <option selected style='display:none'>Select course</option>
                            <?php
                                while($row=mysqli_fetch_assoc($results)) { ?>
                                <option value="<?php echo $row['code'];?>"><?php echo $row['course_name'];?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Unit Code:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type=text class="form-control form-control-sm" placeholder="Example 1920/101" id=unit_code name='unit_code'>
                        </div>
                    </div>
					<div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Unit Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type=text class="form-control form-control-sm" placeholder="e.g Communication Skills" id=unit_name name ='unit_name'>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Hours per lesson:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type=text class="form-control form-control-sm" placeholder="e.g 2" id=hourly_lessons name ='hourly_lessons'>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Hours per week:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type=text class="form-control form-control-sm" placeholder="e.g 4" id=weekly_hours name ='weekly_hours'>
                        </div>
                    </div>
                    
                    <br>
                </div>
                <div class="card-footer ">
                    <button type="button" class="btn btn-info float-right" id=button style=width:50%>OK</button>
                </div>
            </div>  
        </form>
    </body>
    <style>
        button:hover{
            cursor: pointer;
        }
        
        .row{
            margin-top: 10px;
        }
        h6{
            font-size: 13px;
            font-weight: bold;
            padding-top: 5px;
        }
    </style>
</html>
<script>
    $(document).ready(function(){
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-center',
          showConfirmButton: false,
          timer: 3000
        });

        $('#button').click(function(){
            var unit_name = $('#unit_name').val();
            if ( $('select[name=course]')[0].selectedIndex === 0 )
            {
                toastr.error('Please select a course')
            }
            else if( $('#unit_code').val()=='')
            {
                toastr.error('Unit code cannot be blank')
            }  
            else if( $('#unit_name').val()=='')
            {
                toastr.error('Unit name cannot be blank')
            }
            else if( $('#hourly_lessons').val()=='')
            {
                toastr.error('Please specify hourly lessons')
            }    
            else if( $('#weekly_hours').val()=='')
            {
                toastr.error('Please specify weekly lessons')
            }
            else{
                $.ajax({
                    url:'Insertdata/insert_unit.php',
                    method:'post',
                    data:$('#form').serialize(),
                    
                    success:function(data)
                    {
                        if(data == 'Success'){
                            toastr.info('Unit added successfully')
                            // $('.form').trigger('reset'); 
                        }
                        else{
                            toastr.error(data)
                        }
                    }
                })
            }
        });

    });
</script>