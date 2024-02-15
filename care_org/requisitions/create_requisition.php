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
<style>
	.control-label{
		font-weight:600;
	}
	.table>thead>tr>th {
    color:black;
	}
	.prices{
		border: none;
	}
	.item_total_price{
		border: none;
	}
</style>
</head>
<body>
<div id="container" class="effect aside-float aside-bright mainnav-sm">

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
<li class="active text-lg">New Requisition</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
		<form id="demo-bv-bsc-tabs" action="add_requisition" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token" value="create_requisition">
		<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin-bottom:20px">Requisition Details</h5>
				<!-- Row 1-->
				<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Department</label>
						<select class="selectpicker form-control" name="department_name" data-live-search="true" required>
							<option></option>
							<?php 
							$departments = DB::query('SELECT * from departments order by name');
							foreach($departments as $department){
							?>
							<option value="<?php echo $department['dept_id'];?>"><?php echo $department['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Requisition ID</label>
						<input type="text" class="form-control" name="requisition_number"  placeholder="Requisition ID" autocomplete="off" required>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Requisition Name</label>
						<textarea placeholder="e.g. Supply of materials ..." rows="2" class="form-control" name="requisition_name"></textarea>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Procurement Category</label>
						<select class="selectpicker form-control" data-live-search="true" name="requisition_category">
						<option></option>
							<?php 
							$categories = DB::query('SELECT * from procurement_categories order by name');
							foreach($categories as $category){
							?>
							<option value="<?php echo $category['id'];?>"><?php echo $category['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Currency</label>
						<select class="selectpicker form-control" data-live-search="true" name="requisition_currency">
						<option></option>
						<option value="USD">USD</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Requisition Due Date</label>
						<div id="demo-dp-component">
						<div class="input-group date">
							<input type="text" class="form-control" name="requisition_due_date">
							<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
						</div>
						<small class="text-muted">Auto close on select</small>
						</div>
					</div>
				</div>
				</div>
				<br/>

				<div class="row">
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Distrib</label>
						<input type="text" class="form-control" name="distrib"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Location</label>
						<input type="text" class="form-control" name="location"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">GL Unit</label>
						<input type="text" class="form-control" name="gl_unit"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Account</label>
						<input type="text" class="form-control" name="account"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Alt Acct</label>
						<input type="text" class="form-control" name="alt_account"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Fund</label>
						<input type="text" class="form-control" name="fund"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">PC Bus Unit</label>
						<input type="text" class="form-control" name="pc_bus_unit"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Project</label>
						<input type="text" class="form-control" name="project"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Activity</label>
						<input type="text" class="form-control" name="activity"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Source Type</label>
						<input type="text" class="form-control" name="source_type"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Affiliate</label>
						<input type="text" class="form-control" name="affiliate"  autocomplete="off" >
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Fund Affiliate</label>
						<input type="text" class="form-control" name="fund_affiliate"  autocomplete="off">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Project Affiliate</label>
						<input type="text" class="form-control" name="project_affiliate"  autocomplete="off">
					</div>
				</div>
				
				</div>

				<div class="row">
				<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin:20px 20px">Requisition Item/Services/Works Details</h5>
					<div class="table-respondssive">
					<table class="table table-bordered" style="background: #DEEFF2" id="requisition_item_table">
					<thead>
					<tr>
						<th>Description</th>
						<th>Category (Level 1)</th>
						<th>Category (Level 2)</th>
						<th>Category (Level 3)</th>
						<th>Category (Level 4)</th>
						<th>Unit of Measure</th>
						<th>Quantiy</th>
						<th>Unit Price</th>
						<th>Total Price</th>
					</tr>
					</thead>
						<tbody id="requisition_item_body">
						<tr class="item-row">
						<td class="col-md-2">
						<textarea placeholder="Enter items description" rows="2" class="form-control" name="item_description[]"></textarea></td>
						<td class="">
							<select class="form-control" name="item_category_1[]" >
								<option></option>
								<?php 
								$l1_codes = DB::query('SELECT * from level1_category order by description');
								foreach($l1_codes as $l1_code){
								?>
								<option value="<?php echo $l1_code['id'];?>"><?php echo $l1_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
						<td class="">
							<select class="form-control" name="item_category_2[]" >
								<option></option>
								<?php 
								$l2_codes = DB::query('SELECT * from level2_category order by description');
								foreach($l2_codes as $l2_code){
								?>
								<option value="<?php echo $l2_code['id'];?>"><?php echo $l2_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
						<td class="">
							<select class="form-control" name="item_category_3[]" >
								<option></option>
								<?php 
								$l3_codes = DB::query('SELECT * from level3_category order by description');
								foreach($l3_codes as $l3_code){
								?>
								<option value="<?php echo $l3_code['id'];?>"><?php echo $l3_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
						<td class="">
							<select class="form-control" name="item_category_4[]" >
								<option></option>
								<?php 
								$l4_codes = DB::query('SELECT * from level4_category order by description');
								foreach($l4_codes as $l4_code){
								?>
								<option value="<?php echo $l4_code['id'];?>"><?php echo $l4_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
						<td>
							<select class="form-control prices" name="item_unit_of_measure[]">
							<option></option>
							<option value="All">All</option>
							<option value="Annual">Annual</option>
							<option value="Bag">Bag</option>
							<option value="Batch">Batch</option>
							<option value="Belt">Belt</option>
							<option value="Block">Block</option>
							<option value="Book">Book</option>
							<option value="Bottle">Bottle</option>
							<option value="Box">Box</option>
							<option value="Bundle">Bundle</option>
							<option value="Can">Can</option>
							<option value="Card">Card</option>
							<option value="Case">Case</option>
							<option value="Catridge">Catridge</option>
							<option value="Centimeter">Centimeter</option>
							<option value="Coil">Coil</option>
							<option value="Cone">Cone</option>
							<option value="Container">Container</option>
							<option value="Copy">Copy</option>
							<option value="Crate">Crate</option>
							<option value="Cubic Meter">Cubic Meter</option>
							<option value="Cubic Yard">Cubic Yard</option>
							<option value="Cylinder">Cylinder</option>
							<option value="Day">Day</option>
							<option value="Days">Days</option>
							<option value="Display">Display</option>
							<option value="Dozen">Dozen</option>
							<option value="Drum">Drum</option>
							<option value="Each">Each</option>
							<option value="Feet">Feet</option>
							<option value="Foot">Foot</option>
							<option value="Gallons">Gallons</option>
							<option value="Gram">Gram</option>
							<option value="Grams">Grams</option>
							<option value="Gross">Gross</option>
							<option value="Hour">Hour</option>
							<option value="Hours">Hours</option>
							<option value="Inch">Inch</option>
							<option value="Kilogram">Kilogram</option>
							<option value="Kilometer">Kilometer</option>
							<option value="Liter">Liter</option>
							<option value="Meter">Meter</option>
							<option value="Milligram">Milligram</option>
							<option value="Millimeter">Millimeter</option>
							<option value="Month">Month</option>
							<option value="Months">Months</option>
							<option value="Night">Night</option>
							<option value="Nights">Nights</option>
							<option value="Other">Other</option>
							<option value="Ounce">Ounce</option>
							<option value="Pack">Pack</option>
							<option value="Package">Package</option>
							<option value="Pair">Pair</option>
							<option value="Pallet">Pallet</option>
							<option value="Person">Person</option>
							<option value="Piece">Piece</option>
							<option value="Pound">Pound</option>
							<option value="Pounds">Pounds</option>
							<option value="Quire">Quire</option>
							<option value="Ream">Ream</option>
							<option value="Roll">Roll</option>
							<option value="Set">Set</option>
							<option value="Sheet">Sheet</option>
							<option value="Spool">Spool</option>
							<option value="Square Centimeter">Square Centimeter</option>
							<option value="Square Foot">Square Foot</option>
							<option value="Square Inch">Square Inch</option>
							<option value="Square Meter">Square Meter</option>
							<option value="Tin">Tin</option>
							<option value="Tray">Tray</option>
							<option value="Tube">Tube</option>
							<option value="Unit">Unit</option>
							<option value="Wallet">Wallet</option>
							<option value="Week">Week</option>
							<option value="Weeks">Weeks</option>
							<option value="Yard">Yard</option>
							<option value="Year">Year</option>
							<option value="Years">Years</option>
								
							</select>
						</td>

						<td><input id="item_quantity" type="text" class="form-control prices item_quantity" name="item_quantity[]" /></td>
						<td><input type="text" id="item_unit_price" class="form-control prices item_price" name="item_price[]" /></td>
						<td><input type="text" id="item_total_price" class="form-control item_total_price" disabled name="item_total_price[]" /></td>
						<td><button class="btn btn-sm btn-danger DeleteRow" id="DeleteRow" type="button" title="Remove"><i class="fa fa-trash"></i></button></td>	
					</tr>
						</tbody>
					</table>
					<h4 class="text-right" id="items_total_value"></h4>
					</div>
					<button class="btn btn-sm btn-primary" id="addMoreDesc" type="button">Add More Description</button>
					</div>
					<hr/>
				    <br/>
				<div class="tab-footer clearfix">	
					<a href="../requisitions" class="btn btn-danger">Cancel</a>	
					<button type="submit" id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Save Draft</button>
					<button type="submit" id="btnCreate" name="formBtn" value="btnCreate" class="btn btn-mint pull-right">Create Requisition</button>
				</div>
	</form>
		</div>
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
	$('#demo-dp-component .input-group.date').datepicker({autoclose:true, todayHighlight: true,});
</script>
<script>
	//Saving as Draft
$('#btnSaveDraft').on("click", function () {
	$("#demo-bv-bsc-tabs").submit(function(e) {
	e.preventDefault(); 
	var form = $(this).serializeArray();
	form.push({name: "formAction", value: "SaveDraft"});
		$.ajax({
			type: "POST",
			url: "add_requisition",
			contentType: 'application/x-www-form-urlencoded',
			data: $.param(form),
			success: function(data)
			{

				var result = JSON.parse(data);
				if (result.Status == "Success") {
					$.niftyNoty({
						type: 'success',
						icon : 'pli-like-2 icon-2x',
						message : result.Message,
						container : 'floating',
						timer : 5000
					});
				}else{
					$.niftyNoty({
						type: 'danger',
						icon : 'pli-cross icon-2x',
						message : result.Message,
						container : 'floating',
						timer : 5000
					});
				};
				setTimeout(function(){ window.location = "../requisitions"; }, 6000);
			}
		});
	});

});


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
$('#btnCreate').on("click", function () {
	$('#demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,

	fields: {
		department_name: { validators: { notEmpty: { message: 'The Department is required' } } },
		requisition_number: { validators: { notEmpty: { message: 'The Requisition ID is required' } } },
		requisition_name: { validators: { notEmpty: { message: 'The Requisition Name is required' } } },
		requisition_project_id: { validators: { notEmpty: { message: 'The Project ID is required' } } },
		requisition_activity_id: { validators: { notEmpty: { message: 'The Activity ID is required' } } },
		requisition_fund_code: { validators: { notEmpty: { message: 'The Fund Code is required' } } },
		requisition_acc_code: { validators: { notEmpty: { message: 'The Account Code is required' } } },
		requisition_business_unit: { validators: { notEmpty: { message: 'The Business Unit is required' } } },
		requisition_currency: { validators: { notEmpty: { message: 'The Currency is required' } } },
		requisition_due_date: { validators: { notEmpty: { message: 'The Due Date is required' } } }
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
});
</script>
</body>
</html>