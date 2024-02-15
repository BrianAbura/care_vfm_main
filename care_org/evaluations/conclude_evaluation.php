<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$narration = filter_var(( isset( $_REQUEST['narration'] ) )?  $_REQUEST['narration']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$level = filter_var(( isset( $_REQUEST['level'] ) )?  $_REQUEST['level']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if($token == "approve_all"){
    $add_report = array(
        'tender_id' => $tender_id,
        'decision' => 'Approved',
        'narration' => $narration,
        'level' => $level,
        'user_id' => $_SESSION['user_id']
    );
    DB::insert('completed_evaluations', $add_report);
    if($level == 1){
        $msg = "The evaluation has been finalized and the report submitted to the procurement department for further action.";
    }
    elseif($level == 2){
        $msg = "The evaluation report has been submitted to SMT for review.";
    }
    else{
        $msg = "The evaluation report has been approved award to the vendor confirmed.";
    }

    $response = array(
        'Status'=> "Success",
        'Message'=> $msg
    );
    print_r(json_encode($response));
}


elseif($token == "discard_all"){
    $add_report = array(
        'tender_id' => $tender_id,
        'decision' => 'Discarded',
        'narration' => $narration,
        'level' => $level,
        'user_id' => $_SESSION['user_id']
    );
    DB::insert('completed_evaluations', $add_report);
    //Erase all evaluations
    DB::delete('evaluation_summary', 'tender_id=%s', $tender_id);
    $update = array(
        'status' => 1,
        'date_modified' => date('Y-m-d H:i:s')
    );

    DB::update('evaluations', $update, 'tender_id=%s', $atender_id);
    DB::update('financial_evaluations', $update, 'tender_id=%s', $atender_id);

    $response = array(
        'Status'=> "Success",
        'Message'=> "The evaluation has been discard and reverted to all evaluators for re-evaluation."
    );
    print_r(json_encode($response));
}

else{
    $response = array(
        'Status'=> "Error",
        'Message'=> "The information you are trying to access does not exist."
    );
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}
?>