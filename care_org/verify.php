<?php
require_once('defines/defines.php');
$log = new Logger(LOG_FILE,Logger::DEBUG);

if(empty($_REQUEST['user_verified_data_id'])){
$ver_id = filter_var(trim($_REQUEST['ver_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
$code = trim($_REQUEST['code']);
$type = filter_var(trim($_REQUEST['link_type']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$verification = array(
'ver_id' => $ver_id,
'email' => $email,
'code' => $code,
'link_type' => $type,
);
$log->LogInfo("USER_ORG_EMAIL_POST_DATA: ".print_r($verification,true));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="css\bootstrap.min.css" rel="stylesheet">

    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="css\nifty.min.css" rel="stylesheet">
</head>
<body>
<div class="col-md-1 pad-all">
<img src="img\care-int-logo.png" alt="Care-International" width="100%">
</div>
<div class="col-md-5 pad-all">
<div class="card pad-all">
    <br/>
    <h4>Token Verification</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POSt">
        <input type="hidden" name="user_verified_data_id" value="<?php echo $ver_id?>">
        <input type="hidden" name="email" value="<?php echo $email?>">
        <input type="hidden" name="code" value="<?php echo $code?>">
        <input type="hidden" name="link_type" value="<?php echo $type?>">
        <button type="submit" class="btn btn-mint btn-sm text-bold" >Click Here to Verify the Token</button>
    </form>
<?php
//Get the Posted Data
if(!empty($_REQUEST['user_verified_data_id'])){
    $curDate = date('Y-m-d H:i:s');
    $ver_id = filter_var(trim($_REQUEST['user_verified_data_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
    $code = trim($_REQUEST['code']);
    $type = filter_var(trim($_REQUEST['link_type']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $home_url = "https://vfmplatform.com/care_org";
    $reset_pass_url = "https://vfmplatform.com/care_org/verify_changes.php?email=".$email;
    
    $verification = array(
    'ver_id' => $ver_id,
    'email' => $email,
    'code' => $code,
    'link_type' => $type,
    );
    $log->LogInfo("USER_ORG_EMAIL_VERIFY_DATA: ".print_r($verification,true));

    if($type == "registration"){
        $msg = "REGISTRATION";
    }
    else{
        $msg = "PASSWORD";
    }

    $check_link = DB::queryFirstRow('SELECT * from verifications where ver_id=%s', $ver_id);
    if(!$check_link){
        echo '<h3 style="color:#DC143C">INVALID TOKEN....</h3>';
        $log->LogInfo("EMAIL_VERIFY_RESPONSE: ".print_r('INVALID TOKEN',true));
        echo "Redirecting ......";
        header( "refresh:3;url=".$home_url."" );
    }
    elseif($curDate > $check_link['expiry_date']){
        echo '<h3 style="color:#DC143C">EXPIRED '.$msg.' TOKEN....</h3> <strong>Please contact System Administrator for support.</strong>';
        $log->LogInfo("EMAIL_VERIFY_RESPONSE: ".print_r('EXPIRED '.$msg.' TOKEN',true));
        echo "Redirecting ......";
        header( "refresh:3;url=".$home_url."" );
    }
    elseif($code != $check_link['code']){
        echo '<h3 style="color:#DC143C">INVALID '.$msg.' TOKEN...</h3>';
        $log->LogInfo("EMAIL_VERIFY_RESPONSE: ".print_r('INVALID '.$msg.' TOKEN',true));
        echo "Redirecting ......";
        header( "refresh:3;url=".$home_url."" );
    }
    elseif($check_link['status'] == "VERIFIED"){
        echo '<h3 style="color:#DC143C">'.$msg.' TOKEN ALREADY USED.</h3>';
        $log->LogInfo("EMAIL_VERIFY_RESPONSE: ".print_r($msg.' TOKEN ALREADY USED',true));
        echo "Redirecting ......";
        header( "refresh:3;url=".$home_url."" );
    }
    else{
       DB::update('verifications',array('status'=>'VERIFIED'),'ver_id=%s',$check_link['ver_id']);
        echo '<h3 style="color:#009900">'.$msg.' TOKEN VERIFIED SUCCESSFULLY.</h3>';
        $log->LogInfo("EMAIL_VERIFY_RESPONSE: ".print_r($msg.' TOKEN VERIFIED SUCCESSFULLY',true));
        echo "Redirecting ......";
        header( "refresh:3;url=".$reset_pass_url."" );
    }
}
else{
   // No action to perform
}
?>
</div>
</div>
<?php 
/*** Include the Global Footer and Java Scripts */
include "footers.php"; 
?>
</body>
</html>
