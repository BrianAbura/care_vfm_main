<?php
/**
 * Account Login for Staff
 */
session_start();
require_once 'defines/functions.php';

$user_id = htmlspecialchars((isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : null);
$user_password = htmlspecialchars((isset($_REQUEST['user_password'])) ? $_REQUEST['user_password'] : null);
$repeat_password = htmlspecialchars((isset($_REQUEST['repeat_password'])) ? $_REQUEST['repeat_password'] : null);

$login_date = date('Y-m-d H:i:s');

    $user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $user_id);
    

    if (isset($user['email_address'])) {
        if($user['acc_status'] != 'Active'){
            $_SESSION['error_msg'] = "Your account is not active. Please contact the System Administrator.";
            header("Location:login_page_redirect");
        }
        elseif($user_password != $repeat_password){
            $_SESSION['error_msg'] = "The passwords do not match. Please try again.";
            header("Location: verify_changes.php?email=".$user['email_address']);
        }
        else{
            DB::update("org_users",array(
                "user_password"=>password_hash($user_password, PASSWORD_DEFAULT), 
                "date_modified"=> $login_date), "user_id=%s",$user['user_id']);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> "Your account password has been changed Successfully. Proceed to login."
                );
                $_SESSION['action_response'] = json_encode($response);
            
            header("Location:login_page_redirect");
        }  
    } 
    else {
        $_SESSION['error_msg'] = "Account not found!";
        header("Location:login_page_redirect");
    }

