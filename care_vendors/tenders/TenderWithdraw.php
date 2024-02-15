<?php 
/**
 * Tenders Class to Manage Tenders Withdrawn
 * Note: Withrawing removes all the submitted details.
 */

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Tenders{
    public $tender_id;
    public $vendor_id;

    //Create Tenders
    public function tender_withdraw(){
        //Remove application for Evaluation and Financials
        $submitted_tenders = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $this->tender_id, $this->vendor_id);
        foreach($submitted_tenders as $application){
            if(file_exists($application['resp_attachment'])){
                unlink($application['resp_attachment']);
            }
        }
        DB::query("DELETE FROM tender_evaluation_app where tender_id=%s AND vendor_id=%s", $this->tender_id, $this->vendor_id);
        DB::query("DELETE FROM tender_finance_app where tender_id=%s AND vendor_id=%s", $this->tender_id, $this->vendor_id);

        $Message = "Your Tender application has been withdrawn successfully.";
        $response = array(
            'Status'=> "Success",
            'Message'=> $Message
        );
    return $response;
    }
}

$vendor_id = get_vendor_id($_SESSION['user_id']);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

$tdr = new Tenders;
$tdr->tender_id = $tender_id;
$tdr->vendor_id = $vendor_id;

if(isset($_REQUEST['token'])) {
    $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //Start Token Tasks Here.   
    if($token == "withdraw_tender"){
        print_r(json_encode($tdr->tender_withdraw()));
    }
    else{
        $response = array(
            'Status'  => 'Error',
            'Message' => 'Your request cannot be completed.'
        );
        print_r(json_encode($response));
    }   
}
?>