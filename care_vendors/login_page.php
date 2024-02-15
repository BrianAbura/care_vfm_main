<?php 
session_start();
require_once('cspheader.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Care Vendors | Care Uganda</title>

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
</script>

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
		    <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <div class="mar-ver pad-btm">
		                <h1 class="h3 text-mint">Vendor Login</h1>
		                <p>Sign In to your account</p>
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

<?php
if(isset($_SESSION['action_response'])){
$response = $_SESSION['action_response'];
$response = json_decode($response);
$res_msg = $response->Message;
?>
<div class="alert alert-success">
    <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
    <?php print($res_msg); ?>
</div>
<?php
unset($_SESSION['action_response']);
session_destroy();
}
?>
		            <form action="login" method="POST" enctype="multipart/form-data">
		                <div class="form-group">
		                    <input type="email" class="form-control" placeholder="Email Address" name="user_login" autocomplete="off" required>
		                </div>
		                <div class="form-group">
		                    <input type="password" class="form-control" placeholder="Password" name="user_password" autofocus="" autocomplete="off" required>
		                </div>
		                <button class="btn btn-primary btn-lg btn-block" type="submit">Sign In</button>
		            </form>
		        </div>
		
		        <div class="pad-all text-left">
					<a href="password-reset" class="btn-link mar-rgt">Forgot password?</a>
                    <a href="new_registration" class="pull-right text-lg"><span class="text-mint">Vendor Registration</span></a>
                    <br/> <br/>
                    <div class="media pad-top bord-top">
		                <div class="text-center text-mint">
		                    * This interface is inteded for Vendors only
		                </div>
		            </div>
                <hr/>
                <a href="../care_org/" class="text-semibold text-uppercase"><span class="text-warning">Staff Login</span></a>
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
