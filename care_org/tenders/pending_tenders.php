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
<li class="active text-lg">Pending Approval</li>
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
	<table id="demo-dt-addrow" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>#</th>
			<th>ID</th>
			<th>Subject</th>
			<th>Procurement Method</th>
			<th>Estimated Amount</th>
			<th>Assigned To</th>
			<th>Tender Status</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$cnt = 1;
		$tenders = DB::query('SELECT * from tenders where status IN %ls', [2,4]);
		/**
		 * 2 - Pending SMT
		 * 4 - Approved by SMT - Pending Publication
		 */
		foreach($tenders as $tender){
		if(restrict_soc_oc($current_user['role_id']) || restrict_au($current_user['role_id']) || restrict_smt($current_user['role_id'])){ //View All
			$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
		}
		else{ //Restrict to Department
			$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s AND department=%s', $tender['requisition_id'], $current_user['department_id']);
		}
		if($requisition){
		$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $tender['requisition_id']);
		$proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
		?>
			<tr>
			<td><?php echo $cnt; ?></td>
			<td ><?php echo $tender['tender_id']; ?></td>
			<td style="width:40%"><span class="text-bold text-lg text-info"><?php echo $tender['tender_title'];?></span><br/>
				<small>Dept: <?php echo get_deparment($requisition['department']);?></small>
			</td>
			<td><?php echo $proc_method['method_name']; ?></td>
			<td><?php echo $requisition['currency'].' '.number_format($estimate['total']);?></td>
			<td><?php echo requisition_assigned_to($tender['requisition_id']); ?></td>
			<td><div class="label label-mint">
					<?php 
						if($tender['status'] == 2){
						$status = "Pending SMT Approval"; 
						}
						else{
							$status = "Pending Publication";
						}
						echo $status;
					?> 
				</div>
			</td>
			<td>
				<?php 
				if(restrict_smt($current_user['role_id']) && $tender['status'] == 2){
				?>
				<a href="<?php echo $tender['tender_id']."-review";?>" title="Review Tender"><i class="btn btn-dark ion-clipboard icon-lg"></i></a>
				<?php }
				else if(restrict_soc_oc($current_user['role_id'])&& $tender['status'] == 4){
				?>
				<a href="<?php echo $tender['tender_id']."-publish";?>" title="Publish Tender"><i class="btn btn-success ion-upload icon-lg"></i></a>
				<?php
				}
				else{
				?>
				<a href="<?php echo $tender['tender_id']."-view";?>" title="View Tender"><i class='btn btn-info ion-search icon-lg'></i></a>
				<?php }?>
			</td>
		</tr>
		<?php 
		}
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
