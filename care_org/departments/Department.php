<?php 
/**
 * Department Class that manages all Department Actions
 * 
 */
require_once('../validator.php');
 class Department{
    public $id;
    public $name;
    
    /** 
     * Create a Department.
     **/
    public function createDepartment($id, $name){
        if(empty($id) || empty($name)){
            $response = array(
                'Status'=>'Error',
                'Message'=> 'Department ID or Name Cannot be Empty.'
            );
        }
        else{
            $query = DB::queryFirstRow('SELECT * from departments where dept_id=%s', $id);
            if($query){
                    $response = array(
                        'Status'=>'Error',
                        'Message'=> 'The Department already Exists'
                    );
            }
            else{
                DB::insert('departments', array(
                    'dept_id' => $id, 
                    'name' => $name));
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $name.' Department has been created Successfully.'
                );
            }
        }
    return $response;
    }

    /** 
     *Edit/Update the Department Name
     **/
    public function updateDepartment($id, $dpt_id, $name){
        if(empty($dpt_id) || empty($name)){
            $response = array(
                'Status'=>'Error',
                'Message'=> 'Department ID or Name Cannot be Empty.'
            );
        }
        else{
            $query = DB::queryFirstRow('SELECT * from departments where dept_id=%s AND id!=%s', $dpt_id, $id);
            if($query){
                    $response = array(
                        'Status'=>'Error',
                        'Message'=> 'A similar department already exists.'
                    );
            }
            else{
                DB::update('departments', array('dept_id' => $dpt_id, 'name' => $name), 'id=%s', $id);
                $response = array(
                    'Status'=>'Success',
                    'Message'=> $name.' Department has been updated Successfully.'
                );
            }
        }
    return $response;
    }

   /** 
     *Fetch Department Name.
     **/
    public function getDepartmentName($id){
       $department = DB::queryFirstRow('SELECT * from departments where id=%s', $id);
       return json_encode(array($department['dept_id'], $department['name']));
    }

    /** 
     *Delete a Department.
     **/
    public function deleteDepartment($id){
        $query = DB::queryFirstRow('SELECT * from org_users where department_id=%s', $id);
        $department = DB::queryFirstRow('SELECT * from departments where id=%s', $id);

        if($query){
            $response = array(
                'Status'=>'Error',
                'Message'=> $department['name'].' department has existing users. <br/> Transfer users to another department before proceeding to delete.'
            );
        }
        else{
           DB::delete('departments', 'id=%s', $id);
            $response = array(
                'Status'=>'Success',
                'Message'=> $department['name'].' Department has been deleted.'
            );
        }
    return $response;
    }
 }

 $dp = new Department();
 /** Create New Department */
 if(isset($_REQUEST['deptId']) && isset($_REQUEST['deptName'])) {
    $dp_id = trim($_REQUEST['deptId']);
    $dp_name = trim($_REQUEST['deptName']);

    $dp_id = filter_var($dp_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dp_name = filter_var($dp_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    print_r(json_encode($dp->createDepartment($dp_id, $dp_name)));
}

/** Update/Edit a Department */
if(isset($_REQUEST['department_id_update'])){
    $dp_id = trim($_REQUEST['id_update']);
    $dp_id_update = trim($_REQUEST['department_id_update']);
    $dp_name_update = trim($_REQUEST['department_name_update']);
    print_r(json_encode($dp->updateDepartment($dp_id, $dp_id_update, $dp_name_update)));
}

/** Show Department */
if(isset($_REQUEST['department_id_get'])){
    $dp_id_get = trim($_REQUEST['department_id_get']);
    print($dp->getDepartmentName($dp_id_get));
}

/** Delete a Department */
if(isset($_REQUEST['deptId_del'])) {
    $dp_id_del = trim($_REQUEST['deptId_del']);
    $dp_id_del = filter_var($dp_id_del, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    print_r(json_encode($dp->deleteDepartment($dp_id_del)));
}
?>