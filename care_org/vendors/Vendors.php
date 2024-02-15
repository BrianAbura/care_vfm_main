<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Vendor{
        public $vendor_id;

        //Create Requisition
        public function vendor_review($status, $comments){
        $query = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $this->vendor_id);
        if($query){
            $resp = "Success";
            $msg = "Vendor Review has been successfully captured.";
            
            $add = array(
                'vendor_id'        =>  $this->vendor_id,
                'vendor_status'    =>  $status,
                'vendor_comments'  =>  $comments,
                'review_by'        =>  $_SESSION['user_id']
            );
            DB::insert('vendor_reviews', $add);
            $vendor = DB::queryFirstRow('SELECT * from vendor_users where user_id=%s', $query['vendor_user_id']);
            
            //Send Email Here
            $content = array(
            'link_type' => 'company_review',
            'name' => $query['vendor_name'],
            'vendor_type'=> $query['vendor_type'],
            'status' => $status,
            'comments' => $comments
            );
            vendor_mails($vendor['email_address'], $content);
            //Update Vendor
            DB::update('vendors', array('vendor_status'=> $status), 'vendor_id=%s', $this->vendor_id);
        }
        else{
            $resp = "Danger";
            $msg = "The Vendor Account does not exist";
        } 

        $response = array(
            'Status'=>$resp,
            'Message'=> $msg
        );

        return $response;
        }
}

  //formData
$vendor_id = filter_var(( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor_status = filter_var($_REQUEST['vendor_status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor_comments = filter_var(( isset( $_REQUEST['vendor_comments'] ) )?  $_REQUEST['vendor_comments']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

$vend = new Vendor;
$vend->vendor_id = $vendor_id;

$response = $vend->vendor_review($vendor_status, $vendor_comments);
$_SESSION['action_response'] = json_encode($response);
header("Location:../vendors/".$vendor_id."-view");
?>
