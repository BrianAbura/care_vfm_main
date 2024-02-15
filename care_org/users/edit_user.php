<?php 
$BASEPATH = dirname(__DIR__);
$DIR = __DIR__;
require_once($BASEPATH.'/validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php 
/*** Include the Global Headers Scripts */
include $DIR."/headers.php"; 
?>

</head>
<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg navbar-fixed">

<?php 
/*** Include the Global Headers Scripts */
include $DIR."/navbar.php";
?>

<div class="boxed">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<ol class="breadcrumb">
<li></li>
<li class="active text-lg">Edit User Details</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<?php 
$user_id = filter_var(trim($user_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $user_id);
if(!isset($user))
{ //Display the notice below if null
?>
<div class="row"> 
	<div class="col-sm-7">
		<div class="panel">
			<div class="panel-body">
				<br/>
				<div class="alert alert-danger">
					<button class="close" data-dismiss="alert"></button>
					<strong>Warning!</strong> <br/><br/>
					The Record your are trying to access does not exist. Click <a href="../users" class="alert-link">Here.</a> to go back to the list of users.
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
} //End User Query || Null records
else{
?>

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
		<form id="demo-bv-bsc-tabs" action="edit_user" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token" value="edit_user">
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
				<hr>
				<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Firstname</label>
						<input type="text" class="form-control" name="firstname" value="<?php echo $user['first_name']; ?>" autocomplete="off">
						
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Lastname</label>
						<input type="text" class="form-control" name="lastname" value="<?php echo $user['last_name']; ?>" autocomplete="off">
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Email Address</label>
						<input type="email" class="form-control" name="email" value="<?php echo $user['email_address']; ?>" autocomplete="off">
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Department</label>
						<select class="selectpicker form-control" name="department">
							<?php 
							//Users department
							$udept = DB::queryFirstRow('SELECT * from departments where dept_id=%s', $user['department_id']);
							?>	
							<option value="<?php echo $udept['dept_id'];?>"><?php echo $udept['name'];?></option>
							
							<?php
							//All Departments 
							$depts = DB::query('SELECT * from departments order by name');
							foreach($depts as $dept){
								if($dept['dept_id'] == $user['department_id']){
									continue;
								}
							?>
							<option value="<?php echo $dept['dept_id'];?>"><?php echo $dept['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				</div>
				<br/>
				<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Role</label>
						<select class="selectpicker form-control" name="role">
							<?php 
							//Users Roles
							$urole = DB::queryFirstRow('SELECT * from org_roles where id=%s', $user['role_id']);
							?>	
							<option value="<?php echo $urole['id'];?>"><?php echo $urole['role_desc'];?></option>

							<?php 
							$roles = DB::query('SELECT * from org_roles order by role_desc');
							foreach($roles as $role){
								//All other Roles: Evaluation Roles ommited too
								if($role['role'] == 'ECS' || $role['role'] == "EC" || $role['id'] == $user['role_id']){
									continue;
								}
							?>
							<option value="<?php echo $role['id'];?>"><?php echo $role['role_desc'];?></option>

							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Status</label>
						<select class="selectpicker form-control" name="acc_status">	
							<option value="<?php echo $user['acc_status'];?>" ><?php echo $user['acc_status'];?></option>
							<?php 
							if($user['acc_status'] == "Active"){
							?>
							<option value="Inactive">De-Activate</option>
							<?php 
							}
							else{
							?>
							<option value="Active">Activate</option>
							<?php } ?>
						</select>
					</div>
				</div>
				</div>

			<div class="tab-footer clearfix">
				<div class="col-lg-7 col-lg-offset-4">
					<button type="submit" class="btn btn-success">Update User Details</button>
				</div>
			</div>
	</form>
		</div>
		</div>
	</div>
</div>
<?php 
} //End User Query || Record found
?>

</div>
<!--===================================================-->

<!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
</div>



<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php"; 
?>

<script>
//Validate Submission Form
$(document).on('nifty.ready', function() {

// FORM VALIDATION FEEDBACK ICONS
// =================================================================
var faIcon = {
	valid: 'fa fa-check-circle fa-lg text-success',
	invalid: 'fa fa-times-circle fa-lg',
	validating: 'fa fa-refresh'
}



// FORM VALIDATION ON TABS
// =================================================================
$('#demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,
	fields: {
	firstname: {
		validators: {
			notEmpty: {
				message: 'The first name is required'
			}
		}
	},
	lastname: {
		validators: {
			notEmpty: {
				message: 'The last name is required'
			}
		}
	},
	email: {
		validators: {
			notEmpty: {
				message: 'The email address is required'
			}
		}
	},
	department: {
		validators: {
			notEmpty: {
				message: 'The department is required'
			}
		}
	},
	role: {
		validators: {
			notEmpty: {
				message: 'The user role is required'
			}
		}
	}
	}
}).on('status.field.bv', function(e, data) {
	var $form     = $(e.target),
	validator = data.bv,
	$tabPane  = data.element.parents('.tab-pane'),
	tabId     = $tabPane.attr('id');

	if (tabId) {
	var $icon = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent().find('i');

	// Add custom class to tab containing the field
	if (data.status == validator.STATUS_INVALID) {
		$icon.removeClass(faIcon.valid).addClass(faIcon.invalid);
	} else if (data.status == validator.STATUS_VALID) {
		var isValidTab = validator.isValidContainer($tabPane);
		$icon.removeClass(faIcon.valid).addClass(isValidTab ? faIcon.valid : faIcon.invalid);
	}
	}
});

});
</script>
</body>

</html>
