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
	.remove{
		cursor: pointer;
	}
</style>
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
<li class="active text-lg">Edit Tender</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
if(!isset($tender))
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
					The Record your are trying to access does not exist. Click <a href="../tenders" class="alert-link">Here.</a> to go back to the list of tenders.
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
} //End User Query || Null records
else{
	$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
	$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);
	$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $tender['requisition_id']);
	$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%d', $requisition['category']);
	$proc_method = DB::queryFirstRow("SELECT * FROM thresholds WHERE proc_category=%d_cat AND min_amount <= %d_min AND max_amount >= %d_max", 
	[
	'cat' => $category['id'],
	'min' => $estimate['total'],
	'max' => $estimate['total'],
	]
	);
	$query_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $proc_method['proc_method']);
?>
				<div class="row">
				<div class="col-xs-12">
				<div class="panel">
				<div class="panel-body">
				<table class="table table-bordered table-striped pad-ver mar-no">
				<h4 style="color:teal;">Tender Details - #<?php echo $tender_id;?></h4><br/>
					<tbody>
						<tr>
							<td class="text-info text-bold">Requisition Number</td>
							<td><?php echo $requisition['requisition_number'];?></td>
							<td class="text-info text-bold">Status</td>
							<td><span class="label label-success">Approved</span></td>
							<td class="text-info text-bold">Department</td>
							<td><?php echo $department['name'];?></td>
						</tr>
						<tr>
							<td class="text-info text-bold">Requisition Description</td>
				<td colspan="7" class="text-bold"><?php echo $requisition['requisition_name'];?></td>
				</tr>
				<tr>
				<td class="text-info text-bold">Estimate Amount</td>
				<td class="text-bold text-lg"><?php echo number_format($estimate['total']);?><input id="requisition_amount" type="hidden" value="<?php echo $estimate['total'];?>"></td>
				<td class="text-info text-bold">Currency</td>
				<td><?php echo $requisition['currency'];?> <input id="requisition_currency" type="hidden" value="<?php echo $requisition['currency'];?>"></td>
				<td class="text-info text-bold">Requisition Due Date</td>
				<td><?php echo date_format(date_create($requisition['due_date']), 'd-M-Y'); ?></td>
				</tr>
				</tbody>
				</table>
				<br/>
				<div class="col-lg-12">
				<div class="panel">
				<div id="demo-bv-wz">
				<div class="wz-heading pad-top">

					<!--Nav-->
			<ul class="row wz-step wz-icon-bw wz-nav-off mar-top">
				<li class="col-xs-3">
					<a data-toggle="tab" href="#demo-bv-tab1">
						<span class="text-danger"><i class="ti-receipt icon-2x"></i></span>
						<p class="text-semibold mar-no">Tender Details</p>
					</a>
				</li>
				
				<li class="col-xs-3">
					<a data-toggle="tab" href="#demo-bv-tab2">
						<span class="text-warning"><i class="ti-ruler-pencil icon-2x"></i></span>
						<p class="text-semibold mar-no">Evaluation Criteria</p>
					</a>
				</li>
				<li class="col-xs-3">
					<a data-toggle="tab" href="#demo-bv-tab3">
						<span class="text-info"><i class="ti-announcement icon-2x"></i></span>
						<p class="text-semibold mar-no">Tender Notice</p>
					</a>
				</li>
				<li class="col-xs-3">
					<a data-toggle="tab" href="#demo-bv-tab4">
						<span class="text-success"><i class="demo-pli-medal-2 icon-2x"></i></span>
						<p class="text-semibold mar-no">Publish</p>
					</a>
				</li>
			</ul>
				</div>

				<!--progress bar-->
				<div class="progress progress-xs progress-striped active">
					<div class="progress-bar progress-bar-dark"></div>
				</div>

				<!--Form-->
				<form id="demo-bv-wz-form" action="edit_tender" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="token" value="edit_tender">
				<input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
				<input type="hidden" id="init_method" name="init_method" value="<?php echo $proc_method['proc_method'];;?>">
				<div class="panel-body">
				<div class="tab-content">
		
				<!--First tab-->
				<div id="demo-bv-tab1" class="tab-pane">
				<div class="row">
				<div class="col-sm-2">
				<div class="form-group">
				<label class="control-label">Category</label>
				<input type="hidden" name="category" value="<?php echo $category['id'];?>">
				<h5 class="text-uppercase" style="color:blue"><?php echo $category['name'];?></h5>
				</div>
				</div>
		
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Title</label>
						<textarea rows="3" readonly class="form-control" name="tender_title"><?php echo $requisition['requisition_name'];?></textarea>
					</div>
				</div>


				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Solicitation Method</label>
						<select class="selectpicker form-control" id="solicitation_method" name="solicitation_method" data-live-search="true">

						<?php 
						$cur_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
						?>	
						<option value="<?php echo $cur_method['id'];?>"><?php echo $cur_method['method_name'];?></option>
						<?php
						$methods = DB::query('SELECT * from procurement_methods order by method_name');
						foreach($methods as $method){
							if($method['id'] == $tender['solicitation_method']){
								continue;
							}
						?>
						<option value="<?php echo $method['id'];?>"><?php echo $method['method_name'];?></option>
						<?php } ?>

						</select>
						<br/><br/>
						<textarea rows="3" class="form-control" placeholder="Justification for change of method" id="method_justification" name="method_justification"><?php echo $tender['method_justify'];?></textarea>
					</div>
				</div>
				<div class="col-sm-3	">
				<div class="form-group">
					<label class="control-label">Location of Delivery/Service</label>
					<input type="text"  class="form-control" name="location" autocomplete="off" value="<?php echo $tender['location'];?>">
					<small class="text-muted">In case of delivery of Goods or Service sites</small>
				</div>
			</div>
			</div><!-- End of First>
			 Row-->
		<br/>
		<hr/>
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<label class="control-label">Submission Deadline Date</label>
					<div id="demo-dp-component">
					<div class="input-group date">
						<input type="text" class="form-control" name="submission_date" value="<?php echo  date_format(date_create($tender['submission_date']), 'm/d/Y');?>">
						<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
					</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label class="control-label">Submission Deadline Time</label>
					<div class="input-group date">
					<input id="demo-tp-com" type="text" class="form-control" name="submission_time" value="<?php echo  date_format(date_create($tender['submission_date']), 'h:i A');?>">
						<span class="input-group-addon"><i class="demo-pli-clock"></i></span>
					</div>
				</div>
			</div>
			<div class="col-sm-3" id="sole_vendor_div">
			<div class="form-group">
					<label class="control-label">Select Vendor</label>
					<select class="selectpicker form-control" data-live-search="true" name="sole_vendor" id="sole_vendor">
					<option></option>
					<?php 
					$sole_vendors = DB::query('SELECT * from vendors where vendor_status=%s order by vendor_name', 3); //Approved
					?>	
					<?php
					foreach($sole_vendors as $sole_vendor){
					?>
					<option value="<?php echo $sole_vendor['vendor_id'];?>"><?php echo $sole_vendor['vendor_name'];?></option>
					<?php } ?>
					</select>
				</div>
				<?php 
				if($tender['solicitation_method'] == 1){
					$vend_shtlst = DB::queryFirstRow('SELECT * from tender_shortlist where tender_id=%s', $tender_id);
					if($vend_shtlst){
						$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $vend_shtlst['vendor_id']);
				?>
				 	<div class="panel panel-bordered panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Shortlisted Vendor</h3>
					</div>
					<div class="panel-body">
					<ul>
                    <li><p><?php echo $vendor['vendor_name'];?> - <i data-id="<?php echo $vend_shtlst['id'];?>" class="ion-close-circled icon-xs text-danger remove remove_vendor">Remove</i></p></li>
					</ul>
					</div>
				</div>
				<?php 
					} }
				?>

			</div>
			<div class="col-sm-3" id="multiple_vendors_div">
			<div class="form-group">
					<label class="control-label">Select Vendors</label>
					<select data-placeholder="Select Vendors..." multiple="" tabindex="4" name="multiple_vendors[]" id="multiple_vendors"> 
					<option></option>
					<?php 
					$multiple_vendors = DB::query('SELECT * from vendors where vendor_status=%s order by vendor_name', 3); //Approved
					?>	
					<?php
					foreach($multiple_vendors as $multiple_vendor){
					?>
					<option value="<?php echo $multiple_vendor['vendor_id'];?>"><?php echo $multiple_vendor['vendor_name'];?></option>
					<?php } ?>
					</select>
					<small class="text-muted">Min 3.</small>
				</div>
			<?php 
				if($tender['solicitation_method'] != 1){
					$vend_shtlst_multi = DB::query('SELECT * from tender_shortlist where tender_id=%s', $tender_id);
					if($vend_shtlst_multi){
				?>
				 <div class="panel panel-bordered panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Shortlisted Vendors</h3>
					</div>
					<div class="panel-body">
					<ul>
						<?php 
						foreach($vend_shtlst_multi as $list){
							$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $list['vendor_id']);
						?>
                    <li><p><?php echo $vendor['vendor_name'];?> - <i data-id="<?php echo $list['id']; ?>" class="ion-close-circled icon-xs text-danger remove remove_vendor">Remove</i></p></li>
					<?php } ?>
                	</ul>
					</div>
				</div>
				<?php 
					}
				}
				?>
			</div>
	</div> <!-- End Row 2-->
				</div>
					<!--Second tab-->
					<div id="demo-bv-tab2" class="tab-pane fade">
						<!-- Row 1-->	
					<div class="row">
					<div class="col-sm-12">
					<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;">Preliminary Evaluation Criteria</h5>
						<div class="table-respondssive">
						<table class="table" style="background: #DEEFF2" id="pleliminary_item_table">
						<thead>
						<tr>
							<th>Description</th>
						</tr>
						</thead>
							<tbody id="pleliminary_item_body">
								<?php 
								$pre_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 1);
								foreach($pre_evals as $pre_eval){
								?>
							<tr>
							<td>
							<input id="prelimininary_id" type="hidden" value="<?php echo $pre_eval['id'];?>" name="prelimininary_id[]" />
							<textarea placeholder="Specify Evaluation criteria" required rows="1" class="form-control prices" name="prelimininary_description[]"><?php echo $pre_eval['criteria_description'];?></textarea></td>
							<td><button class="btn btn-sm btn-danger" id="delPreCriteria" type="button" title="Remove"><i class="fa fa-trash"></i></button></td>	
							</tr>
								<?php } //End foreach?>
							</tbody>
						</table>
						</div>
						<buttoitemn class="btn btn-sm btn-primary" id="addPreCriteria" type="button">Add Criteria</buttoitemn>
					</div>
					</div>
					<hr/>
					<!-- End Row 1-->
					<!-- Row 2-->
				<div class="row">
					<div class="col-sm-12">
					<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin:20px 20px">Techincal Evaluation Criteria</h5>
					<div class="table-respondssive">
					<table class="table" style="background: #DCFFD2" id="technical_item_table">
					<thead>
					<tr>
						<th>Description</th>
					</tr>
					</thead>
						<tbody id="technical_item_body">
						<?php 
						$tech_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 2);
						foreach($tech_evals as $tech_eval){
						?>
						<tr>
						<td>
						<input id="tech_id" type="hidden" value="<?php echo $tech_eval['id'];?>" name="tech_id[]" />
						<textarea placeholder="Specify Technical criteria" required rows="1" class="form-control prices" name="tech_description[]"><?php echo $tech_eval['criteria_description'];?></textarea></td>
						<td><button class="btn btn-sm btn-danger" id="delTechCriteria" type="button" title="Remove"><i class="fa fa-trash"></i></button></td>	
						</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<buttoitemn class="btn btn-sm btn-mint" id="addTechCriteria" type="button">Add Criteria</buttoitemn>
					</div>
				</div>
				<hr/>
				<!-- End Row 2-->
				<!-- Row 3-->
				<div class="row">
				<div class="col-sm-12">
				<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin:20px 20px">Financial Evaluation Criteria 
				
					</h5>
					
					<div class="table-respondssive">
					<table class="table" id="financial_item_table">
					<thead>
					<tr>
					<th>No</th>
					<th>Description</th>
					<th>Category</th>
					<th>Unit of Measure</th>
					<th>Quantity</th>
					<th>Estimated Unit Price</th>
					</tr>
					</thead>
					<tbody>
					<?php 
					$items = DB::query('SELECT * from requisition_items where requisition_number=%s', $requisition['requisition_number']);
					$cnt = 1;
					foreach($items as $item){
						$unspsc = DB::queryFirstRow('SELECT * from unspsc where fam_code=%s', $item['category']);
						if(!empty($item['quantity']) || !empty($item['price'])){
							$qty = $item['quantity'];
							$price = $item['price'];
						}
						else{
							$qty = 0;
							$price = 0;
						}
					?>
					<tr>
						<td><?php echo $cnt;?></td>
						<td><?php echo $item['description'];?></td>
						<td><?php echo $unspsc['description'];?></td>
						<td><?php echo $item['unit_of_measure'];?></td>
						<td><?php echo number_format($qty);?></td>
						<td><?php echo number_format($price);?></td>
					</tr>
					<?php 
					$cnt ++;  
					}
						?>
					</tbody>
						</table>
						<br/>
					<p class="text-muted text-bold">Note: Vendors will only be required to provide the unit price estimates</p>
					</div>
					</div>
					</div>
					<!-- End Row 3-->
					</div>

						<!--Third tab-->
						<div id="demo-bv-tab3" class="tab-pane">
						<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin:20px 20px">
						Tender Notice Details</h5>
						<div class="col-sm-12" style=" border: 1px solid rgb(255, 158, 48);">
						<?php 
						$notice = DB::queryFirstRow('SELECT * from tender_notice where tender_id=%s', $tender_id);
						?>
						<textarea id="demo-mail-compose" class="form-control summernote_text" name="tender_notice"><?php echo $notice['message'];?></textarea>
						</div>
						
						</div>

						<!--Fourth tab-->
						<div id="demo-bv-tab4" class="tab-pane  mar-btm text-center">
							<h4>All Done</h4>
							<p class="text-mted">Tender has been fully prepared and is ready for approval/publication.</p>
						</div>
					</div>
				</div>

				<!--Footer button-->

				<div class="panel-footer text-right">
				<div class="box-inline">
						<button type="button" class="previous btn btn-primary">Previous</button>
						<button type="button" class="next btn btn-primary">Next</button>
						<button type="submit" id="formPublish" name="formAction" value="Publish" class="finish btn btn-success" disabled="">Publish Tender</button>
						<button type="submit" id="approvalPending" name="formAction" value="pendApproval" class="finish btn btn-mint" disabled="">Submit for Approval</button>
					</div>
				</div>

	<div class="panel-footer text-left">
	<div class="box-inline">
	<a href="javascript:history.back()" class="btn btn-danger">Cancel</a>	
	<button id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Save Draft</button>
	</div>
	</div>
			</form>
		</div>
	<!--===================================================-->
<!-- End Classic Form Wizard -->

</div>
</div>
</div>
</div>
</div>
</div>
<?php 
} //End Requisition Query || Record found
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
//Operations on the actions
	var method = document.getElementById("solicitation_method");
	var sole = document.getElementById("sole_vendor_div");
	var multiple = document.getElementById("multiple_vendors_div");
	var justification = document.getElementById("method_justification");
	var init_method = document.getElementById("init_method");
	//Approvals
	var formPublish = document.getElementById("formPublish");
	var approvalPending = document.getElementById("approvalPending");
	var amount = document.getElementById("requisition_amount");
	var currency = document.getElementById("requisition_currency");
	//Default
	sole.style.display = "none";
	multiple.style.display = "none";
	justification.style.display = "none";
	formPublish.style.visibility = "hidden";
	approvalPending.style.visibility = "hidden";

	//For Justification
	if(method.value != init_method.value){
		justification.style.display = "block";
	}
	else{
		justification.style.display = "none";
	}

	//Scenario 1. Approval/Publish
	if(method.value == 1){ //Direct Purchase
		sole.style.display = "block";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
	}
	else if(method.value == 2){ //Quotation
		multiple.style.display = "block";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "hidden"; 
				formPublish.style.visibility = "visible"; //Publish
			}
	}
	else{ //Competitive Sealed Bids
		//multiple.style.display = "block";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "hidden"; 
				formPublish.style.visibility = "visible"; //Publish
			}
	}

	method.onchange = function(){
		//For Vendor Selection
		if(method.value == 1){ //Direct Purchase
			sole.style.display = "block";
			multiple.style.display = "none";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}

		}
		else if(method.value == 2){ //Quotations
			multiple.style.display = "block";
			sole.style.display = "none";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "hidden"; 
				formPublish.style.visibility = "visible"; //Publish
			}
		}
		else{ //Competitive Sealed bids
			multiple.style.display = "none";
			sole.style.display = "none";
			if((currency.value == "USD" && amount.value >= 150000) || (currency.value == "UGX" && amount.value >= (150000 * 3700)) ){
				approvalPending.style.visibility = "visible"; //Submit for approval
				formPublish.style.visibility = "hidden";
			}
			else{
				approvalPending.style.visibility = "hidden"; 
				formPublish.style.visibility = "visible"; //Publish
			}
		}

		//For Justification on change
		if(method.value != init_method.value){
			justification.style.display = "block";
		}
		else{
			justification.style.display = "none";
		}
	}
</script>


<script>
$('#demo-dp-component .input-group.date').datepicker({autoclose:true, todayHighlight: true, startDate:"currentDate"});
</script>
<script>
//Saving as Draft
$('#btnSaveDraft').on("click", function () {
$("#demo-bv-wz-form").submit(function(e) {
e.preventDefault(); 
var form = $(this).serializeArray();
form.push({name: "formAction", value: "SaveDraft"});
$.ajax({	
	type: "POST",
	url: "edit_tender",
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
		setTimeout(function(){ window.location = "../tenders"; }, 3000);
	}
});
});

});

//Validate Submission Form
$(document).on('nifty.ready', function() {

// FORM WIZARD WITH VALIDATION
// =================================================================
$('#demo-bv-wz').bootstrapWizard({
tabClass		    : 'wz-steps',
nextSelector	    : '.next',
previousSelector	: '.previous',
onTabClick          : function(tab, navigation, index) {
	return false;
},
onInit : function(){
	$('#demo-bv-wz').find('.finish').hide().prop('disabled', true);
},
onTabShow: function(tab, navigation, index) {
	var $total = navigation.find('li').length;
	var $current = index+1;
	var $percent = ($current/$total) * 100;
	var wdt = 100/$total;
	var lft = wdt*index;

	$('#demo-bv-wz').find('.progress-bar').css({width:wdt+'%',left:lft+"%", 'position':'relative', 'transition':'all .5s'});

	// If it's the last tab then hide the last button and show the finish instead
	if($current >= $total) {
		$('#demo-bv-wz').find('.next').hide();
		$('#demo-bv-wz').find('.finish').show();
		$('#demo-bv-wz').find('.finish').prop('disabled', false);
	} else {
		$('#demo-bv-wz').find('.next').show();
		$('#demo-bv-wz').find('.finish').hide().prop('disabled', true);
	}
},
onNext: function(){
	isValid = null;
	$('#demo-bv-wz-form').bootstrapValidator('validate');


	if(isValid === false)return false;
}
});

var isValid;
$('#demo-bv-wz-form').bootstrapValidator({
message: 'This value is not valid',
feedbackIcons: {
valid: 'fa fa-check-circle fa-lg text-success',
invalid: 'fa fa-times-circle fa-lg',
validating: 'fa fa-refresh'
},
fields: {
	category: { validators: { notEmpty: { message: 'The Category is required' } } },
}
}).on('success.field.bv', function(e, data) {
// $(e.target)  --> The field element
// data.bv      --> The BootstrapValidator instance
// data.field   --> The field name
// data.element --> The field element

var $parent = data.element.parents('.form-group');

// Remove the has-success class
$parent.removeClass('has-success');


// Hide the success icon
//$parent.find('.form-control-feedback[data-bv-icon-for="' + data.field + '"]').hide();
}).on('error.form.bv', function(e) {
isValid = false;
});
});
</script>

<script>
	//Tender Notice Tab
$(document).on('nifty.ready', function() {
if ($('#demo-mail-compose').length) {

$('#demo-mail-compose').summernote({
height:300,
toolbar: [
['font', ['bold', 'italic', 'underline', 'clear']],
['para', ['ul', 'ol']],
],
focus: true,
placeholder: 'Provide full details of the tender notice as it will be presented to the vendors. e.g. Background, Project Details, etc.',
});

return;
}
});
</script>

<script>
//Confirm Delete
$('.remove_vendor').on('click', function(){
	var shortlist_id = $(this).data("id");
	bootbox.dialog({
	//title: "Create New Department",
	message : "<br/><h4 class='text-semibold text-warning'>Are you sure you want to remove this vendor from the current shortlist? </h4>",
	buttons: {
		success: {
			label: "Yes, Remove",
			className: "btn-primary",
			callback : function(result) {
                $.ajax({
					type : 'post',
					url : 'TenderEdit.php',
					data :  {token: 'del_shortlist', shortlist_id: shortlist_id},
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
						setTimeout(function(){ window.location.reload(1); }, 2000);
					}
				});
            }
		}
	}
});
});
</script>

<script>
//Confirm Delete Committee Member
$('.remove_member').on('click', function(){
	var com_member_id = $(this).data("id");
	bootbox.dialog({
	//title: "Create New Department",
	message : "<br/><h4 class='text-semibold text-warning'>Are you sure you want to remove this member from the current committee? </h4>",
	buttons: {
		success: {
			label: "Yes, Remove",
			className: "btn-primary",
			callback : function(result) {
                $.ajax({
					type : 'post',
					url : 'TenderEdit.php',
					data :  {token: 'del_com_member', com_member_id: com_member_id},
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
						setTimeout(function(){ window.location.reload(1); }, 2000);
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
