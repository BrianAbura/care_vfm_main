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
    <title>Tender Submit | Care Uganda</title>
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
					<li><a href="#"><i class="ion-search icon-2x"></i></a></li>
					<li class="active">Submit Application for Tender</li>
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
									<li class="active"><a href="../tenders" class="list-group-item"> Current Tenders</a></li>
									<li><a href="submitted" class="list-group-item"> Tenders Submitted</a></li>
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
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
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
            </div>
            <div class="fluid">
                <div class="blog blog-list">
            <div class="panel">
            <form id="demo-bv-bsc-tabs" action="send_application" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="token" value="tender_apply">
            <input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
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
                        ?>
                        <tr>
                        <input type="hidden" class="form-control" name="pre_criteria_id[]" value="<?php echo $pre_eval['id'];?>">
                        <th rowspan="3" class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;"><?php echo $cnt;?></th>
                        <th>Criteria:</th>
                        <td class="text-dark text-lg text-bold"><?php echo $pre_eval['criteria_description'];?></td>
                        </tr>
                        <tr>
                        <th style="width:15%">Response:</th>
                        <td><textarea placeholder="Type Response here.." class="form-control" name="pre_vendor_resp[]"></textarea></td>
                        </tr>
                        <tr>
                        <th>Support Document:</th>
                        <td>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input">
                                <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                                </div>
                                <span class="btn btn-default btn-file">
                                <span class="btn btn-xs btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
                                <span class="fileupload-new btn btn-xs btn-primary fa fa-upload"> Select file</span>
                                <input type="file" id="InputReceiptImg" name="pre_vendor_doc[]" onchange="ValidateDocs(this);"/>
                                </span>
                                <a href="#" class="btn btn-xs btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
                                <small class="help-block text-info">Accepted Formats: jpg, jpeg, png, doc, docx and pdf</small>
                            </div>
                            </div>
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
                        ?>
                        <tr>
                        <input type="hidden" class="form-control" name="tech_criteria_id[]" value="<?php echo $tech_eval['id'];?>">
                        <th rowspan="3" class="text-center" style="vertical-align:middle;border-left:5px solid #20B2AA;"><?php echo $cnt;?></th>
                        <th>Criteria:</th>
                        <td class="text-dark text-lg text-bold"><?php echo $tech_eval['criteria_description'];?></td>
                        </tr>
                        <tr>
                        <th style="width:15%">Response:</th>
                        <td><textarea placeholder="Type Response here.." class="form-control" name="tech_vendor_resp[]"></textarea></td>
                        </tr>
                        <tr>
                        <th>Support Document:</th>
                        <td>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input">
                                <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                                </div>
                                <span class="btn btn-default btn-file">
                                <span class="btn btn-xs btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
                                <span class="fileupload-new btn btn-xs btn-primary fa fa-upload"> Select file</span>
                                <input type="file" id="InputReceiptImg" name="tech_vendor_doc[]" onchange="ValidateDocs(this);"/>
                                </span>
                                <a href="#" class="btn btn-xs btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
                                <small class="help-block text-info">Accepted Formats: jpg, jpeg, png, doc, docx and pdf</small>
                            </div>
                            </div>
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
                        foreach($requests as $request){
                        ?>
                        <tr style="border-left:5px solid #3CB371;">
                        <input type="hidden" class="form-control" name="requisition_id[]" value="<?php echo $request['id'];?>">
                        <td sclass="text-center" tyle="vertical-align:middle;"><?php echo $cnt;;?></td>
                        <td class="text-dark"><?php echo $request['description'];?></td>
                        <td class="text-dark"><?php echo $request['unit_of_measure'];?></td>
                        <td class="text-dark"><input type="hidden" id="item_quantity" value="<?php echo $request['quantity'];?>"><?php echo $request['quantity'];?></td>
                        <td><input type="text" id="item_unit_price" class="form-control item_price var_prices" name="finance_vendor_resp[]" /></td>
                        <td><input type="text" id="item_total_price" class="form-control prices var_prices item_total_price" disabled name="item_total_price[]" /></td>
                        </tr>
                        <?php
                        $cnt++;
                            } ?>
                                <!-- SUb Total-->
                            <tr>
                            <td></td><td></td>
                            <td></td><td></td>
                            <td><h5 class="text-center">Sub Total</h5></td>
                            <td class="text-dark"><input type="text" class="form-control prices" id="items_total_value" name="items_total_value" readonly/></td>
                            </tr>
                                <!-- SUb Total-->
                            <tr>
                            <td></td><td></td>
                            <td></td><td></td>
                            <td><h5 class="text-center">VAT(%)</h5></td>
                            <td class="text-dark"><input type="number" max="100" class="form-control items_vat" id="items_vat" name="vat_vendor_resp"/></td>
                            </tr>

                                <!-- Grand Total-->
                            <tr>
                            <td></td><td></td>
                            <td></td><td></td>
                            <td><h4 class="text-center">Grand Total</h4></td>
                            <td class="text-dark"><input type="text" class="form-control prices text-lg text-bold" id="items_grand_total" name="items_grand_total" readonly/></td>
                            </tr>

                     <?php  } //End Preliminary?>

                </tbody>
             </table>
                </div>
                <br/>

                <div class="blog-footer">
                <div class="tab-footer clearfix">	
                <a href="javascript:history.back()" class="btn btn-danger">Cancel</a>	
                <button type="submit" id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Save Draft</button>
                <button type="submit" id="btnCreate" name="formBtn" value="btnCreate" class="btn btn-mint pull-right">Submit Details</button>
            </div>
                </div>
                    </form>
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
          $(function () { 
    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

        })
    </script>
    <script>
	//Saving as Draft
$('#btnSaveDraft').on("click", function () {
	$("#demo-bv-bsc-tabs").submit(function(e) {
	e.preventDefault(); 
	var form = $(this).serializeArray();
	form.push({name: "formAction", value: "SaveDraft"});
		$.ajax({
			type: "POST",
			url: "send_application",
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
                setTimeout(function(){ window.location = "../tenders"; }, 6000);
			}
		});
	});

});

</script>
</body>
</html>