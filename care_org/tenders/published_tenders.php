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
<li class="active text-lg">Published Tenders</li>
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
				<div class="table-responsive">
			<table id="demo-dt-addrow" class="table table-striped table-bordered" cellspacing="1" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>ID</th>
					<th>Subject</th>
					<th>Procurement Method</th>
					<th>Estimated Amount</th>
					<th>Received Quotations</th>
					<th>Deadline</th>
					<th>Assigned To</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$cnt = 1;
				$curDate = date('Y-m-d H:i:s');
				$tenders = DB::query('SELECT * from tenders where status=%s order by submission_date desc', 5);
				
				foreach($tenders as $tender){
				$owner = DB::queryFirstRow('SELECT * from requisition_assign where requisition_id=%s AND status=%s', $tender['requisition_id'], 'Active');
				
				//End Restriction View
				if(restrict_soc_oc($current_user['role_id']) || restrict_au($current_user['role_id']) || restrict_smt($current_user['role_id'])){ //View All
					$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
				}
				else{ //Restrict to Department
					$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s AND department=%s', $tender['requisition_id'], $current_user['department_id']);
				}
				if($requisition){
				$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $tender['requisition_id']);
				$proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
				if($tender['submission_date'] <= $curDate){
					$status = "Bid Closed";
					$class = "danger";
				}
				else{
					$status = "Receiving Bids";
					$class = "success";
				}
				//Received Quotations
				$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender['tender_id'], 2);
				if($quotes){
					$sum_quotes = DB::count();
				}
				else{
					$sum_quotes = 0;
				}
				?>
					<tr>
					<td><?php echo $cnt; ?></td>
					<td ><?php echo $tender['tender_id']; ?></td>
					<td style="width:30%"><span class="text-bold text-lg text-info"><?php echo $tender['tender_title'];?></span><br/>
					<small>Dept: <?php echo get_deparment($requisition['department']);?></small>
					</td>
					<td><?php echo $proc_method['method_name']; ?></td>
					<td><?php echo $requisition['currency'].' '.number_format($estimate['total']); ?></td>
					<td><?php echo $sum_quotes; ?></td>
					<td class="text-dark text-lg"><?php echo date_format(date_create($tender['submission_date']), 'd/m/Y H:i'); ?></td>
					<td><?php echo requisition_assigned_to($tender['requisition_id']); ?></td>
					<td><div class="label label-<?php echo $class;?>"><?php echo $status;?></div></td>
					<td>
					<a href="<?php echo $tender['tender_id']."-view";?>" title="View Details"><i class="btn btn-primary ion-search icon-lg"></i></a>
					<?php
					
					if($current_user['user_id'] == $owner['user_id']){ 
					if($status == "Bid Closed" && $sum_quotes == 0){
					?>
					<a href="<?php echo $tender['tender_id']."-edit";?>" title="Re-invite" ><i class="btn btn-purple ion-loop icon-lg"></i></a>
					<?php } } ?>
					</td>
				</tr>
				<?php 
				} //End Restriction View
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
include $DIR."/footers.php"; 
?>
</body>
</html>
