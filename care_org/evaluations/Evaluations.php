<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Evaluation{
        public $tender_id;

        public function nominate_member($nominate_member){
            $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $nominate_member);

            $resp = "Success";
            if($member){
                $msg = $member['first_name']." ".$member['last_name']." has been successfully nominated the evaluation committee secretary.";
            
                $add = array(
                    'tender_id'  =>  $this->tender_id,
                    'user_id'    =>  $nominate_member,
                    'role'    =>  "Secretary"
                );
                DB::insert('evaluation_nominations', $add);
            }
            

        $response = array(
            'Status'=>$resp,
            'Message'=> $msg
        );

        return $response;
    }

    //Evaluate Bids
    public function evaluate_preliminary($preliminary, $status){
        foreach($preliminary as $criteria){
            $criteria['status'] = $status;
            $check_draft = DB::queryFirstRow('SELECT * from evaluations where user_id=%s_uid AND tender_eval_app_id=%s_tid AND stage=%s_stage AND (status=%s_stat1 OR status=%s_stat2)', 
            [
                'uid' => $criteria['user_id'],
                'tid' => $criteria['tender_eval_app_id'],
                'stage' => $criteria['stage'],
                'stat1' => 1,
                'stat2' => 2
            ]
            );
            if($check_draft){
                DB::update('evaluations', $criteria, 'id=%s', $check_draft['id']);
            }
            else{
                DB::insert('evaluations', $criteria);
            }
        }
        $msg_status = "Success";
        if($status == 1){
            $Message = "Your Preliminary Evaluation has been saved as draft.";
        }
        else{
            $Message = "Your Preliminary Evaluation has been saved successfully as final.";
        }
        $response = array(
            'Status'=> $msg_status,
            'Message'=> $Message
        );
    return $response;
    }

    public function evaluate_technical($technical, $status){
        foreach($technical as $criteria){
            $criteria['status'] = $status;
            $check_draft = DB::queryFirstRow('SELECT * from evaluations where user_id=%s_uid AND tender_eval_app_id=%s_tid AND stage=%s_stage AND (status=%s_stat1 OR status=%s_stat2)', 
            [
                'uid' => $criteria['user_id'],
                'tid' => $criteria['tender_eval_app_id'],
                'stage' => $criteria['stage'],
                'stat1' => 1,
                'stat2' => 2
            ]
            );
            if($check_draft){
                DB::update('evaluations', $criteria, 'id=%s', $check_draft['id']);
            }
            else{
                DB::insert('evaluations', $criteria);
            }
        }
        $msg_status = "Success";
        if($status == 1){
            $Message = "Your Technical Evaluation has been saved as draft.";
        }
        else{
            $Message = "Your Technical Evaluation has been saved successfully as final.";
        }
        $response = array(
            'Status'=> $msg_status,
            'Message'=> $Message
        );
    return $response;
    }

}

$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Nominations of Secretary
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$nominate_member = filter_var(( isset( $_REQUEST['nominate_member'] ) )?  $_REQUEST['nominate_member']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

//Evaluation Data - Draft and Final Submissions
$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor_id = filter_var(( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Preliminary
$pre_eval_id = ( isset( $_REQUEST['pre_eval_id'] ) )?  $_REQUEST['pre_eval_id']: null;
$pre_eval_decision = ( isset( $_REQUEST['pre_eval_decision'] ) )?  $_REQUEST['pre_eval_decision']: null;
$pre_eval_justify = ( isset( $_REQUEST['pre_eval_justify'] ) )?  $_REQUEST['pre_eval_justify']: null;

//Technical
$tech_eval_id = ( isset( $_REQUEST['tech_eval_id'] ) )?  $_REQUEST['tech_eval_id']: null;
$tech_eval_decision = ( isset( $_REQUEST['tech_eval_decision'] ) )?  $_REQUEST['tech_eval_decision']: null;
$tech_eval_justify = ( isset( $_REQUEST['tech_eval_justify'] ) )?  $_REQUEST['tech_eval_justify']: null;

 //Evaluation Criteria - Preliminary
$preliminary = array();
foreach($pre_eval_id as $a=> $b)
{
     $criteria1 = array(
     'tender_id'=> $tender_id,
     'vendor_id'=> $vendor_id,
     'user_id'=> $_SESSION['user_id'],
     'tender_eval_app_id'=> $pre_eval_id[$a],
     'stage'=> 1,
     'decision'=> $pre_eval_decision[$a],
     'narration'=> $pre_eval_justify[$a]
     );
array_push($preliminary, $criteria1);
}

 //Evaluation Criteria - Technical
$technical = array();
foreach($tech_eval_id as $a=> $b)
{
     $criteria2 = array(
        'tender_id'=> $tender_id,
        'vendor_id'=> $vendor_id,
        'user_id'=> $_SESSION['user_id'],
        'tender_eval_app_id'=> $tech_eval_id[$a],
        'stage'=> 2,
        'decision'=> $tech_eval_decision[$a],
        'narration'=> $tech_eval_justify[$a]
     );
array_push($technical, $criteria2);
}


$eval = new Evaluation;
$eval->tender_id = $tender_id;

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
?>