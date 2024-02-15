<?php
/**
 * Account Login for Staff
 */
session_start();
require_once 'defines/functions.php';

$user_login = htmlspecialchars((isset($_REQUEST['user_login'])) ? $_REQUEST['user_login'] : null);
$user_login = filter_var($user_login, FILTER_SANITIZE_EMAIL); //Email
$cur_date = date('Y-m-d H:i:s');

if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
    $user = DB::queryFirstRow('SELECT * from org_users where email_address=%s order by id desc limit 1', $user_login);
    
    if (isset($user['email_address'])) {
        if($user['acc_status'] != 'Active'){
            $_SESSION['error_msg'] = "Your account has been deactivated. Please contact the System Administrator.";
            header("Location:password-reset");
        }
        else{
		//Send Email Here
send_mail($user['email_address'], 'password_reset');
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
