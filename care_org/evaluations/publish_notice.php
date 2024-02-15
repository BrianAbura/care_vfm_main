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
.td_vendor_name{
    color: blue;
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
<li class="active text-lg">Publish Notice</li>
</ol>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(( isset( $_REQUEST['tender'] ) )?  $_REQUEST['tender']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 5, $curDate);
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


<div class="blog blog-list">
<div class="panel">
<div class="blog-content">
<form id="demo-bv-bsc-tabs" action="complete_publish" method="POST" enctype="multipart/form-data">
<input type="hidden" name="tender_id" value="<?php echo $tender_id; ?>"/>
<input type="hidden" name="token" value="publish_notice"/>
<table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <td class="text-bold text-uppercase text-lg text-primary">Publish Evaluation Notice</td>
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
        <input type="hidden" name="beb_id" value="<?php echo $best_vendor['vendor_id']; ?>"/>
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
    /**
     *     Eliminations at Financials
     * 
     */
    $financials = $fin_rank; //Iterate and eliminate the BEB first
   for($i = 1; $i < (sizeof($financials)); $i++)
   {
    $vendor_id = $financials[$i]['vendor_id'];
    $vendor_name = $financials[$i]['vendor_name'];
    ?>
    <tr>
        <td class="text-lg td_vendor_name text-center" width="35%"><?php echo $vendor_name;?></td>
        <td style="color:#3CB371" class="text-semibold text-uppercase">Financial Evaluation</td>
        <td>Bid Price Higher than the Best Evaluted</td>
        <input type="hidden" name="vendor_id[]" value="<?php echo $vendor_id; ?>"/>
        <input type="hidden" name="stage[]" value="3"/>
        <input type="hidden" name="narration[]" value="Bid Price Higher than the Best Evaluted"/>
    </tr>
    <?php
    }
    /**
     *     Eliminations at Technicals
     * 
     */
    $technicals = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d order by id desc', $tender_id, 2);
    foreach($technicals as $technical){
    if($technical['decision'] == 'Responsive'){
        continue;
    }
    $eliminated_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $technical['vendor_id']);
    $stage = "Technical Evaluation";
    ?>
    <tr>
        <td class="text-lg td_vendor_name text-center" width="35%"><?php echo $eliminated_vendor['vendor_name'];?></td>
        <td style="color:#20B2AA" class="text-semibold text-uppercase"><?php echo $stage;?></td>
        <td><?php echo $technical['narration'];?></td>
        <input type="hidden" name="vendor_id[]" value="<?php echo $eliminated_vendor['vendor_id']; ?>"/>
        <input type="hidden" name="stage[]" value="2"/>
        <input type="hidden" name="narration[]" value="<?php echo $technical['narration']; ?>"/>
    </tr>
    <?php  
    }
    /**
     *     Eliminations at Preliminary
     * 
     */
    $prems = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d order by id desc', $tender_id, 1);
    foreach($prems as $prem){
    if($prem['decision'] == 'Compliant'){
        continue;
    }
    $eliminated_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $prem['vendor_id']);
    $stage = "Preliminary Evaluation";
    ?>
    <tr>
        <td class="text-lg td_vendor_name text-center" width="35%"><?php echo $eliminated_vendor['vendor_name'];?></td>
        <td style="color:#FF8C00" class="text-semibold text-uppercase"><?php echo $stage;?></td>
        <td><?php echo $prem['narration'];?></td>
        <input type="hidden" name="vendor_id[]" value="<?php echo $eliminated_vendor['vendor_id']; ?>"/>
        <input type="hidden" name="stage[]" value="1"/>
        <input type="hidden" name="narration[]" value="<?php echo $prem['narration']; ?>"/>
    </tr>
    <?php  }?>
</tbody>
</table>

<div class="blog-footer">
<div class="tab-footer clearfix">	
<button type="submit" id="btnPublish" class="btn btn-success pull-right">Publish Notice</button>

</div>
</div>
</form>

</div>
</div>
</div>
<?php 
} //End Requisition Query || Record found
?>
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
<script>
    //Confirm Delete
$('#btnPublish').on('click', function(){
    $('#btnPublish').prop('disabled', true);
        $("#demo-bv-bsc-tabs").submit(function(e) {
        e.preventDefault(); 
        var form = $(this).serializeArray();
            $.ajax({
                type: "POST",
                url: "complete_publish",
                contentType: 'application/x-www-form-urlencoded',
                data: $.param(form),
                success: function(data)
                {
                    var result = JSON.parse(data);
                    if (result.Status == "Success") {
                        $.niftyNoty({
                            type: 'success',
                            icon : 'pli-like-2 icon-2x',
                            message : result.Message,
                            container : 'floating',
                            timer : 5000
                        });
                    }else{
                        $.niftyNoty({
                            type: 'danger',
                            icon : 'pli-cross icon-2x',
                            message : result.Message,
                            container : 'floating',
                            timer : 5000
                        });
                    };
                    setTimeout(function(){ window.location = "completed_evaluations"; }, 5000);
                }
            });
        });
});
</script>
</body>
</html>