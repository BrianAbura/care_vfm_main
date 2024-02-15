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
	.space {
	margin-bottom: 4px;
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
<li class="active text-lg">Completed Evaluations</li>
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
		<div class="table-respsonsive">
		<table id="demo-dt-addrow" class="table" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						<th>Subject</th>
						<th>Procurement Method</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
		<?php 
		$cnt = 1;
		$curDate = date('Y-m-d H:i:s');
		$tenders = DB::query('SELECT * from tenders where status=%s and submission_date<=%s order by submission_date desc', 5, $curDate);
		//All Closed tenders
		foreach($tenders as $tender){
		//Check if the Tender was discarded.
		//Approved and completed tenders will not appear here.
		$chk_completed = DB::queryFirstRow('SELECT * from completed_evaluations where tender_id=%s AND decision=%s order by id desc ', $tender['tender_id'], "Approved");
		$chk_published = DB::queryFirstRow('SELECT * from published_notice where tender_id=%s', $tender['tender_id']);
		
		if($chk_completed){
			//End Restriction View
			if(restrict_soc_oc($current_user['role_id']) || restrict_au($current_user['role_id']) || restrict_smt($current_user['role_id'])){ //View All
				$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
			}
			else{ //Restrict to Department
				$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s AND department=%s', $tender['requisition_id'], $current_user['department_id']);
			}
			if($requisition){
				$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
		$proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
		$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);

		//Received Quotations
		$sum_quotes = 0;
		$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender['tender_id'], 2);
			if($quotes){
				$sum_quotes = DB::count();
			}
		?>
			<tr>
			<td><?php echo $cnt; ?></td>
			<td ><?php echo $tender['tender_id']; ?></td>
			<td style="width:40%"><a href="../tenders/<?php echo $tender['tender_id']."-view";?>"><span class="text-bold text-lg text-info"><?php echo $tender['tender_title'];?></span></a><br/>
				<small>Dept: <?php echo $department['name'];?></small>
			</td>
			<td><?php echo $proc_method['method_name']; ?></td>
			<td>
			<?php 
				if($chk_completed['level'] == 1){
					echo '<label class="label label-mint text-bold btn-sm">Pending Procurement Review</label>';
				}
				elseif($chk_completed['level'] == 2){
					echo '<label class="label label-mint text-bold btn-sm">Pending SMT Review</label>';
				}
				elseif($chk_published){
					echo '<label class="label label-success text-bold btn-sm">Published</label>';
				}
				else{
					echo '<label class="label label-mint text-bold btn-sm">Fully Approved</label>';
					
				}
			?>
			</td>
			<!-- Actions Column -->

			<td>
			<div class="btn-group dropdown">
				<button class="btn btn-primary btn-active-success btn-sm dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
					Action <i class="dropdown-caret"></i>
				</button>
				<ul class="dropdown-menu">
					<li><a href="../tenders/<?php echo $tender['tender_id']."-view";?>">View Tender Details</a></li>
					<li><a href="report?tender=<?php echo $tender['tender_id']?>" > View Evaluation Report</a></li>
				</ul>
			</div>

			<?php 
			if($chk_completed['level'] == 1){	
					
			//For SOC to handle	
			if(restrict_soc($current_user['role_id'])){
			?>
			<a href="finalize?level=2&tender=<?php echo $tender['tender_id']?>" class="btn btn-mint text-bold btn-sm"> Review Evaluation Report</a>
			<?php } }
			elseif($chk_completed['level'] == 2){
				//For SMT to handle	
				if(restrict_smt($current_user['role_id'])){
			?>
			<a href="finalize?level=3&tender=<?php echo $tender['tender_id']?>" class="btn btn-mint text-bold btn-sm"> Review Evaluation Report</a>
			<?php } }
			else{ 
				if(restrict_soc($current_user['role_id'])){
					if(!$chk_published){
			?>
			<a href="publish?&tender=<?php echo $tender['tender_id']?>" class="btn btn-success text-bold btn-sm"> Publish Notice</a>
			<?php
					}
				}
			}
			?>
			</td>
		<!-- End of Actions Column -->
		</tr>
		<?php
		}
			$cnt ++;
} //End Checking for completed
			} // EndForEach Tender ?>
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
<!--END CONTENT CONTAINER-->
</div>

<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php"; 
?>

</body>
</html>
