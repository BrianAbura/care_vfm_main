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

			<div class="tab-base">
					
					<!--Nav tabs-->
					<ul class="nav nav-tabs">
						<li><span><a href="../vendors" style="color:white"><button class="btn btn-success">Active </button></a></span></li>
						<li> &nbsp;&nbsp;&nbsp;</li>
                        <li><span><a href="pending" style="color:white"><button class="btn btn-info">Pending Approval</button></a></span></li>
						<li> &nbsp;&nbsp;&nbsp;</li>
						<li><span><a href="on_hold" style="color:white"><button class="btn btn-warning">On-Hold</button></a></span></li>
						<li> &nbsp;&nbsp;&nbsp;</li>
                        <li><span><a href="rejected" style="color:white"><button class="btn btn-danger">Rejected</button></a></span></li>
					</ul>
					<br/>
		
					<!--View Full Profile-->
					<div class="tab-content">
						<div id="demo-ico-lft-tab-1" class="tab-pane active in">
						<div class="table-responsive">
									<table id="active_vendors_table" class="table table-vcenter mar-top" width="100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Vendor Name</th>
												<th>Vendor Type</th>
												<th>Registration Number</th>
												<th>Status</th>
												<th>Date Submitted</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
										<?php 
										$cnt = 1;
										$vendors = DB::query('SELECT * from vendors where vendor_status=%s order by id desc',2);
										foreach($vendors as $vendor){
										?>
										<tr>
										<td><?php echo $cnt; ?></td>
										<td><a class="btn-link text-bold text-lg" href="<?php echo $vendor['vendor_id']."-view"; ?>"><?php echo $vendor['vendor_name']; ?></a></td>
										<td class="text-semibold"><?php echo $vendor['vendor_type']; ?></td>
										<td><?php echo $vendor['registration_num']; ?></td>
										<td><button class="btn btn-info btn-sm text-bold">Pending Approval</button></td>
										<td><?php echo date_format(date_create($vendor['date_created']), 'd/m/Y h:i');?></td>
										<td><a class="btn btn-dark btn-sm text-bold" href="<?php echo $vendor['vendor_id']."-review"; ?>">Review</td>
											</tr>
											<?php 
											$cnt++;
											} ?>
										</tbody>
									</table>
								</div>
						</div>							
				</div>
				
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
