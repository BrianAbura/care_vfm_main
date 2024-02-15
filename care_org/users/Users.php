<?php 
/**
 * Department Class that manages all Department Actions
 * 
 */
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Users{
        public $id;
        public $firstname;
        public $lastname;
        public $email;

        //Create Department
        public function addUser($firstname, $lastname, $email, $role, $department){
            $query = DB::queryFirstRow('SELECT * from org_users where first_name=%s OR email_address=%s', $firstname, $email);
            if(isset($query)){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The user account already exists.'
                );
            }
            else{
                $add = array(
                    'user_id' => mt_rand(111111, 999999),
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'email_address' => $email,
                    'role_id' => $role,
                    'department_id' => $department,
                    'user_password' => password_hash($email, PASSWORD_DEFAULT)
                );
                DB::insert('org_users', $add);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $firstname." ".$lastname."\'s account has been created Successfully."
                );
                //Send Email Here
                send_mail($email, 'registration');
            }
        return $response;
        }
        public function editUser($id, $firstname, $lastname, $email, $role, $department, $acc_status){
            $query = DB::queryFirstRow('SELECT * from org_users where email_address=%s AND user_id NOT IN %ls', $email, [$id]);
            if(isset($query)){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The updated user Email Address already exists.'
                );
            }
            else{
                $update = array(
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'email_address' => $email,
                    'role_id' => $role,
                    'department_id' => $department,
                    'acc_status' => $acc_status,
                    'date_modified' => date('Y-m-d H:i:s')
                );
                DB::update('org_users', $update, 'user_id=%s', $id);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $firstname." ".$lastname."\'s account has been updated Successfully."
                );
            }
        return $response;
        }
        public function resendVerification($id){
            $user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $id);
            if($user['acc_status'] != "Active"){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> $user['first_name']." ".$user['last_name']."\'s account is not active. Please activate it first."
                );
            }
            else{
                //Send Email Here
                send_mail($user['email_address'], 'registration');
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $user['first_name']." ".$user['last_name']."\'s account verfication email has been sent."
                );
            }
        return $response;
        }
}

$user_id = filter_var(trim($_REQUEST['user_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$firstname = filter_var(trim($_REQUEST['firstname']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lastname = filter_var(trim($_REQUEST['lastname']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
$role = filter_var(trim($_REQUEST['role']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$department = filter_var(trim($_REQUEST['department']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$acc_status = filter_var(trim($_REQUEST['acc_status']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$usr = new Users();
if(isset($_REQUEST['token'])) {
    $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Create User
        if($token == "create_user"){
            $response = $usr->addUser($firstname, $lastname, $email, $role, $department);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:add_user");
        }
        //Edit User
        elseif($token == "edit_user"){            
            $response = $usr->editUser($user_id, $firstname, $lastname, $email, $role, $department,$acc_status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../users/".$user_id);
        }
        elseif($token == "resend_verification"){
            $response = $usr->resendVerification($user_id);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../users");
        }
        else{
            $response = array(
                'Status'=>'Error',
                'Message'=> "Invalid Request."
            );
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../users");
        }
}
?>