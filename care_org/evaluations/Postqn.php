<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

$token = filter_var(( isset( $_REQUEST['token'] ) )?  $_REQUEST['token']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Delete
$postqn_id = filter_var(( isset( $_REQUEST['postqn_id'] ) )?  $_REQUEST['postqn_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Multiple Records
$vendor_id = ( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null;
$postqn_narration = ( isset( $_REQUEST['postqn_narration'] ) )?  $_REQUEST['postqn_narration']: null;
$target_dir = $BASEPATH."/attachments/";
$curTime = date('Ymdhis');

if($token == "add_postqn"){
    $postqn_report = [];
    $secNum = 1;
    $count=0;			
    foreach($_FILES['postqn_report']['name'] as $orig_file){
        $tmp = $_FILES['postqn_report']['tmp_name'][$count];
        $target_file = basename($orig_file);
        if(empty($target_file)){
            $target_file = "";
        }
        else{
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $imageName = 'Postqn_rpt-'.$tender_id.'-'.$curTime.'-'.$secNum.'.' .$imageFileType;
            $target_file = $target_dir . $imageName;
    
            move_uploaded_file($tmp,$target_file);
        }
        $count=$count + 1;
        array_push($postqn_report, $target_file);
        $secNum++;
    }
    foreach($vendor_id as $a => $b){		
        $add_postqn = array(
        'tender_id'=> $tender_id,
        'vendor_id'=> $vendor_id[$a],
        'report_file'=> $postqn_report[$a],
        'narration'=> $postqn_narration[$a],
        'user_id'=> $_SESSION['user_id'], //Secretary only
        );
        if(!empty($postqn_report[$a])){
            DB::insert('post_qualification', $add_postqn);
        }
    }
    $response = array(
        'Status'=> "Success",
        'Message'=> "Post Qualification Report has been added successfully."
    );
    
   $_SESSION['action_response'] = json_encode($response);
   header("Location:../evaluations/all_evaluations");
}

//Delete Post-Qualification Report
elseif($token = "del_postqn"){
    $query = DB::queryFirstRow('SELECT * from post_qualification where id=%s', $postqn_id);
        if($query){
            $name = DB::queryFirstRow('SELECT vendor_name from vendors where vendor_id=%s', $query['vendor_id']);
            $posted_file =  $query['report_file'];
            unlink($posted_file);
            DB::delete('post_qualification', 'id=%s', $postqn_id);
            $msg = "Post-Qualification Report for ".$name['vendor_name']." has been deleted.";
            $stat = "Success";
        }
        else{
            $msg = "Post-Qualification Report does not exist.";
            $stat = "Error";
        }
    $response = array(
        'Status'=> $stat,
        'Message'=> $msg
    );
    print_r(json_encode($response));
}
else{
    $response = array(
        'Status'=> "Error",
        'Message'=> "The information you are trying to access does not exist."
    );
    $_SESSION['action_response'] = json_encode($response);
    header("Location:../evaluations/all_evaluations");
}
?>