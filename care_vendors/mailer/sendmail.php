<?php
require_once('../defines/defines.php');
require "vendor/autoload.php";
require_once('templates.php');
$log = new Logger(LOG_FILE,Logger::DEBUG);
use PHPMailer\PHPMailer\PHPMailer;
$developmentMode = true;

//Post Variables
$request = file_get_contents("php://input");
$log->LogInfo("EMAIL_SENDING_REQUEST_VENDOR: ".print_r($request,true));

$request = json_decode($request);
$email = $request->email;
$link_type = $request->link_type;

$query = DB::queryFirstRow('SELECT * from vendor_users where email_address=%s', $email);
$fullname = $query['first_name']." ".$query['last_name'];

$verification = array(
'ver_id' => mt_rand(111111, 999999),
'email' => $email,
'code' => password_hash($email, PASSWORD_DEFAULT),
'link_type' => $link_type,
);

$redirect_link = "https://vfmplatform.com/care_vendors/verify.php?".http_build_query($verification);

$verification['status'] = "PENDING";
$verification['expiry_date'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 1 day'));
DB::insert('verifications', $verification);

if($link_type == "password_reset"){
    $msg_body = password_reset_template($query['first_name'], $redirect_link);
    $subject = "Reset Password Notification";
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
?>
