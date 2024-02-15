<?php 
require_once('defines.php');

//Get the Current User Details
function get_user($id){ 
    $user = DB::queryFirstRow('SELECT * from vendor_users where user_id=%s', $id);
    return $user;
}
function genVend_user_id(){
	$num = mt_rand(11111,99999);
	$query = DB::queryFirstRow('SELECT * from vendor_users where user_id=%s',$num);
	if(!isset($query['tender_id'])){
		return $num;
	}
}
function genVendId(){
	$num = mt_rand(11111,99999);
	$query = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s',$num);
	if(!isset($query['tender_id'])){
		return $num;
	}
}

function get_vendor_id($user_id){
	$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $user_id);
	if($vendor){
		return $vendor['vendor_id'];
	}
	
}

function check_vendor_status($vendor_id){
	$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s AND vendor_status=%s order by id desc',$vendor_id, 3); //Active Vendors
	if($vendor){
		return true;
	}
	else{
		return false;
	}
}

function publishedTenders(){
	//Competitive Sealed Bids
	$curDate = date('Y-m-d H:i:s');
    $pub_tenders = DB::query('SELECT * from tenders where solicitation_method=%s AND  status=%s AND submission_date>=%s order by submission_date', 3, 5, $curDate); 
	return DB::count();				
}

function checkTenderApplication($tender_id, $vendor_id){
	$check = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $tender_id, $vendor_id);
	if($check){
		return true;
	}
	else{
		return false;
	}
}

function TenderApplicationStatus($tender_id, $vendor_id){
	$check = DB::queryFirstRow('SELECT * from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $tender_id, $vendor_id);
	if($check['status'] == 1){ //Draft
		return 1;
	}
	else{ //Final
		return 2;
	}
}

function TenderApplicationCount($vendor_id){
	if(DB::query('SELECT DISTINCT tender_id from tender_evaluation_app where vendor_id=%s and status=%d', $vendor_id, 2)){
		$sum = DB::count();
	}
	else{
		$sum = 0;
	}
	return $sum;
}
function send_mail($email, $link_Type){
    $log = new Logger(LOG_FILE,Logger::DEBUG);
    $data = array('email' => $email,'link_type'=>$link_Type);
    $url = "https://vfmplatform.com/care_vendors/mailer/sendmail.php"; 
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
