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
    font-size: 15px;
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

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<ol class="breadcrumb">
<li></li>
<li class="active text-lg">Evaluation Summary</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(( isset( $_REQUEST['tender_id'] ) )?  $_REQUEST['tender_id']: null, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$stage = $_REQUEST['stage'];
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 5, $curDate);
$summary_complete = DB::query('SELECT * from evaluation_summary where tender_id=%s AND status=%s AND stage=%s', $tender_id, 2, $stage);
if(!$tender || $summary_complete)
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
//Content Found|| Process Data Below
?>

<div class="blog blog-list">
<div class="panel">
<?php
if($stage == 1){//Preliminary Stage Starts Here with a Form>>>>
?>
<form id="demo-bv-bsc-tabs" action="submit_summary" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="token" value="preliminary_summary">
    <input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
    <div class="blog-content">
    <table class="table table-bordered table-striped pad-ver mar-no">
        <tbody>
        <tr>
        <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="7"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
        </tr>
                <!--Preliminary Criteria-->
        <?php 
        $evaluators = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, $stage);
        ?>
        <td class="text-bold text-lg text-uppercase" style="color:#FF8C00" colspan="4">Preliminary Evaluation Summary</td>
            <tr>
            <th class="text-center">Vendor(s)</th>
            <td class="text-purple">Evaluation Criteria</td>
                <?php 
                foreach($evaluators as $evaluator){
                    $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $evaluator['user_id']);
                ?>
            <td class="text-dark text-lg text-semibold"><?php echo $member['first_name']." ".$member['last_name'];?></td>
            <?php } ?>
            <td class="text-purple">Overall</td>
            </tr>

            <?php 
            $evals = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, $stage);
            foreach($evals as $eval){
            $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
            $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, $stage, $eval['vendor_id'], 2);
            $rowspan = (DB::count() * DB::count()) + 1;
            ?>
        <tr>
        <th rowspan="<?php echo $rowspan; ?>"><?php echo $name['vendor_name'];?></th>
            <?php 
            foreach($criterias as $criteria){
                $tender_evaluations = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s AND tender_id=%s AND stage=%s', $criteria['criteria_id'], $tender_id, $stage);
                $eval_members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s order by user_id', $tender_id, $stage, $criteria['id'], 2);
            ?>
            <tr>
            <th style="width:25%"><?php echo $tender_evaluations['criteria_description'];?></th>
                <?php
                $comp = 0;
                $non_comp = 0;
                foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id'], $criteria['id']);
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
                <td class="text-<?php echo $class;?> text-semibold"><?php echo strtoupper($decision);?><br/>
                <small class="text-dark"><?php echo $narration;?></small>
                </td>
                <?php } ?>
                <?php 
                //Overall Summary
                if($comp > $non_comp){
                    $class = 'success';
                    $overall = 'Compliant';
                }
                else{
                    $class = 'danger';
                    $overall = 'Non-Compliant';
                }
                ?>
                <td class="text-<?php echo $class;?> text-semibold"><i>~<?php echo strtoupper($overall);?>~</i></td>
            </tr>
            <tr>
            <td></td>
                <?php 
                foreach($eval_members as $eval_member){
                    $all_eval = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s and user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                    $evaluation_id = $all_eval['id'];
                ?>
                <td class="text-info text-semibold">
                    <div>
                    <input id="<?php $eval_member['user_id'];?>" type="checkbox" name="evaluation_id[]" value="<?php echo $evaluation_id;?>">
                    <label class="text-dark" for="<?php $eval_member['user_id'];?>">Re-Evaluate</label>
                    </div>
                </td>
                
                <?php } ?>
            </tr>
            <?php } ?>
        </tr>

        <?php } ?>

            <?php
            ?>
    </tbody>
    </table>
        <!--Handle Summary here-->
        <h5 style="color:blue">Overall Summary</h5>
        <?php 
        $eval_sums = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, $stage);
        foreach($eval_sums as $eval_sum){
        $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval_sum['vendor_id']);
        $overall_summary = [];
        $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, $stage, $eval_sum['vendor_id'], 2);
        foreach($criterias as $criteria){

        $members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s', $tender_id, $stage, $criteria['id'], 2);
        $comp_sum = 0;
        $non_comp_sum = 0;
        foreach($members as $member){
            $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, $stage, $eval_sum['vendor_id'], $member['user_id'], $criteria['id']);
            $decision = $evaluation_decision['decision'];
            if($decision == 'Compliant'){
                $comp_sum ++;
            }
            else{
                $non_comp_sum ++;
            }
            }
            if($comp_sum > $non_comp_sum){
                $overall = 'C';
            }
            else{
                $overall = 'NC';
            }
            array_push($overall_summary, $overall);
        }
        ?>
        <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <h4><?php echo $vendor['vendor_name'];?></h4>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Decision</label>
                <?php
                $arr = $overall_summary;
                if(array_search("NC", $arr) === false){
                $overall_value = 'Compliant';
                $overall_class = 'success';
                }
                else{
                $overall_value = 'Non-Compliant';
                $overall_class = 'danger';
                    }
                ?>
                <h5 class="text-<?php echo $overall_class;?>"><?php echo strtoupper($overall_value);?></h5>
                <input type="hidden" name="vendor_id[]" value="<?php echo $vendor['vendor_id']; ?>">
                <input type="hidden" name="overall_decision[]" value="<?php echo $overall_value; ?>">
            </div>
        </div>
            <?php 
            if(array_search("NC", $arr) !== false){
            ?>
                <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Reason for Disqualification</label>
                    <textarea placeholder="Type overall reason for disqualification... " rows="3" class="form-control" name="overall_narration[]"></textarea></td>
                </div>
                </div>
            <?php } 
            else{
            ?>
            <input type="hidden" name="overall_narration[]" value="">
            <?php  } ?>
            </div>
            <?php
            }    //End Foreach Handle Summary
            ?>
    <!--Handle Summary Ends Here-->
    </div>  <!--End Blog Content-->
<br/>  
    <div class="blog-footer">
    <div class="tab-footer clearfix">	
    <button type="submit" id="btnSaveDraft" name="formBtn" value="reEvaluate" class="btn btn-danger">Request Evaluators to Re-Evaluate</button>
    <button type="submit" id="btnCreate" name="formBtn" value="submitDetails" class="btn btn-mint pull-right">Submit Preliminary Results</button>
    </div>
    </div> 
</form>

<?php 
} // <<<<Preliminary Stage Ends Here With the Form
/**
 * 
 * 
 */
elseif($stage == 2){ // <<<< Technical Stage Ends Here With the Form
?>
<form id="demo-bv-bsc-tabs2" action="submit_summary" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="token" value="technical_summary">
    <input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
    <div class="blog-content">
    <table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="7"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
    <?php 
    $evaluators = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, $stage);
    ?>
   <td class="text-bold text-lg text-uppercase" style="color:#20B2AA" colspan="4">Technical Evaluation Summary</td>
        <tr>
        <th class="text-center">Vendor(s)</th>
        <th>Evaluation Criteria</th>
            <?php 
            foreach($evaluators as $evaluator){
                $member = DB::queryFirstRow('SELECT * from org_users where user_id=%s', $evaluator['user_id']);
            ?>
        <td class="text-dark text-lg text-semibold"><?php echo $member['first_name']." ".$member['last_name'];?></td>
        <?php } ?>
        <td class="text-purple">Overall</td>
        </tr>
        <?php 
        $evals = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, $stage);
        foreach($evals as $eval){
        $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
        $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, $stage, $eval['vendor_id'], 2);
       $rowspan = (DB::count() * DB::count()) + 1;
        ?>
     <tr>
     <td rowspan="<?php echo $rowspan;?>" class="td_vendor_name text-semibold"><?php echo $name['vendor_name'];?></td>
        <?php 
        foreach($criterias as $criteria){
            $tender_evaluations = DB::queryFirstRow('SELECT * from tender_evaluations where id=%s AND tender_id=%s AND stage=%s', $criteria['criteria_id'], $tender_id, $stage);
            $eval_members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s order by user_id', $tender_id, $stage, $criteria['id'], 2);
        ?>
        <tr>
        <th style="width:25%"><?php echo $tender_evaluations['criteria_description'];?></th>
            <?php
             $resp = 0;
             $non_resp = 0;
             foreach($eval_members as $eval_member){
                 $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id'], $criteria['id']);
                     $decision = $evaluation_decision['decision'];
                     $narration = $evaluation_decision['narration'];
                     if($decision == 'Responsive'){
                         $class = 'success';
                         $resp ++;
                     }
                     else{
                         $class = 'danger';
                         $non_resp ++;
                     }
             ?>
             <td class="text-<?php echo $class;?> text-semibold"><?php echo strtoupper($decision);?><br/>
             <small class="text-dark"><?php echo $narration;?></small>
            </td>
            <?php } ?>
            <?php 
            //Overall Summary
            if($resp > $non_resp){
                $class = 'success';
                $overall = 'Responsive';
            }
            else{
                $class = 'danger';
                $overall = 'Non-Responsive';
            }
            ?>
            <td class="text-<?php echo $class;?> text-semibold"><i>~<?php echo strtoupper($overall);?>~</i></td>
        </tr>
        
        <?php } ?>
    </tr>
    <tr>
        <td></td>
            <?php 
            foreach($eval_members as $eval_member){
                $all_eval = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s and user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                $evaluation_id = $all_eval['id'];
            ?>
             <td class="text-info text-semibold">
                <div>
				<input id="<?php $eval_member['user_id'];?>" type="checkbox" name="evaluation_id[]" value="<?php echo $evaluation_id;?>">
	            <label class="text-dark" for="<?php $eval_member['user_id'];?>">Re-Evaluate</label>
				</div>
            </td>
            <?php } ?>
        </tr>
       <?php } ?>
        <?php
        ?>
</tbody>
</table>
<br/>
        <!--Handle Summary here-->
        <h5 style="color:blue">Overall Summary</h5>
        <?php 
        $eval_sums = DB::query('SELECT DISTINCT vendor_id from evaluations where tender_id=%s AND stage=%s', $tender_id, $stage);
        foreach($eval_sums as $eval_sum){
        $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval_sum['vendor_id']);
        $overall_summary = [];
        $criterias = DB::query('SELECT * from tender_evaluation_app where tender_id=%s AND stage=%s AND vendor_id=%s AND status=%s', $tender_id, $stage, $eval_sum['vendor_id'], 2);
        foreach($criterias as $criteria){
            
        $members = DB::query('SELECT DISTINCT user_id from evaluations where tender_id=%s AND stage=%s AND tender_eval_app_id=%s AND status=%s', $tender_id, $stage, $criteria['id'], 2);
        $comp_sum = 0;
        $non_comp_sum = 0;
        foreach($members as $member){
            $evaluation_decision = DB::queryFirstRow('SELECT * from evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s AND tender_eval_app_id=%s', $tender_id, $stage, $eval_sum['vendor_id'], $member['user_id'], $criteria['id']);
            $decision = $evaluation_decision['decision'];
            if($decision == 'Responsive'){
                $resp_sum ++;
            }
            else{
                $non_resp_sum ++;
            }
        }
        if($resp_sum > $non_resp_sum){
        $overall = 'R';
        }
        else{
        $overall = 'NR';
        }
        array_push($overall_summary, $overall);
        }
        ?>
        <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
            <h4><?php echo $vendor['vendor_name'];?></h4>
            </div>
        </div>

        <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">Decision</label>
            <?php
            $arr = $overall_summary;
            if(array_search("NR", $arr) === false){
            $overall_value = 'Responsive';
            $overall_class = 'success';
            }
        else{
            $overall_value = 'Non-Responsive';
            $overall_class = 'danger';
            }
            ?>
            <h5 class="text-<?php echo $overall_class;?>"><?php echo strtoupper($overall_value);?></h5>
            <input type="hidden" name="vendor_id[]" value="<?php echo $vendor['vendor_id']; ?>">
            <input type="hidden" name="overall_decision[]" value="<?php echo $overall_value; ?>">
        </div>
        </div>
        <?php 
        if(array_search("NC", $arr) !== false){
        ?>
        <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Reason for Disqualification</label>
            <textarea placeholder="Type overall reason for disqualification... " rows="3" class="form-control" name="overall_narration[]"></textarea></td>
        </div>
        </div>
        <?php } 
        else{
        ?>
        <input type="hidden" name="overall_narration[]" value="">
        <?php  } ?>
        </div>
        <?php
        } //End Foreach Handle Summary
        ?>
    <!--Handle Summary Ends here-->
    </div>
    <br/>  
    <div class="blog-footer">
    <div class="tab-footer clearfix">	
    <button type="submit" id="btnSaveDraft" name="formBtn" value="reEvaluate" class="btn btn-danger">Request Evaluators to Re-Evaluate</button>
    <button type="submit" id="btnCreate" name="formBtn" value="submitDetails" class="btn btn-mint pull-right">Submit Technical Results</button>
    </div>
    </div> 
</form>
<?php 
} // <<<< Technical Stage Ends Here With the Form
/**
 * 
 * 
 */
elseif($stage == 3){ // <<<< Financial Stage Ends Here With the Form
?>
<form id="demo-bv-bsc-tabs" action="submit_summary" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="token" value="financial_summary">
    <input type="hidden" name="tender_id" value="<?php echo $tender_id;?>">
    <div class="blog-content">
    <table class="table table-bordered table-striped pad-ver mar-no">
    <tbody>
    <tr>
    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="7"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
    </tr>
            <!--Financial Summary-->
    <?php 
    $cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
    $cur = $cur['cur'];
    $evaluators = DB::query('SELECT DISTINCT user_id from financial_evaluations where tender_id=%s AND stage=%s order by user_id', $tender_id, $stage);
    ?>
    <td class="text-bold text-lg text-uppercase" style="color:#3CB371" colspan="4">Financial Evaluation Summary</td>
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
        $evals = DB::query('SELECT DISTINCT vendor_id from financial_evaluations where tender_id=%s AND stage=%s', $tender_id, $stage);
        foreach($evals as $eval){
        $name = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $eval['vendor_id']);
        ?>
    <tr>
    <td rowspan="6" class="td_vendor_name"><?php echo $name['vendor_name'];?></td>
        <?php 
        $eval_members = DB::query('SELECT DISTINCT user_id from financial_evaluations where tender_id=%s AND stage=%s AND status=%s order by user_id', $tender_id, $stage, 2);
        ?>
        <!--
            -Sub Total, Current VAT, Evaluated VAT, Evaluated Total
        -->
        <tr>
        <th style="width:25%" class="text-center">Total Bid Price (<?php echo $cur;?>)</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                    $cur_sub_total = $evaluation_decision['cur_sub_total'];
            ?>
            <td class="text-semibold text-info"><?php echo number_format($cur_sub_total);?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Current VAT</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                    $cur_vat = $evaluation_decision['cur_vat'];
            ?>
            <td class="text-semibold text-info"><?php echo $cur_vat;?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Corrected VAT</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                    $corrected_vat = $evaluation_decision['eval_vat'];

                    if(!$corrected_vat){
                        $corrected_vat = "-";
                    }
            ?>
            <td class="text-semibold text-warning"><?php echo $corrected_vat;?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th style="width:25%" class="text-center">Evaluated Total Bid Price(<?php echo $cur;?>)</th>
            <?php
            foreach($eval_members as $eval_member){
                    $evaluation_decision = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
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
            <td class="text-semibold text-mint text-lg"><?php echo number_format($evaluated_total);?><br/></td>
            <?php } ?>
        </tr>
        <tr>
        <th></th>
            <?php 
            foreach($eval_members as $eval_member){
                $all_eval = DB::queryFirstRow('SELECT * from financial_evaluations where tender_id=%s AND stage=%s AND vendor_id=%s AND user_id=%s', $tender_id, $stage, $eval['vendor_id'], $eval_member['user_id']);
                $evaluation_id = $all_eval['id'];
            ?>
            <td class="text-info text-semibold">
                <div>
                <input id="<?php $eval_member['user_id'];?>" type="checkbox" name="evaluation_id[]" value="<?php echo $evaluation_id;?>">
                <label class="text-dark" for="<?php $eval_member['user_id'];?>">Re-Evaluate</label>
                </div>
            </td>
            <?php } ?>
        </tr>
    </tr>
    <input type="hidden" name="vendor_id[]" value="<?php echo $eval['vendor_id']; ?>">
    <input type="hidden" name="overall_decision[]" value="Pass">
    <?php } ?>
        <?php
        ?>
    </tbody>
    </table>
    </div>
    <br/>  
    <div class="blog-footer">
    <div class="tab-footer clearfix">	
    <button type="submit" id="btnSaveDraft" name="formBtn" value="reEvaluate" class="btn btn-danger">Request Evaluators to Re-Evaluate</button>
    <button type="submit" id="btnCreate" name="formBtn" value="submitDetails" class="btn btn-success pull-right">Submit Financial Results</button>
    </div>
    </div> 
</form>
<?php 
} // <<<< Financial Stage Ends Here With the Form
?>
</div>  <!-- END OF panel -->
</div>  <!-- END OF blog blog-list -->
<?php 
}  //End Content Found|| Process Data
?>
</div>  <!-- END OF Page-content -->
</div>  <!-- END OF CONTENT-CONTAINER -->
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
