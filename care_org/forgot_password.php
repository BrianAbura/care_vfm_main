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

    <title>Forgot Password| Care Uganda</title>

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
      
        <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <h1 class="h3">Forgot password</h1>
		            <p class="pad-btm">Enter your email address to recover your password. </p>
                    <?php
                    //Notifications
                    if(isset($_SESSION['succcess_msg'])){
                    ?>
                        <div class="alert alert-success">
                        <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
                        <?php print($_SESSION['succcess_msg']); ?>
                        </div>
                    <?php
                    unset($_SESSION['error_msg']);
                    }
                    elseif(isset($_SESSION['error_msg'])){
                        ?>
                            <div class="alert alert-danger">
                            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
                            <?php print($_SESSION['error_msg']); ?>
                            </div>
                        <?php
                        unset($_SESSION['error_msg']);
                        }
                        session_destroy();
                        ?>
		            <form action="reset_pass" method="POST" enctype="multipart/form-data">
		                <div class="form-group">
		                    <input type="hidden" name="token" value="reset_password" class="form-control">
                            <input type="email" class="form-control" placeholder="Email Address" name="user_login">
		                </div>
		                <div class="form-group text-right">
		                    <button class="btn btn-danger btn-md btn-block" type="submit">Send Password Reset Link</button>
		                </div>
		            </form>
		            <div class="pad-top">
		                <a href="login" class="btn-link text-bold text-main">Back to Login</a>
		            </div>
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
