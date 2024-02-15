<?php 
/**
 * Requisitions Class that manages all Requisition Actions
 * 
 */
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

//Uploaded Items
$token = ( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null;

$requisition_number = ( isset( $_REQUEST['requisition_number'] ) )?  $_REQUEST['requisition_number']: null; //For Requistion Table
$distrib = ( isset( $_REQUEST['distrib'] ) )?  $_REQUEST['distrib']: null;
$req_status = ( isset( $_REQUEST['req_status'] ) )?  $_REQUEST['req_status']: null;
$location = ( isset( $_REQUEST['location'] ) )?  $_REQUEST['location']: null;
$req_qty = ( isset( $_REQUEST['req_qty'] ) )?  $_REQUEST['req_qty']: null;
$merchandise_amt = ( isset( $_REQUEST['merchandise_amt'] ) )?  $_REQUEST['merchandise_amt']: null;
$currency = ( isset( $_REQUEST['currency'] ) )?  $_REQUEST['currency']: null;
$gl_unit = ( isset( $_REQUEST['gl_unit'] ) )?  $_REQUEST['gl_unit']: null;
$account = ( isset( $_REQUEST['account'] ) )?  $_REQUEST['account']: null;
$alt_account = ( isset( $_REQUEST['alt_account'] ) )?  $_REQUEST['alt_account']: null;
$dept_id = ( isset( $_REQUEST['dept_id'] ) )?  $_REQUEST['dept_id']: null;
$fund = ( isset( $_REQUEST['fund'] ) )?  $_REQUEST['fund']: null;
$pc_bus_unit = ( isset( $_REQUEST['pc_bus_unit'] ) )?  $_REQUEST['pc_bus_unit']: null;
$project = ( isset( $_REQUEST['project'] ) )?  $_REQUEST['project']: null;
$activity = ( isset( $_REQUEST['activity'] ) )?  $_REQUEST['activity']: null;
$source_type = ( isset( $_REQUEST['source_type'] ) )?  $_REQUEST['source_type']: null;
$req_category = ( isset( $_REQUEST['req_category'] ) )?  $_REQUEST['req_category']: null;
$affiliate = ( isset( $_REQUEST['affiliate'] ) )?  $_REQUEST['affiliate']: null;
$fund_affiliate = ( isset( $_REQUEST['fund_affiliate'] ) )?  $_REQUEST['fund_affiliate']: null;
$project_affiliate = ( isset( $_REQUEST['project_affiliate'] ) )?  $_REQUEST['project_affiliate']: null;

$target_dir = $BASEPATH."/attachments/";

    //Start Token Tasks Here.   
if($token == "upload_requisitions"){

    $people_soft_upload = basename($_FILES["people_soft_upload"]["name"]);

    foreach($requisition_number as $a=> $b)
    {
          //Check for Requisition
          $query = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $requisition_number[$a]);
          if(!$query){

            // Upload people Soft Extract

            $docName = 'People_soft_reqfile-'.$requisition_number[$a].'.' . strtolower(pathinfo($people_soft_upload,PATHINFO_EXTENSION));
            $req_attachment = $target_dir . $docName;
            move_uploaded_file($_FILES["people_soft_upload"]["tmp_name"], $target_dir . $docName);

            $requisition = array(
              'requisition_number'=>  $requisition_number[$a],
              'department'        =>  $dept_id[$a],
              'requisition_name'  =>  $requisition_number[$a],
              'currency'          =>  $currency[$a],
              'distrib'           =>  $distrib[$a],
              'req_status'        =>  $req_status[$a],
              'location'          =>  $location[$a],
              'req_qty'           =>  $req_qty[$a],
              'merchandise_amt'   =>  $merchandise_amt[$a],
              'gl_unit'           =>  $gl_unit[$a],
              'account'           =>  $account[$a],
              'alt_account'       =>  $alt_account[$a],
              'fund'              =>  $fund[$a],
              'pc_bus_unit'       =>  $pc_bus_unit[$a],
              'project'           =>  $project[$a],
              'activity'          =>  $activity[$a],
              'source_type'       =>  $source_type[$a],
              'req_category'      =>  $req_category[$a],
              'affiliate'         =>  $affiliate[$a],
              'fund_affiliate'    =>  $fund_affiliate[$a],
              'project_affiliate' =>  $project_affiliate[$a],
              'req_attachment'    =>  $req_attachment,
              'status'            =>  1,
              'added_by'          =>  $_SESSION['user_id']
          );
              DB::insert('requisitions', $requisition);
          }

      $items = array(
        'requisition_number'=> $requisition_number[$a],
        'description'=> $description[$a],
        'project_id'=> $project_id[$a],
        'category'=> $category[$a],
        'activity_id'=> $activity_id[$a],
        'fund_code'=> $fund_code[$a],
        'acc_code'=> $acc_code[$a],
        'business_unit'=> $business_unit[$a],
        'quantity'=> $quantity[$a],
        'price'=> $price[$a],
        'status'=> 1,
        'added_by'=> $_SESSION['user_id'],
        );
        // DB::insert('requisition_items', $items);
    }

    $response = array(
    'Status' => 'Success',
    'Message' => 'Requisitions have been successfully uploaded.'
    );
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../requisitions");
}
else{
    $response = array(
      'Status' => 'Error',
      'Message' => 'Your request cannot be completed.'
    );
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../requisitions");
}
?>