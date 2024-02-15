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
<li class="active text-lg">Quotations Received</li>
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
                    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="4"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
                    </tr>
                    <td class="text-bold text-lg" style="color:#FF8C00" colspan="4">Quotations Received</td>
                        <?php
                        $cnt = 1;
                        foreach($quotes as $quote){
                        $submission = DB::queryFirstRow('SELECT date_added from tender_evaluation_app where tender_id=%s AND vendor_id=%s', $tender_id, $quote['vendor_id']);
                        $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $quote['vendor_id']);
                        ?>
                        <tr>
                        <th rowspan="3" class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;width:15%"><?php echo $cnt;?></th>
                        <th style="width:15%">Vendor:</th>
                        <td class="text-primary text-lg text-bold"><?php echo $vendor['vendor_name'];?></td>
                        </tr>
                        <tr>
                        <th>Bid Price:</th>
                        <?php 
                            $sub_total = 0;
                            $grand_total = 0;
                            $vat = 0;
                            $cur = DB::queryFirstRow('SELECT currency as cur from requisitions where requisition_number=%s',$tender['requisition_id']);
                            $cur = $cur['cur'];
                         $requests = DB::query('SELECT * from requisition_items where requisition_number=%s',$tender['requisition_id']);
                         foreach($requests as $request){
                            $amt_resp = DB::queryFirstRow('SELECT * from tender_finance_app where tender_id=%s AND vendor_id=%s AND stage=%i AND requisition_id=%d', $tender_id, $vendor['vendor_id'], 3, $request['id']);
                            $unit_price = $amt_resp['vendor_response'];
                            $total = $unit_price * $request['quantity'];
                            $vat = $amt_resp['vat'];
                            
                            $sub_total += $total;
                            
                            } 
                        if($vat != 0){
                            $total_vat = $sub_total * ($vat/100);
                            $grand_total = $sub_total + $total_vat;
                        }
                        else{
                            $grand_total = $sub_total;
                        }
                        ?>
                        <td class="text-pink text-semibold text-lg"><?php echo $cur." ".number_format($grand_total);?></td>
                        </tr>
                        <tr>
                        <th>Submission Date:</th>
                        <td class="text-info text-semibold text-lg"><?php echo  date_format(date_create($submission['date_added']), 'd/m/Y H:i');?></td>
                        </tr>
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
