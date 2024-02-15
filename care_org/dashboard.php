<?php 
require_once('validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>Home | Care Uganda</title>

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


	<ul id="mainnav-menu" class="list-group">

		<!--Category name-->
		<li class="list-header">Navigation</li>

		<!--Menu list item-->
		<li class="active-sub">
			<a href="home">
				<i class="demo-pli-home" style="color:coral; font-size:16px; font-weight:bold"></i>
					<span class="menu-title">Dashboard</span>
			</a>
		</li>
		<br/>
		<li>
			<a href="requisitions">
			<i class="ion-clipboard" style="color:coral; font-size:20px; font-weight:bold"></i> 
				<span class="menu-title">Requisitions</span>
			</a>
		</li>
		<br/>
		<li>
			<a href="tenders">
				<i class="fa fa-newspaper-o" style="color:green; font-size:18px; font-weight:bold"></i>
				<span class="menu-title">Tenders</span>
			</a>
		</li>
		<br/>
		<li>
			<a href="evaluations">
				<i class="ion-ios-toggle" style="color:blue; font-size:20px; font-weight:bold"></i>
				<span class="menu-title">Evaluations</span>
			</a>
		</li>
		<br/>
		<li>
			<a href="reports">
				<i class="demo-psi-bar-chart" style="color:deeppink; font-size:20px; font-weight:bold"></i>
				<span class="menu-title">Reports</span>
			</a>
		</li>
		<br/>


		<!-- Menu Divider -->
		<li class="list-divider"></li>
		<!-- This menu section is for Authorisized Users only
			Role Based -->
		<?php 
		if($current_user['role_id'] == 1 || $current_user['role_id'] == 11 || $current_user['role_id'] == 3){
		//Restricted to System Administrator (SA) Role, IT
		?>
		<li>
			<a href="vendors">
				<i class="ion-ios-people" style="color:mediumblue; font-size:22px; font-weight:bold"></i>
				<span class="menu-title">Vendor Managment</span>
			</a>
		</li> <br/>
		<?php } ?>

<?php 
if($current_user['role_id'] == 1 || $current_user['role_id'] == 11){
//Restricted to System Administrator (SA) Role, IT
?>
		<li>
			<a href="departments">
				<i class="demo-pli-building" style="color:brown; font-size:17px; font-weight:bold"></i>
				<span class="menu-title">Departments</span>
			</a>
		</li>
		<br/>

		<li>
			<a href="users">
				<i class="demo-psi-male-female" style="color:darkmagenta; font-size:17px; font-weight:bold"></i>
				<span class="menu-title">User Management</span>
			</a>
		</li>

		<br/>

		<li>
			<a href="#">
			<i class="ion-settings" style="color:green; font-size:20px; font-weight:bold"></i> 
				<span class="menu-title">System Management</span>
				<i class="arrow"></i>
			</a>

			<ul class="collapse">
				<li><a href="system_management/thresholds">Threshold Management</a></li>
			</ul>
		</li>

<?php  } //End System Administrator Roless
?>
	</ul>


	<!--Widget-->
	<!--================================-->
	<div class="mainnav-widget">

		<!-- Show the button on collapsed navigation -->
		<div class="show-small">
			<a href="#" data-toggle="menu-widget" data-target="#demo-wg-server">
				<i class="demo-pli-monitor-2"></i>
			</a>
		</div>

		<!-- Hide the content on collapsed navigation -->
		<div id="demo-wg-server" class="hide-small mainnav-widget-content">
			<ul class="list-group">
				<li class="pad-ver"><a href="logout" class="btn btn-danger btn-bock"><i class="ion-lock-combination"></i> Logout</a></li>
			</ul>
		</div>
	</div>
	<!--================================-->
	<!--End widget-->

</div>
</div>
</div>
<!--================================-->
<!--End menu-->

</div>
</nav>
<!--===================================================-->
<!--END MAIN NAVIGATION-->


<div class="boxed">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<div class="text-center">
<h3>Welcome to your Dashboard.</h3>
</p1></div>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
<div class="col-xs-12">
	<div class="panel">
		<div class="panel-heading">
			<h3 class="panel-title">PENDING TASKS</h3>
		</div>

		<!--Data Table-->
		<!--===================================================-->
		<div class="panel-body">
			<div class="table-responsive">
				<table id="demo-dt-addrow"  class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Module</th>
							<th>Description</th>
							<th>Date Received</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					//Tender Reviews by SMT
					$smt_tender_reviews = DB::query('SELECT * from tenders where status=%s', 2);
					if(restrict_smt($current_user['role_id'])){
						foreach($smt_tender_reviews as $review){
					?>
						<tr>
							<td style="color:green;">Tender Review # <?php echo $review['tender_id']?></td>
							<td class="text-info text-lg text-bold"><?php echo $review['tender_title']?></td>
							<td><span class="text-muted"><?php echo date_format(date_create($review['date_created']), 'd/m/Y H:i')?></span></td>
							<td class="text-center">
							<a href="tenders/<?php echo $review['tender_id']."-review";?>" class="label label-table label-info">Review Task</a>
							</td>
						</tr>
						<?php } }?>
					<?php 
					//Get Assigned Requisitions
					$requests = DB::query('SELECT * from requisition_assign where user_id=%s AND status=%s', $current_user['user_id'], 'Active');
					foreach($requests as $request){
						$tender = DB::queryFirstRow('SELECT * from tenders where requisition_id=%s', $request['requisition_id']);
						$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $request['requisition_id']);
					if(!$tender){ //No Tender Yet, then create the tender
					?>
					<tr>
						<td style="color:green;">Tender Preparation # <?php echo $requisition['requisition_number']?></td>
						<td class="text-info text-lg text-bold"><?php echo $requisition['requisition_name']?></td>
						<td><span class="text-muted"><?php echo date_format(date_create($request['date_assigned']), 'd/m/Y H:i')?></span></td>
						<td class="text-center">
						<a href="tenders/<?php echo $requisition['requisition_number']."-create";?>" class="label label-table label-info">Create Tender</a>
						</td>
					</tr>
					<?php
					}	//No Tender Yet, then create the tender
					if($tender['status'] == 1 || $tender['status'] == 3){
						if($tender['status'] == 1){ 
							$module = "Draft Tender Preparation";
							$action = "Resume Edit";
							$style = "color:green;";
						}
						elseif($tender['status'] == 3){
							$module = "Tender -Rejected";
							$action = "Edit Details";
							$style = "color:red;";
						}
					?>
					<tr>
						<td style="<?php echo $style;?>"><?php echo $module;?> # <?php echo $tender['tender_id']?></td>
						<td class="text-info text-lg text-bold"><?php echo $tender['tender_title']?></td>
						<td><span class="text-muted"><?php echo date_format(date_create($request['date_assigned']), 'd/m/Y H:i')?></span></td>
						<td class="text-center">
						<a href="tenders/<?php echo $tender['tender_id']."-edit";?>" class="label label-table label-info"><?php echo $action;?></a>
						</td>
					</tr>
					<?php
						}
					}
					//Review Vendor Applications
					$reviews = DB::query('SELECT * from vendor_reviewers where user_id=%s AND status=%s order by date_added', $current_user['user_id'], 'Pending');
					foreach($reviews as $review){
						$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $review['vendor_id']);
						
					?>	
					<tr>
						<td style="color:blue">Vendor Review # <?php echo $review['vendor_id']?></td>
						<td class="text-info text-lg text-bold"><?php echo $vendor['vendor_name']?></td>
						<td><span class="text-muted"><?php echo date_format(date_create($review['date_added']), 'd/m/Y H:i')?></span></td>
						<td class="text-center">
						<a href="vendors/<?php echo $review['vendor_id']."-review";?>" class="label label-table label-info">Review</a>
						</td>
					</tr>

					<?php } ?>
					</tbody>
				</table>
			</div>
			<hr class="new-section-xs">
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
