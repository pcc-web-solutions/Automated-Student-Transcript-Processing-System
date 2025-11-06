<?php
include("../../Database/config.php");
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="#" id="form" class="form">
            <div class="card card-info card-outline">
                    <h5 class="card-header">Add Trainer</h5>
                <div class="card-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>First Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="tr_fname">
                        </div>
                    </div>
					<div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Last Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="tr_lname">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Phone Number:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="number" class="form-control form-control-sm" name="phone">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Department Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control form-control-sm" name=department>
                                <option value="">--Select--</option>
                                <?php 
                                    $sql = "SELECT * FROM departments";
                                    $run = mysqli_query($conn, $sql);
                                    while($result=mysqli_fetch_assoc($run)) {
                                        echo "<option value=".$result['department_code'].">".$result['department_name']."</option>";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="card-footer ">
                    <button type="submit" name="logoutbtn" class="btn btn-primary float-right" id="submitbtn">Submit</button>
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

        $('#submitbtn').click(function(event){
            event.preventDefault();
  
            $.ajax({
                url: 'Insertdata/inserttrainer.php',
                method: 'post',
                data: $('#form').serialize(),

                success:function(data)
                {
                    // toastr.error(data)
                    if(data == 'Success'){
                      toastr.success('Trainer registered successfully.')
                    }else{
                      toastr.error(data)
                    }
                }
            });
        });
    });
</script>