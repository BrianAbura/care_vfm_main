<?php 
/**
 * Requisitions Class that manages all Requisition Actions
 * 
 */

$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Requisitions{
        public $requisition_number;

        //Create Requisition
        public function create_requisition($status, $request, $items){
            //Status: 1 - Draft, 2 - Approved
            $query = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $request[0]); //Approved
            if(isset($query)){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The Requisition with ID number '.$request[0].' already exists.'
                );
            }
            else{ 
                if($status == 1){
                    $Message = "The requisition has been saved as draft.";
                }
                else{
                    $Message = "The requisition has been created Successfully.";
                }
                $add = array(
                    'requisition_number'=>  $request[0],
                    'department'        =>  $request[1],
                    'requisition_name'  =>  $request[2],
                    'currency'          =>  $request[3],
                    'category'          =>  $request[4],
                    'due_date'          =>  $request[5],
                    'distrib'           =>  $request[6],
                    'location'          =>  $request[7],
                    'gl_unit'           =>  $request[8],
                    'account'           =>  $request[9],
                    'alt_account'       =>  $request[10],
                    'fund'              =>  $request[11],
                    'pc_bus_unit'       =>  $request[12],
                    'project'           =>  $request[13],
                    'activity'          =>  $request[14],
                    'source_type'       =>  $request[15],
                    'affiliate'         =>  $request[16],
                    'fund_affiliate'    =>  $request[17],
                    'project_affiliate' =>  $request[18],
                    'status'            =>  $status,
                    'added_by'          =>  $_SESSION['user_id']
                );
                    DB::insert('requisitions', $add);

                foreach($items as $item){
                    DB::insert('requisition_items', $item);
                }

                $response = array(
                    'Status'=>'Success',
                    'Message'=> $Message
                );
            }
        return $response;
        }

        public function edit_requisition($status, $request, $items){
            if($status == 1){
                $Message = "The requisition has been saved as draft.";
            }
            else{
                $Message = "The requisition has been edited Successfully.";
            }
            $update = array(
                'requisition_number'=>  $request[0],
                'department'        =>  $request[1],
                'requisition_name'  =>  $request[2],
                'currency'          =>  $request[3],
                'category'          =>  $request[4],
                'due_date'          =>  $request[5],
                'distrib'           =>  $request[6],
                'location'          =>  $request[7],
                'gl_unit'           =>  $request[8],
                'account'           =>  $request[9],
                'alt_account'       =>  $request[10],
                'fund'              =>  $request[11],
                'pc_bus_unit'       =>  $request[12],
                'project'           =>  $request[13],
                'activity'          =>  $request[14],
                'source_type'       =>  $request[15],
                'affiliate'         =>  $request[16],
                'fund_affiliate'    =>  $request[17],
                'project_affiliate' =>  $request[18],
                'status'            =>  $status,
                'added_by'          =>  $_SESSION['user_id'],
                'date_modified'     =>  date('Y-m-d H:i:s')
            );
                $requisition_id = $request[19];
                DB::update('requisitions', $update, 'id=%s', $requisition_id);


            //Delet the Items First
             DB::delete('requisition_items', 'requisition_number=%s', $request[0]);
              foreach($items as $item){
                //$check_new = DB::queryFirstRow('SELECT * from requisition_items where id=%s', $item['id']);
                //if($check_new){
                 //   DB::update('requisition_items', $item, 'id=%s', $item['id']);
               // }
               // else{
                    $add_items = array(
                        'requisition_number'=> $item['requisition_number'],
                        'description'=> $item['description'],
                        'category_1'=> $item['category_1'],
                        'category_2'=> $item['category_2'],
                        'category_3'=> $item['category_3'],
                        'category_4'=> $item['category_4'],
                        'unit_of_measure'=> $item['unit_of_measure'],
                        'quantity'=> $item['quantity'],
                        'price'=> $item['price'],
                        'status'=> 1,
                        'added_by'=> $_SESSION['user_id'],
                        );
                    DB::insert('requisition_items', $add_items);
               // }
            }

            $response = array(
                'Status'=>'Success',
                'Message'=> $Message
            );

        return $response;
        }

        public function delete_requisition($id){ //Check for tender process in draft or processed.
            try {
                DB::delete('requisitions', 'requisition_number=%s', $id);
                DB::delete('requisition_items', 'requisition_number=%s', $id);
                $response = array("Status" => "Success","Message" => "The Requisition and all it's items have been deleted successfully.");
            } 
            catch(MeekroDBException $e) {
                $response = array("Status" => "Error","Message" => "An error has occured while deleting the requisition. Please try again later.");
            }
            return $response;
        }
}

  //formData
$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$requisition_number = filter_var($_REQUEST['requisition_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$requisition_id = filter_var(( isset( $_REQUEST['requisition_id'] ) )?  $_REQUEST['requisition_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$department_name = filter_var(( isset( $_REQUEST['department_name'] ) )?  $_REQUEST['department_name']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$requisition_name = filter_var(( isset( $_REQUEST['requisition_name'] ) )?  $_REQUEST['requisition_name']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$requisition_currency = filter_var(( isset( $_REQUEST['requisition_currency'] ) )?  $_REQUEST['requisition_currency']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$requisition_category = filter_var(( isset( $_REQUEST['requisition_category'] ) )?  $_REQUEST['requisition_category']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
//$requisition_due_date = filter_var(( isset( $_REQUEST['requisition_due_date'] ) )?  $_REQUEST['requisition_due_date']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$requisition_due_date = ( isset( $_REQUEST['requisition_due_date'] ) )?  $_REQUEST['requisition_due_date']: null;
// New Requirements
$distrib = ( isset( $_REQUEST['distrib'] ) )?  $_REQUEST['distrib']: null;
$location = ( isset( $_REQUEST['location'] ) )?  $_REQUEST['location']: null;
$gl_unit = ( isset( $_REQUEST['gl_unit'] ) )?  $_REQUEST['gl_unit']: null;
$account = ( isset( $_REQUEST['account'] ) )?  $_REQUEST['account']: null;
$alt_account = ( isset( $_REQUEST['alt_account'] ) )?  $_REQUEST['alt_account']: null;
$fund = ( isset( $_REQUEST['fund'] ) )?  $_REQUEST['fund']: null;
$pc_bus_unit = ( isset( $_REQUEST['pc_bus_unit'] ) )?  $_REQUEST['pc_bus_unit']: null;
$project = ( isset( $_REQUEST['project'] ) )?  $_REQUEST['project']: null;
$activity = ( isset( $_REQUEST['activity'] ) )?  $_REQUEST['activity']: null;
$source_type = ( isset( $_REQUEST['source_type'] ) )?  $_REQUEST['source_type']: null;
$affiliate = ( isset( $_REQUEST['affiliate'] ) )?  $_REQUEST['affiliate']: null;
$fund_affiliate = ( isset( $_REQUEST['fund_affiliate'] ) )?  $_REQUEST['fund_affiliate']: null;
$project_affiliate = ( isset( $_REQUEST['project_affiliate'] ) )?  $_REQUEST['project_affiliate']: null;

//FormItems
    $item_id = ( isset( $_REQUEST['item_id'] ) )?  $_REQUEST['item_id']: null;
    $item_description = ( isset( $_REQUEST['item_description'] ) )?  $_REQUEST['item_description']: null;
    $item_category_1 = ( isset( $_REQUEST['item_category_1'] ) )?  $_REQUEST['item_category_1']: null;
    $item_category_2 = ( isset( $_REQUEST['item_category_2'] ) )?  $_REQUEST['item_category_2']: null;
    $item_category_3 = ( isset( $_REQUEST['item_category_3'] ) )?  $_REQUEST['item_category_3']: null;
    $item_category_4 = ( isset( $_REQUEST['item_category_4'] ) )?  $_REQUEST['item_category_4']: null;
    $item_unit_of_measure = ( isset( $_REQUEST['item_unit_of_measure'] ) )?  $_REQUEST['item_unit_of_measure']: null;
    $item_quantity = ( isset( $_REQUEST['item_quantity'] ) )?  $_REQUEST['item_quantity']: null;
    $item_price = ( isset( $_REQUEST['item_price'] ) )?  $_REQUEST['item_price']: null;
    
    $item_quantity = str_replace(',','', $item_quantity);
    $item_price = str_replace(',','', $item_price);

$req = new Requisitions;
    if(isset($_REQUEST['token'])) {
        $token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $request_fields = array(
            $requisition_number, //0
            $department_name,    //1
            $requisition_name,   //2
            $requisition_currency, //3
            $requisition_category, //4
            date_format(date_create($requisition_due_date),"Y-m-d"), //5 
            // 6-18
            $distrib, 
            $location,
            $gl_unit,
            $account,
            $alt_account,
            $fund,
            $pc_bus_unit,
            $project,
            $activity,
            $source_type,
            $affiliate,
            $fund_affiliate,
            $project_affiliate,
        );
        
       $item_list = array();
       foreach($item_description as $a=> $b)
       {
            $items = array(
            'requisition_number'=> $requisition_number,
            'description'=> $item_description[$a],
            'category_1'=> $item_category_1[$a],
            'category_2'=> $item_category_2[$a],
            'category_3'=> $item_category_3[$a],
            'category_4'=> $item_category_4[$a],
            'unit_of_measure'=> $item_unit_of_measure[$a],
            'quantity'=> $item_quantity[$a],
            'price'=> $item_price[$a],
            'status'=> 1,
            'added_by'=> $_SESSION['user_id'],
            );
      array_push($item_list, $items);
       }

    //Start Token Tasks Here.   
        if($token == "create_requisition"){ //New Requisition
            if($formAction == "SaveDraft"){
                $status = 1;
                print_r(json_encode($req->create_requisition($status, $request_fields, $item_list)));
            }
            else{
                $status = 2;
                $response = $req->create_requisition($status, $request_fields, $item_list);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../requisitions/".$requisition_number."-view");
            }
        }
        elseif($token == "edit_requisition"){ //Edit Existing Requisition
            array_push($request_fields, $requisition_id); // - Item 19
            $item_list_edit = array();
            foreach($item_description as $a=> $b)
            {
                if(isset($item_id[$a])){
                    $item_id = $item_id[$a];
                }
                else{
                    $item_id = "";
                   
                }

                 $items = array(
                 'requisition_number'=> $requisition_number,
                 'id'=> $item_id,
                 'description'=> $item_description[$a],
                 'category_1'=> $item_category_1[$a],
                 'category_2'=> $item_category_2[$a],
                 'category_3'=> $item_category_3[$a],
                 'category_4'=> $item_category_4[$a],
                 'unit_of_measure'=> $item_unit_of_measure[$a],
                 'quantity'=> $item_quantity[$a],
                 'price'=> $item_price[$a],
                 'status'=> 1,
                 'added_by'=> $_SESSION['user_id'],
                 );
                array_push($item_list_edit, $items);
            }
     
            
            if($formAction == "SaveDraft"){
                $status = 1;
                print_r(json_encode($req->edit_requisition($status, $request_fields, $item_list_edit)));
            }
            else{
                $status = 2;
                $response = $req->edit_requisition($status, $request_fields, $item_list_edit);
                $_SESSION['action_response'] = json_encode($response);
                header("Location:../requisitions/".$requisition_number."-view");
            }

        }
        elseif($token == "delete_requisition"){ //Delete Requisition
            print_r(json_encode($req->delete_requisition($requisition_number)));
        }
        else{
            $response = array(
                'Status' => 'Error',
                'Message' => 'Your request cannot be completed.'
            );
            print_r(json_encode($response));
        }
        
    }

?>
