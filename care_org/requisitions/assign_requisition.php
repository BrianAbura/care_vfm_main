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



<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<ol class="breadcrumb">
<li></li>
<li class="active text-lg">Assign Requisition Details</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<?php 
$requisition_id = filter_var(trim($requisition_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $requisition_id);
if(!isset($requisition))
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
					The Record your are trying to access does not exist. Click <a href="../requisitions" class="alert-link">Here.</a> to go back to the list of requisitions.
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
} //End User Query || Null records
else{
    $assignment = DB::queryFirstRow('SELECT * from requisition_assign where requisition_id=%s and status=%s order by id desc', $requisition_id, 'Active');
    $user = 'NONE';
    if(isset($assignment)){
        $query = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $assignment['user_id']);
        $user = $query['first_name'].' '.$query['last_name'].' ('.$query['acc_status'].')';
    }
?>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel">
			<div class="panel-body">
				<table class="table table-bordered table-striped pad-ver mar-no">
				<h4 style="color:teal;">Assign/Re-Assign Requisition to Operations User to handle</h4><br/>
				<tbody>
					<tr>
						<td colspan="1" class="text-info text-bold">Requisition Number</td>
						<td colspan="3"><?php echo $requisition['requisition_number'];?></td>

						<td class="text-info text-bold">Status</td>
						<td class="text-lg"><span class="label label-success">Approved</span></td>
					</tr>
					<tr>
						<td class="text-info text-bold">Requisition Description</td>
						<td colspan="3"><?php echo $requisition['requisition_name'];?></td>
					</tr>
					<tr>
						<td class="text-info text-bold">Currently Assigned User</td>
						<td colspan="3"><?php echo $user;?></td>
					</tr>
				</tbody>
				</table>
				<hr/>

<form id="demo-bv-bsc-tabs" class="panel-body form-horizontal form-padding" action="assignments" method="POST" enctype="multipart/form-data">
<div class="form-group">
            <input type="hidden" class="form-control" name="requisition_id" value="<?php echo $requisition_id;?>">
                <label class="col-md-3 control-label" for="demo-text-input">Assign to User</label>
                <div class="col-md-3">
                <select class="selectpicker form-control" name="user_id" data-live-search="true">
                    <option></option>
                    <?php 
                    $users = DB::query('SELECT * from org_users where role_id=%s OR role_id=%s AND acc_status=%s order by first_name', 3, 4, 'Active');
                    foreach($users as $user){
                    ?>
                    <option value="<?php echo $user['user_id'];?>"><?php echo $user['first_name'].' '.$user['last_name'];?></option>
                    <?php } ?>
                </select>
                </div>
				<div class="col-sm-3">
					<div class="form-group">
						<textarea placeholder="Comments/Instructions" rows="3" class="form-control" name="assign_comments"></textarea>
					</div>
				</div>
            </div>

            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button class="btn btn-mint" id="SubmitForm" type="submit">Confirm</button>
                    </div>
                </div>
            </div>
			</form>
			<hr/>
			<br/> 
			<div class="table-responsive">
				<table class="table table-striped table-bordered" cellspacing="0">
                    <caption>Requisition Assignment History</caption>
						<thead>
							<tr>
								<th>#</th>
								<th>User</th>
								<th>Comments/Instructions</th>
								<th>Date Assigned</th>
								<th>Date Modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$cnt = 1;
							$all_assignments = DB::query('SELECT * from requisition_assign where requisition_id=%s order by id desc',$requisition_id);
                            foreach($all_assignments as $all_assignment)
							{
                                $assigned_user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $all_assignment['user_id']);
                                $date_modified = "";
                                if($all_assignment['status'] != 'Active'){
                                    $date_modified = date_format(date_create($all_assignment['date_modified']), 'd/m/Y H:i');
                                }
							?>
								<tr>
								<td><?php echo $cnt; ?></td>
								<td><?php echo $assigned_user['first_name'].' '.$assigned_user['last_name'];?></td>
								<td><?php echo $all_assignment['comments'];?></td>
								<td><?php echo date_format(date_create($all_assignment['date_assigned']), 'd/m/Y H:i'); ?></td>
                                <td><?php echo $date_modified; ?></td>
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

<?php 
} //End User Query || Record found
?>

</div>
<!--===================================================-->
<!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->




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
		user_id: { validators: { notEmpty: { message: 'The Assigned user is required' } } },
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
