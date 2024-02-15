<?php 
/**
 * Department Class that manages all Department Actions
 * 
 */
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Profile{
        public $id;

        //Edit Profile
        public function editProfile($id, $email){
            $query = DB::queryFirstRow('SELECT * from org_users where email_address=%s AND user_id NOT IN %ls', $email, [$id]);
            if(isset($query)){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The updated Email Address already exists.'
                );
            }
            else{
                $update = array(
                    'email_address' => $email,
                    'date_modified' => date('Y-m-d H:i:s')
                );
                DB::update('org_users', $update, 'user_id=%s', $id);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> "Your Email Address has been updated Successfully."
                );
            }
        return $response;
        }
          //Edit Password
        public function editPassword($id, $password){
                $update = array(
                    'user_password' => password_hash($password, PASSWORD_DEFAULT),
                    'date_modified' => date('Y-m-d H:i:s')
                );
                DB::update('org_users', $update, 'user_id=%s', $id);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> "Your login Password has been updated Successfully. <br/> Please login to resume session."
                );

        return $response;
        }
}

$usr = new Profile();
if(isset($_REQUEST['token'])) {
    $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Edit Password
        if($token == "edit_profile"){
            $user_id = filter_var(trim($_REQUEST['user_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL);

            $response = $usr->editProfile($user_id, $email);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../profile");
        }
        //Edit Password
        elseif($token == "edit_password"){
            $user_id = filter_var(trim($_REQUEST['user_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var(trim($_REQUEST['rpt_new_password']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $response = $usr->editPassword($user_id, $password);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../login_page_redirect");
        }
}
?>