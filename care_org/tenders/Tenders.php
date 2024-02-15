<?php 
/**
 * Tenders Class that manages all Tender Actions
 * 
 */

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Tenders{
    public $tender_id;

    //Create Tenders
    public function create_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist){
        //Status: 1 - Draft, 2 - Pending Approval, 3 - Published
        $msg_status = 'Success';
        $query = DB::queryFirstRow('SELECT * from tenders where requisition_id=%s', $tender_fields[0]);
        if(isset($query)){
            $response = array(
                'Status'=>'Error',
                'Message'=> 'The Tender with requisition ID number '.$tender_fields[0].' already exists.'
            );
        }
        else{
            $add_tender = array(
                'requisition_id'      =>  $tender_fields[0],
                'tender_id'           =>  $tender_fields[1],
                'category'            =>  $tender_fields[2],
                'tender_title'        =>  $tender_fields[3],
                'solicitation_method' =>  $tender_fields[4],
                'cur_method'          =>  $tender_fields[5],
                'method_justify'      =>  $tender_fields[6],
                'location'            =>  $tender_fields[7],
                'submission_date'     =>  $tender_fields[8]." ".$tender_fields[9],
                'status'              =>  $status,
                'added_by'            =>  $_SESSION['user_id']
            );
                DB::insert('tenders', $add_tender);
            
            $add_notice = array(
                'tender_id' => $notice[0],
                'message'   => $notice[1],
            );
                DB::insert('tender_notice', $add_notice);

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
        }
    return $response;
    }


}

  //formData
$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$requisition_id = filter_var($_REQUEST['requisition_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

//Multiple_Selection
    $multiple_vendors = ( isset( $_REQUEST['multiple_vendors'] ) )?  $_REQUEST['multiple_vendors']: null;

    //Stage 1
    $prelimininary_description = ( isset( $_REQUEST['prelimininary_description'] ) )?  $_REQUEST['prelimininary_description']: null;

    //stage 2
    $tech_description = ( isset( $_REQUEST['tech_description'] ) )?  $_REQUEST['tech_description']: null;

    $tdr = new Tenders;
    //$tdr->tender_id = genTenderNum();
    $tender_id = $tdr->tender_id = genTenderNum();

    if(isset($_REQUEST['token'])) {
        $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //Tender Main Details
        $tender_fields = array(
            $requisition_id, //0
            $tender_id, //1
            $category,    //2
            $tender_title,   //3
            $solicitation_method, //4
            $init_method, //5
            $method_justification, //6
            $location, //7
            date_format(date_create($submission_date),"Y-m-d"), //8
            date_format(date_create($submission_time),"H:i") //9
        );
        
          //Tender Main Details
        $notice = array(
        $tender_id, //0
        $tender_notice, //1
        );

        //Evaluation Criteria - Preliminary
       $preliminary = array();
       foreach($prelimininary_description as $a=> $b)
       {
            $criteria1 = array(
            'tender_id'=> $tender_id,
            'stage'=> 1,
            'criteria_description'=> $prelimininary_description[$a],
            );
      array_push($preliminary, $criteria1);
       }

        //Evaluation Criteria - Technical
       $technical = array();
       foreach($tech_description as $a=> $b)
       {
            $criteria2 = array(
            'tender_id'=> $tender_id,
            'stage'=> 2,
            'criteria_description'=> $tech_description[$a],
            );
      array_push($technical, $criteria2);
       }

       //Vendor Shortlist.
       $shortlist = array();
        if($solicitation_method == 1){
            $vendor = array(
            'tender_id'=> $tender_id,
            'vendor_id'=> $sole_vendor,
            'submission_date'=> date_format(date_create($submission_date),"Y-m-d")." ".date_format(date_create($submission_time),"H:i")
            );
        array_push($shortlist, $vendor);
        }
        else{
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

     //Start Token Tasks Here.   
        if($token == "create_tender"){ //New Tender
            if($formAction == "SaveDraft"){
                $status = 1;
                print_r(json_encode($tdr->create_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist)));
            }
            elseif($formAction == "pendApproval"){
                $status = 2;
                $response = $tdr->create_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../tenders");
            }
            elseif($formAction == "Publish"){
                $status = 4;
                $response = $tdr->create_tender($status, $tender_fields, $notice, $preliminary, $technical, $shortlist);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../tenders");
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