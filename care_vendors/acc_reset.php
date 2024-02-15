<?php
/**
 * Account Login for Staff
 */
session_start();
require_once 'defines/functions.php';

$vendor_email = htmlspecialchars((isset($_REQUEST['vendor_email'])) ? $_REQUEST['vendor_email'] : null);
$vendor_email = filter_var($vendor_email, FILTER_SANITIZE_EMAIL); //Email
$cur_date = date('Y-m-d H:i:s');

if (filter_var($vendor_email, FILTER_VALIDATE_EMAIL)) {
    $vendor = DB::queryFirstRow('SELECT * from vendor_users where email_address=%s order by id desc limit 1', $vendor_email);
    
    if (isset($vendor['email_address'])) {
        if($vendor['acc_status'] != 'Active'){
            $_SESSION['error_msg'] = "Your account has been deactivated. Please contact the Care Administrator.";
            header("Location:password-reset");
        }
        else{
            send_mail($vendor['email_address'], 'password_reset');
            $_SESSION['succcess_msg'] = 'Your password reset request has been captured. <br/>Please check your email for the recovery link.';
            header("Location:password-reset");
        }  
    } 
    else {
        $_SESSION['error_msg'] = "Account not found!";
        header("Location:password-reset");
    }
} 
else {
    $_SESSION['error_msg'] = "The email address is not valid. Please try again.";
    header("Location:password-reset");
}
