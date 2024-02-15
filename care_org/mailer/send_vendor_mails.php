<?php
require_once('../defines/defines.php');
require "vendor/autoload.php";
require_once('templates.php');
$log = new Logger(LOG_FILE,Logger::DEBUG);
use PHPMailer\PHPMailer\PHPMailer;
$developmentMode = true;

//Post Variables
$request = file_get_contents("php://input");
$log->LogInfo("EMAIL_SENDING_REQUEST: ".print_r($request,true));
$request = json_decode($request);

$email = $request->email;
//Params for Email
$query = DB::queryFirstRow('SELECT * from vendor_users where email_address=%s', $email);
$fullname = $query['first_name']." ".$query['last_name'];

$content = $request->content;
//Params for Content
$link_type = $content->link_type;

if($link_type == "company_review"){
    $vendor_name = $content->name;
    $vendor_type = $content->vendor_type;
    $status = $content->status;
    $comments = $content->comments;

    if($status == 3){ //Approved
        $msg_body = vendor_review_approval($vendor_name);
        $subject = "Care Vendor Registration - Approval";
    }
    elseif($status == 4){ //On-hold
        $msg_body = vendor_review_hold($vendor_name);
        $subject = "Care Vendor Registration - On-Hold";
    }
    elseif($status == 5){ // Rejected
        $msg_body = vendor_review_rejection($vendor_name, $comments);
        $subject = "Care Vendor Registration - Rejection";
    }
}

$mailer = new PHPMailer($developmentMode);
//$mailer->SMTPDebug = 2;
$mailer->isSMTP();
$mailer->Host = EMAIL_HOST;
$mailer->SMTPAuth = true;
$mailer->Username = EMAIL_USER;
$mailer->Password = EMAIL_PASS;
$mailer->SMTPSecure = EMAIL_SECURE;
$mailer->Port = EMAIL_PORT;
$mailer->setFrom(EMAIL_USER, 'Care Uganda');
$mailer->addAddress($email, $fullname);
$mailer->isHTML(true);
$mailer->AddEmbeddedImage('care-int-logo.png', 'care_logo');
$mailer->Subject = $subject;
$mailer->Body = $msg_body;

if($mailer->send()) {
$log->LogInfo("MAIL_HAS_BEEN_SENT_SUCCESSFULLY");
} 
else{
$log->LogError("EMAIL_SENDING_FAILED: ".print_r($mailer->ErrorInfo,true));
}
