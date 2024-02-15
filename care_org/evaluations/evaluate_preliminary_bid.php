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
<li class="active text-lg">Evaluate Bid</li>
</ol>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$vendor_id = filter_var(( isset( $_REQUEST['eval_vendor_id'] ) )?  $_REQUEST['eval_vendor_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$tender_id = filter_var(( isset( $_REQUEST['eval_tender_id'] ) )?  $_REQUEST['eval_tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender_id, 2);
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 5, $curDate);
if(empty($quotes) || !$tender)
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
$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $vendor_id);
?>
<div class="blog blog-list">
<div class="panel">
<form id="demo-bv-bsc-tabs" action="submit_evaluation" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="evaluate_preliminary_bid">
<input type="hidden" name="tender_id" value="<?php echo $tender_id; ?>">
<input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="4"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <tr>
    <td class="text-bold text-uppercase text-lg text-info" colspan="4"><small class="text-dark text-normal">vendor:: </small><?php echo $vendor['vendor_name'];?></td>
    </tr>
            <!--Preliminary Criteria-->
    <?php 
    $pre_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 1);
    if($pre_evals){
    ?>
    <td class="text-bold text-lg" style="color:#FF8C00" colspan="4">Preliminary Evaluation</td>
        <?php
        $cnt = 1;
        foreach($pre_evals as $pre_eval){
            $response = DB::queryFirstRow('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s AND stage=%i AND criteria_id=%d', $tender_id, $vendor_id, 1, $pre_eval['id']);
            $attachment =  str_replace($BASEPATH, '..', $response['resp_attachment']);
            $attachment = str_replace('/var/www/vfmplatform.com', '', $attachment);
        ?>
        <tr>
        <input type="hidden" class="form-control" name="pre_eval_id[]" value="<?php echo $response['id'];?>">
        <th rowspan="5" class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;"><?php echo $cnt;?></th>
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
        <tr>
        <th><span class="text-mint">Evaluation Decision:</span></th>
        <td>
        <select class="selectpicker" name="pre_eval_decision[]" required>
            <option></option>
            <option value="Compliant">Compliant</option>
            <option value="Non-Compliant">Non-Compliant</option>
        </select>
        </td>
        </tr>
        <tr>
        <th><span class="text-warning">Justification:</span></th>
        <td><textarea placeholder="Type Justification here.." class="form-control" name="pre_eval_justify[]"></textarea></td>
        </tr>
        <?php
        $cnt++;
            }
        } //End Preliminary?>
</tbody>
</table>
</div>
<br/>  
<div class="blog-footer">
<div class="tab-footer clearfix">	
<a href="javascript:history.back()" class="btn btn-danger">Cancel</a>
	
<button type="submit" id="btnSaveDraft" name="formAction" value="SaveDraft" class="btn btn-info">Save Draft</button>

<button type="submit" id="btnCreate" name="formAction" value="btnFinal" class="btn btn-success pull-right">Submit Final</button>
</div>
</div> 
</form>
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
//Tender Notice Tab
$('#demo-mail-compose').summernote('disable');
</script>

</body>
</html>
