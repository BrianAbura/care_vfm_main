<?php 
/**
 * Department Class that manages all Department Actions
 * 
 */
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Thresholds{
        public $id;

        //Edit Threshold
        public function editThreshold($id, $min_amount, $max_amount){
            $min_amount = str_replace(',', '', $min_amount);
            $max_amount = str_replace(',', '', $max_amount);
            $query = DB::queryFirstRow('SELECT * from thresholds where id=%s', $id);
           
            $category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $query['proc_category']);
            $method = DB::queryFirstRow('SELECT * from procurement_methods where id=%d', $query['proc_method']);
            if($min_amount > $max_amount){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The Minimum threshold amount cannot be greater than the Maximum threshold amount.'
                );
            }
            else{
                $update = array(
                    'min_amount' => $min_amount,
                    'max_amount' => $max_amount,
                    'date_modified' => date('Y-m-d H:i:s')
                );
                DB::update('thresholds', $update, 'id=%s', $id);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> "The thresholds for ".$category['name']." - {".$method['method_name']."} has been updated Successfully."
                );
            }
        return $response;
        }
}

$thr = new Thresholds();
if(isset($_REQUEST['token'])) {
    $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($token == "edit_threshold"){
            $threshold_id = filter_var(trim($_REQUEST['threshold_id']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $min_amount = filter_var(trim($_REQUEST['min_amount']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $max_amount = filter_var(trim($_REQUEST['max_amount']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $response = $thr->editThreshold($threshold_id,$min_amount, $max_amount);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../system_management/thresholds");
        }
}
?>