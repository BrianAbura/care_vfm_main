<?php 
require_once('../validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
<div id="page-head">
<ol class="breadcrumb">
<li></li>
<li class="active text-lg">Approved Requisitions</li>
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
	<th>Estimated Amount</th>
	<th>Assigned To</th>
	<th>Tender Status</th>
	<th>Action</th>
</tr>
</thead>
<tbody>
<?php 
$cnt = 1;
//Only Assigned Requisitions will appear here
//$requests = DB::query('SELECT * from requisition_assign where status=%s', 'Active');

if(restrict_soc_oc($current_user['role_id']) || restrict_au($current_user['role_id']) || restrict_smt($current_user['role_id'])){ //View All
	$requisitions = DB::query('SELECT * from requisitions order by id desc');
}
else{ //Restrict to Department
	$requisitions = DB::query('SELECT * from requisitions where department=%s order by id desc', $current_user['department_id']);
}
foreach($requisitions as $requisition){
	$request = DB::queryFirstRow('SELECT * from requisition_assign where requisition_id=%s AND status=%s',$requisition['requisition_number'], 'Active');
	if(!$request){
		continue;
	}
	$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $request['requisition_id']);
	$tender = DB::queryFirstRow('SELECT * from tenders where requisition_id=%s', $requisition['requisition_number']);
	if(!$tender){
		$tender_status ='Not Started';
		$class = "warning";
	}
	else{
		$query = DB::queryFirstRow('SELECT * from tender_status where code=%s', $tender['status']);
		$tender_status = $query['value'];
		$class = $query['class'];
	}
?>
	<tr>
	<td><?php echo $cnt; ?></td>
	<td ><?php echo $requisition['requisition_number']; ?></td>
	<td style="width:30%"><span class="text-bold text-lg text-info"><?php echo $requisition['requisition_name'];?></span><br/>
		<small>Dept: <?php echo get_deparment($requisition['department']);?></small>
	</td>
	<td><?php echo $requisition['currency'].' '.number_format($estimate['total']); ?></td>
	<td><?php echo requisition_assigned_to($request['requisition_id']); ?></td>
	<td><div class="label label-<?php echo $class;?>"><?php echo $tender_status;?></div></td>
	<td>
	<?php 
	if($current_user['user_id'] == $request['user_id']){ 

		if(!$tender){
		?>
		<a href="<?php echo $requisition['requisition_number']."-create";?>" title="Create Tender"><i class="btn btn-mint ion-plus icon-lg "></i></a>
		
		<?php 
		}
		/*
		 * 1 - Draft
		 * 2 - Pending SMT Approval
		 * 3 - Rejected by SMT
		 * 4 - Pending Publication
		 * 5 - Published
		 */
		else{
			switch($tender['status']){
				case 1:
					echo "<a href='".$tender['tender_id']."-edit' title='Edit Tender'><i class='btn btn-dark ion-edit icon-lg'></i></a>";
					break;
				case 2:
					echo "<a href='".$tender['tender_id']."-view' title='View tender'><i class='btn btn-info ion-search icon-lg'></i></a>";
					break;
				case 3:
					echo "<a href='".$tender['tender_id']."-edit' title='Edit Tender'><i class='btn btn-dark ion-edit icon-lg'></i></a>";
					break;
				case 4:
					echo "<a href='".$tender['tender_id']."-view' title='View tender'><i class='btn btn-info ion-search icon-lg'></i></a>";
					break;
				case 5:
					echo "<a href='".$tender['tender_id']."-view' title='View tender'><i class='btn btn-info ion-search icon-lg'></i></a>";
					break;
				default:
				//echo "<a href='".$tender['tender_id']."-view'><button class='btn btn-info btn-icon btn-sm' title='View Tender'><i class='ti-zoom-in'></i> View Tender</button></a>";
			}
		}
	}
	else{
		if($tender){
			echo "<a href='".$tender['tender_id']."-view' title='View tender'><i class='btn btn-info ion-search icon-lg'></i></a>";
		}
	}
		?>	
</td>
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
</body>
</html>
