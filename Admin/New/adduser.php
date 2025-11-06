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
                <div class="card-header">
                    <h5 class="card-title text-center">Add User</h5>
                </div>
                <div class="card-body">
                    <div class="row"  style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Usertype:</h6> 
                        </div>
                        <div class="col-md-7">
                            <select class="form-control form-control-sm" id="usertype" name="usertype">
                                <option value="" selected="">--Select usertype--</option>
                                <option value="Department Head">Department Head</option>
                                <option value="Trainer">Trainer</option>
                            </select>
                        </div>
                    </div>
                    <div class="row"  style="margin-bottom: 10px;">
                        <div class="col-md-5" id="label"></div>
                        <div class="col-md-7" id="name"></div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>First Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="fname" id="fname" readonly>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Last Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="lname" id="lname" readonly>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Phone Number:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="number" class="form-control form-control-sm" name="phone" id="phone" readonly>
                        </div>
                    </div>
                    <div class="row"  style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>User Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="username">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Create Password:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="password" class="form-control form-control-sm" name="npassword">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5">
                            <h6>Confirm Password:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="password" class="form-control form-control-sm" name="cpassword">
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <button type="submit" name="refresh" class="btn btn-success" id="refresh"><i class="fa fa-refresh"></i> &nbsp Refresh</button>
                    <button type="submit" name="submit" class="btn btn-primary float-right" id="submitbtn">Submit</button>
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

        $('#usertype').change(function(event){
            event.preventDefault();
            var usertype = $('#usertype').children("option:selected").val();
            if(usertype == 'Trainer'){
                $.ajax({
                    url: 'Requests/req_trainers.php',
                    method: 'post',
                    data: {request:"Trainers",usertype:usertype},
                    dataType: 'json',
                    success:function(response)
                    {
                        $('#label').html('<h6 >Trainer Name:</h6>');
                        $('#name').html('<select class="form-control form-control-sm" id="selected_trainer" name="selected_trainer"> <option value="" selected="">--Select trainer--</option> <option value="Department Head">Department Head</option> <option value="Trainer">Trainer</option></select>');
                        var len = response.length;
                        $("#selected_trainer").empty();
                        if(len<1){
                            $("#selected_trainer").append("<option value=''>No trainer found</option>");
                        }
                        else{
                            $("#selected_trainer").append("<option value=''>--Select trainer--</option>");
                            for( var i = 0; i < len; i++){
                                var trainer_id = response[i]['trainer_id'];
                                var first_name = response[i]['first_name'];
                                var last_name = response[i]['last_name'];
                                var tr_phone = response[i]['phone_no'];
                                
                                var trainer_name = first_name+" "+last_name;
                                $("#selected_trainer").append("<option value='"+trainer_id+"'>"+trainer_name+"</option>");
                            }
                        }
                    }
                });
            }
            else if (usertype == 'Department Head') {
                $.ajax({
                    url: 'Requests/req_trainers.php',
                    method: 'post',
                    data: {request:"hods",usertype:usertype},
                    dataType: 'json',
                    success:function(response)
                    {
                        $('#label').html('<h6 >H.O.D Name:</h6>');
                        $('#name').html('<select class="form-control form-control-sm" id="selected_trainer" name="selected_trainer"> <option value="" selected="">--Select trainer--</option> <option value="Department Head">Department Head</option> <option value="Trainer">Trainer</option></select>');
                        var len = response.length;
                        $("#selected_trainer").empty();
                        if(len<1){
                            $("#selected_trainer").append("<option value=''>No H.O.Ds found</option>");
                        }
                        else{
                            $("#selected_trainer").append("<option value=''>--Select H.O.D--</option>");
                            for( var i = 0; i < len; i++){
                                var hod_id = response[i]['hod_id'];
                                var hod_first_name = response[i]['hod_first_name'];
                                var hod_last_name = response[i]['hod_last_name'];
                                var hod_phone = response[i]['hod_phone'];
                                $("#fname").val() = hod_first_name;
                                $("#lname").val() = hod_last_name;
                                $("#phone").val() = hod_phone;
                                var hod_name = hod_first_name+" "+hod_last_name;
                                $("#selected_trainer").append("<option value='"+hod_id+"'>"+hod_name+"</option>");
                            }
                        }
                        $("#fname").val("jmkn");
                        $("#lname").val("last name here");
                        $("#phone").val("phone number here");
                    }
                });


            }

            else{}
        })
        
        $("#selected_trainer").change(function(event){
            alert("Ok");           
        })
        $('#refresh').click(function(event){
            event.preventDefault();
            $('#container').load('adduser.php');
        });

        $('#submitbtn').click(function(event){
            event.preventDefault();
            
            var usertype = $('#usertype').children("option:selected").val();
            var user_id = $('#selected_trainer').children("option:selected").val();
            var first_name = $('input[name="fname"]').val();
            var last_name = $('input[name="lname"]').val();
            var phone = $('input[name="phone"]').val();
            var username = $('input[name="username"]').val();
            var npassword = $('input[name="npassword"]').val();
            var cpassword = $('input[name="cpassword"]').val();
            
            if(usertype == ""){toastr.error("Please specify the usertype");}
            else if(user_id == ""){toastr.error("Please select a user");}
            else if(first_name == ""){toastr.error("First name can't be retrieved");}
            else if(last_name == ""){toastr.error("Last name can't be retrieved");}
            else if(phone == ""){toastr.error("Phone number can't be retrieved");}
            else if(username == ""){toastr.error("Please enter a preffered username");}
            else if(npassword == ""){toastr.error("Please create a password");}
            else if(cpassword == ""){toastr.error("Please confirm your password");}
            else if(cpassword != npassword){toastr.error("Password Mismatch");}
            else{
                $.ajax({
                    url: 'insertdata/insertuser.php',
                    method: 'post',
                    data: {usertype:usertype, user_id:user_id, first_name:first_name, last_name:last_name, phone_number:phone, username:username, npassword:npassword, cpassword:cpassword},
                    success:function(data)
                    {
                        alert(data);
                    }
                });
            }
        });
    });
</script>