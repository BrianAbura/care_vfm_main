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
$quotes = DB::query('SELECT * from evaluation_summary where tender_id=%s AND decision=%s AND vendor_id=%s AND stage=%s', $tender_id, "Responsive", $vendor_id, 2);

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
<form id="demo-bv-bsc-tabs" action="submit_financials" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="evaluate_financials_bid">
<input type="hidden" name="tender_id" value="<?php echo $tender_id; ?>">
<input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no" id="financials_table">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="6"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <tr>
    <td class="text-bold text-uppercase text-lg text-info" colspan="4"><small class="text-dark text-normal">vendor:: </small><?php echo $vendor['vendor_name'];?></td>
    </tr>
            <!--Financials Criteria-->
    <?php 
    ?>
    <td class="text-bold text-lg" style="color:#3CB371" colspan="6">Financials/Price Schedule</td>
         <?php 
        $cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
        $cur = $cur['cur'];
        $requests = DB::query('SELECT * from requisition_items where requisition_number=%s',$tender['requisition_id']);
        if($requests){
        ?>
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
            <td class="text-info text-semibold text-lg"><?php echo number_format($sub_total);?>
            <input type="hidden" id="sub_total" name="cur_sub_total" value="<?php echo $sub_total;?>">
            </td>
            </tr>
                <!-- SUb Total-->
            <tr>
            <td style="border:none"></td><td style="border:none"></td>
            <td style="border:none"></td><td style="border:none"></td>
            <td><h5 class="text-center">VAT:</h5></td>
            <td class="text-info text-semibold text-lg"><?php echo number_format($total_vat) ." (".$vat."%)";?>
            </td>
            </tr>

                <!-- Grand Total-->
            <tr>
            <td style="border:none"></td><td style="border:none"></td>
            <td style="border:none"></td><td style="border:none"></td>
            <td><h5 class="text-center">Grand Total</h5></td>
            <td class="text-info text-bold text-lg"><?php echo $cur." ".number_format($grand_total);?>
            <input type="hidden" id="grand_total" value="<?php echo $grand_total;?>">
            </td>
            </tr>

            <!-- Corrections on VAT Content-->
            <?php 
            //Incase of saved Data
            $corrected_vat = "";
            $fin_save_eval = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND status=%s AND user_id=%s AND vendor_id=%s',
            $tender_id, 3, 1, $current_user['user_id'], $vendor_id);
            
            if($fin_save_eval){
                $corrected_vat = $fin_save_eval['eval_vat'];
            }
            
            ?>
            <tr>
            <td><h5 class="text-center">Corrected VAT %</h5></td>
            <td>
                <input type="hidden" name="cur_vat" value="<?php echo $vat;?>"/>
                <input type="number" max="100" style="width:50%" class="form-control items_vat" id="corrected_vat" name="corrected_vat" value="<?php echo $corrected_vat;?>"/>
            </td>
            </tr>

            <tr>
            <td><h5 class="text-center">Evaluated Total (<?php echo $cur;?>)</h5></td>
            <td><input type="text"style="width:50%" class="form-control prices text-lg text-bold text-primary" id="evaluted_total" value="<?php echo number_format($grand_total);?>" name="evaluted_total"/></td>
            </tr>

            <tr>
            <td><h5 class="text-center">Supporting Evaluation Document</h5></td>
            <td>
            <div class="form-group col-md-12">
                <div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="input-append">
					<div class="uneditable-input">
					<span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
					</div>
					<span class="btn btn-default btn-file">
					<span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
					<span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
					<input type="file" id="evaluation_doc_upload" name="evaluation_doc_upload" onchange="ValidateSingleInput(this);"/>
					</span>
					<a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
					<p class="help-block text-success">Accepted Formats: .pdf, .xlsx or .xls only</p>
				</div>
				</div>
                </div>
            </td>
            </tr>

        <?php  } //End Financials?>
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
     //Image Validation
var _validLogoExtensions = [".pdf", ".xlsx", ".xls"];   
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validLogoExtensions.length; j++) {
                var sCurExtension = _validLogoExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                $.niftyNoty({
                    type: 'danger',
                    container : 'floating',
                    title : 'Error!',
                    message : 'Sorry, the file type is invalid, allowed file extensions are: pdf, xlsx and xls',
                    closeBtn : true,
                    timer : 7000,
                });
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
</script>

<script>
//Tender Notice Tab
$('#demo-mail-compose').summernote('disable');
</script>

</body>
</html>
