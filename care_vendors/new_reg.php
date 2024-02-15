<?php
/**
 * Vendor Login
 */
session_start();
require_once 'defines/functions.php';

$first_name = htmlspecialchars((isset($_REQUEST['first_name'])) ? $_REQUEST['first_name'] : null);
$last_name = htmlspecialchars((isset($_REQUEST['last_name'])) ? $_REQUEST['last_name'] : null);
$email_address = filter_var(( isset( $_REQUEST['email_address'] ) )?  $_REQUEST['email_address']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$password = filter_var(( isset( $_REQUEST['password'] ) )?  $_REQUEST['password']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$password_repeat = filter_var(( isset( $_REQUEST['password_repeat'] ) )?  $_REQUEST['password_repeat']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

$login_date = date('Y-m-d H:i:s');

if($password != $password_repeat){
    $_SESSION['error_msg'] = 'The Passwords do not match.';
    header("Location:new_registration");
}
else{
    $check_email = DB::queryFirstRow('SELECT * from vendor_users where email_address=%s', $email_address);
    if (isset($check_email['email_address'])) {
        $_SESSION['error_msg'] = 'The Email Address you have entered already exists.';
        header("Location:new_registration");
    }
    else{
        $user_id = genVend_user_id();
        $user = array(
            'user_id' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'user_password' => password_hash($password, PASSWORD_DEFAULT),
            'last_login' => $login_date,
            'login_count' => 1 //First Time
        );
        DB::insert('vendor_users', $user);
        $_SESSION['signed_in'] = true;
        $_SESSION['user_id'] = $user_id;
        header("Location:home"); 
    }
}
?>