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
<li class="active text-lg">Evaluate Bids</li>
</ol>
</div>


<!--Page content-->
<!--===================================================-->
<div id="page-content">
<?php 
$tender_id = filter_var(trim($tender_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender_id, 2);
$curDate = date('Y-m-d H:i:s');
$tender = DB::queryFirstRow('SELECT * from tenders where tender_id=%s AND status=%s AND submission_date<=%s order by submission_date desc', $tender_id, 5, $curDate);
if(empty($quotes) || !$tender)
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
        <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="5"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
        </tr>
        <td class="text-bold text-lg" style="color:#FF8C00" colspan="5">Quotations Received</td>
            <?php
            $cnt = 1;
            foreach($quotes as $quote){
            $submission = DB::queryFirstRow('SELECT date_added from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $tender_id, $quote['vendor_id']);
            $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $quote['vendor_id']);

            $check_pre_evaluation = DB::queryFirstRow('SELECT * from evaluations where user_id=%s AND tender_id=%s AND vendor_id=%s AND stage=%s', $current_user['user_id'], $tender_id, $quote['vendor_id'], 1);
            ?>
            <form action="evaluate-preliminary" method="POST" enctype="multipart/form-data"> 
            <input type="hidden" name="eval_tender_id" value="<?php echo $tender['tender_id'];?>">
            <input type="hidden" name="eval_vendor_id" value="<?php echo $quote['vendor_id'];?>">
            <tr>
            <th class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;width:15%"><?php echo $cnt;?></th>
            <th style="width:15%" class="text-center">Vendor:</th>
            <td class="text-info text-lg text-bold text-center"><?php echo $vendor['vendor_name'];?></td>
            <?php 
            if(!$check_pre_evaluation){ //No Evaluation
            ?>
            <th style="width:15%" class="text-primary"><i>Pending Evaluation</i></th>
            <td>
            <button class="btn btn-info text-bold btn-icon btn-sm" type="submit"> Evaluate Preliminary</button>
            </td>
            <?php } 
            elseif($check_pre_evaluation['status'] == 1){ //Saved as Draft
            ?>
            <th style="width:15%" class="text-primary"><i>Preliminary Evaluation Ongoing</i></th>
            <td>
            <button class="btn btn-info text-bold btn-icon btn-sm" type="submit"> Continue Preliminary Evaluation</button>
            </td>
            <?php } 
            else{ //Complete. No Further Action
            ?>
            <th style="width:40%" class="text-primary"><i>Evaluation Completed</i></th>
            
            <?php } ?>
            </tr>
            </form>
            <?php
            $cnt++;
                }
        ?>
                </tbody>
             </table>
            <!--Financials Criteria-->
                </div>
                <br/>   
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
	//Tender Notice Tab
    $('#demo-mail-compose').summernote('disable');
</script>

</body>
</html>
