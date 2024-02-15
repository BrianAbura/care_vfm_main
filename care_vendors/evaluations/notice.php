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
.td_vendor_name{
    color: blue;
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
        <h3 class="text-overflow text-uppercase">Evaluation Notice</h3>
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
                        <a href="../tenders" class="list-group-item"><i class="ti-receipt icon-lg icon-fw"></i> Tenders</a>
                        <a href="../evaluations" class="list-group-item active"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                        <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>




<div id="page-content">
<?php 
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 4, $curDate);
if(!$tender)
{ //Quotes received and within the deadline
?>
<div class="row"> 
<div class="col-sm-7">
<div class="panel">
<div class="panel-body">
<br/>
<div class="alert alert-danger">
    <button class="close" data-dismiss="alert"></button>
    <strong>Warning!</strong> <br/><br/>
    The Record(s) your are trying to access does not exist. Click <a href="../evaluations" class="alert-link">Here.</a> to go back.
</div>
</div>
</div>
</div>
</div>
<?php 
} //End User Query || Null records
else{
?>
<div class="panel">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no">
<tbody>
<tr>
<td class="text-bold text-uppercase text-lg text-mint text-center active"><?php echo $tender['tender_title'];?> <br/><small>Tender ID: <?php echo "# ".$tender_id;?></small></td>
</tr>
</tbody>
</table>

<?php 
//Financial Evaluation Summary
$cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
$cur = $cur['cur'];

$fin_rank = array();
$financial_sums = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d AND status=%s order by decision', $tender_id, 3, 2);
foreach($financial_sums as $financial_sum){
    $sum_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $financial_sum['vendor_id']);
    $query_vendor= DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s', $tender_id, 3, $financial_sum['vendor_id']);
    $fin_sub_ttl = $query_vendor['cur_sub_total'];
    $fin_vat = $query_vendor['cur_vat'];
    $fin_corrected_vat = $query_vendor['eval_vat'];
    if($fin_corrected_vat){
        $fin_eval_ttl = ($fin_sub_ttl * ($fin_corrected_vat/100)) + $fin_sub_ttl;
    }
    else{
        $fin_eval_ttl = ($fin_sub_ttl * ($fin_vat/100)) + $fin_sub_ttl;
    }
    $vendor_details = array(
        'vendor_id' => $financial_sum['vendor_id'],
        'vendor_name' => $sum_vendor['vendor_name'],
        'evaluated_total' => $fin_eval_ttl
    );
    array_push($fin_rank, $vendor_details);
}
?>
<?php 
//Sort the Vendors based on their financials. 
function rank_sort($a, $b) {
return $a['evaluated_total'] > $b['evaluated_total'];
}
usort($fin_rank, "rank_sort");
$best_vendor = $fin_rank[0];
?>
<table class="table table-bordered table-striped pad-ver mar-no">
<tbody>
<tr>
    <td class="text-primary text-semibold text-lg" width="35%">Best Evaluated Vendor</td>
    <td class="text-primary text-semibold td_vendor_name text-lg" width="65%"><?php echo strtoupper($best_vendor['vendor_name']);?></td>
</tr>
<tr>
    <td class="text-primary text-semibold text-lg" width="35%">Bid Price</td>
    <td class="text-primary text-bold text-lg" width="65%"><?php echo $cur." ".number_format($best_vendor['evaluated_total']);?> <small>(VAT Included)</small></td>
</tr>
</tbody>
</table> 
<br/> 

<table class="table table-bordered table-striped pad-ver mar-no">
<tbody>
<td class="text-bold text-md text-center text-mint active" colspan="3">UNSUCCESSFUL VENDORS</td>
<tr>
    <th class="text-center">Vendor(s)</th>
    <th>Elimination Stage</th>
    <th>Reason for Elimination</th>
</tr>
<?php 

$out_vendors = DB::query('SELECT * from published_notice where tender_id=%s', $tender_id);

foreach($out_vendors as $out_vendor){
    $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $out_vendor['vendor_id']);
    $reason = $out_vendor['narration'];
    if($reason == "BEB"){
        continue;
    }
    if($out_vendor['stage'] == 1){ //Preliminary
        $stage = "Preliminary Evaluation";
        $color = "#FF8C00";
    }
    elseif($out_vendor['stage'] == 1){ //Technical
        $stage = "Technical Evaluation";
        $color = "#20B2AA";
    }
    else{ //Financial
        $stage = "Financial Evaluation";
        $color = "#3CB371";
    }
?>
<tr>
    <td class="text-lg td_vendor_name text-center" width="35%"><?php echo $vendor['vendor_name'];?></td>
    <td style="color:<?php echo $color;?>" class="text-semibold text-uppercase"><?php echo $stage;?></td>
    <td class="text-dark"><?php echo $reason;?></td>
</tr>
<?php 
}
?>
</tbody>
</table>
<hr/>
<br/>
<a class="btn btn-primary" href="../evaluations"> <i class="ti-back-left"></i> <i>Back to Notices</i></a></div>
</div>
</div>
<?php } ?>


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