<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class TenderReview{
        public $tender_id;

        public function tender_review($decision, $narration){
        $query = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $this->tender_id);
            if($query){
                $add = array(
                    'tender_id' =>  $this->tender_id,
                    'decision'  =>  $decision,
                    'narration' =>  $narration,
                    'reviewer'  =>  $_SESSION['user_id']
                );
                DB::insert('tender_reviews', $add);
                //Update Tenders
                DB::update('tenders', array('status'=> $decision), 'tender_id=%s', $this->tender_id);

                $response = array(
                    'Status'=>"Success",
                    'Message'=> "Your review decision has been captured successfully."
                );
            }
            else{
                $response = array(
                    'Status'=>"Danger",
                    'Message'=> "The Tendor does not exist."
                );
            } 
        return $response;
        }

        public function publish_tender($dates){
            $query = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $this->tender_id);
            if(!$query){
                $response = array(
                    'Status'=>"Danger",
                    'Message'=> "The Tendor does not exist."
                );
            }
            else{
                $update_tender = array(
                    'submission_date'     =>  $dates[0]." ".$dates[1],
                    'status'              =>  5,
                    'date_published'       =>  date('Y-m-d H:i:s'),
                    'published_by'       =>  $_SESSION['user_id']
                );
                DB::update('tenders', $update_tender, 'tender_id=%s', $this->tender_id);
                //Send Email here
                tender_publication($this->tender_id);
                
                $response = array(
                    'Status'=>"Success",
                    'Message'=> "The Tender has been successfully published."
                );
            }
        return $response;
        }
}

  //formData
$review_type = filter_var(( isset( $_REQUEST['review_type'] ) )?  $_REQUEST['review_type']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$decision = filter_var($_REQUEST['decision'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$narration = filter_var(( isset( $_REQUEST['narration'] ) )?  $_REQUEST['narration']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

//For Publication
$submission_date = filter_var(( isset( $_REQUEST['submission_date'] ) )?  $_REQUEST['submission_date']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$submission_time = filter_var(( isset( $_REQUEST['submission_time'] ) )?  $_REQUEST['submission_time']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

$review = new TenderReview;
$review->tender_id = $tender_id;

if($review_type == "smt_review"){
    $response = $review->tender_review($decision, $narration);
}
else{
    $dates = array(
        date_format(date_create($submission_date),"Y-m-d"), 
        date_format(date_create($submission_time),"H:i")
    );
    $response = $review->publish_tender($dates);
}

$_SESSION['action_response'] = json_encode($response);
header("Location:../tenders");
?>