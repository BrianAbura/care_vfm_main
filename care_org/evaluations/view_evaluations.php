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
	.space {
	margin-bottom: 4px;
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
<li class="active text-lg">Current Evaluations</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<!--Data Table-->
			<!--===================================================-->
	<div class="panel-body">
		<div class="table-respsonsive">
		<table id="demo-dt-addrow" class="table" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						<th>Subject</th>
						<th>Procurement Method</th>
						<th>Received Quotations</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
		<?php 
		$cnt = 1;
		$curDate = date('Y-m-d H:i:s');
		$tenders = DB::query('SELECT * from tenders where status=%s and submission_date<=%s order by submission_date desc', 5, $curDate);
		//All Closed tenders
		foreach($tenders as $tender){
		//Check if the Tender was discarded.
		//Approved and completed tenders will not appear here.
		$chk_completed = DB::queryFirstRow('SELECT * from completed_evaluations where tender_id=%s AND decision=%s', $tender['tender_id'], "Approved");
		if(!$chk_completed){
		if(is_evaluator($current_user['user_id'], $tender['tender_id']) || restrict_pcc($current_user['role_id'])){
		$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $tender['requisition_id']);
		$proc_method = DB::queryFirstRow('SELECT * from procurement_methods where id=%s', $tender['solicitation_method']);
		$department = DB::queryFirstRow('SELECT name from departments where id=%s', $requisition['department']);

		//Received Quotations
		$sum_quotes = 0;
		$quotes = DB::query('SELECT DISTINCT vendor_id from tender_evaluation_app where tender_id=%s and status=%d', $tender['tender_id'], 2);
		if($quotes){
			$sum_quotes = DB::count();
		}
		?>
			<tr>
			<td><?php echo $cnt; ?></td>
			<td ><?php echo $tender['tender_id']; ?></td>
			<td style="width:40%"><a href="../tenders/<?php echo $tender['tender_id']."-view";?>"><span class="text-bold text-lg text-info"><?php echo $tender['tender_title'];?></span></a><br/>
				<small>Dept: <?php echo $department['name'];?></small>
			</td>
			<td><?php echo $proc_method['method_name']; ?></td>
			<td><?php echo $sum_quotes; ?></td>

			<!-- Actions Column -->
			<td>
			<?php
			//Check if evaluators exist
			$check_eval = DB::query('SELECT * from tender_committee where tender_id=%s', $tender['tender_id']);
			if(!$check_eval){
			?>
			<a href="<?php echo $tender['tender_id']."-nominate_committee";?>"><button class="btn btn-mint text-bold btn-icon btn-sm"> Nominate Evaluation Committee</button></a>
			<?php
			}
			else{
				//Actions for Nominated Evaluators
			if(is_evaluator($current_user['user_id'], $tender['tender_id'])){

			//Check Summary First
			$overall_summary = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s',$tender['tender_id'], 1); //Preliminary
			if(!$overall_summary){
			$check_pre_Evauation = DB::query('SELECT DISTINCT vendor_id from evaluations where user_id=%s AND tender_id=%s AND status=%d AND stage=%s', $current_user['user_id'], $tender['tender_id'], 2, 1);
			$prelimnary_quotes_evaluated = DB::count();
				if($prelimnary_quotes_evaluated != $sum_quotes){
				?>
				<a href="<?php echo $tender['tender_id']."-bids-preliminary";?>"><button class="btn btn-success text-bold btn-icon btn-sm"> Evaluate Preliminary</button></a>
				<?php
				}
				else{
					echo "<span style='color:#FF8C00'>Preliminary Evaluation Completed</span>";
				}
			?>
			<?php 
				//Only the Evaluation Secretary| Manage the completed bids
				if(is_eval_secretary($current_user['user_id'], $tender['tender_id'])){
					if(total_evaluated_members($tender['tender_id'],1) == total_evaluators($tender['tender_id'])){
					?>
					<a href="summary?tender_id=<?php echo $tender['tender_id']?>&stage=1"><button class="btn btn-mint text-bold btn-icon btn-sm" id="eval_completed"> Complete Evaluation</button></a>
				<?php     
				} }
			}//End Overall_summary_preliminary
			?>

			<?php
			//Preliminary present, then we handle the technical evaluation
			$overall_summary_tech = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s',$tender['tender_id'], 2); //Preliminary
			if($overall_summary && !$overall_summary_tech){
				//Successfull at Preliminary
				$sum_pre_quotes = 0;
				$quotes = DB::query('SELECT DISTINCT vendor_id from evaluation_summary where tender_id=%s AND stage=%d AND decision=%s', $tender['tender_id'], 1, "Compliant");
					if($quotes){
						$sum_pre_quotes = DB::count();
					}
				$check_tech_Evauation = DB::query('SELECT DISTINCT vendor_id from evaluations where user_id=%s AND tender_id=%s AND status=%d AND stage=%s', $current_user['user_id'], $tender['tender_id'], 2, 2);
				$technical_quotes_evaluated = DB::count();
					if($technical_quotes_evaluated != $sum_pre_quotes){
					?>
					<a href="<?php echo $tender['tender_id']."-bids-technical";?>"><button class="btn btn-success text-bold btn-icon btn-sm"> Evaluate Technical</button></a>
					<?php
					}
					else{
						echo "<span class='text-mint'>Technical Evaluation Completed</span>";
					}
			?>
			<?php 
				//Only the Evaluation Secretary| Manage the completed bids
				if(is_eval_secretary($current_user['user_id'], $tender['tender_id'])){
					if(total_evaluated_members($tender['tender_id'],2) == total_evaluators($tender['tender_id'])){
					?>
					<a href="summary?tender_id=<?php echo $tender['tender_id']?>&stage=2"><button class="btn btn-mint text-bold btn-icon btn-sm" id="eval_completed"> Complete Evaluation</button></a>
				<?php     
				} }
			}//End Overall_summary_technicals
			?>

			<?php
			//Preliminary and Tech Present we push to Financials and complete the evaluation
			$overall_summary_finance = DB::query('SELECT * from evaluation_summary where tender_id=%s AND stage=%s',$tender['tender_id'], 3); //Finanicals
			if($overall_summary && $overall_summary_tech && !$overall_summary_finance){
				//Successfull at Technical
				$sum_tech_quotes = 0;
				$quotes = DB::query('SELECT DISTINCT vendor_id from evaluation_summary where tender_id=%s AND stage=%d AND decision=%s', $tender['tender_id'], 2, "Responsive");
					if($quotes){
						$sum_tech_quotes = DB::count();
					}
				$check_fin_Evauation = DB::query('SELECT DISTINCT vendor_id from financial_evaluations where user_id=%s AND tender_id=%s AND status=%d AND stage=%s', $current_user['user_id'], $tender['tender_id'], 2, 3);
				$fin_quotes_evaluated = DB::count();
					if($fin_quotes_evaluated != $sum_tech_quotes){
					?>
					<a href="<?php echo $tender['tender_id']."-bids-financial";?>"><button class="btn btn-success text-bold btn-icon btn-sm"> Evaluate Financials</button></a>
					<?php
					}
					else{
						echo "<span style='color:#3CB371'>Financial Evaluation Completed</span>";
					}
			?>
			<?php 
				//Only the Evaluation Secretary| Manage the completed bids
				if(is_eval_secretary($current_user['user_id'], $tender['tender_id'])){
					if(total_evaluated_fin_members($tender['tender_id']) == total_evaluators($tender['tender_id'])){
					?>
					<a href="summary?tender_id=<?php echo $tender['tender_id']?>&stage=3"><button class="btn btn-mint text-bold btn-icon btn-sm" id="eval_completed"> Complete Evaluation</button></a>
				<?php     
				} }
			}//End Overall_summary_Financials
			/** Once financials are done ONLY.
			 * Secretary will either: 1. Conduct Post Qualification or 2. Finalize Evaluation
			 */
			 if($overall_summary_finance){
				if(is_eval_secretary($current_user['user_id'], $tender['tender_id'])){
			?>
				<a href="post_qualification?tender_id=<?php echo $tender['tender_id']?>" class="btn btn-warning text-bold btn-sm"> Upload Post-Qualification Report</a>
				<div class="space"></div>
				<a href="finalize?level=1&tender=<?php echo $tender['tender_id']?>" class="btn btn-success text-bold btn-sm"> Finalize Evaluation</a>
			<?php 
				}
			 }	

				} //End Actions for Nominated Evaluators
			} //End here if no evaluators were nominated
			?>

			</td>
		<!-- End of Actions Column -->
		</tr>
		<?php
			$cnt ++;
	}//Only Evaluators
} //End Checking for completed
			} // EndForEach Tender ?>
				</tbody>
			</table>
		</div>
	</div>
	<!--===================================================-->
	<!--End Data Table-->

		</div>
	</div>
</div>
</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
</div>

<?php 
/*** Include the Global Footer and Java Scripts */
include $DIR."/footers.php"; 
?>

</body>
</html>
