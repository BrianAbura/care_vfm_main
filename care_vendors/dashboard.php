<?php 
require_once('validator.php');
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
include "headers.php"; 
?> 
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
			<a href="profile"><i class="demo-pli-gear icon-lg icon-fw"></i> Account Settings</a>
		</li>
		<li>
			<a href="logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
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
<h1 class="page-header text-overflow">Vendor Dashboard</h1>
</div>
</div>
<div class="page-fixedbar-container">
<div class="page-fixedbar-content">
<span class="pad-ver text-main text-sm text-uppercase text-bold"><img src="img\care-int-logo.png" alt="Care-International" width="80%"></span>
<div class="nano">
<div class="nano-content">
<hr class="new-section-xs">
<?php 
include "panel_profile.php"; 
?>
<p class="pad-all text-main text-lg text-uppercase text-bold">Navigation</p>
<div class="list-group bg-trans">
<a href="tenders" class="list-group-item"><i class="ti-receipt icon-lg icon-fw"></i> Tenders</a>
<a href="evaluations" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
<a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
<a href="docs/Vendor_Registration_Guide_v1.docx.pdf" target="_blank" class="list-group-item"><span class="label label-info pull-right">*</span><i class="ti-book icon-lg icon-fw"></i> Vendor Registration Guide</a>
</div>
<hr>
</div>
</div>
</div>
</div>
<div id="page-content">
<div class="panel">
<div class="panel-body">
<div class="row">
<div class="col-lg-3">
<div class="row mar-top">
	<a href="tenders/submitted">
		<div class="col-sm-3 col-lg-6">
		<div class="panel panel-primary panel-colorful">
		<div class="pad-all text-center">
		<span class="text-3x text-thin"><?php echo TenderApplicationCount(get_vendor_id($current_user['user_id'])); ?></span>
		<p>Submitted Tenders</p>
		<i class="ti-receipt icon-2x"></i>
		</div>
		</div>
		</div>
	</a>

	<a href="tenders">
		<div class="col-sm-3 col-lg-6">
		<div class="panel panel-purple panel-colorful">
		<div class="pad-all text-center">
		<span class="text-3x text-thin"><?php echo publishedTenders(); ?></span>
		<p>Published Notices</p>
		<i class="ti-ruler-pencil icon-2x"></i>
		</div>
		</div>
		</div>
	</a>
</div>
</div>
<div class="col-lg-9">
<h4 class="text-main text-normal mar-no">Company/Organisation/Individual</h4>
<div class="panel">
<div class="panel-body">
<div class="table-responsive">
<?php 
$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $current_user['user_id']);
if(!$vendor){
//Register Company First
?>
<h5 class="text-normal">You have not registered as a Vendor for Care International in Uganda.</h5>
<br/>
<a href="register" class="btn btn-mint">Register Company/Organisation <i class="fa fa-building icon-lg"></i> </a>
<a href="register/individual" class="btn btn-primary">Register as a Individual <i class="ion-person-add icon-lg"></i> </a>
<?php 
} 
else{
if($vendor['vendor_type'] == "Individual"){
	$desc = "Profile";
}
else{
	$desc = "Logo";
}
$logo = "img\care_logo_default.png";
$attachment = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description=%s', $vendor['vendor_id'], $desc);
if(!empty($attachment['document_file'])){
$logo =  str_replace(dirname(__DIR__), '..', $attachment['document_file']);
}
?>
<table class="table table-vcenter mar-top">
<thead>
<tr>
<th class="min-w-td">#</th>
<th class="min-w-td">Logo/Profile</th>
<th>Business Name</th>
<th>Registration Number</th>
<th>Status</th>
<th class="text-center">Actions</th>
</tr>
</thead>
<tbody>

<tr>
<td class="min-w-td text-bold">1</td>
<td><img src="<?php echo $logo; ?>" alt="Profile Picture" class="img-circle img-lg"></td>
<td><a class="btn-link text-bold text-lg" href="<?php echo "register/".$vendor['vendor_id']."-view"; ?>"><?php echo $vendor['vendor_name']; ?></a></td>
<td><?php echo $vendor['registration_num']; ?></td>
<td><?php  
	//1 - Draft 2 - Approved
	if($vendor['vendor_status'] == 1){ 
	?>
	<buttonz class="btn btn-warning btn-sm text-bold">Draft</buttonz>
	<?php }
	elseif($vendor['vendor_status'] == 2){ 
	?>
	<buttton class="btn btn-info btn-sm text-bold">Pending Approval</buttton>
	<?php } 
	elseif($vendor['vendor_status'] == 3){ 
	?>
	<button class="btn btn-success btn-sm text-bold">Approved</button>
	<?php } 
	elseif($vendor['vendor_status'] == 4)
	{
	?>
	<button class="btn btn-warning btn-sm text-bold">On-Hold</button>
	<?php } 
	else{
	?>
	<button class="btn btn-danger btn-sm text-bold">Rejected</button>
	<?php }?>
</td>
<td class="text-center">
	<div class="btn-group">
		<?php  
		//1 - Draft - Delete
		if($vendor['vendor_status'] == 1 || $vendor['vendor_status'] == 4 || $vendor['vendor_status'] == 5){ 
			if($vendor['vendor_type'] == "Individual"){
		?>
		<a class="btn btn-md btn-mint btn-hover-success demo-psi-pen-5 add-tooltip" href="<?php echo "register/".$vendor['vendor_id']."-individual"; ?>" data-original-title="Edit" data-container="body"></a>
		<?php } 
		else{
		?>	
		<a class="btn btn-md btn-mint btn-hover-success demo-psi-pen-5 add-tooltip" href="<?php echo "register/".$vendor['vendor_id']."-company"; ?>" data-original-title="Edit" data-container="body"></a>
		<?php } ?>
			<button class="btn btn-md btn-danger btn-hover-danger demo-psi-trash add-tooltip delete_draft" data-id="<?php echo $vendor['vendor_id']; ?>"></button>	
		<?php } ?>
	</div>
</td>
</tr>
</tbody>
</table>
<?php 
}	
?>
<hr>
</div>
</div>
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
			<a href="home">
				<i class="demo-pli-home"></i>
				<span class="menu-title">Dashboard</span>
				<i class="arrow"></i>
			</a>
		</li>
		<li>
			<a href="logout">
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
include "footer.php"; 
?>
<script>
//Confirm Delete
$('.delete_draft').on('click', function(){
var vendor_id = $(this).data("id");
var token = 'delete_vendor';
bootbox.dialog({
//title: "Create New Department",
message : "<br/><h4 class='text-danger'>Are you sure you want to delete this record? </h4>",
buttons: {
success: {
label: "Yes, Delete",
className: "btn-primary",
callback : function(result) {
$.ajax({
type : 'post',
url : 'register/Vendor.php',
data :  'vendor_id='+ vendor_id+'&token='+token, 
success : function(data){
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
setTimeout(function(){ window.location = "../requisitions"; }, 6000);
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
