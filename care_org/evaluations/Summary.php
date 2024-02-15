<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');


$formBtn = filter_var(( isset( $_REQUEST['formBtn'] ) )?  $_REQUEST['formBtn']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Multiples
$evaluation_id = ( isset( $_REQUEST['evaluation_id'] ) )?  $_REQUEST['evaluation_id']: null;

//Overall Decisions
$vendor_id = ( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null;
$overall_decision = ( isset( $_REQUEST['overall_decision'] ) )?  $_REQUEST['overall_decision']: null;
$overall_narration = ( isset( $_REQUEST['overall_narration'] ) )?  $_REQUEST['overall_narration']: null;

//Handle Stage 1
if($token == "preliminary_summary"){
    if($formBtn == 'reEvaluate'){
        if($evaluation_id != null){
            foreach($evaluation_id as $a=> $b){
                $query = DB::queryFirstRow('SELECT * from evaluations where id=%s', $evaluation_id[$a]);
                $q_tender = $query['tender_id'];
                $q_vendor = $query['vendor_id'];
                $q_evaluator = $query['user_id'];
               
                $all_criterias = DB::query('SELECT * from evaluations where stage=%s AND tender_id=%s AND vendor_id=%s AND user_id=%s', 1, $q_tender, $q_vendor, $q_evaluator);
                foreach($all_criterias as $all_criteria){
                    $update = array(
                        'status' => 1,
                        'date_modified' => date('Y-m-d H:i:s')
                    );
                    DB::update('evaluations', $update, 'id=%s', $all_criteria['id']);
                }
            }
            $response = array(
                'Status'=> "Success",
                'Message'=> "The evaluation process has been reverted for preliminary re-evaluation."
            );
        }
        else{
            $response = array(
                'Status'=> "Error",
                'Message'=> "You have not tasked any evaluator to re-evaluate the preliminary bids."
            );
        }
        print_r($response);
    }
    else{
        foreach($vendor_id as $a => $b){
            if(empty($overall_narration[$a])){
                $overall_narration[$a] = "";
            }
            $summary = array(
                'tender_id' => $tender_id,
                'vendor_id' => $vendor_id[$a],
                'user_id'   => $_SESSION['user_id'], //Secretary only
                'stage'     => 1,
                'decision'  => $overall_decision[$a],
                'narration' => $overall_narration[$a],
                'status'    => 2, //Final
            );
            DB::insert('evaluation_summary', $summary);
        }
        $response = array(
            'Status'=> "Success",
            'Message'=> "The Preliminary Evaluation summary has been completed successfully."
        );
        print_r($response);
    }
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}
   //Technical Evaluation Summary
elseif($token == "technical_summary"){
    if($formBtn == 'reEvaluate'){
        if($evaluation_id != null){
            foreach($evaluation_id as $a=> $b){
                $query = DB::queryFirstRow('SELECT * from evaluations where id=%s', $evaluation_id[$a]);
                $q_tender = $query['tender_id'];
                $q_vendor = $query['vendor_id'];
                $q_evaluator = $query['user_id'];
               
                $all_criterias = DB::query('SELECT * from evaluations where stage=%s AND tender_id=%s AND vendor_id=%s AND user_id=%s', 2, $q_tender, $q_vendor, $q_evaluator);
                foreach($all_criterias as $all_criteria){
                    $update = array(
                        'status' => 1,
                        'date_modified' => date('Y-m-d H:i:s')
                    );
                    DB::update('evaluations', $update, 'id=%s', $all_criteria['id']);
                }
            }
            $response = array(
                'Status'=> "Success",
                'Message'=> "The evaluation process has been reverted for techinical re-evaluation."
            );
        }
        else{
            $response = array(
                'Status'=> "Error",
                'Message'=> "You have not tasked any evaluator to re-evaluate the technical bids."
            );
        }
        print_r($response);
    }
    else{
        foreach($vendor_id as $a => $b){
            if(empty($overall_narration[$a])){
                $overall_narration[$a] = "";
            }
            $summary = array(
                'tender_id' => $tender_id,
                'vendor_id' => $vendor_id[$a],
                'user_id'   => $_SESSION['user_id'], //Secretary only
                'stage'     => 2,
                'decision'  => $overall_decision[$a],
                'narration' => $overall_narration[$a],
                'status'    => 2, //Final
            );
            DB::insert('evaluation_summary', $summary);
        }
        $response = array(
            'Status'=> "Success",
            'Message'=> "The Technical Evaluation summary has been completed successfully."
        );
        print_r($response);
    }
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}

    //Financial Evaluation Summary
elseif($token == "financial_summary"){
    if($formBtn == 'reEvaluate'){
        if($evaluation_id != null){
            foreach($evaluation_id as $a=> $b){
                $status = 1;
                $update = array(
                    'status' => 1,
                    'date_modified' => date('Y-m-d H:i:s')
                );
                DB::update('financial_evaluations', $update, 'id=%s', $evaluation_id[$a]);
            }
            $response = array(
                'Status'=> "Success",
                'Message'=> "The evaluation process has been reverted for financial re-evaluation"
            );
        }
        else{
            $response = array(
                'Status'=> "Error",
                'Message'=> "You have not tasked any evaluator to re-evaluate the financial bids."
            );
        }
       
        print_r($response);
    }
    else{
        foreach($vendor_id as $a => $b){
            if(empty($overall_narration[$a])){
                $overall_narration[$a] = "";
            }
            $summary = array(
                'tender_id' => $tender_id,
                'vendor_id' => $vendor_id[$a],
                'user_id'   => $_SESSION['user_id'], //Secretary only
                'stage'     => 3,
                'decision'  => $overall_decision[$a],
                'narration' => $overall_narration[$a],
                'status'    => 2, //Final
            );
            DB::insert('evaluation_summary', $summary);
        }
        $response = array(
            'Status'=> "Success",
            'Message'=> "The financial Evaluation summary has been completed successfully."
        );
        print_r($response);
    }
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}
else{
    $response = array(
        'Status'=> "Error",
        'Message'=> "The record you are trying to access does not exist."
    );
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}
?>