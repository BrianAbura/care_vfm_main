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
<li class="active text-lg">Quotations Received</li>
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
				<th>Received Quotations</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$cnt = 1;
			$curDate = date('Y-m-d H:i:s');
			$tenders = DB::query('SELECT * from tenders where status=%s and submission_date<=%s order by submission_date desc', 5, $curDate);
			//All Closed tenders
			foreach($tenders as $tender){
			
			//End Restriction View
			if(restrict_soc_oc($current_user['role_id']) || restrict_au($current_user['role_id']) || restrict_smt($current_user['role_id'])){ //View All
				$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
			}
			else{ //Restrict to Department
				$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s AND department=%s', $tender['requisition_id'], $current_user['department_id']);
			}

			if($requisition){
			$proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
			$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);
			if($tender['submission_date'] <= $curDate){
				$status = "Closed";
				$class = "danger";
			}
			else{
				$status = "Published";
				$class = "success";
			}
			//Received Quotations
			
			$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender['tender_id'], 2);
			if($quotes){
				$sum_quotes = DB::count();
			?>
				<tr>
				<td><?php echo $cnt; ?></td>
				<td ><?php echo $tender['tender_id']; ?></td>
				<td style="width:30%"><span class="text-bold text-lg text-info"><?php echo $tender['tender_title'];?></span><br/>
					<small>Dept: <?php echo $department['name'];?></small>
				</td>
				<td><?php echo $proc_method['method_name']; ?></td>
				<td><?php echo $sum_quotes; ?></td>
				<td>
				<div class="btn-group dropdown">
				<button class="btn btn-primary btn-active-success btn-sm dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
					Action <i class="dropdown-caret"></i>
				</button>
				<ul class="dropdown-menu">
					<li><a href="<?php echo $tender['tender_id']."-quotations";?>">View Quotations</a></li>
					<li><a href="../tenders/<?php echo $tender['tender_id']."-view";?>">View Tender Details</a></li>
				</ul>
			</div>
									
			</td>
			</tr>
			<?php
			}
			$cnt ++;
		}
				
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
