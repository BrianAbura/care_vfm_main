
<?php 
require_once('validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Error 404 - Page Not Found</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include "headers.php"; 
    ?>
</head>
<body>
    <div id="container" class="cls-container">
        
		<!-- HEADER -->
		<!--===================================================-->
		<div class="cls-header">
		    <div class="cls-brand">
		        <a class="box-inline" href="index.html">
		            <!--<img alt="Nifty Admin" src="img/logo.png" class="brand-icon">-->
		        </a>
		    </div>
		</div>
		
		<!-- CONTENT -->
		<!--===================================================-->
		<div class="cls-content">
		    <h1 class="error-code text-info">404</h1>
		    <p class="h4 text-uppercase text-bold">Page Not Found!</p>
		    <div class="pad-btm">
		        Sorry, but the page you are looking for has not been found on our server.
		    </div>
		    <div class="pad-top"><a class="btn btn-primary" href="javascript:history.back()">Return Home</a></div>
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

