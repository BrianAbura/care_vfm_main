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
<li class="active text-lg">Edit Requisition</li>
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
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
		<form id="demo-bv-bsc-tabs" action="edit_requisition" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token" value="edit_requisition">
		<input type="hidden" name="requisition_id" value="<?php echo $requisition['id']; ?>">
		<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin-bottom:20px">Requisition Details</h5>
				<!-- Row 1-->
				<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Department</label>
						<input type="text" value="<?php echo $requisition['department'];?>" class="form-control" name="department_name" readonly>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Requisition ID</label>
						<input type="text" value="<?php echo $requisition['requisition_number'];?>" class="form-control" name="requisition_number" readonly >
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Requisition Name</label>
						<textarea placeholder="e.g. Supply of materials ..." rows="2" class="form-control" name="requisition_name"><?php echo $requisition['requisition_name'];?></textarea>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Procurement Category</label>
						<select class="selectpicker form-control" data-live-search="true" name="requisition_category" required>
						<?php 
							//Users department
							$req_cat = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $requisition['category']);
							?>	
							<option value="<?php echo $req_cat['id'];?>"><?php echo $req_cat['name'];?></option>
							<?php
							//All Categories 
							$categories = DB::query('SELECT * from procurement_categories order by name');
							foreach($categories as $category){
								if($category['id'] == $requisition['category']){
									continue;
								}
							?>
							<option value="<?php echo $category['id'];?>"><?php echo $category['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Currency</label>
						<input type="text" name="requisition_currency" value="<?php echo $requisition['currency'];?>" class="form-control" readonly>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Requisition Due Date</label>
						<div id="demo-dp-component">
						<div class="input-group date">
							<input type="text" value="<?php echo  date_format(date_create($requisition['due_date']), 'm/d/Y');?>" class="form-control" name="requisition_due_date">
							<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
						</div>
						<small class="text-muted">Auto close on select</small>
						</div>
					</div>
				</div>
				</div>
				<br>

				<div class="row">
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Distrib</label>
						<input type="text" class="form-control" name="distrib"   value="<?php echo $requisition['distrib'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Location</label>
						<input type="text" class="form-control" name="location"   value="<?php echo $requisition['location'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">GL Unit</label>
						<input type="text" class="form-control" name="gl_unit"   value="<?php echo $requisition['gl_unit'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Account</label>
						<input type="text" class="form-control" name="account"   value="<?php echo $requisition['account'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Alt Acct</label>
						<input type="text" class="form-control" name="alt_account"   value="<?php echo $requisition['alt_account'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Fund</label>
						<input type="text" class="form-control" name="fund"   value="<?php echo $requisition['fund'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">PC Bus Unit</label>
						<input type="text" class="form-control" name="pc_bus_unit"   value="<?php echo $requisition['pc_bus_unit'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Project</label>
						<input type="text" class="form-control" name="project"   value="<?php echo $requisition['project'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Activity</label>
						<input type="text" class="form-control" name="activity"   value="<?php echo $requisition['activity'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Source Type</label>
						<input type="text" class="form-control" name="source_type"   value="<?php echo $requisition['source_type'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Affiliate</label>
						<input type="text" class="form-control" name="affiliate"   value="<?php echo $requisition['affiliate'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Fund Affiliate</label>
						<input type="text" class="form-control" name="fund_affiliate"   value="<?php echo $requisition['fund_affiliate'];?>">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<label class="control-label">Project Affiliate</label>
						<input type="text" class="form-control" name="project_affiliate"   value="<?php echo $requisition['project_affiliate'];?>">
					</div>
				</div>
				
				</div>
				
				<div class="row">
				<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e;font-weight:600;margin:20px 10px">Requisition Item/Services/Works Details
				<!-- <a href="upload_requisition?requisition_id=<?php echo $requisition_id;?>" class="btn btn-mint btn-sm" title="Upload Requisition Items"><i class="fa fa-upload icon-fw"></i>Upload Items</a> -->
				</h5>
				
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

                        <?php 
                         $items = DB::query('SELECT * from requisition_items where requisition_number=%s', $requisition['requisition_number']);
						
						 if($items)
						 {
                         $sum = 0;
                         foreach($items as $item){
                            //$unspsc = DB::queryFirstRow('SELECT * from unspsc_class where class_code=%s', $item['category']);
                            if(!empty($item['quantity']) || !empty($item['price'])){
                                $qty = $item['quantity'];
                                $price = $item['price'];
                                $value = $qty * $price;
                                $sum += $value;
                            }
                            else{
                                $qty = 0;
                                $price = 0;
                                $value = 0;
                            }
                        ?>
						<tr class="item-row">
						<td class="col-md-2">
                        <input id="item_id" type="hidden" value="<?php echo $item['id'];?>" class="form-control prices item_id" name="item_id[]" />
						<textarea placeholder="Enter items description" rows="2" class="form-control" name="item_description[]" ><?php echo $item['description'];?></textarea></td>
						
									<!-- Category1 -->
						<td class="">
							<select class="form-control" name="item_category_1[]" >
								<?php 
								$cat1 = DB::queryFirstRow('SELECT * from level1_category where id=%s', $item['category_1']);
								?>
								<option value="<?php echo $cat1['id'];?>"><?php echo $cat1['description'];?></option>
								<?php 
								$l1_codes = DB::query('SELECT * from level1_category order by description');
								foreach($l1_codes as $l1_code){
									if($l1_code['id'] == $item['category_1']){
										continue;
									}
								?>
								<option value="<?php echo $l1_code['id'];?>"><?php echo $l1_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
									<!-- Category2 -->
						<td class="">
							<select class="form-control" name="item_category_2[]" >
								<?php 
								$cat2 = DB::queryFirstRow('SELECT * from level2_category where id=%s', $item['category_2']);
								?>
								<option value="<?php echo $cat2['id'];?>"><?php echo $cat2['description'];?></option>
								<?php 
								$l2_codes = DB::query('SELECT * from level2_category order by description');
								foreach($l2_codes as $l2_code){
									if($l2_code['id'] == $item['category_2']){
										continue;
									}
								?>
								<option value="<?php echo $l2_code['id'];?>"><?php echo $l2_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
								<!-- Category3 -->
						<td class="">
							<select class="form-control" name="item_category_3[]" >
								<?php 
								$cat3 = DB::queryFirstRow('SELECT * from level3_category where id=%s', $item['category_3']);
								?>
								<option value="<?php echo $cat3['id'];?>"><?php echo $cat3['description'];?></option>
								<?php 
								$l3_codes = DB::query('SELECT * from level3_category order by description');
								foreach($l3_codes as $l3_code){
									if($l3_code['id'] == $item['category_3']){
										continue;
									}
								?>
								<option value="<?php echo $l3_code['id'];?>"><?php echo $l3_code['description'];?></option>
								<?php } ?>
							</select>
						</td>
									<!-- Category4 -->
						<td class="">
							<select class="form-control" name="item_category_4[]" >
								<?php 
								$cat4 = DB::queryFirstRow('SELECT * from level4_category where id=%s', $item['category_4']);
								?>
								<option value="<?php echo $cat4['id'];?>"><?php echo $cat4['description'];?></option>
								<?php 
								$l4_codes = DB::query('SELECT * from level4_category order by description');
								foreach($l4_codes as $l4_code){
									if($l4_code['id'] == $item['category_4']){
										continue;
									}
								?>
								<option value="<?php echo $l4_code['id'];?>"><?php echo $l4_code['description'];?></option>
								<?php } ?>
							</select>
						</td>

						<td>
							<select class="form-control prices" name="item_unit_of_measure[]">
                            <option value="<?php echo $item['unit_of_measure'];?>"><?php echo $item['unit_of_measure'];?></option>
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
						<td><input id="item_quantity" type="text" value="<?php echo number_format($qty);?>" name="item_quantity[]"  class="form-control prices item_quantity"/></td>
						<td><input type="text" id="item_unit_price" value="<?php echo number_format($price);?>" name="item_price[]" class="form-control prices item_price"/></td>
						<td><input type="text" id="item_total_price" value="<?php echo number_format($value);?>" class="form-control item_total_price"/></td>
						<td><button class="btn btn-sm btn-danger DeleteRow" type="button" title="Remove"><i class="fa fa-trash"></i></button></td>	
					</tr>
                    <?php 
                         } //End Foreach Items Here
						} // End if any items are found
						else{
                    ?>

					<tr	tr class="item-row">
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
						<td><select class="form-control prices" name="item_unit_of_measure[]">
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

					<?php 
						} // End Else if no items are found
					?>
						</tbody>
					</table>
					<h4 class="text-right" id="items_total_value"><?php echo "Total: ".number_format($sum);?></h4>
					</div>
					<button class="btn btn-sm btn-primary" id="addMoreDesc" type="button">Add More Description</button>
				</div>
					<hr/>
				    <br/>
				<div class="tab-footer clearfix">	
					<a href="../requisitions/<?php echo $requisition_id; ?>-view" class="btn btn-danger">Cancel</a>	
					<button type="submit" id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Save Draft</button>
					
					<button type="submit" id="btnCreate" name="formBtn" value="btnCreate" class="btn btn-mint pull-right">Update Requisition</button>
				</div>
	</form>
		</div>
		 
<button class="btn btn-danger btn-sm delete-requisition" data-id="<?php echo $requisition_id; ?>"><i class="fa fa-trash"></i> Delete Requisition</button>	
               
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
	$('#demo-dp-component .input-group.date').datepicker({autoclose:true, todayHighlight: true,});
</script>
<script>
	//Saving as Draft
$('#btnSaveDraft').on("click", function () {
	$("#demo-bv-bsc-tabs").submit(function(e) {
	e.preventDefault(); 
	$('#btnSaveDraft').prop('disabled', true);
	var form = $(this).serializeArray();
	form.push({name: "formAction", value: "SaveDraft"});
		$.ajax({
			type: "POST",
			url: "edit_requisition",
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
				setTimeout(function(){ window.location = "../requisitions/<?php echo $requisition_id."-view"?>"; }, 6000);
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
		requisition_name: { validators: { notEmpty: { message: 'The Requisition Name is required' } } },
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

<script>
//Confirm Delete
$('.delete-requisition').on('click', function(){
	var requisition_id = $(this).data("id");
    var token = 'delete_requisition';
	bootbox.dialog({
	//title: "Create New Department",
	message : "<br/><h4 style='color:#e47s01e' class='text-danger'>Are you sure you want to delete this Requisition and all it's items? </h4>",
	buttons: {
		success: {
			label: "Yes, Delete",
			className: "btn-primary",
			callback : function(result) {
                $.ajax({
					type : 'post',
					url : 'Requisitions.php',
					data :  'requisition_number='+ requisition_id+'&token='+token, 
					success : function(data){
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
            }
		}
	}
});
});
</script>
</body>
</html>
