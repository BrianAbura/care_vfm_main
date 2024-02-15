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
    <title>Tenders | Care Uganda</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include $DIR."/headers.php"; 
	?> 
    <style>
	.control-label{
		font-weight:bold;
        color: #139cdb;
	}
    #demo-bv-bsc-tabs hr{
        background-color: rgb(133, 206, 249, 0.3);
        border: 0 none;
        height: 6px;
    }
</style>
</head>
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-sm page-fixedbar">
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
					
                        <div class="brand-title">
                            <span class="brand-text">Care</span>
                        </div>
                    </a>
                </div>
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">

                                    <i class="ion-person icon-lg"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                            <ul class="head-list">
                                    <li>
                                    <a href="../profile"><i class="demo-pli-gear icon-lg icon-fw"></i> Account Settings</a>
                                    </li>
                                    <li>
                                        <a href="../logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#">
                                <i class="demo-pli-dot-vertical"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">
            <div id="content-container">
                <div id="page-head">
                    <div id="page-title">
					<h3 class="text-overflow text-uppercase">Public Tenders</h3>
                    </div>
                </div>
                <div class="page-fixedbar-container">
                    <div class="page-fixedbar-content">
					<span class="pad-ver text-main text-sm text-uppercase text-bold"><img src="..\img\care-int-logo.png" alt="Care-International" width="80%"></span>
                        <div class="nano">
                            <div class="nano-content">
							<hr class="new-section-xs">
                            <div class="panel">
                            <div class="panel-body text-center">
                                <?php 
                                $profile = "..\\img\\5.png";
                                $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $current_user['user_id']);
                                if($vendor){
                                    $attachment = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description IN %ls', $vendor['vendor_id'], ['Logo','Profile']);
                                    if(!empty($attachment['document_file'])){
                                        $profile =  str_replace($BASEPATH, '..', $attachment['document_file']);
                                    }
                                }
                                ?>
                                <img alt="Profile Picture" class="img-lg img-circle mar-btm" src="<?php echo $profile;?>">
                                <p class="text-lg text-semibold mar-no text-main"><?php echo $current_user['first_name']." ".$current_user['last_name'];?></p>
                                <p class="text-muted"><?php echo $current_user['email_address'];?></p>
                            </div>
                        </div>
                                <p class="pad-all text-main text-lg text-uppercase text-bold">Navigation</p>
                                <div class="list-group bg-trans">
                                    <a class="list-group-item active"><i class="ti-receipt icon-lg icon-fw"></i> Tenders</a>
									<ul class="breadcrumb">
									<li class="active"><a href="" class="list-group-item"> Current Tenders</a></li>
									<li><a href="submitted" class="list-group-item"> Tenders Submitted</a></li>
									<li><a href="closed" class="list-group-item">Closed Tenders</a></li>
									</ul>
						            <a href="../evaluations" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                                    <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="page-content">
					<div class="panel">
					    <div class="panel-body">
						<div class="tab-base">
					
					<!--Nav tabs-->
					<ul class="nav nav-tabs">
						<li><span><a href="" style="color:white"><button class="btn btn-dark">Public Tenders</button></a></span></li>
						<li> &nbsp;&nbsp;&nbsp;</li>
                        <li><span><a href="shortlisted" style="color:white"><button class="btn btn-info">Shortlisted Tenders</button></a></span></li>
					</ul>
		
					<!--View Public Tenders-->
					<div class="tab-content">
						<div id="demo-ico-lft-tab-1" class="tab-pane active  in">
						<div class="row">
							<div class="table-responsive">
							<?php
                             //Competitive Sealed Quotations and Published
                            $curDate = date('Y-m-d H:i:s');
                            $pub_tenders = DB::query('SELECT * from tenders where solicitation_method=%s AND  status=%s AND submission_date>=%s order by submission_date', 3, 5, $curDate); 
							if(!$pub_tenders){
							?>
							<div class="alert alert-mint col-md-7">
								<strong> There are currently no Public Tenders available. Please check again later.</strong>
							</div>
							<?php }
							else{
							?>
								<table id="demo-dt-basic" class="table table-bordered">
								<thead>
									<tr>
										<th class="min-w-td">#</th>
										<th>Reference Number</th>
										<th>Title</th>
										<th>Category</th>
										<th>Submission Deadline</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$cnt = 1;
									foreach($pub_tenders as $pub_tender){
										$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $pub_tender['category']);
									?>
								<tr>
								<td class="text-dark text-bold"><?php echo $cnt;?></td>
								<td class="text-dark text-bold"><?php echo $pub_tender['tender_id'];?></td>
								<td class="text-lg text-info text-bold"><?php echo $pub_tender['tender_title'];?></td>
								<td class="text-dark text-lg"><?php echo $category['name'];?></td>
								<td class="text-dark text-lg"><?php echo date_format(date_create($pub_tender['submission_date']), 'd/m/Y')." ".date_format(date_create($pub_tender['submission_date']), 'h:i A'); ?></td>
								<td class="text-center">
									<div class="btn-group">
									<a class="btn btn-md btn-mint" href="<?php echo $pub_tender['tender_id']."-view"?>"> View Details</a>
									</div>
								</td>
								</tr>
									<?php 
									$cnt++;
									} ?>
								</tbody>
								</table>
								<?php } ?>
					                </div>
							</div>
						</div>
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
            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">
                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">
                                <ul id="mainnav-menu" class="list-group">
						            <!--Menu list item-->
						            <li class="active-sub">
						                <a href="../home">
						                    <i class="demo-pli-home"></i>
						                    <span class="menu-title">Dashboard</span>
											<i class="arrow"></i>
						                </a>
						            </li>
									<li>
						                <a href="../logout">
						                    <i class="demo-pli-unlock icon-lg icon-fw"></i>
						                    <span class="menu-title">Logout</span>
											<i class="arrow"></i>
						                </a>
						            </li>
                            	 </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
        </div>
		<?php 
    /*** Include the Global Footer and Java Scripts */
	include $DIR."/footers.php";  
    ?>
<script>
$('#demo-dt-basic').dataTable( {
"responsive": true,
"language": {
"paginate": {
	"previous": '<i class="demo-psi-arrow-left"></i>',
	"next": '<i class="demo-psi-arrow-right"></i>'
}
},
"dom": '<"newtoolbar">frtip'
} );
</script>
</body>
</html>