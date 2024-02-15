<?php 
require_once('../validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>Departments | Care Uganda</title>

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
							<button class="btn btn-primary" id="add-new-department-btn"><i class="demo-pli-add icon-fw"></i>Add New</button>
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
								<th>Department ID</th>
								<th>Department Name</th>
								<?php 
							if(restrict_it($current_user['role_id'])){
							?>
								<th>Action</th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
							<?php 
							$cnt = 1;
							$departments = DB::query('SELECT * from departments order by id');
							foreach($departments as $department)
							{
							?>
								<tr>
								<td><?php echo $cnt; ?></td>
								<td class="text-bold" style="color:midnightblue"><?php echo $department['dept_id']; ?></td>
								<td class="text-bold" style="color:midnightblue"><?php echo $department['name']; ?></td>
								<?php 
							if(restrict_it($current_user['role_id'])){
							?>
								<td> 
									<button class="btn btn-mint btn-icon edit-department" data-id="<?php echo $department['id']; ?>" title="Edit"><i class="demo-psi-pen-5 icon-sm"></i></button>
									<!-- <button class="btn btn-danger btn-icon btn-circle delete-department" data-id="<?php echo $department['id']; ?>" title="Delete"><i class="fa fa-trash icon-lg"></i></button> -->
								</td>
								<?php }?>
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
	title: "Create a New Department",
	message:'<div class="row"> ' + '<div class="col-md-12"> ' +
			'<div class="form-group"> <form>' +
			'<div class="col-md-6"> ' +
			'<label class="control-label" for="name">Department id</label> ' +
			'<input id="department_id" name="department_id" type="text" class="form-control input-lg"> ' +
			'</div>'+ 
			'<div class="col-md-6"> ' +
			'<label class="control-label" for="name">Department Name</label> ' +
			'<input id="department_name" name="department_name" type="text" placeholder="Type Department Name...." class="form-control input-lg"> ' +
			'</div>'+ 
			'</form></div>',
	buttons: {
		success: {
			label: "Save",
			className: "btn-success",
			callback: function() {
				var deptId = $('#department_id').val();
				var deptName = $('#department_name').val();
				//Notifications
				$.ajax({
					type : 'post',
					url : 'Department.php',
					data :  {'deptId' : deptId, 'deptName' : deptName},
					success : function(data){
						var result = JSON.parse(data);
						if (result.Status == "Success") {
							$.niftyNoty({
								type: 'success',
								icon : 'pli-like-2 icon-2x',
								message : result.Message,
								container : 'floating',
								timer : 2000
							});
						}else{
							$.niftyNoty({
								type: 'danger',
								icon : 'pli-cross icon-2x',
								message : result.Message,
								container : 'floating',
								timer : 2000
							});
						};
						setTimeout(function(){ window.location.reload(1); }, 2000);
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
	dataType: 'json',
	success : function(data){
		var department_id = data[0];
		var department_name = data[1];
		
		bootbox.dialog({
		title: "Edit Department",
		message:'<div class="row"> ' + '<div class="col-md-12"> ' +
				'<div class="form-group"> <form>' +
				'<div class="col-md-6"> ' +
				'<label class="control-label" for="name">Department id</label> ' +
				'<input id="id_update" type="hidden" value="'+department_id_get+'"> ' +
				'<input id="department_id_update" type="text" value="'+department_id+'" class="form-control input-lg"> ' +
				'</div>'+ 
				'<div class="col-md-6"> ' +
				'<label class="control-label" for="name">Department Name</label> ' +
				'<input id="department_name_update" type="text" value="'+department_name+'" class="form-control input-lg"> ' +
				'</div>'+ 
				'</form></div>',
		buttons: {
			success: {
				label: "Save",
				className: "btn-success",
				callback: function() {
					var id_update = $('#id_update').val();
					var department_id_update = $('#department_id_update').val();
					var department_name_update = $('#department_name_update').val();
					//Notifications
					$.ajax({
						type : 'post',
						url : 'Department.php',
						data :  {'id_update' : id_update, 'department_id_update' : department_id_update, 'department_name_update' : department_name_update},
						success : function(data){
						var result = JSON.parse(data);
						if (result.Status == "Success") {
							$.niftyNoty({
								type: 'success',
								icon : 'pli-like-2 icon-2x',
								message : result.Message,
								container : 'floating',
								timer : 4000
							});
						}else{
							$.niftyNoty({
								type: 'danger',
								icon : 'pli-cross icon-2x',
								message : result.Message,
								container : 'floating',
							});
						};
						setTimeout(function(){ window.location.reload(1); }, 4000);
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
