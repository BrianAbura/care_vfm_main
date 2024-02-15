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
    <title>Tender View | Care Uganda</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include $DIR."/headers.php"; 
	?> 
    <style>
	.control-label{
		font-weight:bold;
        color: #139cdb;
	}
    .box-block{
        font-size: 12px;
    }
    .blog-body{
        margin-top: 10px;
        font-size: 15px;
    }
    .prices{
		border: none;
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
					<li><a href="#"><i class="ion-search icon-lg"></i></a></li>
					<li class="active">View Tender Application</li>
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
									<li class="active"><a href="submitted" class="list-group-item"> Tenders Submitted</a></li>
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


<?php 
$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $_SESSION['user_id']);
$vendor_id = $vendor['vendor_id'];

$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_application = DB::queryFirstRow('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $tender_id, $vendor_id);
if(!isset($tender_application))
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
					The Record your are trying to access does not exist. Click <a href="../tenders" class="alert-link">Here.</a> to go back to the list of tenders.
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
}
else{
    $tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
    $category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $tender['category']);
    $proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);

?>
<div id="page-content">
        <div class="fixed-fluid">
            <div class="fixed-sm-250 pull-sm-right" style="background:#ecf0f5">
                <p class="pad-hor mar-top text-main text-bold text-sm text-uppercase">Information Section</p>
                <div class="list-group bg-trans pad-btm bord-btm">
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $category['name'];?></a><small class="box-block">Category</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $proc_method['method_name'];?></a><small class="box-block">Procurement Method</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo $tender['location'];?></a><small class="box-block">Location/Site</small></div>
                    <div class="list-group-item list-item-sm"><a href="#" class="btn-link"><?php echo date_format(date_create($tender['submission_date']), 'd M Y')." ".date_format(date_create($tender['submission_date']), 'h:i A');?></a><small class="box-block">Bid Submission Deadline</small></div>

                </div>
                <div class="Option Details">
                <?php 
                $curDate = date('Y-m-d H:i:s');
                if($curDate <= $tender['submission_date']){
                ?>	
                <a href="<?php echo $tender_id."-draft"?>" class="btn btn-mint btn-sm">Resume Application</a>
                <button type="submit" id="withdraw_bid" data-id="<?php echo $tender_id;?>" class="btn btn-danger btn-sm">Withdraw Bid</button>
                <?php } ?>
                <hr>
            </div>
            </div>
            <div class="fluid">
            <div class="blog blog-list">
            <div class="panel">
                <div class="blog-content">
                <table class="table table-bordered table-striped pad-ver mar-no">
                    <tbody>
                    <tr>
                    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="4"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
                    </tr>
                         <!--Preliminary Criteria-->
                    <?php 
                    $pre_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 1);
                    if($pre_evals){
                    ?>
                    <td class="text-bold text-lg" style="color:#FF8C00" colspan="4">Preliminary Criteria</td>
                        <?php
                        $cnt = 1;
                        foreach($pre_evals as $pre_eval){
                            $response = DB::queryFirstRow('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s AND stage=%i AND criteria_id=%d', $tender_id, $vendor_id, 1, $pre_eval['id']);
                            $attachment =  str_replace($BASEPATH, '..', $response['resp_attachment']);
                        ?>
                        <tr>
                        <th rowspan="3" class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;"><?php echo $cnt;?></th>
                        <th>Criteria:</th>
                        <td class="text-dark text-lg text-bold"><?php echo $pre_eval['criteria_description'];?></td>
                        </tr>
                        <tr>
                        <th style="width:15%">Response:</th>
                        <td class="text-info text-semibold"><?php echo $response['vendor_response']; ?></td>
                        </tr>
                        <tr>
                        <th>Support Document:</th>
                        <td>
                            <?php 
                            if($attachment != null){
                            ?>
                            <a href="<?php echo $attachment;?>" target="_blank" class="btn-link"><i class="fa fa-cloud-download icon-2x icon-fw"></i>view_criteria_file</a><br/>
                            <?php } ?>
                        </td>
                        </tr>
                        <?php
                        $cnt++;
                            }
                      } //End Preliminary?>
    
                        <!--Techical Criteria-->
                    <?php 
                    $tech_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 2);
                    if($tech_evals){
                    ?>
                        <td class="text-bold text-lg" style="color:#20B2AA" colspan="4">Technical Criteria</td>
                        <?php
                        $cnt = 1;
                        foreach($tech_evals as $tech_eval){
                            $response = DB::queryFirstRow('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s AND stage=%i AND criteria_id=%d', $tender_id, $vendor_id, 2, $tech_eval['id']);
                            $attachment =  str_replace($BASEPATH, '..', $response['resp_attachment']);
                        ?>
                        <tr>
                        <th rowspan="3" class="text-center" style="vertical-align:middle;border-left:5px solid #20B2AA;"><?php echo $cnt;?></th>
                        <th>Criteria:</th>
                        <td class="text-dark text-lg text-bold"><?php echo $tech_eval['criteria_description'];?></td>
                        </tr>
                        <tr>
                        <th style="width:15%">Response:</th>
                        <td class="text-info text-semibold"><?php echo $response['vendor_response']; ?></td>
                        </tr>
                        <tr>
                        <th>Support Document:</th>
                        <td>
                            <?php 
                            if($attachment != null){
                            ?>
                           <a href="<?php echo $attachment;?>" target="_blank" class="btn-link"><i class="fa fa-cloud-download icon-2x icon-fw"></i>view_criteria_file</a><br/>
                            <?php } ?>
                        </td>
                        </tr>
                        <?php
                        $cnt++;
                            }
                      } //End Preliminary?>
                </tbody>
             </table>
                        <!--Financials Criteria-->
             <table id="requisition_item_table" class="table table-bordered table-striped pad-ver mar-no">
                <tbody>
                    <?php 
                    $cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
                    $cur = $cur['cur'];
                    $requests = DB::query('SELECT * from requisition_items where requisition_number=%s',$tender['requisition_id']);
                    if($requests){
                    ?>
                        <td class="text-bold text-lg" style="color:#3CB371" colspan="6">Financials/Price Schedule</td>
                        <tr style="vertical-align:middle;border-left:5px solid #3CB371;">
                        <th>No.</th>
                        <th>Items Description</th>
                        <th>Unit of Measure</th>
                        <th>Quantity</th>
                        <th>Unit Price (<?php echo $cur;?>)</th>
                        <th>Total Price (<?php echo $cur;?>)</th>
                        </tr>
                        <?php
                        $cnt = 1;
                        $sub_total = 0;
                        $grand_total = 0;
                        $total_vat = 0;
                        $vat = 0;
                        foreach($requests as $request){
                            $amt_resp = DB::queryFirstRow('SELECT * from tender_finance_app where tender_id=%s AND vendor_id=%s AND stage=%i AND requisition_id=%d', $tender_id, $vendor_id, 3, $request['id']);
                            $unit_price = $amt_resp['vendor_response'];
                            $total = $unit_price * $request['quantity'];
                            $vat = $amt_resp['vat'];
                        ?>
                        <tr style="border-left:5px solid #3CB371;">
                        <td sclass="text-center" tyle="vertical-align:middle;"><?php echo $cnt;;?></td>
                        <td class="text-dark"><?php echo $request['description'];?></td>
                        <td class="text-dark"><?php echo $request['unit_of_measure'];?></td>
                        <td class="text-dark"><?php echo $request['quantity'];?></td>
                        <td class="text-info text-semibold text-lg"><?php echo number_format($unit_price);?></td>
                        <td class="text-info text-semibold text-lg"><?php echo number_format($total);?></td>
                        </tr>
                        <?php
                            $sub_total += $total;
                            $cnt++;
                            } 
                        if($vat != 0){
                            $total_vat = $sub_total * ($vat/100);
                            $grand_total = $sub_total + $total_vat;
                        }
                        else{
                            $grand_total = $sub_total;
                        }
                        ?>
                                <!-- SUb Total-->
                            <tr>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td><h5 class="text-center">Sub Total</h5></td>
                            <td class="text-info text-semibold text-lg"><?php echo number_format($sub_total);?></td>
                            </tr>
                                <!-- SUb Total-->
                            <tr>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td><h5 class="text-center">VAT:</h5></td>
                            <td class="text-info text-semibold text-lg"><?php echo number_format($total_vat) ." (".$vat."%)";?></td>
                            </tr>

                                <!-- Grand Total-->
                            <tr>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td style="border:none"></td><td style="border:none"></td>
                            <td><h4 class="text-center">Grand Total</h4></td>
                            <td class="text-info text-bold text-2x"><?php echo number_format($grand_total);?></td>
                            </tr>

                     <?php  } //End Financials?>

                </tbody>
             </table>


            
                </div>
                
                <br/>   

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
    <script>
//Confirm Delete
$('#withdraw_bid').on('click', function(){
var tender_id = $(this).data("id");
	
bootbox.confirm({
    message : "<br/><h5 class='text-semibold text-danger'>Please note that your bid will not be considered for evaluation and all the submitted details will be erased. <br/><br/> Are you sure you want to withdraw your bid? </h5>",buttons: {
        confirm: {
            label: "Yes, Withdraw Bid",
            className: 'btn-mint'
        },
        cancel:{
           label: "No, Cancel", 
           className: 'btn-danger'
        }
    },
    callback : function(result) {
    if(result){
        $.ajax({
        type : 'post',
        url : 'TenderWithdraw.php',
        data :  {token: 'withdraw_tender', tender_id: tender_id},
        success : function(data){
            var result = JSON.parse(data);
            if (result.Status == "Success") {
                $.niftyNoty({
                    type: 'success',
                    icon : 'pli-like-2 icon-2x',
                    message : result.Message,
                    container : 'floating',
                    timer : 4000
                });
            }else{
                $.niftyNoty({
                    type: 'danger',
                    icon : 'pli-cross icon-2x',
                    message : result.Message,
                    container : 'floating',
                });
            };
            setTimeout(function(){ window.location = "../tenders"; }, 3500);
        }
        });
    } 
    }
});
});
</script>
</body>
</html>