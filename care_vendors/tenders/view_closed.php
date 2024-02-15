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
    <title>Tender View Closed | Care Uganda</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include $DIR."/headers.php"; 
	?> 
    <style>
	.control-label{
		font-weight:bold;
        color: #139cdb;
	}
    hr{
        background-color: rgb(133, 206, 249, 0.3);
        border: 0 none;
        height: 4px;
    }
    .box-block{
        font-size: 12px;
    }
    .blog-body{
        margin-top: 10px;
        font-size: 15px;
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
                        <h1 class="page-header text-overflow">Tenders</h1>
						<ol class="breadcrumb">
					<li><a href="#"><i class="ion-search icon-2x"></i></a></li>
					<li class="active">Closed Tender Details</li>
                    </ol>
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
									<li><a href="../tenders" class="list-group-item"> Current Tenders</a></li>
									<li><a href="submitted" class="list-group-item"> Tenders Submitted</a></li>
									<li class="active"><a href="closed" class="list-group-item">Closed Tenders</a></li>
									</ul>
						            <a href="../evaluations" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                                    <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>


<?php 
 $curDate = date('Y-m-d H:i:s');
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND submission_date<=%s AND status=%d', $tender_id, $curDate, 4);
if(!isset($tender))
{ //Display the notice below if null
?>
 <div id="page-content">
<div class="row"> 
	<div class="col-sm-7">
		<div class="panel">
			<div class="panel-body">
				<div class="alert alert-danger">
					<button class="close" data-dismiss="alert"></button>
					<strong>Warning!</strong> <br/><br/>
					The Record your are trying to access does not exist. Click <a href="closed" class="alert-link">Here.</a> to go back to the list of tenders.
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
}
else{
    $category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $tender['category']);
    $proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
    $notice = DB::queryFirstRow('SELECT * from tender_notice where tender_id=%s', $tender_id);
    $vendor_id =  get_vendor_id($current_user['user_id']);
?>
<div id="page-content">
        <div class="fixed-fluid">
            <div class="fixed-sm-350 pull-sm-right" style="background:#ecf0f5">
                <p class="pad-hor mar-top text-main text-bold text-sm text-uppercase">Information Section</p>
                <div class="list-group bg-trans pad-btm bord-btm">
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo "# ".$tender_id;?></a><small class="box-block">Reference Number</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $category['name'];?></a><small class="box-block">Category</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $proc_method['method_name'];?></a><small class="box-block">Procurement Method</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $tender['location'];?></a><small class="box-block">Location/Site</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo date_format(date_create($tender['submission_date']), 'd M Y')." ".date_format(date_create($tender['submission_date']), 'h:i A');?></a><small class="box-block">Bid Submission Deadline</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="text-bold text-danger">CLOSED</a><small class="box-block">Tender Status</small></div>
                </div>
            </div>
            <div class="fluid">
                <div class="blog blog-list">
            <div class="panel">
                <div class="blog-content">
                    <div class="blog-title media-block">
                        <div class="media-body">    
                          <h3 class="text-info"><?php echo $tender['tender_title'];?></h3>
                        </div>
                    </div>
                    <div class="blog-body text-dark">
                        <?php 
                        //Tender Notice
                        echo $notice['message'];
                        ?>
                    </div>
                </div>
            </div>
            </div>
            </div>
        </div>
</div>
<?php }?>
            </div>
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
</body>
</html>