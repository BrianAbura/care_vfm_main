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
    #demo-bv-bsc-tabs hr{
        background-color: rgb(133, 206, 249, 0.3);
        border: 0 none;
        height: 6px;
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
                        <h1 class="page-header text-overflow">Profile</h1>
						<ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-gear icon-2x"></i></a></li>
					<li class="active">View Profile</li>
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
                                    <a href="../evaluations" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                                    <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="page-content">
					<div class="panel">
					    <div class="panel-body">
                        <div class="tab-base">
					
					<!--Nav tabs-->
					<ul class="nav nav-tabs">
						<li class="active"> <a data-toggle="tab" href="#demo-ico-lft-tab-1">My Profile <span class="badge badge-mint"><i class="demo-psi-add-user"></i></span></a></li>
						<li> <a data-toggle="tab" href="#demo-ico-lft-tab-2">Edit Account<span class="badge badge-pink"><i class="demo-psi-pencil"></i></span></a></li>
					</ul>
		
					<!--View Full Profile-->
					<div class="tab-content">
						<div id="demo-ico-lft-tab-1" class="tab-pane active  in">
							<div class="col-md-6">
							<hr class="new-section">
							<h3 class="text-main text-normal text-2x mar-no"><?php echo $current_user['first_name'].' '.$current_user['last_name'];?></h3>
							<div class="list-group bg-trans mar-no">
							<a class="list-group-item" style="color:teal"><i class="demo-pli-mail icon-lg icon-fw"></i> <?php echo $current_user['email_address'];?></a>
							</div>
							<hr class="new-section-xs">
							<button class="btn btn-success mar-ver">Active Account</button>
							<br/>
							<p class="text-xs">Last Login: <?php echo date_format(date_create($current_user['last_login']), 'd-M-Y H:i'); ?></p>
							<p class="text-xs">Date Modified: <?php echo date_format(date_create($current_user['date_modified']), 'd-M-Y H:i'); ?></p>
							</div>
						</div>


						<!--Edit Full Profile-->
						<div id="demo-ico-lft-tab-2" class="tab-pane fade">
							<div class="row">
								<div class="col-md-6">
								<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e">Edit your profile</h5>
								<hr class="new-section-xs">
									<!--Edit Email Address-->
									<form class="demo-bv-bsc-tabs" action="edit_profile" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="token" value="edit_profile">
									<input type="hidden" name="user_id" value="<?php echo $current_user['user_id'];?>">
									<div class="form-group col-xs-7">
										<label class="control-label">Email Address</label>
										<input type="email" class="form-control" name="email" value="<?php echo $current_user['email_address'];?>">
									</div>
									<div class="col-lg-6 col-lg-offset-2">
									<button type="submit" class="btn btn-primary">Edit Account</button>
									</div>
									</form>
								</div>

								<div class="col-md-6">
								<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e">Change your Password</h5>
								<hr class="new-section-xs">
									<!--Change Password-->
									<form class="demo-bv-bsc-tabs" action="edit_password" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="token" value="edit_password">
									<input type="hidden" name="user_id" value="<?php echo $current_user['user_id'];?>">
									<div class="form-group col-xs-7">
										<label class="control-label">New Password</label>
										<input type="password" class="form-control" name="new_password">
									</div>
									<div class="form-group col-xs-7">
										<label class="control-label">Repeat Password</label>
										<input type="password" class="form-control" name="rpt_new_password">
									</div>
									<div class="col-lg-6 col-lg-offset-2">
									<button type="submit" class="btn btn-success">Change Password</button>
									</div>
									
									</form>
								</div>
								
							</div>
							<hr/>
							<p class="text-xs text-right text-bold" style="color:#e4701e">Note: * Your current session will be closed once you change your password.</p>
					</div>
				
							
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
			url: "add_vendor",
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