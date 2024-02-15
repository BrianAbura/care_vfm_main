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
<li class="active text-lg">Vendor Review</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<?php 
$vendor_id = filter_var(trim($vendor_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $vendor_id);
if(!isset($vendor))
{ //Display the notice below if null
?>
 <div id="page-content">
<div class="row"> 
	<div class="col-sm-7">
		<div class="panel">
			<div class="panel-body">
				<br/>
				<div class="alert alert-danger">
					<button class="close" data-dismiss="alert"></button>
					<strong>Warning!</strong> <br/><br/>
					The Record your are trying to access does not exist. Click <a href="../home" class="alert-link">Here.</a> to go back to the list of tenders.
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
}
else{
    $logo = "..\img\care_logo_default.png";
    $BASEPATH_2 =  dirname(__DIR__, 2);
    $logo_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description IN %ls', $vendor['vendor_id'], ['Logo','Profile']);
    if(!empty($logo_file['document_file'])){
        $logo =  str_replace($BASEPATH_2, '../..',$logo_file['document_file']);
    }
?>
        <div id="page-content">
                <div class="panel">
                    
					    <div class="panel-body">
                            <?php 
                            if($vendor['vendor_status'] == 1){
                            ?>
                        <a class="btn btn-md btn-mint demo-psi-pen-5 add-tooltip pull-right" href="<?php echo $vendor_id; ?>" data-original-title="Edit" data-container="body"> Edit</a>
                        <?php } ?>
                                <table class="table table-bordered table-striped pad-ver mar-no">
                    <tbody>
                    <tr>
                    <td class="text-info text-bold">Logo/Profile</td>
                    <td class="text-normal"><img src="<?php echo $logo; ?>" alt="Profile Picture" class="img-circle img-lg" width="40%"></td>

                </tr>
                <tr>
                    <td class="text-info text-bold">Vendor Name</td>
                    <td class="text-bold text-lg text-dark" colspan="3"><?php echo $vendor['vendor_name'];?></td>

                </tr>
                <tr>
                    <td class="text-info text-bold">Status</td>
                    <?php  
                    if($vendor['vendor_status'] == 1){ 
                    ?>
                    <td class="text-lg"><span class="label label-warning">Draft</span></td>
                    <?php }
                    elseif($vendor['vendor_status'] == 2){ 
                    ?>
                     <td class="text-lg"><span class="label label-info">Pending Approval</span></td>
                    <?php } 
                   elseif($vendor['vendor_status'] == 3){ 
                    ?>
                    <td class="text-lg"><span class="label label-success">Active</span></td>
                    <?php }
                    elseif($vendor['vendor_status'] == 4){
                    ?>
                    <td class="text-lg"><span class="label label-warning">On-Hold</span></td>
                    <?php }
                    else{
                    ?>
                    <td class="text-lg"><span class="label label-danger">Rejected</span></td>
                    <?php } ?>
                    <td class="text-info text-bold">Vendor Type</td>
                    <td class="text-dark"><?php echo $vendor['vendor_type'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Registration Number</td>
                    <td class="text-dark"><?php echo $vendor['registration_num'];?></td>

                    
                    <td class="text-info text-bold">Business Phone Number</td>
                    <td class="text-dark"><?php echo $vendor['phone_num'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Tax Identification Number</td>
                    <td class="text-dark"><?php echo $vendor['tin_num'];?></td>

                    <td class="text-info text-bold">Country of Registration</td>
                    <td class="text-dark"><?php echo $vendor['country'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Business Email Address</td>
                    <td class="text-dark"><?php echo $vendor['email_address'];?></td>

                    <td class="text-info text-bold">City</td>
                    <td class="text-dark"><?php echo $vendor['city'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Street Address</td>
                    <td class="text-dark"><?php echo $vendor['street_address'];?></td>

                    
                    <td class="text-info text-bold">Postal Address</td>
                    <td class="text-dark"><?php echo $vendor['postal_code'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Website</td>
                    <td class="text-dark"><?php echo $vendor['website'];?></td>

                    
                    <td class="text-info text-bold">Business Categories</td>
                    <td class="text-dark">
                        <?php 
                        $strings = explode(',',$vendor['main_category']);
                        foreach($strings as $string){
                            $category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $string);
                            echo "- ".$category['name']."<br/>";
                        }
                        ?>
                    </td>
                </tr>

            </tbody>
        </table>
        <hr/>
        <table class="table table-bordered table-striped pad-ver mar-no">
            <tbody>
                <tr>
                    <td class="text-info text-bold col-md-3">Business Categories</td>
                    <td class="text-dark text-lg">
                        <?php
                        $cnt = 1;
                        $cur_categs = DB::query('SELECT * from vendor_categories where vendor_id=%s', $vendor_id);
                        foreach($cur_categs as $cur_categ)
                        {
                            $codes = DB::queryFirstRow('SELECT * from unspsc where fam_code=%s', $cur_categ['fam_code']);
                            echo $cnt.". ".$codes['description']."<br/>";
                            $cnt ++;
                        }

                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr/>
        <table class="table table-bordered table-striped pad-ver mar-no">
            <tbody>
                <tr>
                    <td class="text-info text-bold col-md-3">Attachments</td>
                    <td class="text-dark text-lg">
                    <?php 
                       $attachments = DB::query('SELECT * from vendor_attachments where vendor_id=%s', $vendor['vendor_id']);
                        foreach($attachments as $attachment){
                            if($attachment['description'] == "Logo" || $attachment['description'] == "Profile" || empty($attachment['document_file'])){
                                continue;
                            }  
                            $file = str_replace($BASEPATH_2, '../..',$attachment['document_file']);
                            $description = $attachment['description'];                  
                        ?>
                        <a href="<?php echo $file;?>" target="_blank" class="btn-link text-semibold"><i class="fa fa-cloud-download icon-2x icon-fw"></i><?php echo $description;?></a><br/>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
            <hr/>
			<h4 style="color:teal;">Vendor Review</h4>
            <br/>
			<form id="demo-bv-bsc-tabs" action="vendor_review" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">

			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<label class="control-label">Status</label>
						<select class="selectpicker form-control" data-live-search="true" name="vendor_status" required>
							<option></option>
                            <option value="3">Approved</option>
                            <option value="4">On-Hold</option>
                            <option value="5">Rejected</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
						<label class="control-label">Review Comments</label>
						<textarea placeholder="Comments.." rows="4" class="form-control" name="vendor_comments"></textarea>
					</div>
				</div>
				</div>
				<br/>

				<div class="tab-footer clearfix">	
					<a href="../vendors" class="btn btn-danger">Cancel</a>	
					<button type="submit" id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Submit Details</button>
				</div>
			</form>
					    </div>
					</div>
                </div>
<?php }?>
</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
</div>

<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php";
?>
</body>
</html>
