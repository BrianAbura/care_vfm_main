<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$beb_id = filter_var(( isset( $_REQUEST['beb_id'] ) )?  $_REQUEST['beb_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Multiple Records
$vendor_id = ( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null;
$stage = ( isset( $_REQUEST['stage'] ) )?  $_REQUEST['stage']: null;
$narration = ( isset( $_REQUEST['narration'] ) )?  $_REQUEST['narration']: null;

if($token == "publish_notice"){
    //Handle BEB First
   $best_vendor = array(
        'tender_id' => $tender_id,
        'vendor_id' => $beb_id,
        'stage' => 3,
        'narration' => 'BEB',
        'user_id' => $_SESSION['user_id']
   );
   DB::insert('published_notice', $best_vendor);

    //Handle BEB First
   foreach($vendor_id as $a=> $b){
        $other_vendors = array(
        'tender_id' =>$tender_id,
        'vendor_id' => $vendor_id[$a],
        'stage' => $stage[$a],
        'narration' => $narration[$a],
        'user_id' => $_SESSION['user_id']
    );
    DB::insert('published_notice', $other_vendors);
   }
    $response = array(
        'Status'=> "Success",
        'Message'=> "The tender has been published and the reason for elimination sent to all unsuccessful vendors."
    );
    print_r(json_encode($response));
}
else{
    $response = array(
        'Status'=> "Error",
        'Message'=> "The information you are trying to access does not exist."
    );
    print_r(json_encode($response));
}
?>