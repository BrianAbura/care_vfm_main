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
//require 'vendor/autoload.php';

require $DIR.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$target_dir = $DIR;
$upload = basename($_FILES["requisition_upload"]["name"]);
$fileName = '/DataFile_upload.'. strtolower(pathinfo($upload,PATHINFO_EXTENSION));
$uploaded_file = $target_dir . $fileName;
move_uploaded_file($_FILES["requisition_upload"]["tmp_name"], $target_dir . $fileName);

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
<li class="active text-lg">Verify Requisitions</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
		<form role="form" class="form-content" method="POST" action="upload_complete" enctype="multipart/form-data">
      <input type="hidden" name="token" value="upload_requisitions">
<table class="table table-bordered ">
<thead>
<tr>
<th>No.</th>
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
<tbody>
<?php
$spreadsheet = $reader->load($uploaded_file);
$d=$spreadsheet->getSheet(0)->toArray();
$sheetData = $spreadsheet->getActiveSheet()->toArray();
unset($sheetData[0]);
$overall = "OK";
$cnt = 1;
foreach ($sheetData as $Key => $Row)
{
    if($Key == 0){
        continue;
    }
    if ($Row && !empty($Row[0]))
    {//Table Goes here
      
      $errors = [];
      $checkId = DB::queryFirstRow('SELECT * from requisitions where requisition_number like %ss', $Row[0]);

      if($checkId){
        $tr_row = "<tr class='danger'>";
        $status = "ERROR";
        $errors['RequisitionId'] = "Requisition ID already exists.";
        $overall = "ERROR";
      } 
      else{
        $tr_row = "<tr class='success'>";
        $status = "OK";
      }
    echo $tr_row;
      ?>
      <td><?php echo $cnt;?></td>
      <td><input type="hidden" value="<?php echo $Row[0]?>" name="requisition_number[]" /><?php echo $Row[0]?></td> <!-- Unique -->
      <td><input type="hidden" value="<?php echo $Row[1]?>" name="distrib[]"/><?php echo $Row[1]?></td> 
      <td><input type="hidden" value="<?php echo $Row[2]?>" name="req_status[]"/><?php echo $Row[2]?></td>
      <td><input type="hidden" value="<?php echo $Row[3]?>" name="location[]"/><?php echo $Row[3]?></td>
      <td><input type="hidden" value="<?php echo $Row[4]?>" name="req_qty[]"/><?php echo $Row[4]?></td> 
      <td><input type="hidden" value="<?php echo $Row[5]?>" name="merchandise_amt[]" /><?php echo number_format($Row[5])?></td>
      <td><input type="hidden" value="<?php echo $Row[6]?>" name="currency[]" /><?php echo $Row[6]?></td>
      <td><input type="hidden" value="<?php echo $Row[7]?>" name="gl_unit[]" /><?php echo $Row[7]?></td>
      <td><input type="hidden" value="<?php echo $Row[8]?>" name="account[]"/><?php echo $Row[8]?></td>
      <td><input type="hidden" value="<?php echo $Row[9]?>" name="alt_account[]"/><?php echo $Row[9]?></td>
      <td><input type="hidden" value="<?php echo $Row[10]?>" name="dept_id[]"/><?php echo $Row[10]?></td> <!-- Unique for departments -->
      <td><input type="hidden" value="<?php echo $Row[11]?>" name="fund[]"/><?php echo $Row[11]?></td>
      <td><input type="hidden" value="<?php echo $Row[12]?>" name="pc_bus_unit[]"/><?php echo $Row[12]?></td>
      <td><input type="hidden" value="<?php echo $Row[13]?>" name="project[]"/><?php echo $Row[13]?></td>
      <td><input type="hidden" value="<?php echo $Row[14]?>" name="activity[]"/><?php echo $Row[14]?></td>
      <td><input type="hidden" value="<?php echo $Row[15]?>" name="source_type[]"/><?php echo $Row[15]?></td>
      <td><input type="hidden" value="<?php echo $Row[16]?>" name="req_category[]"/><?php echo $Row[16]?></td>
      <td><input type="hidden" value="<?php echo $Row[17]?>" name="affiliate[]"/><?php echo $Row[17]?></td>
      <td><input type="hidden" value="<?php echo $Row[18]?>" name="fund_affiliate[]"/><?php echo $Row[18]?></td>
      <td><input type="hidden" value="<?php echo $Row[19]?>" name="project_affiliate[]"/><?php echo $Row[19]?></td>
  </tr>
    <?php
     $cnt++;
     if(sizeof($errors) != 0){
       if(isset($errors['RequisitionId'])){
       echo "<tr class='error_rows'>";
         echo "<td colspan='10'>Error(s): Requisition ID already exists."."<br/> </td>";
       echo "</tr>";
       // $status = "ERROR";
       }
           }
        }
      else
      {//Warning Message Here
        //$status = "ERROR";
      }
  }  
?>
</tbody>
</table>  
    <br/>
    <div class="form-group col-md-12">
      <div class="fileupload fileupload-new" data-provides="fileupload">
        <label for="people_soft_upload" class="text-bold" >People Soft PR Extract</label>
				<div class="input-append">
					<div class="uneditable-input">
					<span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
					</div>
					<span class="btn btn-default btn-file">
					<span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
					<span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
					<input type="file" id="people_soft_upload" name="people_soft_upload" onchange="ValidateSingleInput(this);" required />
					</span>
					<a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
					<p class="help-block text-success">Accepted Formats: PDF. PNG and JPEG only</p>
				</div>
				</div>
                </div>
            <!--Second Row -->
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="upload_requisition">Cancel</a>
                <?php if($overall == "OK"){ //To avoid double verification:: Edit your excel.?>
                <button type="submit" class="btn btn-success">Proceed</button>
                <?php }?>
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
  var _validLogoExtensions = [".pdf", ".png", ".jpeg"];   
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
                    message : 'Sorry, the file type is invalid, allowed file extensions are: PDF. PNG and JPEG',
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
</body>
</html>
