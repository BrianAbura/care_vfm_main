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
<li class="active text-lg">Post-Qualification</li>
</ol>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
<form id="demo-bv-bsc-tabs" action="postqn" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="add_postqn">
<input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <td class="text-bold text-lg" style="color:#FF8C00" >Upload Post-Qualification Report</td>
</tbody>
</table>
<br/>
<?php 
/**
 * Post-Qualification Will only be done for the Vendors that were successfull upto Finacials
 * Consider: Summary of Financials
 * 
 */
$get_vendors = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d', $tender_id, 3);
foreach($get_vendors as $get_vendor){
$name = DB::queryFirstRow('SELECT vendor_name from vendors where vendor_id=%s', $get_vendor['vendor_id']);
?>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
            <label></label>
            <h4 class="text-center text-bold text-primary"><?php echo $name['vendor_name']?></h4>
            <input type="hidden" name="vendor_id[]" value="<?php echo $get_vendor['vendor_id']; ?>"/>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
            <label class="control-label">Comments</label>
            <textarea rows="3" class="form-control" name="postqn_narration[]"></textarea>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
            <label class="control-label">Post-Qualification Report</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="input-append">
                <div class="uneditable-input">
                <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                </div>
                <span class="btn btn-default btn-file">
                <span class="btn btn-xs btn-mint fa fa-edit fileupload-exists" title="Change Attachment"> Edit</span>
                <span class="fileupload-new btn btn-xs btn-primary fa fa-upload"> Select file</span>
                <input type="file" id="InputReceiptImg" name="postqn_report[]" onchange="ValidateDocs(this);"/>
                </span>
                <a href="#" class="btn btn-xs btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"> Remove</a>
                <small class="help-block text-primary">Accepted Formats: doc, docx and pdf</small>
            </div>
            </div>
            </div>
        </div>
    </div>
<?php } ?>

</div>
  
<div class="blog-footer">
<div class="tab-footer clearfix">	
<a href="javascript:history.back()" class="btn btn-danger">Cancel</a>	
<button type="submit" id="btnCreate" name="formBtn" value="submitDetails" class="btn btn-success pull-right">Save Record</button>
</div>
</div> 
</form>
</div>

<?php 
$query_reports = DB::query('SELECT * from post_qualification where tender_id=%s', $tender_id);
if($query_reports){
?>
<!--Uploaded Post-Qualification Reports -->
<div class="panel">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no">
<h5 class="text-bold text-lg text-mint">Post-Qualification Reports</h5>
<thead>
    <tr>
    <th>#</th>
    <th>Vendor</th>
    <th>Comments</th>
    <th>Post-Qualification Report</th>
    <th>Date Added</th>
    <th>Action</th>
    </tr>
	</thead>
    <tbody>
    <?php
    $cnt = 1;
    foreach($query_reports as $post_report){
        $name = DB::queryFirstRow('SELECT vendor_name from vendors where vendor_id=%s', $post_report['vendor_id']);
        $report_file =  str_replace($BASEPATH, '..', $post_report['report_file']);
    ?>
    <tr>
    <td><?php echo $cnt; ?></td>
    <td class="text-lg"><?php echo $name['vendor_name']; ?></td> 
    <td class="text-md"><?php echo $post_report['narration']; ?></td> 
    <td>
        <?php 
        if($report_file != null){
        ?>
        <a href="<?php echo $report_file;?>" target="_blank" class="btn-link"><i class="fa fa-cloud-download icon-2x icon-fw"></i>View Report</a>
        <?php  } 
        else{
            echo "REPORT NOT AVAILABLE";
        }
        ?>
    </td> 
    <td><?php echo date_format(date_create($post_report['date_added']), 'd/m/Y'); ?></td>
    <td><button data-id="<?php echo $post_report['id']?>" class="btn btn-danger text-bold btn-sm del_report"> Delete</button></td>
    </tr>
    <?php } ?>    
    </tbody>
</table>
</div>
</div> 
<!--END Uploaded Post-Qualification Reports -->
<?php } ?>

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
  var _validLogoExtensions = [".doc", ".docx", ".pdf"];   
function ValidateDocs(oInput) {
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
                    message : 'Sorry, the file type is invalid, accepted file formats are: doc, docx and pdf',
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
    //Confirm Delete
$('.del_report').on('click', function(){
	var postqn_id = $(this).data("id");
	bootbox.dialog({
	//title: "Create New Department",
message : "<br/><h4 style='color:#FF8C00'>Are you sure you want to delete this post-qualification report? </h4>",
buttons: {
    success: {
    label: "Confirm",
    className: "btn-primary",
    callback : function(result) {
$.ajax({
    type : 'post',
    url : 'postqn',
    data :  'postqn_id='+ postqn_id+"&token=del_postqn", 
    success : function(data){
    var result = JSON.parse(data);
    if (result.Status == "Success") {
        $.niftyNoty({
            type: 'success',
            icon : 'pli-like-2 icon-2x',
            message : result.Message,
            container : 'floating',
            timer : 7000
        });
    }else{
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-cross icon-2x',
            message : result.Message,
            container : 'floating',
        });
    };
    setTimeout(function(){ window.location.reload(1); }, 1000);
    }
    });
    }
    }
	}
});
});
</script>

</body>
</html>
