<html>
<head>
    <title></title>
    <script src="<?= base_url()?>asset/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url()?>asset/bootstrap.min.css" />
    <script src="<?= base_url()?>asset/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <br />
        <h3 align="center"></h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-4">
                        <?php if(!empty($date)){
                        ?>
                            <label>Select date</label>
                            <select name="date_range" id="date_range">
                                <option value="0">
                                    Plese select date
                                </option>
                                <?php foreach($date as $row) {?>
                                    <option value="<?= $row->date_added ?>"><?= $row->date_added ?></option>
                                <?php } ?>
                            </select>
                       <?php } ?>
                        
                    </div>
                    <div class="col-md-4">
                        <?php if(!empty($role)){ ?>
                            <label>Select Role</label>
                            <select name="role" id="role">
                                <option value="0">
                                    Plese select role
                                </option>
                                <?php foreach($role as $row) {?>
                                    <option value="<?= $row->role_type ?>"><?= $row->role_type ?></option>
                                <?php } ?>
                            </select>
                       <?php } ?>
                        
                    </div>
                    <div class="col-md-4" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-xs">Add New User</button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <span id="success_message"></span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role type</th>
                            <th>Date added</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    <label>Enter First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" />
                    <span id="first_name_error" class="text-danger"></span>
                    <br />
                    <label>Enter Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" />
                    <span id="last_name_error" class="text-danger"></span>
                    <br />
                    <label>Enter Email</label>
                    <input type="text" name="email" id="email" class="form-control" />
                    <span id="email_error" class="text-danger"></span>
                    <br />
                    <label>Enter phone_number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="10" onkeypress="return isNumberKey(event)"/>
                    <span id="phone_number_error" class="text-danger"></span>
                    <br />
                    <label>Enter role_type</label>
                    <input type="text" name="role_type" id="role_type" class="form-control" />
                    <span id="role_type_error" class="text-danger"></span>
                    <br />
                    <input type="hidden" name="old_email" id="old_email" class="form-control" />
                    <input type="hidden" name="old_phone_number" id="old_phone_number" class="form-control" />
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="data_action" id="data_action" value="Insert" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript" >    
var date_data;
var role;
$(document).ready(function(){
    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{data_action:'fetch_all',date_added:date_data,role_type:role},
            success:function(data)
            {
                $('tbody').html(data);
            }
        });
    }

    
   fetch_data(data_action,date_data,role);

    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Add User");
        $('#action').val('Add');
        $('#data_action').val("Insert");
        $('#userModal').modal('show');
    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url() . 'test_api/action' ?>",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                {
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    fetch_data();
                    if($('#data_action').val() == "Insert")
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Inserted</div>');
                        location.reload();
                    }
                }

                if(data.error)
                {
                    $('#first_name_error').html(data.first_name_error);
                    $('#last_name_error').html(data.last_name_error);
                    $('#email_error').html(data.email_error);
                    $('#phone_number_error').html(data.phone_number_error);
                    $('#role_type_error').html(data.role_type_error);
                }
            }
        })
    });

    $(document).on('click', '.edit', function(){
        var user_id = $(this).attr('id');
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{user_id:user_id, data_action:'fetch_single'},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#phone_number').val(data.phone_number);
                $('#old_email').val(data.email);
                $('#old_phone_number').val(data.phone_number);
                $('#role_type').val(data.role_type);
                $('.modal-title').text('Edit User');
                $('#user_id').val(user_id);
                $('#action').val('Edit');
                $('#data_action').val('Edit');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var user_id = $(this).attr('id');
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"<?php echo base_url(); ?>test_api/action",
                method:"POST",
                data:{user_id:user_id, data_action:'Delete'},
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Deleted</div>');
                        fetch_data();
                    }
                }
            })
        }
    });
    
});

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

$(document).on('change', '#date_range', function(){
    date_data = $('#date_range').val();
    if(date_data == 0){
        date_data = '';
    }
    $('tbody').html('');
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{data_action:'fetch_all',date_added:date_data,role_type:role},
            success:function(data)
            {
                $('tbody').html(data);
            }
        });
    }
});

$(document).on('change', '#role', function(){
   role = $('#role').val();
   if(role == 0){
        role = '';
    }
    $('tbody').html('');
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{data_action:'fetch_all',date_added:date_data,role_type:role},
            success:function(data)
            {
                $('tbody').html(data);
            }
        });
    }
});
</script>