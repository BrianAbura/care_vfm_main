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


       <!--Bootstrap Validator [ OPTIONAL ]-->
       <link href="plugins\bootstrap-validator\bootstrapValidator.min.css" rel="stylesheet">
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
		<div class="cls-content ">
        <div class="cls-content-lsg panel col-md-1"></div>
		    <div class="cls-content-lsg panel col-md-10">
		        <div class="panel-body">
                <h1 class="h3">Vendor Registration</h1>
                <br/>
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
            }
        ?>
            <form action="new_reg" id="demo-bv-bsc-tabs" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" autocomplete="off" >
                </div>
                </div>
                <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label text-bold">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" autocomplete="off" required>
                    </div>
                </div>  
                <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label text-bold">Email Address</label>
                    <input type="email" class="form-control" name="email_address" placeholder="Email Address" autocomplete="off" required>
                    </div>
                </div> 
                <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label text-bold">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required>
                    </div>
                </div> 
            </div>
            <br/>
            <div class="row">
                <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label text-bold">Confirm Password</label>
                    <input type="password" class="form-control" name="password_repeat" placeholder="Repeat Password" autocomplete="off" required>
                    </div>
                </div>
            </div>

            <div class="tab-footer">	
                <a href="login" class="btn btn-danger">Cancel</a>	
                <button type="submit" id="btnCreate" name="formBtn" value="btnCreate" class="btn btn-mint">Register</button>
            </div>
            </form>
        </div>
            <div class="pad-all text-left">
            <div class="media pad-top bord-top">
                <div class="text-center text-mint">
                    * This interface is inteded for Vendors only
                </div>
            </div>
        <hr/>
             <a href="login" class="text-semibold text-uppercase"><span class="text-mint">Vendor Login</span></a> &nbsp;|| &nbsp;
             <a href="../care_org/" class="text-semibold text-uppercase" ><span class="text-warning">Staff Login</span></a>
        </div>
        </div>
       
    </div>
</div>
    </div>
	<?php 
    /*** Include the Global Footer and Java Scripts */
    include "footer.php"; 
    ?>   
    <!--Form validation [ SAMPLE ]-->
    <script src="js\demo\form-validation-reg.js"></script>
</body>
</html>