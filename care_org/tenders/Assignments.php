<?php 
/**
 * Requisitions Class that manages all Requisition Actions
 * 
 */

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

$requisition_id = filter_var(( isset( $_REQUEST['requisition_id'] ) )?  $_REQUEST['requisition_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$user_id = filter_var(( isset( $_REQUEST['user_id'] ) )?  $_REQUEST['user_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

$user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $user_id);
$name = $user['first_name'].' '.$user['last_name'];

//Acions Here
$de_assign = array(
'status'  =>  "Unassigned",
'date_modified' => date('Y-m-d H:i:s')
);
DB::update('requisition_assign', $de_assign, 'requisition_id=%s', $requisition_id);

//Assign
$assign = array(
'user_id'=>  $user_id,
'requisition_id' => $requisition_id,
'status'  =>  "Active",
'date_assigned' => date('Y-m-d H:i:s')
);

DB::insert('requisition_assign', $assign);

$response = array(
    'Status'=>'Success',
    'Message'=> $name.' has been assigned this requisition and can now manage all tender processes of this requisition.'
);

$_SESSION['action_response'] = json_encode($response);
header("Location:../requisitions/".$requisition_id."-assign");
?>