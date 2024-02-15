    <?php
    /**
     * Account Login for Staff
     */
    session_start();
    require_once 'defines/functions.php';

    $vendor_id = htmlspecialchars((isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : null);
    $vendor_password = htmlspecialchars((isset($_REQUEST['user_password'])) ? $_REQUEST['user_password'] : null);
    $repeat_password = htmlspecialchars((isset($_REQUEST['repeat_password'])) ? $_REQUEST['repeat_password'] : null);

    $login_date = date('Y-m-d H:i:s');

        $vendor = DB::queryFirstRow('SELECT * from vendor_users where user_id=%s', $vendor_id);
        

        if (isset($vendor['email_address'])) {
            if($vendor_password != $repeat_password){
                $_SESSION['error_msg'] = "The passwords do not match. Please try again.";
                header("Location: verify_changes.php?email=".$vendor['email_address']);
            }
            else{
                DB::update("vendor_users",array(
                    "user_password"=>password_hash($vendor_password, PASSWORD_DEFAULT),
                    "def_password_change" => $vendor['def_password_change'] + 1,
                    "date_modified"=> $login_date), "user_id=%s",$vendor['user_id']);
                    $response = array(
                        'Status'=>'Success',
                        'Message'=> "Your account password has been modified Successfully. Proceed to login."
                    );
                    $_SESSION['action_response'] = json_encode($response);
                
                header("Location:login_page_redirect");
            }  
        } 
        else {
            $_SESSION['error_msg'] = "Account not found!";
            header("Location:login_page_redirect");
        }
?>
