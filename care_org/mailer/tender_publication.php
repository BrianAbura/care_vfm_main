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

$tender_id = $request->tender_id;
//Params for the tender
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $tender['category']);
$method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);

$title = $tender['tender_title'];
$category = $category['name'];
$method = $method['method_name'];
$deadline = date_format(date_create($tender['submission_date']), 'd M Y | h:i a');

$vendor_list = DB::query('SELECT * from tender_shortlist where tender_id=%s', $tender_id); //Shortlited Vendors
foreach($vendor_list as $vend){
    //Vendor Name
    $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $vend['vendor_id']);
    $vendor_name = $vendor['vendor_name'];

    $vendor_user = DB::queryFirstRow('SELECT * from vendor_users where user_id=%s', $vendor['vendor_user_id']);
    $vendor_email = $vendor_user['email_address'];

    $msg_body = tender_publication_template($vendor_name, $title, $category, $method, $deadline);
    $subject = "CARE TENDER NOTICE";


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
    $mailer->addAddress($vendor_email, $vendor_name);
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

}
