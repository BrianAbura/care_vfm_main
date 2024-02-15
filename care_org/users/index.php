<?php 
require_once('../validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php 
/*** Include the Global Headers Scripts */
include "headers.php"; 
?>

</head>
<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg navbar-fixed">

<?php 
/*** Include the Global Headers Scripts */
include "navbar.php";
?>


<div class="boxed">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
<div class="col-xs-12">
<div class="panel">
<!--Data Table-->
<!--===================================================-->
<div class="panel-body">
<div class="pad-btm form-inline">
	<div class="row">
		<div class="col-sm-6 table-toolbar-left">
		<?php 
		if(restrict_it($current_user['role_id'])){
		?>
		<a href="add_user" style="color:#fff"><button class="btn btn-primary"><i class="demo-pli-add icon-fw"></i>Add New User</button></a>
		<?php } ?>
			<button class="btn btn-default" title="Print"><i class="demo-pli-printer icon-lg"></i></button>
			<div class="btn-group dropdown">
					<button class="btn btn-default btn-active-primary dropdown-toggle" data-toggle="dropdown" title="Download">
					<i class="fa fa-download"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<li><a href="#">PDF</a></li>
						<li><a href="#">Excel</a></li>
						<li><a href="#">CSV</a></li>
					</ul>
				</div>
		</div>
	</div>
</div>
<div class="table-responsive">
<table id="demo-dt-addrow" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Fullname</th>
				<th>Email Address</th>
				<th>Role</th>
				<th>Department</th>
				<th>Status</th>
				<th>Last Login</th>
				<th>Date Modified</th>
				<?php 
				if(restrict_it($current_user['role_id'])){
				?>
				<th>Action</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php 
			$cnt = 1;
			$curDate = date('Y-m-d H:i:s');
			$users = DB::query('SELECT * from org_users');
			foreach($users as $user)
			{
				$role = DB::queryFirstRow('SELECT role_desc from org_roles where id=%s', $user['role_id']);
				$verification = DB::queryFirstRow('SELECT * from verifications where email=%s and link_type=%s order by id desc limit 1', $user['email_address'], 'registration');
			?>
				<tr>
				<td><?php echo $cnt; ?></td>
				<td class="text-bold" style="color:midnightblue"><?php echo $user['first_name']." ".$user['last_name']; ?></td>
				<td><?php echo $user['email_address']; ?></td>
				<td><?php echo $role['role_desc']; ?></td>
				<td><?php echo get_deparment($user['department_id']); ?></td>
				<?php 
				if($user['acc_status'] == 'Active'){
					$cls = 'mint';
				}else{
					$cls = 'danger';
				}
				?>
				<td class="text-<?php echo $cls;?>"><?php echo $user['acc_status'];?></td>

				<td><?php echo date_format(date_create($user['last_login']), 'd-M-Y h:i'); ?></td>
				<td><?php echo date_format(date_create($user['date_modified']), 'd-M-Y h:i'); ?></td>
				<?php 
				if(restrict_it($current_user['role_id'])){
				?>
				<td> 
				<a href="<?php echo $user['user_id'];?>"><button class="btn btn-mint btn-icon" title="Edit/Update"><i class="demo-psi-pen-5 icon-sm"></i></button></a>
				<?php
				if(!$verification || (($curDate > $verification['expiry_date']) && ($verification['status'] != "VERIFIED"))){
				?>
				<form action="add_user" method="POST" enctype="multipart/form-data" style="display: inline-block;">
				<input type="hidden" name="token" value="resend_verification">
				<input type="hidden" name="user_id" value="<?php echo $user['user_id'];?>">
				<button class="btn btn-purple btn-icon" title="Resend Verification Link"><i class="fa fa-refresh icon-sm"></i></button>
				</form>
				<?php
				}
				?>
				</td>
				<?php } ?>

			</tr>
			<?php 
				$cnt ++;
				} // EndForEach?>

		</tbody>
	</table>
</div>
</div>
<!--===================================================-->
<!--End Data Table-->

</div>
</div>
</div>
</div>
<!--===================================================-->

<!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
</div>



<?php 
/*** Include the Global Footer and Java Scripts */
include "footers.php"; 
?>

<script>
//Add New Department
$('#add-new-department-btn').on('click', function(){
bootbox.dialog({
title: "Create New Department",
message:'<div class="row"> ' + '<div class="col-md-12"> ' +
'<div class="form-group"> <form>' +
'<label class="col-md-4 control-label" for="name">Department Name</label> ' +
'<div class="col-md-7"> ' +
'<input id="department_name" name="department_name" required type="text" placeholder="Type Department Name...." class="form-control input-md"> ' +
'</div> </form></div>',
buttons: {
success: {
label: "Save",
className: "btn-success",
callback: function() {
var name = $('#department_name').val();
//Notifications
$.ajax({
	type : 'post',
	url : 'Department.php',
	data :  'deptName='+ name, 
	success : function(data){
		var result = JSON.parse(data);
		if (result.Status == "Success") {
			$.niftyNoty({
				type: 'success',
				icon : 'pli-like-2 icon-2x',
				message : result.Message,
				container : 'floating',
				timer : 7000
			});
		}else{
			$.niftyNoty({
				type: 'danger',
				icon : 'pli-cross icon-2x',
				message : result.Message,
				container : 'floating',
				timer : 7000
			});
		};
		setTimeout(function(){ window.location.reload(1); }, 5000);
	}
});
}
}
}
});
});

//Edit_Update Department
$('.edit-department').on('click', function(){
var department_id_get = $(this).data("id");

$.ajax({
type : 'post',
url : 'Department.php',
data :  'department_id_get='+ department_id_get, 
success : function(data){
var department_name = data;
bootbox.dialog({
title: "Edit Department",
message:'<div class="row"> ' + '<div class="col-md-12"> ' +
'<div class="form-group"> <form>' +
'<label class="col-md-4 control-label" for="name">Department Name</label> ' +
'<div class="col-md-7"> ' +
'<input id="department_id_update" type="hidden" value="'+department_id_get+'" class="form-control input-md"> ' +
'<input id="department_name_update" type="text" value="'+department_name+'" class="form-control input-md"> ' +
'</div> </form></div>',
buttons: {
success: {
label: "Save",
className: "btn-success",
callback: function() {
	var department_id_update = $('#department_id_update').val();
	var department_name_update = $('#department_name_update').val();
	//Notifications
	$.ajax({
		type : 'post',
		url : 'Department.php',
		data :  {'department_id_update' : department_id_update, 'department_name_update' : department_name_update},
		success : function(data){
		var result = JSON.parse(data);
		if (result.Status == "Success") {
			$.niftyNoty({
				type: 'success',
				icon : 'pli-like-2 icon-2x',
				message : result.Message,
				container : 'floating',
				timer : 7000
			});
		}else{
			$.niftyNoty({
				type: 'danger',
				icon : 'pli-cross icon-2x',
				message : result.Message,
				container : 'floating',
			});
		};
		setTimeout(function(){ window.location.reload(1); }, 7000);
	}
	});
}
}
}
});
}
});
});

//Confirm Delete
$('.delete-department').on('click', function(){
var department_id = $(this).data("id");
bootbox.dialog({
//title: "Create New Department",
message : "<br/><h4 class='text-semibold text-main'>Are you sure you want to delete this department? </h4>",
buttons: {
success: {
label: "Confirm",
className: "btn-primary",
callback : function(result) {
$.ajax({
	type : 'post',
	url : 'Department.php',
	data :  'deptId_del='+ department_id, 
	success : function(data){
		var result = JSON.parse(data);
		if (result.Status == "Success") {
			$.niftyNoty({
				type: 'success',
				icon : 'pli-like-2 icon-2x',
				message : result.Message,
				container : 'floating',
				timer : 7000
			});
		}else{
			$.niftyNoty({
				type: 'danger',
				icon : 'pli-cross icon-2x',
				message : result.Message,
				container : 'floating',
			});
		};
		setTimeout(function(){ window.location.reload(1); }, 7000);
	}
});
}
}
}
});
});
</script>
</body>
</html>
