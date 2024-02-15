<?php
/**
 * Account Login for Staff
 */
session_start();
require_once 'defines/functions.php';

$user_login = htmlspecialchars((isset($_REQUEST['user_login'])) ? $_REQUEST['user_login'] : null);
$user_password = htmlspecialchars((isset($_REQUEST['user_password'])) ? $_REQUEST['user_password'] : null);
$user_login = filter_var($user_login, FILTER_SANITIZE_EMAIL); //Email
$login_date = date('Y-m-d H:i:s');

if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
    $user = DB::queryFirstRow('SELECT * from org_users where email_address=%s order by id desc limit 1', $user_login);
    
    if (isset($user['email_address'])) {
        if($user['acc_status'] != 'Active'){
            $_SESSION['error_msg'] = "Your account is not active. Please contact the System Administrator.";
            header("Location:login_page_redirect");
        }
        else{
            if(password_verify($user_password, $user['user_password'])){
                DB::update("org_users",array(
                    "last_login"=>$login_date, 
                    "login_count"=> ($user['login_count'] + 1)), "user_id=%s",$user['user_id']);
                $_SESSION['signed_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                header("Location:home");
            }
            else{
                $_SESSION['signed_in'] = false;
                $_SESSION['error_msg'] = 'Incorrect password';
                header("Location:login_page_redirect");
            }
        }  
    } 
    else {
        $_SESSION['error_msg'] = "Account not found!";
        header("Location:login_page_redirect");
    }
} 
else {
    $_SESSION['error_msg'] = "The email address is not valid. Please try again.";
    header("Location:login_page_redirect");
}
