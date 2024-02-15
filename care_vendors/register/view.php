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
    <title>Home | Care Uganda</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include $DIR."/headers.php"; 
	?> 
    <style>
	.control-label{
		font-weight:bold;
        color: #139cdb;
	}
    hr{
        background-color: rgb(133, 206, 249, 0.3);
        border: 0 none;
        height: 4px;
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
                                        <a href="#"><i class="demo-pli-gear icon-lg icon-fw"></i> Account Settings</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="demo-pli-computer-secure icon-lg icon-fw"></i> Lock screen</a>
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
                        <h1 class="page-header text-overflow">Vendor Registration</h1>
						<ol class="breadcrumb">
					<li><a href="#"><i class="ion-search icon-2x"></i></a></li>
					<li class="active">View Vendor Details</li>
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
                                    <a href="../tenders" class="list-group-item"><i class="ti-receipt icon-lg icon-fw"></i> Tenders</a>
                                    <a href="#" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                                    <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>


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
    $logo_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description IN %ls', $vendor['vendor_id'], ['Logo','Profile']);
    if(!empty($logo_file['document_file'])){
        $logo =  str_replace($BASEPATH, '..', $logo_file['document_file']);
    }
?>
                <div id="page-content">
  
                <div class="panel">
                    
				        <?php  
                        //1 - Draft
                        if($vendor['vendor_status'] == 1 || $vendor['vendor_status'] == 4 || $vendor['vendor_status'] == 5){ 
                            if($vendor['vendor_type'] == "Individual"){
                        ?>
                        <a class="btn btn-md btn-mint demo-psi-pen-5 add-tooltip pull-right" href="<?php echo $vendor['vendor_id']."-individual"; ?>" data-original-title="Edit" data-container="body">Edit</a>
                        <?php } 
                        else{
                        ?>	
                        <a class="btn btn-md btn-mint demo-psi-pen-5 add-tooltip pull-right" href="<?php echo $vendor['vendor_id']."-company"; ?>" data-original-title="Edit" data-container="body">Edit</a>
                        <?php } } ?>
                    <table class="table table-bordered table-striped pad-ver mar-no">
                    <tbody>
                    <tr>
                    <td class="text-info text-bold">Logo/Profile</td>
                    <td class="text-bold text-lg"><img src="<?php echo $logo; ?>" alt="Profile Picture" class="img-circle img-lg"></td>

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
                    <td class="text-info text-bold col-md-3">Business Sub-Categories</td>
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
                            $file = str_replace($BASEPATH, '..', $attachment['document_file']);
                            $description = $attachment['description'];                  
                        ?>
                        <a href="<?php echo $file;?>" target="_blank" class="btn-link text-semibold"><i class="fa fa-cloud-download icon-2x icon-fw"></i><?php echo $description;?></a><br/>
                        <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
            <hr/>
            <table class="table table-bordered table-striped pad-ver mar-no">
            <h4 style="color:#e4701e;">Review Report</h4><br/>
            <thead>
            <tr>
                <th>#</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Review Date</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $cnt = 1;
                $reviews = DB::query('SELECT * from vendor_reviews where vendor_id=%s order by id desc', $vendor_id);
                foreach($reviews as $review){
                    $status = "";
                    $class = "";

                    if($review['vendor_status'] == 3){
                        $status = "Approved";
                        $class = "text-success";
                    }
                    elseif($review['vendor_status'] == 4){
                        $status = "On-Hold";
                        $class = "text-warning";
                    }
                    elseif($review['vendor_status'] == 5){
                        $status = "Rejected";
                        $class = "text-danger";
                    }

                ?>
                <tr>
                <td><?php echo $cnt;?></td>
                <td class="<?php echo $class;?>"><?php echo $status;?></td>
                <td><?php echo $review['vendor_comments'];?></td>
                <td><?php echo date_format(date_create($review['date_reviewed']), 'd-M-Y h:i'); ?></td>
                </tr>
                <?php 
                $cnt ++;
                }
                ?>
               
            </tbody>
        </table>
        
					    </div>
					</div> 
                </div>
                
<?php }?>
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
			url: "edit_vendor",
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
                setTimeout(function(){ window.location = "../home"; }, 6000);
			}
		});
	});

});


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
$('#btnCreate').on("click", function () {
	$('#demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,

	fields: {
		vendor_name: { validators: { notEmpty: { message: 'Vendor is required' } } },
		registration_num: { validators: { notEmpty: { message: 'The Registration Number is required' } } },
		tin_num: { validators: { notEmpty: { message: 'Tax Idenfication Number is required' } } },
		email_address: { validators: { notEmpty: { message: 'Email Address is required' } } },
		phone_number: { validators: { notEmpty: { message: 'Phone Number ID is required' } } },
		street_address: { validators: { notEmpty: { message: 'Street Addresss is required' } } },
		business_categories: { validators: { notEmpty: { message: 'Select atleast 6 Business Categories' } } },

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
