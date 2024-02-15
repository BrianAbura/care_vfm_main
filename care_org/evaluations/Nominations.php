<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

//Nominations of Secretary
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Add/Edit Committee
$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Committee
$evaluation_committee = ( isset( $_REQUEST['evaluation_committee'] ) )?  $_REQUEST['evaluation_committee']: null;
$evaluation_secretary = ( isset( $_REQUEST['evaluation_secretary'] ) )?  $_REQUEST['evaluation_secretary']: null;

 //Evaluation Committee
$all_committee_members = array();
foreach($evaluation_committee as $a=> $b)
{
     $add = array(
     'tender_id'=> $tender_id,
     'user_id'=> $evaluation_committee[$a],
     );
    array_push($all_committee_members, $add);
}


if(isset($_REQUEST['token'])) {
    if($token == "add_committee"){ 
        $count = sizeof($all_committee_members);
        //Evaluation Committee members must more than 3 and an odd number.
        if($count < 3 || ($count % 2 == 0)){
            $response = array(
                'Status'  => 'Error',
                'Message' => 'Evaluation Committee members must more than 3 and an odd number.'
            );
        }
        else{
            DB::insert('tender_committee', $all_committee_members); //Add Members
            $add_secretary = array(
                'tender_id'=> $tender_id,
                'user_id'   => $evaluation_secretary,
                'role'   => 'Secretary'
            );
            DB::insert('evaluation_nominations', $add_secretary); //Add Secretary
            $response = array(
                'Status'  => 'Success',
                'Message' => 'Evaluation Committee members have been successfully nominated.'
            );
        }
    }
    else{
        $response = array(
            'Status'  => 'Error',
            'Message' => 'Your request cannot be completed.'
        );
    }
}

$_SESSION['action_response'] = json_encode($response);
header("Location:../evaluations/all_evaluations");

/*
if(isset($_REQUEST['token'])) {
    if($token == "evaluate_preliminary_bid"){ 
        if($formAction == "SaveDraft"){
            $status = 1; //Draft
            $response = $eval->evaluate_preliminary($preliminary, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-preliminary");
        }
        else{
            $status = 2; //Final
            echo $status;
            $response = $eval->evaluate_preliminary($preliminary, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-preliminary");
        }
    }

    if($token == "evaluate_technical_bid"){ 
        if($formAction == "SaveDraft"){
            $status = 1; //Draft
            $response = $eval->evaluate_technical($technical, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-technical");
        }
        else{
            $status = 2; //Final
            echo $status;
            $response = $eval->evaluate_technical($technical, $status);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../evaluations/".$tender_id."-bids-technical");
        }
    }
}
*/
?>