<?php 
session_start();
require_once('cspheader.php');
require_once 'defines/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Vendor Token Verification| Care Uganda</title>

    <!--STYLESHEET-->
    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="css\bootstrap.min.css" rel="stylesheet">

    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="css\nifty.min.css" rel="stylesheet">

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="plugins\pace\pace.min.css" rel="stylesheet">
    <script src="plugins\pace\pace.min.js"></script>

</head>
<body>
    <div id="container" class="cls-container">
        
		<div class="cls-header">
		    <div class="cls-brand">
		        <a class="box-inline" href="index.html">
		            
		            <span class="brand-title">Care Uganda <span class="text-thin">VMS</span></span>
		        </a>
		    </div>
		</div>
		<div id="bg-overlay"></div>
		
		<!-- LOGIN FORM -->
		<!--===================================================-->
		<div class="cls-content">
<?php 
$email = filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
$vendor = DB::queryFirstRow('SELECT * from vendor_users where email_address=%s', $email);
?>
		    <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <div class="mar-ver pad-btm">
                        <p class="text-bold">Welcome</p>
		                <h4 class=" text-dark"><?php echo $vendor['first_name']." ".$vendor['last_name'];?></h4>
		                <p><?php echo $role['role_desc']?></p>
		            </div>
<!--Alert Notification -->
<?php
if(isset($_SESSION['error_msg'])){
?>
	<div class="alert alert-danger">
	<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
	<?php print($_SESSION['error_msg']); ?>
	</div>
<?php
unset($_SESSION['error_msg']);
session_destroy();
}
?>

 <p>Please change your password below!</p>
            <form action="verify_complete.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                <input type="hidden" class="form-control" name="user_id" value="<?php echo $vendor['user_id']?>">
                <input type="password" class="form-control" placeholder="New Password" name="user_password" autofocus="" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Repeat New Password" name="repeat_password" autofocus="" autocomplete="off" required>
                </div>
                <button class="btn btn-mint btn-lg btn-block" type="submit">Confirm</button>
            </form>
		        </div>
		
		        <div class="pad-all text-left">
		          	<br/> <br/>
		            <div class="media pad-top bord-top">
		                <div class="text-center text-mint">
		                    * This interface is inteded for Vendors only
		                </div>
		            </div>
					<hr/>
		        </div>
		    </div>
		</div>
    </div>
        
    <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--jQuery [ REQUIRED ]-->
    <script src="js\jquery.min.js"></script>

    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="js\bootstrap.min.js"></script>

    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="js\nifty.min.js"></script>

</body>
</html>