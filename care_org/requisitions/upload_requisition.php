<?php 
$BASEPATH = dirname(__DIR__);
$DIR = __DIR__;

require_once($BASEPATH.'/validator.php');
if(file_exists($DIR."/DataFile_upload.xlsx")){
	unlink($DIR."/DataFile_upload.xlsx");
  }
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
<li class="active text-lg">Upload Requisition from PR System</li>
</ol>
</div>
<!--Page content-->
<!--===================================================-->
<div id="page-content">

<!-- <?php 
// $requisition_id = filter_var(trim($_GET['requisition_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
// $requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $requisition_id);
// if(!isset($requisition))
// { //Display the notice below if null
?>
<div class="row"> 
	<div class="col-sm-7">
		<div class="panel">
			<div class="panel-body">
				<br/>
				<div class="alert alert-danger">
					<button class="close" data-dismiss="alert"></button>
					<strong>Warning!</strong> <br/><br/>
					The Record your are trying to access does not exist. Click <a href="../requisitions" class="alert-link">Here.</a> to go back to the list of requisitions.
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
// } //End User Query || Null records
// else{
?> -->
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
			<form id="demo-bv-bsc-tabs" class="form-content" method="POST" action="upload_verification" enctype="multipart/form-data">
                <div class="form-group col-md-12">
                <div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="input-append">
					<div class="uneditable-input">
					<span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
					</div>
					<span class="btn btn-default btn-file">
					<span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
					<span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
					<input type="file" id="requisition_uploads" name="requisition_upload" onchange="ValidateSingleInput(this);"/>
					</span>
					<a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
					<p class="help-block text-success">Accepted Formats: .xlsx or .xls only</p>
				</div>
				</div>
                </div>

                <div class="form-group col-md-12">
                <table class="table table-bordered table-striped small">
				        <h5 style="color:crimson">*Save your uploaded file in the format below*</h5>
                <thead>
                <tr>
				<th>Req ID</th>
				<th>Distrib</th>
				<th>Status</th>
				<th>Location</th>
                <th>Req Qty</th>
                <th>Merchandise Amt</th>
				<th>Currency</th>
				<th>GL Unit</th>
				<th>Account</th>
				<th>Alt Acct</th>
				<th>Dept</th>
				<th>Fund</th>
				<th>PC Bus Unit</th>
				<th>Project</th>
				<th>Activity</th>
				<th>Source Type</th>
				<th>Category</th>
				<th>Affiliate</th>
				<th>Fund Affil</th>
				<th>Project Affiliate</th>
                </tr>
                </thead>
				<tr>
					<td>0000017750</td>
					<td>1</td>
					<td>Processed</td>
					<td>UGA01</td>
					<td>10</td>
					<td>200,000</td>
					<td>UGX</td>
					<td>UGA01</td>
					<td>537100</td>
					<td>XXXYY</td>
					<td>UG0001</td>
					<td>DK270</td>
					<td>UGA01</td>
					<td>CDNKUG0048</td>
					<td>19</td>
					<td>CARE</td>
					<td>50034</td>
					<td>1234</td>
					<td>899292XX</td>
					<td>CAREXX788</td>
				</tr>
				<tr>
					<td>0000017670</td>
					<td>1</td>
					<td>Processed</td>
					<td>UGA01</td>
					<td>10</td>
					<td>300,000,000</td>
					<td>UGX</td>
					<td>UGA02</td>
					<td>537100</td>
					<td>XXXYY</td>
					<td>UG0001</td>
					<td>DK270</td>
					<td>UGA02</td>
					<td>CDNKUG0048</td>
					<td>19</td>
					<td>CARE</td>
					<td>50034</td>
					<td>1234</td>
					<td>899292XX</td>
					<td>CAREXX788</td>
				</tr>
			  </table>
                <h5>You can also download and edit the sample template <a href="Care_Requisition_Template_v4.xlsx" class="btn btn-primary btn-sm"> File HERE </a></h4>
                </div>
            
                <div class="box-footer">
				<a class="btn btn-danger" href="../requisitions">Cancel</a>
                <button type="submit" class="btn btn-mint" id="btnUpload">Proceed</button>
              </div>
            </form>
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
</div>



<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php"; 
?>
<script>
  //Image Validation
var _validLogoExtensions = [".xlsx", ".xls"];   
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
                    message : 'Sorry, the file type is invalid, allowed file extensions are: xlsx and xls',
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



//Validate Submission Form
$(document).on('nifty.ready', function() {

// FORM VALIDATION FEEDBACK ICONS
// =================================================================
var faIcon = {
	valid: 'fa fa-check-circle fa-lg text-success',
	invalid: 'fa fa-times-circle fa-lg',
	validating: 'fa fa-refresh'
}

// FORM VALIDATION ON TABS
// =================================================================
$('#btnUpload').on("click", function () {
	$('#demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,

	fields: {
		requisition_upload: { validators: { notEmpty: { message: 'A file is required.' } } },
	}
}).on('status.field.bv', function(e, data) {
	var $form     = $(e.target),
	validator = data.bv,
	$tabPane  = data.element.parents('.tab-pane'),
	tabId     = $tabPane.attr('id'); 

	if (tabId) {
	var $icon = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent().find('i');

	// Add custom class to tab containing the field
	if (data.status == validator.STATUS_INVALID) {
		$icon.removeClass(faIcon.valid).addClass(faIcon.invalid);
	} else if (data.status == validator.STATUS_VALID) {
		var isValidTab = validator.isValidContainer($tabPane);
		$icon.removeClass(faIcon.valid).addClass(isValidTab ? faIcon.valid : faIcon.invalid);
	}
	}
});
});
});
</script>
</body>
</html>
