<?php 
require_once('defines.php');

//Get the Current User Details
function get_user($id){ 
    $user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $id);
    return $user;
}

function get_deparment($id){
    $dept = DB::queryFirstRow('SELECT * from departments where dept_id=%s', $id);
    return $dept['dept_id'].' - '.$dept['name'];
}

function get_roles($id){
    $dept = DB::queryFirstRow('SELECT role_desc from org_roles where id=%s', $id);
    return $dept['role_desc'];
}

function restrict_soc_oc($id){
    //Functionality will be restricted to SOC(3) and OC(4) roles
    if($id == 3 || $id == 4){
        return true;
    }
    else{
        return false;
    }
}
function restrict_soc($id){
    //Functionality will be restricted to SOC(3) Only
    if($id == 3){
        return true;
    }
    else{
        return false;
    }
}
function restrict_smt($id){
    //Functionality will be restricted to SMT(2) Only
    if($id == 2){
        return true;
    }
    else{
 
       return false;
    }
}
function restrict_it($id){
    //Functionality will be restricted to IT(11) Only
    if($id == 11){
        return true;
    }
    else{
        return false;
    }
}

function restrict_au($id){
    //Functionality will be restricted to Auditors/Reviewers Only - View rights only on everything
    if($id == 8){
        return true;
    }
    else{
        return false;
    }
}
function restrict_pcc($id){
    //Functionality will be restricted to ONLY the Procurement Committee Chairperson
    if($id == 9){
        return true;
    }
    else{
        return false;
    }
}
function restrict_pcc_pc($id){
       //Functionality will be restricted to either Procurement Committee/Chairperson
    if($id == 9 || $id == 10){
        return true;
    }
    else{
        return false;
    }
}
function genTenderNum(){
	$num = mt_rand(11111,99999);
	$query = DB::queryFirstRow('SELECT * from tenders where tender_id=%s',$num);
	if(!isset($query['tender_id'])){
		return $num;
	}
}

function checkTenderFields($tender_id){
    $evaluations = DB::queryFirstRow('SELECT * from tender_evaluations where tender_id=%s', $tender_id);
    $tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
    $vendor = true;
    if($tender['solicitation_method'] == 1 || $tender['solicitation_method'] == 2){// Direct Purchase and Quotations
        $vendor = DB::queryFirstRow('SELECT * from tender_shortlist where tender_id=%s', $tender_id);
    }
   
    if(!$evaluations || !$vendor){
        return true;
    }
    else{
        return false;
    }
}

function requisition_assigned_to($id){
    $query = DB::queryFirstRow('SELECT * from requisition_assign where requisition_id=%s AND status=%s', $id, 'Active');
    $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $query['user_id']);
    return $member['first_name']." ".$member['last_name'];
}

function is_evaluator($user_id, $tender_id){
	$query = DB::query('SELECT * from tender_committee where user_id=%s AND tender_id=%s',$user_id, $tender_id);
    if($query){
        return true;
    }
    else{
        return false;
    }
}

function is_eval_secretary($user_id, $tender_id){
	$query = DB::query('SELECT * from evaluation_nominations where user_id=%s AND tender_id=%s',$user_id, $tender_id);
    if($query){
        return true;
    }
    else{
        return false;
    }
}

function total_evaluators($tender_id){
	DB::query('SELECT * from tender_committee where tender_id=%s', $tender_id);
    $num = DB::count();
   return $num;
}

function total_evaluated_members($tender_id, $stage){ 
    //Status: (1) - Draft, (2) - Final
    $num = 0;
    $members = DB::query('SELECT * from tender_committee where tender_id=%s', $tender_id);
    if($stage == 1){
        //Preliminary Stage
        DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s AND status=%d AND stage=%d', $tender_id, 2, $stage);
    }
    else{ 
        //Technical Stage >> Only those complaint at Preliminary
       DB::query('SELECT DISTINCT vendor_id from evaluation_summary where tender_id=%s AND stage=%d AND decision=%s', $tender_id, 1, "Compliant");
    }
    $received_bids = DB::count();
    foreach($members as $member){
        DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s AND user_id=%s AND status=%s', $tender_id, $stage, $member['user_id'], 2);
        $evaluated_bids = DB::count();
        if($evaluated_bids == $received_bids){
            $num ++;
        }
    }
   return $num;
}

function total_evaluated_fin_members($tender_id){ 
    //Status: (1) - Draft, (2) - Final
    //Default stage 3 for Financials
	DB::query('SELECT DISTINCT user_id from financial_evaluations where tender_id=%s AND status=%s', $tender_id, 2);
    $num = DB::count();
   return $num;
}

function send_mail($email, $link_Type){
    $log = new Logger(LOG_FILE,Logger::DEBUG);
    $data = array('email' => $email,'link_type'=>$link_Type);
    $url = "https://vfmplatform.com/care_org/mailer/sendmail.php";
    
    $json_data = json_encode($data);
     $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
     curl_setopt($ch, CURLOPT_HEADER, 0); 
     curl_setopt($ch, CURLOPT_POST, 1); 
     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); 
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
     $ch_result = curl_exec($ch);
     //$log->LogInfo("EMAIL_RESPONSE: ".print_r($ch_result,true));
     curl_close($ch);
}

function vendor_mails($email, $content){
    $data = array('email' => $email, 'content'=>$content);
    $url = "https://vfmplatform.com/care_org/mailer/send_vendor_mails.php";
    
    $json_data = json_encode($data);
     $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
     curl_setopt($ch, CURLOPT_HEADER, 0); 
     curl_setopt($ch, CURLOPT_POST, 1); 
     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); 
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
     $ch_result = curl_exec($ch);
     //$log->LogInfo("EMAIL_RESPONSE: ".print_r($ch_result,true));
     curl_close($ch);
}

function tender_publication($tender_id){
    $data = array('tender_id' => $tender_id);
    $url = "https://vfmplatform.com/care_org/mailer/tender_publication.php";
    
    $json_data = json_encode($data);
     $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
     curl_setopt($ch, CURLOPT_HEADER, 0); 
     curl_setopt($ch, CURLOPT_POST, 1); 
     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); 
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
     $ch_result = curl_exec($ch);
     //$log->LogInfo("EMAIL_RESPONSE: ".print_r($ch_result,true));
     curl_close($ch);
}
?>
