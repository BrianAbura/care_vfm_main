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
<li class="active text-lg">Procurement Thresholds</li>
</ol>
</div>
<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<!--Data Table-->
			<!--===================================================-->
			<div class="panel-body">
			<div class="tab-base">
					
					<!--Nav tabs-->
					<ul class="nav nav-tabs">
						<li class="active"> <a data-toggle="tab" href="#demo-ico-lft-tab-1">USD<span class="badge badge-mint"><i class="fa fa-dollar"></i></span></a></li>
					</ul>
		
					<!--USD Threshold-->
					<div class="tab-content">
						<div id="demo-ico-lft-tab-1" class="tab-pane active  in">
						<div class="table-responsive">
							<br/>
						<table class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Category</th>
								<th>Solicitation Method</th>
								<th>Minimum Amount (USD)</th>
								<th>Maximum Amount (USD)</th>
								<th>Date Modified</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$cnt = 1;
							$thresholds = DB::query('SELECT * from thresholds where currency=%s order by id desc', 'USD');
							foreach($thresholds as $threshold)
							{
								$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $threshold['proc_category']);
								$method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $threshold['proc_method']);

								if($category['name'] == "Goods"){
									echo '<tr class="success">';
								}
								elseif($category['name'] == "Consultancy"){
									echo '<tr class="warning">';
								}
								else{ //Works
									echo '<tr class="danger">';
								}
							?>
								<td><?php echo $cnt; ?></td>
								<td><?php echo $category['name']; ?></td>
								<td><?php echo $method['method_name']; ?></td>
								<td><?php echo number_format($threshold['min_amount']); ?></td>
								<td><?php echo number_format($threshold['max_amount']); ?></td>
								<td><?php echo date_format(date_create($threshold['date_modified']), 'd-M-Y H:i'); ?></td>
								<td> 
								<a href="<?php echo $threshold['id'];?>"><button class="btn btn-mint btn-icon" title="Edit/Update"><i class="demo-psi-pen-5 icon-sm"></i></button></a>
								</td>
							</tr>
							<?php 
								$cnt ++;
								} // EndForEach?>

						</tbody>
					</table>
				</div>
						</div>
				</div>
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
include $DIR."/footers.php"; 
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
