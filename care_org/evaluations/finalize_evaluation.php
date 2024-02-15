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
.td_vendor_name{
    text-align: center;
    vertical-align: middle;
    text-transform: uppercase;
    color: blue;
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
<li class="active text-lg">Finalize Evaluation</li>
</ol>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(( isset( $_REQUEST['tender'] ) )?  $_REQUEST['tender']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$level = filter_var(( isset( $_REQUEST['level'] ) )?  $_REQUEST['level']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 5, $curDate);
if(!$tender)
{ //Quotes received and within the deadline
?>
<div class="row"> 
<div class="col-sm-7">
<div class="panel">
<div class="panel-body">
<br/>
<div class="alert alert-danger">
    <button class="close" data-dismiss="alert"></button>
    <strong>Warning!</strong> <br/><br/>
    The Record(s) your are trying to access does not exist. Click <a href="../evaluations" class="alert-link">Here.</a> to go back.
</div>
</div>
</div>
</div>
</div>
<?php 
} //End User Query || Null records
else{
?>
<div class="blog blog-list">
<div class="panel">
<div class="blog-content">
<table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <td class="text-bold text-uppercase text-lg text-primary">Evaluation Summary</td>
</tbody>
</table>
<?php 
            //Requisition Details
$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);
$estimate = DB::queryFirstRow('SELECT sum(quantity * price) as total from requisition_items where requisition_number=%s', $tender['requisition_id']);
$requisition_estimate = $estimate['total'];

?>
<table class="table table-bordered table-striped pad-ver mar-no">
   <tbody>
   <td class="text-bold text-lg text-center text-primary active" colspan="4">REQUISITION DETAILS</td>
        <tr>
            <td class="text-info text-bold">Requisition Number</td>
            <td><?php echo $requisition['requisition_number'];?></td>
            <td class="text-info text-bold">Estimated Amount</td>
            <td class="text-bold text-lg"><?php echo $requisition['currency'] ." ".number_format($requisition_estimate);?></td>
        </tr>
<tr>
<td class="text-info text-bold">Requisition Due Date</td>
<td><?php echo date_format(date_create($requisition['due_date']), 'd-M-Y'); ?></td>
</tr>
</tbody>
</table>
               <!-- PRELIMINARY EVALUATION SUMMARY-->
<?php 
$check_preliminary_summary = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s', $tender_id, 1);
if($check_preliminary_summary){
?>
<table class="table table-bordered table-strisped pad-ver mar-no">
    <tbody>
    <?php 
    $evaluators = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, 1);
    $colspan = DB::count() + 2;
    ?>
    <td class="text-bold text-lg text-center active" style="color:#FF8C00" colspan="<?php echo $colspan;?>">PRELIMINARY EVALUATION</td>

        <tr>
        <th class="text-center">Vendor(s)</th>
        <td class="text-purple">Evaluation Criteria</td>
            <?php 
            foreach($evaluators as $evaluator){
                $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $evaluator['user_id']);
            ?>
        <td class="text-dark text-lg text-semibold"><?php echo $member['first_name']." ".$member['last_name'];?></td>
        <?php } ?>
        </tr>

        <?php 
        $evals = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, 1);
        foreach($evals as $eval){
        $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
        $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, 1, $eval['vendor_id'], 2);
        $rowspan = DB::count() + 1;
        ?>
        <tr>
        <td rowspan="<?php echo $rowspan;?>" class="td_vendor_name text-semibold"><?php echo $name['vendor_name'];?></td>
            <?php 
            foreach($criterias as $criteria){
                $tender_evaluations = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s AND tender_id=%s AND stage=%s', $criteria['criteria_id'], $tender_id, 1);
                $eval_members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s order by user_id', $tender_id, 1, $criteria['id'], 2);
            ?>
            <tr>
            <th style="width:25%"><?php echo $tender_evaluations['criteria_description'];?></th>
                <?php
                $comp = 0;
                $non_comp = 0;
                foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, 1, $eval['vendor_id'], $eval_member['user_id'], $criteria['id']);
                    $decision = $evaluation_decision['decision'];
                    $narration = $evaluation_decision['narration'];
                    if($decision == 'Compliant'){
                        $class = 'success';
                        $comp ++;
                    }
                    else{
                        $class = 'danger';
                        $non_comp ++;
                    }
                ?>
                <td class="text-<?php echo $class;?> text-semibold"><?php echo strtoupper($decision);?><br/><small class="text-dark"><?php echo $narration;?></small></td></td>
                <?php } ?>
            </tr>
            <?php } ?>
     </tr>
   
<?php } ?>
</tbody>
</table>
<h5 class="text-bold text-lg" style="color:#FF8C00">Preliminary Evaluation Summary</h5>
    <p>From the conducted evaluation, the evaluation committee decided as follows:</p>
    <?php 
    //Preliminary Evaluation Summary
    $preliminary_sums = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d AND status=%s order by decision', $tender_id, 1, 2);
    foreach($preliminary_sums as $preliminary_sum){
        $sum_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $preliminary_sum['vendor_id']);
        $pre_sum_decision = $preliminary_sum['decision'];
        $pre_sum_narration = $preliminary_sum['narration'];
        if($pre_sum_decision == 'Compliant'){
            $class = 'success';
        }
        else{
            $class = 'danger';
        }
    ?>
    <div class="row">
    <div class="col-sm-2">
        <h5><?php echo $sum_vendor['vendor_name'];?></h5>
    </div>
    <div class="col-sm-10">
        <h6 class="text-<?php echo $class;?> text-md"><?php echo strtoupper($pre_sum_decision);?></h6>      
        <span>
        <?php 
        if(!empty($pre_sum_narration)){
            echo "Reason: { ". $pre_sum_narration ." }";
        }
        ?>
        </span>
    </div>
    </div>
    <br/>
    <?php 
        } //END Preliminary Evaluation Summary 
} //End check_preliminary_summary
else{
echo '<h5 class="text-bold text-lg" style="color:#FF8C00">* Preliminary Evaluation for this tender has not been conducted. *</h5>';
}
?>
<hr style="border-top: 3px dotted;color:#FF8C00"/>

    <!-- TECHNICAL EVALUATION SUMMARY-->
<?php 
$check_technical_summary = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s', $tender_id, 2);
if($check_technical_summary){
?>
<table class="table table-bordered table-strisped pad-ver mar-no">
    <tbody>
    <?php 
    $evaluators = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, 2);
    $colspan = DB::count() + 2;
    ?>
    <td class="text-bold text-lg text-center active" style="color:#20B2AA" colspan="<?php echo $colspan;?>">TECHNICAL EVALUATION</td>
        <tr>
        <th class="text-center">Vendor(s)</th>
        <td class="text-purple">Evaluation Criteria</td>
            <?php 
            foreach($evaluators as $evaluator){
                $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $evaluator['user_id']);
            ?>
        <td class="text-dark text-lg text-semibold"><?php echo $member['first_name']." ".$member['last_name'];?></td>
        <?php } ?>
        </tr>
        <?php 
        $evals = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, 2);
        foreach($evals as $eval){
        $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
        $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, 2, $eval['vendor_id'], 2);
        $rowspan = DB::count() + 1;
        ?>
     <tr>
     <td rowspan="<?php echo $rowspan;?>" class="td_vendor_name text-semibold"><?php echo $name['vendor_name'];?></td>
        <?php 
        foreach($criterias as $criteria){
            $tender_evaluations = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s AND tender_id=%s AND stage=%s', $criteria['criteria_id'], $tender_id, 2);
            $eval_members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s order by user_id', $tender_id, 2, $criteria['id'], 2);
        ?>
        <tr>
        <th style="width:25%"><?php echo $tender_evaluations['criteria_description'];?></th>
            <?php
            $comp = 0;
            $non_comp = 0;
            foreach($eval_members as $eval_member){
                $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, 2, $eval['vendor_id'], $eval_member['user_id'], $criteria['id']);
                    $decision = $evaluation_decision['decision'];
                    $narration = $evaluation_decision['narration'];
                    if($decision == 'Responsive'){
                        $class = 'success';
                        $comp ++;
                    }
                    else{
                        $class = 'danger';
                        $non_comp ++;
                    }
            ?>
             <td class="text-<?php echo $class;?> text-semibold"><?php echo strtoupper($decision);?><br/>
                <small class="text-dark"><?php echo $narration;?></small></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tr>
<?php } ?>
</tbody>
</table>
<h5 class="text-bold text-lg" style="color:#20B2AA">Technical Evaluation Summary</h5>
    <p>From the conducted evaluation, the evaluation committee decided as follows:</p>
    <?php 
    //Technical Evaluation Summary
    $technical_sums = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d AND status=%s order by decision', $tender_id, 2, 2);
    foreach($technical_sums as $technical_sum){
        $sum_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $technical_sum['vendor_id']);
        $tech_sum_decision = $technical_sum['decision'];
        $tech_sum_narration = $technical_sum['narration'];
        if($tech_sum_decision == 'Responsive'){
            $class = 'success';
        }
        else{
            $class = 'danger';
        }
    ?>
       <div class="row">
    <div class="col-sm-2">
        <h5><?php echo $sum_vendor['vendor_name'];?></h5>
    </div>
    <div class="col-sm-10">
        <h6 class="text-<?php echo $class;?> text-md"><?php echo strtoupper($tech_sum_decision);?></h6>      
        <span>
        <?php 
        if(!empty($tech_sum_narration)){
            echo "Reason: { ". $tech_sum_narration ." }";
        }
        ?>
        </span>
    </div>
    </div>
    <br/>
    <?php 
        } //END Technical Evaluation Summary 
} //End check_technical_summary
else{
echo '<h5 class="text-bold text-lg" style="color:#20B2AA">* Technical Evaluation for this tender has not been conducted. *</h5>';
}
?>
<hr style="border-top: 3px dotted;color:#20B2AA"/>

    <!-- FINANCIAL EVALUATION SUMMARY-->
<?php 
$check_financial_summary = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s', $tender_id, 3);
if($check_financial_summary){
?>
<table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
            <!--Financial Summary-->
    <?php 
    $cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
    $cur = $cur['cur'];
    $evaluators = DB::query('SELECT DISTINCT user_id from financial_evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, 3);
    $colspan = DB::count() + 2;
    ?>
    <td class="text-bold text-lg text-center active" style="color:#3CB371" colspan="<?php echo $colspan;?>">FINANCIAL EVALUATION</td>
        <tr>
        <th class="text-center">Vendor(s)</th>
        <td class="text-purple"></td>   
            <?php 
            foreach($evaluators as $evaluator){
                $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $evaluator['user_id']);
            ?>
        <td class="text-dark text-lg text-semibold"><?php echo $member['first_name']." ".$member['last_name'];?></td>
        <?php } ?>
        </tr>

        <?php 
        $evals = DB::query('SELECT DISTINCT vendor_id from financial_evaluations where tender_id=%s AND stage=%s', $tender_id, 3);
        foreach($evals as $eval){
        $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
        ?>
     <tr>
     <td rowspan="6" class="td_vendor_name text-semibold"><?php echo $name['vendor_name'];?></td>
        <?php 
           $eval_members = DB::query('SELECT DISTINCT user_id from financial_evaluations where tender_id=%s AND stage=%s AND status=%s order by user_id', $tender_id, 3, 2);
        ?>
        <!--
            -Sub Total, Current VAT, Evaluated VAT, Evaluated Total
        -->
        <tr>
        <th style="width:25%" class="text-center">Total Bid Price (<?php echo $cur;?>)</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    $cur_sub_total = $evaluation_decision['cur_sub_total'];
            ?>
             <td class="text-semibold text-info text-lg"><?php echo number_format($cur_sub_total);?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Current VAT %</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    $cur_vat = $evaluation_decision['cur_vat'];
            ?>
             <td class="text-semibold text-info text-lg"><?php echo $cur_vat;?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Corrected VAT %</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    $corrected_vat = $evaluation_decision['eval_vat'];

                    if(!$corrected_vat){
                        $corrected_vat = "-";
                    }
            ?>
             <td class="text-semibold text-warning text-lg"><?php echo $corrected_vat;?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Final VAT Amount (<?php echo $cur;?>)</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    $sub_total = $evaluation_decision['cur_sub_total'];
                    $vat = $evaluation_decision['cur_vat'];
                    $corrected_vat = $evaluation_decision['eval_vat'];

                    if($corrected_vat){
                        $final_vat = ($sub_total * ($corrected_vat/100));
                    }
                    else{
                        $final_vat = ($sub_total * ($vat/100));
                    }
            ?>
             <td class="text-semibold text-info text-lg"><?php echo number_format($final_vat);?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Evaluated Total Bid Price (<?php echo $cur;?>)</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    $sub_total = $evaluation_decision['cur_sub_total'];
                    $vat = $evaluation_decision['cur_vat'];
                    $corrected_vat = $evaluation_decision['eval_vat'];

                    if($corrected_vat){
                        $evaluated_total = ($sub_total * ($corrected_vat/100)) + $sub_total;
                    }
                    else{
                        $evaluated_total = ($sub_total * ($vat/100)) + $sub_total;
                    }
            ?>
             <td class="text-semibold text-pink text-lg"><?php echo number_format($evaluated_total);?><br/></td>
            <?php } ?>
        </tr>

        <tr>
            <th></th>
        <th style="width:25%" class="text-center">Supporting Evaluation Documents</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_file = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, 3, $eval['vendor_id'], $eval_member['user_id']);
                    if(empty($evaluation_file['eval_doc'])){
            ?>
                <td class="text-semibold text-danger h6"><i>No file attached</i><br/></td>
             <?php 
                }
                else{
                    $file = str_replace($BASEPATH, '..', $evaluation_file['eval_doc']);
             ?>
                <td><a href="<?php echo $file;?>" target="_blank" class="btn-link text-semibold text-success h6"><i class="fa fa-cloud-download"></i> View document</a><br/></td>
            <?php } } ?>
        </tr>
    </tr>
       <?php } ?>
        <?php
        ?>
</tbody>
</table>

<h5 class="text-bold text-lg" style="color:#3CB371">Financial Evaluation Summary</h5>
<p>From the conducted evaluation, the evaluation committee decided as follows:</p>
<?php 
    //Financial Evaluation Summary
    $fin_rank = array();
    $financial_sums = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%d AND status=%s order by decision', $tender_id, 3, 2);
    foreach($financial_sums as $financial_sum){
        $sum_vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $financial_sum['vendor_id']);
        $query_vendor= DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s', $tender_id, 3, $financial_sum['vendor_id']);
        $fin_sub_ttl = $query_vendor['cur_sub_total'];
        $fin_vat = $query_vendor['cur_vat'];
        $fin_corrected_vat = $query_vendor['eval_vat'];
        if($fin_corrected_vat){
            $fin_eval_ttl = ($fin_sub_ttl * ($fin_corrected_vat/100)) + $fin_sub_ttl;
        }
        else{
            $fin_eval_ttl = ($fin_sub_ttl * ($fin_vat/100)) + $fin_sub_ttl;
        }
        $vendor_details = array(
            'vendor_id' => $financial_sum['vendor_id'],
            'vendor_name' => $sum_vendor['vendor_name'],
            'evaluated_total' => $fin_eval_ttl
        );
        array_push($fin_rank, $vendor_details);
    }
?>
<table class="table">
<thead>
    <tr>
        <th class="text-center">Vendor(s)</th>
        <th>Total Bid Price</th>
        <th>Rank</th>
    </tr>
</thead>
<tbody>
   <?php 
//Sort the Vendors based on their financials. 
function rank_sort($a, $b) {
return $a['evaluated_total'] > $b['evaluated_total'];
}
usort($fin_rank, "rank_sort");
$cnt = 1;
  foreach($fin_rank as $vendor){
   ?>
    <tr>
        <td class="text-semibold td_vendor_name"><?php echo $vendor['vendor_name'];?></td>
        <?php 
        if($vendor['evaluated_total'] > $requisition_estimate){
        ?>
            <td class="text-bold text-danger text-lg"><?php echo number_format($vendor['evaluated_total']);?>
            <small class="text-dark text-normal"><i> Price is above the Estimate</i></small>
            </td>
        <?php 
        }
        else{
        ?>
            <td class="text-bold text-primary text-lg"><?php echo number_format($vendor['evaluated_total']);?></td>
        <?php } ?>
        <td><button class="btn btn-dark text-bold btn-icon"><?php echo $cnt;?></button> </td>
    </tr>
   <?php 
    $cnt ++;
    } ?>
</tbody>
</table>
<?php 
} //End check_financial_summary
else{
echo '<h5 class="text-bold text-lg" style="color:#3CB371">* Financial Evaluation for this tender has not been conducted. *</h5>';
}
?>
<hr style="border-top: 3px dotted;color:#3CB371"/>
    <!-- POST QUALIFICATION REPORTS-->
<?php 
$query_reports = DB::query('SELECT * from post_qualification where tender_id=%s', $tender_id);
if($query_reports){
?>
<table class="table table-bordered table-striped pad-ver mar-no">
<h5 class="text-bold text-lg text-mint text-uppercase">Post-Qualification Reports</h5>
<thead>
    <tr>
    <th>#</th>
    <th class="text-center">Vendor</th>
    <th>Comments</th>
    <th>Post-Qualification Report</th>
    <th>Date Added</th>
    </tr>
	</thead>
    <tbody>
    <?php
    $cnt = 1;
    foreach($query_reports as $post_report){
        $name = DB::queryFirstRow('SELECT vendor_name from vendors where vendor_id=%s', $post_report['vendor_id']);
        $report_file =  str_replace($BASEPATH, '..', $post_report['report_file']);
    ?>
    <tr>
    <td><?php echo $cnt; ?></td>
    <td class="text-semibold td_vendor_name"><?php echo $name['vendor_name'];?></td>
    <td class="text-md"><?php echo $post_report['narration']; ?></td> 
    <td>
        <?php 
        if($report_file != null){
        ?>
        <a href="<?php echo $report_file;?>" target="_blank" class="btn-link"><i class="fa fa-cloud-download icon-2x icon-fw"></i>View Report</a>
        <?php  } 
        else{
            echo "REPORT NOT AVAILABLE";
        }
        ?>
    </td> 
    <td><?php echo date_format(date_create($post_report['date_added']), 'd/m/Y'); ?></td>
    </tr>
    <?php 
    $cnt ++;
    } ?>    
    </tbody>
</table>
<br/>
<hr/>
<?php } //POST QUALIFICATION REPORTS-?>

<div class="col-sm-5">
    <div class="form-group">
    <label class="control-label text-primary">Comments</label>
    <textarea rows="3" class="form-control" id="report_narration" ></textarea>
    </div>
</div>
<div class="blog-footer">
<div class="tab-footer clearfix">	
<input type="hidden" id="approval_level" value="<?php echo $level;?>"/>
<button data-id="<?php echo $tender_id?>" id="btnDiscard" value="discard" class="btn btn-danger ">Discard and Request Re-evaluation</button>

<?php 
if($level == 1){
    echo '<button data-id="'.$tender_id.'" id="btnFinal" value="finalize" class="btn btn-success pull-right">Finalize Evaluation</button>';
}
elseif($level == 2){
    echo '<button data-id="'.$tender_id.'" id="btnFinal" value="finalize" class="btn btn-success pull-right">Submit to SMT for Review</button>';
}
elseif($level == 3){
    echo '<button data-id="'.$tender_id.'" id="btnFinal" value="finalize" class="btn btn-success pull-right">Approve Evaluation Report and Confirm Award</button>';
}
?>
</div>
</div>
</div>
</div>

</div>
<?php 
} //End Requisition Query || Record found
?>
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
    //Confirm Delete
$('#btnDiscard').on('click', function(){
	var tender_id = $(this).data("id");
    var approval_level = $('#approval_level').val();
    var report_narration = $('#report_narration').val();
    
	bootbox.dialog({
	title: "Discard Evaluation",
message : "<h5 style='color:#FF8C00;font-size:15px'> Please note that this action will clear all evaluations and all the evaluators will be required to re-evaluate received bids from the preliminary stage.<br/>"+ 
"<br/>Are you sure you want to proceed with this action?</h5>",
buttons: {
    success: {
    label: "Yes, Discard Evaluation.",
    className: "btn-primary",
    callback : function(result) {
    $('#btnDiscard').prop('disabled', true);
    $('#btnFinal').prop('disabled', true);
    $.ajax({
    type : 'post',
    url : 'conclude_evaluation',
    data :  'tender_id='+ tender_id+"&token=discard_all&level="+approval_level+"&narration="+report_narration, 
    success : function(data){
    var result = JSON.parse(data);
    if (result.Status == "Success") {
        $.niftyNoty({
            type: 'success',
            icon : 'pli-like-2 icon-2x',
            message : result.Message,
            container : 'floating',
            timer : 7000
        });
    }else{
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-cross icon-2x',
            message : result.Message,
            container : 'floating',
        });
    };
    setTimeout(function(){ window.location.href="all_evaluations"; }, 7000); // Redirect to Current evaluations for re-evaluation
    }
    });
    }
    }
	}
});
});
</script>

<script>
    //Confirm Delete
$('#btnFinal').on('click', function(){
	var tender_id = $(this).data("id");
    var approval_level = $('#approval_level').val();
    var report_narration = $('#report_narration').val();
    if(approval_level == 1){
        var rpt_title = "Finalize Evaluation";
        var rpt_msg = "The evaluation report will be submitted to the Procurement Unit for further action.";
    }
    else if(approval_level == 2){
        var rpt_title = "Submit Review";
        var rpt_msg = "The evaluation report will be submitted to Senior Management Team for further action.";
    }
    else{
        var rpt_title = "Approve Evaluation report";
        var rpt_msg = "The evaluation report will be fully approved.";
    }

	bootbox.dialog({
	title: rpt_title,
message : "<h5 style='color:#228B22;font-size:15px'> "+rpt_msg+"<br/>"+ 
"<br/>Are you sure you want to proceed?</h5>",
buttons: {
    success: {
    label: "Confirm",
    className: "btn-primary",
    callback : function(result) {
    $('#btnDiscard').prop('disabled', true);
    $('#btnFinal').prop('disabled', true);
$.ajax({
    type : 'post',
    url : 'conclude_evaluation',
    data :  'tender_id='+ tender_id+"&token=approve_all&level="+approval_level+"&narration="+report_narration, 
    success : function(data){
    var result = JSON.parse(data);
    if (result.Status == "Success") {
        $.niftyNoty({
            type: 'success',
            icon : 'pli-like-2 icon-2x',
            message : result.Message,
            container : 'floating',
            timer : 4000
        });
    }else{
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-cross icon-2x',
            message : result.Message,
            container : 'floating',
        });
    };
    setTimeout(function(){ window.location.href="completed_evaluations"; }, 4000); // Redirect to Completed evaluations for SOC/OC
    }
    });
    }
    }
	}
});
});
</script>
</body>
</html>
