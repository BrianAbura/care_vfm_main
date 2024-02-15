<?php 
$BASEPATH = dirname(__DIR__);
require_once($BASEPATH.'/validator.php');

class Vendor{
        public $vendor_id;

        //Create Vendor
        public function AddVendor($status, $request, $unspsc, $attachments){
            //Status: 1 - Draft, 2 - Approved
            $query = DB::queryFirstRow('SELECT * from vendors where vendor_name=%s OR registration_num=%s', $request[1], $request[2]); //Existing
            if(isset($query)){
                $response = array(
                    'Status'=>'Error',
                    'Message'=> 'The Vendor registration details already exists.'
                );
            }
            else{ 
                if($status == 1){
                    $Message = "Your registration has been saved as draft.";
                }
                else{
                    $Message = "The registration has been Successfully received and is pending review.";
                }
                $add = array(
                    'vendor_user_id'     =>  $_SESSION['user_id'],
                    'vendor_id'          =>  $this->vendor_id,
                    'vendor_type'        =>  $request[0],
                    'vendor_name'        =>  $request[1],
                    'registration_num'   =>  $request[2],
                    'tin_num'            =>  $request[3],
                    'country'            =>  $request[4],
                    'city'               =>  $request[5],
                    'street_address'     =>  $request[6],
                    'email_address'      =>  $request[7],
                    'postal_code'        =>  $request[8], 
                    'phone_num'          =>  $request[9],
                    'website'            =>  $request[10],
                    'main_category'      =>  $request[11],
                    'vendor_status'      =>  $status,
                );
                    DB::insert('vendors', $add);

                foreach($unspsc as $item){
                    $item['vendor_id'] = $this->vendor_id;
                    DB::insert('vendor_categories', $item);
                }

                foreach($attachments as $attachment){
                    DB::insert('vendor_attachments', $attachment);
                }
    
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $Message
                );
            }
        return $response;
        }

        public function EditVendor($status, $request, $unspsc,$attachments){
            if($status == 1){
                $Message = "Your registration has been saved as draft.";
            }
            else{
                $Message = "The registration has been Successfully received and is pending review.";
            }
            $update = array(
                'vendor_type'        =>  $request[0],
                'vendor_name'        =>  $request[1],
                'registration_num'   =>  $request[2],
                'tin_num'            =>  $request[3],
                'country'            =>  $request[4],
                'city'               =>  $request[5],
                'street_address'     =>  $request[6],
                'email_address'      =>  $request[7],
                'postal_code'        =>  $request[8], 
                'phone_num'          =>  $request[9],
                'website'            =>  $request[10],
                'main_category'      =>  $request[11],
                'vendor_status'      =>  $status,
                'date_modified'      => date('Y-m-d H:i:s')                
            );
                
            DB::update('vendors', $update, 'vendor_id=%s', $this->vendor_id);

            DB::delete('vendor_categories', 'vendor_id=%s', $this->vendor_id);
            foreach($unspsc as $item){
                $item['vendor_id'] = $this->vendor_id;
                DB::insert('vendor_categories', $item);
            }

            foreach($attachments as $attachment){
                if(empty($attachment['id'])){
                    DB::insert('vendor_attachments', $attachment);
                }
                else{
                    DB::update('vendor_attachments', $attachment, 'id=%s', $attachment['id']);
                }
             }

            $response = array(
                'Status'=>'Success',
                'Message'=> $Message
            );
        
        return $response;
        }

        public function DeleteVendor(){ //Check for tender process in draft or processed.
            try {
                DB::delete('vendors', 'vendor_id=%s', $this->vendor_id);
                DB::delete('vendor_approvals', 'vendor_id=%s', $this->vendor_id);
                DB::delete('vendor_attachments', 'vendor_id=%s', $this->vendor_id);
                DB::delete('vendor_categories', 'vendor_id=%s', $this->vendor_id);
                $response = array("Status" => "Success","Message" => "Your registration details have been deleted successfully.");
            } 
            catch(MeekroDBException $e) {
                $response = array("Status" => "Error","Message" => "An error has occured while deleting your registration details. Please try again later.");
            }
            return $response;
        }
}

  //formData
$formAction = filter_var(( isset( $_REQUEST['formAction'] ) )?  $_REQUEST['formAction']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$vendorID = filter_var(( isset( $_REQUEST['vendor_id'] ) )?  $_REQUEST['vendor_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$vendor_name = filter_var($_REQUEST['vendor_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$registration_num = filter_var(( isset( $_REQUEST['registration_num'] ) )?  $_REQUEST['registration_num']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$tin_num = filter_var(( isset( $_REQUEST['tin_num'] ) )?  $_REQUEST['tin_num']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$email_address = filter_var(( isset( $_REQUEST['email_address'] ) )?  $_REQUEST['email_address']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$phone_number = filter_var(( isset( $_REQUEST['phone_number'] ) )?  $_REQUEST['phone_number']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$country = filter_var(( isset( $_REQUEST['country'] ) )?  $_REQUEST['country']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$city = filter_var(( isset( $_REQUEST['city'] ) )?  $_REQUEST['city']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$website = filter_var(( isset( $_REQUEST['website'] ) )?  $_REQUEST['website']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$street_address = filter_var(( isset( $_REQUEST['street_address'] ) )?  $_REQUEST['street_address']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$postal_address = filter_var(( isset( $_REQUEST['postal_address'] ) )?  $_REQUEST['postal_address']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
$target_dir = $BASEPATH."/attachments/";
$token = filter_var($_REQUEST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor_type = filter_var($_REQUEST['vendor_type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//Multiples
$main_category = ( isset( $_REQUEST['main_category'] ) )?  $_REQUEST['main_category']: null;
$business_categories = ( isset( $_REQUEST['business_categories'] ) )?  $_REQUEST['business_categories']: null;
$support_doc_name_1 = ( isset( $_REQUEST['support_doc_name_1'] ) )?  $_REQUEST['support_doc_name_1']: null;
$support_doc_name_2 = ( isset( $_REQUEST['support_doc_name_2'] ) )?  $_REQUEST['support_doc_name_2']: null;
$support_doc_name_3 = ( isset( $_REQUEST['support_doc_name_3'] ) )?  $_REQUEST['support_doc_name_3']: null;

$vend = new Vendor;
$vendor_id = $vend->vendor_id = genVendId(); 

    $unspsc = array();
    $main_category = implode(',', $main_category);

    $request_fields = array(
        $vendor_type, //0
        $vendor_name, //1
        $registration_num,    //2
        $tin_num,   //3
        $country, //4
        $city, //5
        $street_address, //6
        $email_address, //7
        $postal_address, //8
        $phone_number, //9
        $website, //10
        $main_category, //11
    );

//Start Token Tasks Here.   
if($token == "add_vendor"){  
    //Categories
    foreach($business_categories as $a=> $b)
    {
            $items = array(
            'fam_code'=> $business_categories[$a],
            );
        array_push($unspsc, $items);
    }
    $unspsc =  array_map("unserialize", array_unique(array_map("serialize", $unspsc)));

    //Attachments
    $vendor_logo = basename($_FILES["vendor_logo"]["name"]);
    $reg_cert = basename($_FILES["reg_cert"]["name"]);
    $trade_license = basename($_FILES["trade_license"]["name"]);
    $attachments = array();

    if(empty($vendor_logo) || empty($reg_cert) || empty($trade_license)){
        $vendor_logo = "";
        $reg_cert = "";
        $trade_license = "";
    }
    else{
        $logoName = 'Logo-'.$vendor_id.'.' . strtolower(pathinfo($vendor_logo,PATHINFO_EXTENSION));
        $regCertName = 'RegCert-'.$vendor_id.'.' . strtolower(pathinfo($reg_cert,PATHINFO_EXTENSION));
        $tradeLName = 'TradeL-'.$vendor_id.'.' . strtolower(pathinfo($trade_license,PATHINFO_EXTENSION));

        $vendor_logo = $target_dir . $logoName;
        $reg_cert = $target_dir . $regCertName;
        $trade_license = $target_dir . $tradeLName;

        move_uploaded_file($_FILES["vendor_logo"]["tmp_name"], $target_dir . $logoName);
        move_uploaded_file($_FILES["reg_cert"]["tmp_name"], $target_dir . $regCertName);
        move_uploaded_file($_FILES["trade_license"]["tmp_name"], $target_dir . $tradeLName);

        $logo = array('vendor_id' => $vendor_id,'description' => 'Profile','document_file' => $vendor_logo);
        $cer = array('vendor_id' => $vendor_id,'description' => 'National ID','document_file' => $reg_cert);
        $license = array('vendor_id' => $vendor_id,'description' => 'Business Operating License','document_file' => $trade_license);

        array_push($attachments, $logo);
        array_push($attachments, $cer);
        array_push($attachments, $license);
    }

      //Additional Attachments
      $support_doc_1 = basename($_FILES["support_doc_1"]["name"]);
      $support_doc_2 = basename($_FILES["support_doc_2"]["name"]);
      $support_doc_3 = basename($_FILES["support_doc_3"]["name"]);
      
     if(!empty($support_doc_1)){
        $docName = 'Doc1-'.$vendor_id.'.' . strtolower(pathinfo($support_doc_1,PATHINFO_EXTENSION));
        $doc_file = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_1"]["tmp_name"], $target_dir . $docName);
        $sup_doc_1 = array('vendor_id' => $vendor_id,'narration' => 'doc_1', 'description' => $support_doc_name_1,'document_file' => $doc_file);
        array_push($attachments, $sup_doc_1);
     }
     if(!empty($support_doc_2)){
        $docName = 'Doc2-'.$vendor_id.'.' . strtolower(pathinfo($support_doc_2,PATHINFO_EXTENSION));
        $doc_file = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_2"]["tmp_name"], $target_dir . $docName);
        $sup_doc_2 = array('vendor_id' => $vendor_id,'narration' => 'doc_2','description' => $support_doc_name_2,'document_file' => $doc_file);
        array_push($attachments, $sup_doc_2);
     }
     if(!empty($support_doc_3)){
        $docName = 'Doc3-'.$vendor_id.'.' . strtolower(pathinfo($support_doc_3,PATHINFO_EXTENSION));
        $doc_file = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_3"]["tmp_name"], $target_dir . $docName);
        $sup_doc_3 = array('vendor_id' => $vendor_id,'narration' => 'doc_3','description' => $support_doc_name_3,'document_file' => $doc_file);
        array_push($attachments, $sup_doc_3);
     }

    //Form Actions || Post Date
    if($formAction == "SaveDraft"){
        $status = 1;
        $response = $vend->AddVendor($status, $request_fields, $unspsc, $attachments);
        $_SESSION['action_response'] = json_encode($response);
        header("Location:../home");
    }
    else{
        $status = 2;
        if(empty($reg_cert) || empty($trade_license) || empty($support_doc_1) || empty($support_doc_2)){
            $response = array(
                'Status'=>'Error',
                'Message'=> 'Please confirm that all mandatory documents have been attached.'
            );
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../home");
        }
        else{
            $response = $vend->AddVendor($status, $request_fields, $unspsc, $attachments);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../home");
        }
    }
}

//Edit Vendor Details
elseif($token == "edit_vendor"){ 
    $vend->vendor_id = $vendorID;
    //Categories
    foreach($business_categories as $a=> $b)
    {
            $items = array(
            'fam_code'=> $business_categories[$a],
            );
        array_push($unspsc, $items);
    }
    $unspsc =  array_map("unserialize", array_unique(array_map("serialize", $unspsc)));
    

    //Attachments
    $attachments = array();
    $Timestamp = date('YmdHis');

    $vendor_logo = basename($_FILES["vendor_logo"]["name"]);
    $reg_cert = basename($_FILES["reg_cert"]["name"]);
    $trade_license = basename($_FILES["trade_license"]["name"]);

    //Logo
    $logo_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description=%s', $vendorID, 'Profile');
    if(empty($vendor_logo)){
        $vendor_logo = $logo_file['document_file'];
    }
    else{
        $logoName = 'Logo-'.$vendorID.'-'.$Timestamp.'.'. strtolower(pathinfo($vendor_logo,PATHINFO_EXTENSION));
        $vendor_logo = $target_dir . $logoName;
        move_uploaded_file($_FILES["vendor_logo"]["tmp_name"], $target_dir . $logoName);
    }
    $logo = array('vendor_id' => $vendorID,'description' => 'Profile','document_file' => $vendor_logo, 'id' => $logo_file['id']);
    array_push($attachments, $logo);

    //RegCert
    $reg_cert_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description=%s', $vendorID, 'National ID');
    if(empty($reg_cert)){
        $reg_cert = $reg_cert_file['document_file'];
    }
    else{
        $regCertName = 'RegCert-'.$vendorID.'-'.$Timestamp.'.'. strtolower(pathinfo($reg_cert,PATHINFO_EXTENSION));
        $reg_cert = $target_dir . $regCertName;
        move_uploaded_file($_FILES["reg_cert"]["tmp_name"], $target_dir . $regCertName);
    }
    $cer = array('vendor_id' => $vendorID,'description' => 'National ID','document_file' => $reg_cert, 'id' => $reg_cert_file['id']);
    array_push($attachments, $cer);

    //Trade License
    $license_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description=%s', $vendorID, 'Business Operating License');
    if(empty($trade_license)){
        $trade_license = $license_file['document_file'];
    }
    else{
        $tradeLName = 'TradeL-'.$vendorID.'-'.$Timestamp.'.' . strtolower(pathinfo($trade_license,PATHINFO_EXTENSION));
        $trade_license = $target_dir . $tradeLName;
        move_uploaded_file($_FILES["trade_license"]["tmp_name"], $target_dir . $tradeLName);
    }
    $license = array('vendor_id' => $vendorID,'description' => 'Business Operating License','document_file' => $trade_license, 'id' => $license_file['id']);
    array_push($attachments, $license);

    //Other Supporting Documents
    $support_doc_1 = basename($_FILES["support_doc_1"]["name"]);
    $support_doc_2 = basename($_FILES["support_doc_2"]["name"]);
    $support_doc_3 = basename($_FILES["support_doc_3"]["name"]);

    //Doc1
    $doc_1_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND narration=%s', $vendorID, 'doc_1');
    if(empty($support_doc_1)){
        $support_doc_1 = $doc_1_file['document_file'];
    }
    else{
        $docName = 'Doc1-'.$vendorID.'-'.$Timestamp.'.' . strtolower(pathinfo($support_doc_1,PATHINFO_EXTENSION));
        $support_doc_1 = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_1"]["tmp_name"], $target_dir . $docName);
    }
    $sup_doc_1 = array('vendor_id' => $vendorID,'narration' => 'doc_1', 'description' => $support_doc_name_1,'document_file' => $support_doc_1, 'id' => $doc_1_file['id']);
    array_push($attachments, $sup_doc_1);

    //Doc2
    $doc_2_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND narration=%s', $vendorID, 'doc_2');
    if(empty($support_doc_2)){
        $support_doc_2 = $doc_2_file['document_file'];
    }
    else{
        $docName = 'Doc2-'.$vendorID.'-'.$Timestamp.'.' . strtolower(pathinfo($support_doc_2,PATHINFO_EXTENSION));
        $support_doc_2 = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_2"]["tmp_name"], $target_dir . $docName);
    }
    $sup_doc_2 = array('vendor_id' => $vendorID,'narration' => 'doc_2', 'description' => $support_doc_name_2,'document_file' => $support_doc_2, 'id' => $doc_2_file['id']);
    array_push($attachments, $sup_doc_2);

    //Doc3
    $doc_3_file = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND narration=%s', $vendorID, 'doc_3');
    if(empty($support_doc_3)){
        $support_doc_3 = $doc_3_file['document_file'];
    }
    else{
        $docName = 'Doc3-'.$vendorID.'-'.$Timestamp.'.' . strtolower(pathinfo($support_doc_3,PATHINFO_EXTENSION));
        $support_doc_3 = $target_dir . $docName;
        move_uploaded_file($_FILES["support_doc_3"]["tmp_name"], $target_dir . $docName);
    }
    $sup_doc_3 = array('vendor_id' => $vendorID,'narration' => 'doc_3', 'description' => $support_doc_name_3,'document_file' => $support_doc_3, 'id' => $doc_3_file['id']);
    array_push($attachments, $sup_doc_3);

    
    if($formAction == "SaveDraft"){
        $status = 1;
        $response = $vend->EditVendor($status, $request_fields, $unspsc, $attachments);
        $_SESSION['action_response'] = json_encode($response);
        header("Location:../home");
    }
    else{
        $status = 2;
        if(empty($reg_cert) || empty($trade_license) || empty($support_doc_1) || empty($support_doc_2)){
            $response = array(
                'Status'=>'Error',
                'Message'=> 'Please confirm that all mandatory documents have been attached.'
            );
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../home");
        }
        else{
            $response = $vend->EditVendor($status, $request_fields, $unspsc, $attachments);
            $_SESSION['action_response'] = json_encode($response);
            header("Location:../home");
        }
    }
}

//Delete Vendor
elseif($token == "delete_vendor"){
    $vend->vendor_id = $vendorID;
    print_r(json_encode($vend->DeleteVendor()));
}
else{
    $response = array(
        'Status' => 'Error',
        'Message' => 'Your request cannot be completed.'
    );
    print_r(json_encode($response));
}
      
        
?>
