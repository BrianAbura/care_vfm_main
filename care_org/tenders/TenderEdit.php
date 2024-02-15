<?php 
/**
 * Tenders Class that manages all Tender Actions
 * 
 */

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class TenderEdit{
    public $tender_id;

    public function edit_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist){
        //Status: 1 - Draft, 2 - Pending Approval, 4 - Published
        $msg_status = 'Success';

            $update_tender = array(
                'category'            =>  $tender_fields[0],
                'tender_title'        =>  $tender_fields[1],
                'solicitation_method' =>  $tender_fields[2],
                'cur_method'          =>  $tender_fields[3],
                'method_justify'      =>  $tender_fields[4],
                'location'            =>  $tender_fields[5],
                'submission_date'     =>  $tender_fields[6]." ".$tender_fields[7],
                'status'              =>  $status,
                'date_modified'       =>  date('Y-m-d H:i:s')
            );
                DB::update('tenders', $update_tender, 'tender_id=%s', $this->tender_id);
            
            $update_notice = array(
                'message'   => $notice[0],
            );
                DB::update('tender_notice', $update_notice, 'tender_id=%s', $this->tender_id);

            foreach($preliminary as $criteria){
                DB::insert('tender_evaluations', $criteria);
            }
            foreach($technical as $criteria){
                DB::insert('tender_evaluations', $criteria);
            }
            foreach($shortlist as $vendor){
                DB::insert('tender_shortlist', $vendor);
            }

            if($status == 1){
                $Message = "The Tender has been saved as draft.";
            }
            elseif($status == 2){
                if(checkTenderFields($this->tender_id)){
                    DB::update('tenders', array('status'=>1), 'tender_id=%s', $this->tender_id);
                    $Message = "Note: Some tender fields are missing. Review the tender details and re-submit for approval.";
                    $msg_status = "Error";
                }
                else{
                    $Message = "The Tender has been submitted to SMT for review.";
                }   
            }
            else{
                if(checkTenderFields($this->tender_id)){
                    DB::update('tenders', array('status'=>1), 'tender_id=%s', $this->tender_id);
                    $Message = "Note: Some tender fields are missing. Review the tender details and publish.";
                    $msg_status = "Error";
                }
                else{
                    $add = array(
                        'tender_id'   =>  $this->tender_id,
                        'decision'    =>  4,
                        'narration'   =>  'Auto Approved',
                        'reviewer'    =>  $_SESSION['user_id']
                    );
                    DB::insert('tender_reviews', $add);
                    $Message = "The Tender has been Successfully published and sent to the shortlisted vendors.";
                }
            }
            $response = array(
                'Status'=> $msg_status,
                'Message'=> $Message
            );
    return $response;
    }
    public function delete_vendor_shortlist($id){
        DB::delete('tender_shortlist', 'id=%s', $id);
        $response = array(
            'Status'=>'Success',
            'Message'=> 'The vendor has been removed from the shortlist'
        );
    return $response;
    }
}
  //formData
$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$tender_id = ( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null;

$category = filter_var(( isset( $_REQUEST['category'] ) )?  $_REQUEST['category']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$tender_title = filter_var(( isset( $_REQUEST['tender_title'] ) )?  $_REQUEST['tender_title']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$solicitation_method = filter_var(( isset( $_REQUEST['solicitation_method'] ) )?  $_REQUEST['solicitation_method']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$init_method = filter_var(( isset( $_REQUEST['init_method'] ) )?  $_REQUEST['init_method']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$method_justification = filter_var(( isset( $_REQUEST['method_justification'] ) )?  $_REQUEST['method_justification']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$location = filter_var(( isset( $_REQUEST['location'] ) )?  $_REQUEST['location']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$submission_date = filter_var(( isset( $_REQUEST['submission_date'] ) )?  $_REQUEST['submission_date']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$submission_time = filter_var(( isset( $_REQUEST['submission_time'] ) )?  $_REQUEST['submission_time']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$sole_vendor = filter_var(( isset( $_REQUEST['sole_vendor'] ) )?  $_REQUEST['sole_vendor']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$tender_notice = ( isset( $_REQUEST['tender_notice'] ) )?  $_REQUEST['tender_notice']: null;

$shortlist_id = ( isset( $_REQUEST['shortlist_id'] ) )?  $_REQUEST['shortlist_id']: null;
$com_member_id = ( isset( $_REQUEST['com_member_id'] ) )?  $_REQUEST['com_member_id']: null;

//Multiple_Selection
    $multiple_vendors = ( isset( $_REQUEST['multiple_vendors'] ) )?  $_REQUEST['multiple_vendors']: null;

    //Stage 1
    $prelimininary_id = ( isset( $_REQUEST['prelimininary_id'] ) )?  $_REQUEST['prelimininary_id']: null;
    $prelimininary_description = ( isset( $_REQUEST['prelimininary_description'] ) )?  $_REQUEST['prelimininary_description']: null;

    //stage 2
    $tech_id = ( isset( $_REQUEST['tech_id'] ) )?  $_REQUEST['tech_id']: null;
    $tech_description = ( isset( $_REQUEST['tech_description'] ) )?  $_REQUEST['tech_description']: null;

    $tdr = new TenderEdit;
    $tdr->tender_id = $tender_id;

    if(isset($_REQUEST['token'])) {
        $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //Tender Main Details
        $tender_fields = array(
            $category,    //0
            $tender_title,   //1
            $solicitation_method, //2
            $init_method, //3
            $method_justification, //4
            $location, //5
            date_format(date_create($submission_date),"Y-m-d"), //6
            date_format(date_create($submission_time),"H:i") //7
        );
        
          //Tender Edit
        $notice = array(
        $tender_notice, //o
        );

        //Evaluation Criteria - Preliminary
       $preliminary = array();
       foreach($prelimininary_description as $a=> $b)
       {
        $query_preliminary = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s', $prelimininary_id[$a]);
        if($query_preliminary){
            DB::update('tender_evaluations', array('criteria_description' => $prelimininary_description[$a]), 'id=%s', $prelimininary_id[$a]);
        }
        else{
            $criteria1 = array(
                'tender_id'=> $tender_id,
                'stage'=> 1,
                'criteria_description'=> $prelimininary_description[$a],
                );
            array_push($preliminary, $criteria1);
        }
       }

        //Evaluation Criteria - Technical
       $technical = array();
       foreach($tech_description as $a=> $b)
       {
        $query_technical = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s', $tech_id[$a]);
        if($query_technical){
            DB::update('tender_evaluations', array('criteria_description' => $tech_description[$a]), 'id=%s', $tech_id[$a]);
        }
        else{
            $criteria2 = array(
            'tender_id'=> $tender_id,
            'stage'=> 2,
            'criteria_description'=> $tech_description[$a],
            );
        array_push($technical, $criteria2);
        }
       }

       //Vendor Shortlist.
       
       $shortlist = array();
        if($solicitation_method == 1){
            if(!empty($sole_vendor)){
            $vendor = array(
            'tender_id'=> $tender_id,
            'vendor_id'=> $sole_vendor,
            'submission_date'=> date_format(date_create($submission_date),"Y-m-d")." ".date_format(date_create($submission_time),"H:i")
            );
        array_push($shortlist, $vendor);
            }
        }
        else{
            if(!empty($multiple_vendors)){
            foreach($multiple_vendors as $a=> $b)
            {
                 $vendors = array(
                    'tender_id'=> $tender_id,
                    'vendor_id'=> $multiple_vendors[$a],
                    'submission_date'=> date_format(date_create($submission_date),"Y-m-d")." ".date_format(date_create($submission_time),"H:i")
                 );
           array_push($shortlist, $vendors);
            }
        }
        }

     //Start Token Tasks Here.   
        if($token == "edit_tender"){ //Edit Tender
            if($formAction == "SaveDraft"){
                $status = 1;
                print_r(json_encode($tdr->edit_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist)));
            }
            elseif($formAction == "pendApproval"){
                $status = 2;
                $response = $tdr->edit_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../tenders");
            }
            elseif($formAction == "Publish"){
                $status = 4;
                $response = $tdr->edit_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../tenders");
            }
        }
        elseif($token == 'del_shortlist'){ //Delete__vendor_shortlist
          $response = $tdr->delete_vendor_shortlist($shortlist_id);
          print_r(json_encode($response));
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