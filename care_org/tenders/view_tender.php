<?php 
$BASEPATH = dirname(__DIR__);
$DIR = __DIR__;

require_once($BASEPATH.'/validator.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php 
/*** Include the Global Headers Scripts */
include $DIR."/headers.php"; 
?>
<style>
.control-label{
font-weight:600;
}
.table>thead>tr>th {
color:black;
}
.prices{
border: none;
}
.item_total_price{
border: none;
}
.remove{
cursor: pointer;
}
</style>
</head>
<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg navbar-fixed">

<?php 
/*** Include the Global Headers Scripts */
include $DIR."/navbar.php";
?>

<div class="boxed">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<ol class="breadcrumb">
<li></li>
<li class="active text-lg">View Details</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s', $tender_id);
if(!isset($tender))
{ //Display the notice below if null
?>
<div class="row"> 
<div class="col-sm-7">
<div class="panel">
<div class="panel-body">
<br/>
<div class="alert alert-danger">
<button class="close" data-dismiss="alert"></button>
<strong>Warning!</strong> <br/><br/>
The Record your are trying to access does not exist. Click <a href="../tenders" class="alert-link">Here.</a> to go back to the list of tenders.
</div>
</div>
</div>
</div>
</div>
<?php 
} //End User Query || Null records
else{
$query = DB::queryFirstRow('SELECT * from tender_status where code=%s', $tender['status']);
$tender_status = $query['value'];
$class = $query['class'];

$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);
$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $tender['requisition_id']);
$proc_method = DB::queryFirstRow("SELECT * FROM thresholds WHERE currency=%s_cur AND min_amount <= %d_min AND max_amount >= %d_max", 
[
'cur' => $requisition['currency'],
'min' => $estimate['total'],
'max' => $estimate['total'],
]
);
$query_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $proc_method['proc_method']);
?>
<div class="row">
<div class="col-xs-12">
<div class="panel">
<div class="panel-body">
<table class="table table-bordered table-striped pad-ver mar-no">
<h4 style="color:teal;">Tender - #<?php echo $tender_id;?> <div class="pull-right btn btn-sm text-bold btn-<?php echo $class;?>"><?php echo $tender_status;?></div></h4><br/>
    <tbody>
        <tr>
            <td class="text-info text-bold">Requisition Number</td>
            <td><?php echo $requisition['requisition_number'];?></td>
            <td class="text-info text-bold">Department</td>
            <td><?php echo $department['name'];?></td>
        </tr>
        <tr>
            <td class="text-info text-bold">Requisition Description</td>
<td colspan="7" class="text-bold"><?php echo $requisition['requisition_name'];?></td>
</tr>
<tr>
<td class="text-info text-bold">Estimate Amount</td>
<td class="text-bold text-lg"><?php echo number_format($estimate['total']);?><input id="requisition_amount" type="hidden" value="<?php echo $estimate['total'];?>"></td>
<td class="text-info text-bold">Currency</td>
<td><?php echo $requisition['currency'];?> <input id="requisition_currency" type="hidden" value="<?php echo $requisition['currency'];?>"></td>
<td class="text-info text-bold">Requisition Due Date</td>
<td><?php echo date_format(date_create($requisition['due_date']), 'd-M-Y'); ?></td>
</tr>
</tbody>
</table>
<br/>
<div class="col-lg-12">
<?php 
//Details
$category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $tender['category']);
$method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
$method_change_justification = "";
if($tender['solicitation_method'] != $tender['cur_method']){
    $init = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['cur_method']);
    $method_change_justification = "Changed from: ".$init['method_name'].".<br/> Reason: ".$tender['method_justify'];
}

?>
<table class="table table-bordered table-striped pad-ver mar-no">
    <h4><span class="text-danger"><i class="ti-receipt icon-2x"></i></span> Details</h4><br/>
        <tbody>
            <tr>
                <td class="text-info text-bold">Procurement Category</td>
                <td><?php echo $category['name'];?></td>
                <td class="text-info text-bold">Solicitation Method</td>
                <td><?php echo $method['method_name'];?></td>
                <td colspan="2" class="text-sm"><?php echo $method_change_justification;?></td>
                
            </tr>
            <tr>
            <td class="text-info text-bold">Location of Delivery/Service</td>
            <td><?php echo $tender['location'];?></td>
            <td class="text-info text-bold">Submission Deadline Date</td>
            <td><?php echo  date_format(date_create($tender['submission_date']), 'd/M/Y');?></td>
            <td class="text-info text-bold">Submission Deadline Time</td>
            <td><?php echo  date_format(date_create($tender['submission_date']), 'h:i A');?></td>
            </tr>
    </tbody>
    </table>
    <table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
        <tr>
            <td class="text-info text-bold col-md-3">Shortlisted Vendors</td>
            <td class="text-dark text-lg">
                <?php
                if($tender['solicitation_method'] == 1){
                    $cnt = 1;
                    $query = DB::queryFirstRow('SELECT * from tender_shortlist where tender_id=%s', $tender_id);
                    $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $query['vendor_id']);
                    echo $cnt.". ".$vendor['vendor_name']."<br/>";
                }
                else{
                    $cnt = 1;
                    $query = DB::query('SELECT * from tender_shortlist where tender_id=%s', $tender_id);
                    foreach($query as $item){
                        $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $item['vendor_id']);
                        echo $cnt.". ".$vendor['vendor_name']."<br/>";
                        $cnt ++;
                    }   
                }
                ?>
            </td>

            <td class="text-info text-bold col-md-3">Evaluation Committee Members</td>
            <td class="text-dark text-lg">
                <?php
                    $cnt = 1;
                    $query = DB::query('SELECT * from tender_committee where tender_id=%s', $tender_id);
                    foreach($query as $list){
                        $secretary = DB::queryFirstRow('SELECT * from evaluation_nominations where tender_id=%s', $tender_id);
                        $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $list['user_id']);
                        if($secretary['user_id'] == $list['user_id']){
                            echo $cnt.". ".$member['first_name']." ".$member['last_name']."<i class='btn-xs btn-default text-purple text-sm'> ~ Secretary</i> <br/>";   
                        }
                        else{
                            echo $cnt.". ".$member['first_name']." ".$member['last_name']."<br/>";
                        }
                        $cnt ++;
                    }
                    
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="col-lg-6">
    <hr/>

    <h4><span class="text-warning"><i class="ti-ruler-pencil icon-2x"></i></span> Preliminary Evaluation Criteria</h4><br/>
    <div>
    <table class="table table-bordered">
    <thead>
    <tr>
        <th>No</th>
        <th>Description</th>
    </tr>
    </thead>
        <tbody>
            <?php 
            $cnt = 1;
            $pre_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 1);
            foreach($pre_evals as $pre_eval){
            ?>
                <tr>
                <td colspan="1"><p class="form-control prices"><?php echo $cnt;?></p></td>	
                <td><p class="form-control prices"><?php echo $pre_eval['criteria_description'];?></p></td>	
                </tr>
            <?php 
                $cnt ++;
                } //End foreach?>
        </tbody>
    </table>
    </div>
</div>

<div class="col-lg-6">
    <hr/>

    <h4><span class="text-warning"><i class="ti-ruler-pencil icon-2x"></i></span> Techinical Evaluation Criteria</h4><br/>
    <div>
    <table class="table table-bordered">
    <thead>
    <tr>
        <th>No</th>
        <th>Description</th>
    </tr>
    </thead>
        <tbody>
            <?php 
            $cnt = 1;
            $tech_evals = DB::query('SELECT * from tender_evaluations where tender_id=%s AND stage=%s', $tender_id, 2);
            foreach($tech_evals as $tech_eval){
            ?>
                <tr>
                <td colspan="1"><p class="form-control prices"><?php echo $cnt;?></p></td>	
                <td><p class="form-control prices"><?php echo $tech_eval['criteria_description'];?></p></td>	
                </tr>
            <?php 
                $cnt ++;
                } //End foreach?>
        </tbody>
    </table>
    </div>
</div>

<div class="col-lg-12">
    <br/>
    <h4><span class="text-warning"><i class="ti-ruler-pencil icon-2x"></i></span> Financial Evaluation Criteria</h4><br/>
    <div>
    <table class="table table-bordered">
    <thead>
    <tr>
    <th>No</th>
    <th>Description</th>
    <th>Category (Level 1)</th>
    <th>Category (Level 2)</th>
    <th>Category (Level 3)</th>
    <th>Category (Level 4)</th>
    <th>Unit of Measure</th>
    <th>Quantity</th>
    <th>Estimated Unit Price</th>
    </tr>
    </thead>
    <tbody>
<?php 
$items = DB::query('SELECT * from requisition_items where requisition_number=%s', $requisition['requisition_number']);
$cnt = 1;
foreach($items as $item){
    $cat1 = DB::queryFirstRow('SELECT * from level1_category where id=%s', $item['category_1']);
    $cat2 = DB::queryFirstRow('SELECT * from level2_category where id=%s', $item['category_2']);
    $cat3 = DB::queryFirstRow('SELECT * from level3_category where id=%s', $item['category_3']);
    $cat4 = DB::queryFirstRow('SELECT * from level4_category where id=%s', $item['category_4']);
    
    if(!empty($item['quantity']) || !empty($item['price'])){
        $qty = $item['quantity'];
        $price = $item['price'];
    }
    else{
        $qty = 0;
        $price = 0;
    }
?>
<tr>
    <td><p class="form-control prices"><?php echo $cnt;?></p></td>
    <td style="padding-right:10em" ><p class="form-control prices"><?php echo $item['description'];?></p></td>
    <td><p class="form-control prices"><?php echo $cat1['description'];?></td>
    <td><p class="form-control prices"><?php echo $cat2['description'];?></td>
    <td><p class="form-control prices"><?php echo $cat3['description'];?></td>
    <td><p class="form-control prices"><?php echo $cat4['description'];?></td>
    <td><p class="form-control prices"><?php echo $item['unit_of_measure'];?></p></td>
    <td><p class="form-control prices"><?php echo number_format($qty);?></p></td>
    <td><p class="form-control prices"><?php echo number_format($price);?></p></td>
</tr>
<?php 
$cnt ++;  
}
    ?>
</tbody>
    </table>
    </div>
</div>

    <hr/>

    
    <div class="col-sm-12">
    <h4><span class="text-info"><i class="ti-announcement icon-2x"></i></span> Tender Notice</h4><br/>
    <div class="row" style=" border: 0.4px solid rgb(255, 158, 48);">
    <?php 
    $notice = DB::queryFirstRow('SELECT * from tender_notice where tender_id=%s', $tender_id);
    ?>
    <textarea id="demo-mail-compose" class="form-control summernote_text"><?php echo $notice['message'];?></textarea>
    </div>
</div>

<div class="col-sm-12">
    <br/>
    <h4><span class="text-info"><i class="ti-pencil-alt icon-2x"></i></span> Tender Reviews</h4><br/>
    <div class="row">
    <table class="table table-bordered table-striped pad-ver mar-no">
        <thead>
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Narration</th>
            <th>Reviewer</th>
            <th>Review Date</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            $cnt = 1;
            $reviews = DB::query('SELECT * from tender_reviews where tender_id=%s order by id desc', $tender_id);
            foreach($reviews as $review){
                $user = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $review['reviewer']);
                $role = get_roles($user['role_id']);
            ?>
            <tr>
            <td><?php echo $cnt;?></td>
            <td><?php 
                    if($review['decision'] == 3){ 
                    echo "Rejected";
                    }
                    else{
                    echo "Approved";
                    }
                ?></td>
            <td><?php echo $review['narration'];?></td>
            <td><?php echo $user['first_name'].' '.$user['last_name'].' ('.$role.')';?></td>
            <td><?php echo date_format(date_create($review['date_added']), 'd-M-Y h:i'); ?></td>
            </tr>
            <?php 
            $cnt ++;
            }
            ?>
            
        </tbody>
    </table>
    </div>
</div>


</div>
</div>
</div>
<?php 
} //End Requisition Query || Record found
?>
</div>
<!--===================================================-->

<!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
</div>

<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php"; 
?>

<script>
//Tender Notice Tab
$('#demo-mail-compose').summernote('disable');
</script>

</body>
</html>
