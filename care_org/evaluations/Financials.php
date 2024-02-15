<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');


Class Financials{
    public $tender_id;

    public function evaluate_financials($financials, $status){
        $financials['status'] = $status;
        $check_draft = DB::queryFirstRow('SELECT * from financial_evaluations where user_id=%s_user AND tender_id=%s_tender AND vendor_id=%s_vendor AND status=%s_stat', 
        [
            'user' => $financials['user_id'],
            'tender' => $this->tender_id,
            'vendor' => $financials['vendor_id'],
            'stat' => 1,
        ]);
            if($check_draft){
                DB::update('financial_evaluations', $financials, 'id=%s', $check_draft['id']);
            }
            else{
                DB::insert('financial_evaluations', $financials);
            }
        $msg_status = "Success";
        if($status == 1){
            $Message = "Financial Evaluation has been saved as draft.";
        }
        else{
            $Message = "Financial Evaluation has been saved successfully as final.";
        }
        $response = array(
            'Status'=> $msg_status,
            'Message'=> $Message
        );
    return $response;
    }
}

$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor_id = filter_var(( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Financials
$cur_sub_total = filter_var(( isset( $_REQUEST['cur_sub_total'] ) )?  $_REQUEST['cur_sub_total']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cur_vat = filter_var(( isset( $_REQUEST['cur_vat'] ) )?  $_REQUEST['cur_vat']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$corrected_vat = filter_var(( isset( $_REQUEST['corrected_vat'] ) )?  $_REQUEST['corrected_vat']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$target_dir = $BASEPATH."/attachments/";


$eval = new Financials;
$eval->tender_id = $tender_id;

$evaluation_doc = basename($_FILES["evaluation_doc_upload"]["name"]);
if(empty($evaluation_doc)){
    $evaluation_doc = "";
}
else{
    $evaluationName = 'Eval_doc-'.$tender_id.'-'.$vendor_id.'-'.$_SESSION['user_id'].'.' . strtolower(pathinfo($evaluation_doc,PATHINFO_EXTENSION));
    $evaluation_doc = $target_dir . $evaluationName;
    move_uploaded_file($_FILES["evaluation_doc_upload"]["tmp_name"], $target_dir . $evaluationName);
}

$add_financials = array(
    'tender_id'     => $tender_id,
    'vendor_id'     => $vendor_id,
    'user_id'       => $_SESSION['user_id'],
    'stage'         => 3,
    'cur_sub_total' => $cur_sub_total,
    'cur_vat'       => $cur_vat,
    'eval_vat'     => $corrected_vat,
    'eval_doc'     => $evaluation_doc
);

if(isset($_REQUEST['token'])) {
    if($token == "evaluate_financials_bid"){ 
        if($formAction == "SaveDraft"){
            $status = 1; //Draft
            $response = $eval->evaluate_financials($add_financials, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-financial");
        }
        else{
            $status = 2; //Final
            $response = $eval->evaluate_financials($add_financials, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-financial");
        }
    }
}
?>