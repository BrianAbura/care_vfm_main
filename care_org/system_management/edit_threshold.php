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
<li class="active text-lg">Edit Procurement Threshold</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<?php
$threshold_id = filter_var(trim($threshold_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$threshold = DB::queryFirstRow('SELECT * from thresholds where id=%s', $threshold_id);
if(!isset($threshold))
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
	$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $threshold['proc_category']);
	$method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $threshold['proc_method']);
?>

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
		<form id="demo-bv-bsc-tabs" action="edit_threshold" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token" value="edit_threshold">
		<input type="hidden" name="threshold_id" value="<?php echo $threshold_id; ?>">
				<hr>
				<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Procurement Category</label>
						<input type="text" class="form-control" value="<?php echo $category['name']; ?>" disabled>
						
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Procurement Method</label>
						<input type="text" class="form-control" value="<?php echo $method['method_name']; ?>" disabled>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Minimum Amount (<?php echo $threshold['currency'];?>)</label>
						<input type="text" class="form-control comma_value" name="min_amount" value="<?php echo number_format($threshold['min_amount']); ?>" autocomplete="off">
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Maximum Amount (<?php echo $threshold['currency'];?>)</label>
						<input type="text" class="form-control comma_value" name="max_amount" value="<?php echo number_format($threshold['max_amount']); ?>" autocomplete="off">
					</div>
				</div>
				</div>
			<br/>
			<div class="tab-footer clearfix">
				<div class="col-lg-7 col-lg-offset-4">
					<button type="submit" class="btn btn-success">Update Threshold Details</button>
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
	min_amount: {
		validators: {
			notEmpty: {
				message: 'The minimum amount is required'
			}
		}
	},
	max_amount: {
		validators: {
			notEmpty: {
				message: 'The maximum amount is required'
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
