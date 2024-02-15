<?php 
require_once('../validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php 
/*** Include the Global Headers Scripts */
include "headers.php"; 
?>

</head>
<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg navbar-fixed">
<?php 
/*** Include the Global Headers Scripts */
include "navbar.php";
?>

<div class="boxed">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<div id="page-title">
</div>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<!--Data Table-->
			<!--===================================================-->
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
							<h5 class="text-uppercase text-muted text-normal" style="color:#e4701e"><?php echo $user_role;?></h5>
							<div class="list-group bg-trans mar-no">
							<a class="list-group-item" style="color:midnightblue"><i class="demo-pli-mail icon-lg icon-fw"></i> <?php echo $current_user['email_address'];?></a>
							<a class="list-group-item" style="color:brown"><i class="demo-pli-building icon-lg icon-fw"></i> <?php echo $user_department;?> Department</a>
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
			<!--===================================================-->
			<!--End Data Table-->

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
include "footers.php"; 
?>

<script>
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
$('.demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,
	fields: {
	email: {
		validators: {
			notEmpty: {
				message: 'The email address is required'
			}
		}
	},
	new_password: {
            validators: {
                notEmpty: {
                    message: 'The password is required and can\'t be empty'
                },
                identical: {
                    field: 'rpt_new_password',
                    message: 'The password and its confirm are not the same'
                }
            }
        },
        rpt_new_password: {
            validators: {
                notEmpty: {
                    message: 'The confirm password is required and can\'t be empty'
                },
                identical: {
                    field: 'new_password',
                    message: 'The password and its confirm are not the same'
                }
            }
        },
	}
}).on('status.field.bv', function(e, data) {
	var $form     = $(e.target),
	validator = data.bv,
	$tabPane  = data.element.parents('.demo-bv-bsc-tabs'),
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
</script>
</body>
</html>
