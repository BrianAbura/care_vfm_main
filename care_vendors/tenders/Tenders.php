<?php 
/**
 * Tenders Class to Manage Bidders Tender Applications
 * 
 */

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Tenders{
    public $tender_id;
    public $vendor_id;

    //Create Tenders
    public function tender_apply($preliminary, $technical, $financial, $status){
        //Status: 1 - Draft, 2 - Final

            foreach($preliminary as $criteria){
                $criteria['status'] = $status;
                DB::insert('tender_evaluation_app', $criteria);
            }

            foreach($technical as $criteria){
                $criteria['status'] = $status;
                DB::insert('tender_evaluation_app', $criteria);
            }

            foreach($financial as $criteria){
                $criteria['status'] = $status;
                DB::insert('tender_finance_app', $criteria);
            }

            $msg_status = "Success";
            if($status == 1){
                $Message = "Your Tender application has been saved as draft.";
            }
            else{
                $Message = "Your Tender application has been submitted successfully.";
            }
            $response = array(
                'Status'=> $msg_status,
                'Message'=> $Message
            );
    return $response;
    }
}

  //formData
$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $_SESSION['user_id']);
$vendor_id = $vendor['vendor_id'];

$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$stage = filter_var(( isset( $_REQUEST['solicitation_method'] ) )?  $_REQUEST['solicitation_method']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$target_dir = $BASEPATH."/attachments/";

    //Stage 1 :: Preliminary
    $pre_criteria_id = ( isset( $_REQUEST['pre_criteria_id'] ) )?  $_REQUEST['pre_criteria_id']: null;
    $pre_vendor_resp = ( isset( $_REQUEST['pre_vendor_resp'] ) )?  $_REQUEST['pre_vendor_resp']: null;
    $pre_vendor_doc = ( isset( $_REQUEST['pre_vendor_doc'] ) )?  $_REQUEST['pre_vendor_doc']: null;

    //stage 2 :: Technical
    $tech_criteria_id = ( isset( $_REQUEST['tech_criteria_id'] ) )?  $_REQUEST['tech_criteria_id']: null;
    $tech_vendor_resp = ( isset( $_REQUEST['tech_vendor_resp'] ) )?  $_REQUEST['tech_vendor_resp']: null;
    $tech_vendor_doc = ( isset( $_REQUEST['tech_vendor_doc'] ) )?  $_REQUEST['tech_vendor_doc']: null;

    //stage 3 :: Financials
    $requisition_id = ( isset( $_REQUEST['requisition_id'] ) )?  $_REQUEST['requisition_id']: null;
    $finance_vendor_resp = ( isset( $_REQUEST['finance_vendor_resp'] ) )?  $_REQUEST['finance_vendor_resp']: null;
    $vat_vendor_resp = ( !empty( $_REQUEST['vat_vendor_resp'] ) )?  $_REQUEST['vat_vendor_resp']: 0;

    $finance_vendor_resp = str_replace(',','', $finance_vendor_resp); //Figures

    $tdr = new Tenders;

    if(isset($_REQUEST['token'])) {
        $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
       //Stage 1 :: Preliminary
       $preliminary = array();
       $pre_attachments = [];
        $secNum = 1;
        $count=0;			
        foreach($_FILES['pre_vendor_doc']['name'] as $orig_file){
            $tmp = $_FILES['pre_vendor_doc']['tmp_name'][$count];
            $target_file = basename($orig_file);
            if(empty($target_file)){
                $target_file = "";
            }
            else{
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $imageName = 'prem-'.$tender_id.'-'.$vendor_id.'-'.$secNum.'.' .$imageFileType;
                $target_file = $target_dir . $imageName;
    
                move_uploaded_file($tmp,$target_file);
                $count=$count + 1;
                array_push($pre_attachments, $target_file);
                $secNum++;
            }
        }
        foreach($pre_criteria_id as $a=> $b)
        {
            $stage1 = array(
            'tender_id'=> $tender_id,
            'vendor_id'=> $vendor_id,
            'stage'=> 1,
            'criteria_id'=> $pre_criteria_id[$a],
            'vendor_response'=> $pre_vendor_resp[$a],
            'resp_attachment'=> $pre_attachments[$a]
            );
            array_push($preliminary, $stage1);
        }

       //Stage 2 :: Technical
       $technical = array();
       $tech_attachments = [];
        $secNum2 = 1;
        $count=0;			
        foreach($_FILES['tech_vendor_doc']['name'] as $orig_file){
            $tmp = $_FILES['tech_vendor_doc']['tmp_name'][$count];
            $target_file = basename($orig_file);
            if(empty($target_file)){
                $target_file = "";
            }
            else{
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $imageName = 'tech-'.$tender_id.'-'.$vendor_id.'-'.$secNum2.'.' .$imageFileType;
                $target_file = $target_dir . $imageName;
    
                move_uploaded_file($tmp,$target_file);
                $count=$count + 1;
                array_push($tech_attachments, $target_file);
                $secNum2++;
            }
        }
        foreach($tech_criteria_id as $a=> $b)
        {
            $stage2 = array(
            'tender_id'=> $tender_id,
            'vendor_id'=> $vendor_id,
            'stage'=> 2,
            'criteria_id'=> $tech_criteria_id[$a],
            'vendor_response'=> $tech_vendor_resp[$a],
            'resp_attachment'=> $tech_attachments[$a]
            );
            array_push($technical, $stage2);
        }

        //Stage 3 :: Financials
       $financial = array();
        foreach($requisition_id as $a=> $b)
        {
            $stage3 = array(
            'tender_id'=> $tender_id,
            'vendor_id'=> $vendor_id,
            'stage'=> 3,
            'requisition_id'=> $requisition_id[$a],
            'vendor_response'=> $finance_vendor_resp[$a],
            'vat'=> $vat_vendor_resp,
            );
            array_push($financial, $stage3);
        }

        //Start Token Tasks Here.   
        if($token == "tender_apply"){
            if($formAction == "SaveDraft"){
                $status = 1;
                print_r(json_encode($tdr->tender_apply($preliminary, $technical, $financial, $status)));
            }
            else{
                $status = 2;
                $response = $tdr->tender_apply($preliminary, $technical, $financial, $status);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../tenders/".$tender_id."-view");
            }
        }
        else{
            $response = array(
                'Status'  => 'Error',
                'Message' => 'Your request cannot be completed.'
            );
            print_r(json_encode($response));
            header("Location:../tenders");
        }   
    }
?>