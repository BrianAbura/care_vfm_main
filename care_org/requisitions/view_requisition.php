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
<li class="active text-lg">Requisition Details</li>
</ol>
</div>

<!--Page content-->
<!--===================================================-->
<div id="page-content">

<?php 
$requisition_id = filter_var(trim($requisition_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$requisition = DB::queryFirstRow('SELECT * from requisitions where requisition_number=%s', $requisition_id);
if(!isset($requisition))
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
					The Record your are trying to access does not exist. Click <a href="../requisitions" class="alert-link">Here.</a> to go back to the list of requisitions.
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
} //End User Query || Null records
else{
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
        <table class="table table-bordered table-striped pad-ver mar-no">
        <h4 style="color:teal;">Requisition Details</h4><br/>
            <tbody>
                <tr>
                    <td class="text-info text-bold">Requisition Number</td>
                    <td><?php echo $requisition['requisition_number'];?></td>
                </tr>

                <tr>
                    <td class="text-info text-bold">Distrib</td>
                    <td><?php echo $requisition['distrib'];?></td>

                    <td class="text-info text-bold">PR Status</td>
                    <td><?php echo $requisition['req_status'];?></td>

                    <td class="text-info text-bold">Location</td>
                    <td><?php echo $requisition['location'];?></td>

                    <td class="text-info text-bold">Req Quantity</td>
                    <td><?php echo $requisition['req_qty'];?></td>

                    <td class="text-info text-bold">Merchandise Amount</td>
                    <td><?php echo ($requisition['merchandise_amt']);?></td>
                </tr>

                <tr>
                    <td class="text-info text-bold">GL Unit</td>
                    <td><?php echo $requisition['gl_unit'];?></td>

                    <td class="text-info text-bold">Account</td>
                    <td><?php echo $requisition['account'];?></td>

                    <td class="text-info text-bold">Alt Acct</td>
                    <td><?php echo $requisition['alt_account'];?></td>

                    <td class="text-info text-bold">Fund</td>
                    <td><?php echo $requisition['fund'];?></td>

                    <td class="text-info text-bold">PC Bus Unit</td>
                    <td><?php echo $requisition['pc_bus_unit'];?></td>
                </tr>

                <tr>
                    <td class="text-info text-bold">Project</td>
                    <td><?php echo $requisition['project'];?></td>

                    <td class="text-info text-bold">Activity</td>
                    <td><?php echo $requisition['activity'];?></td>

                    <td class="text-info text-bold">Source Type</td>
                    <td><?php echo $requisition['source_type'];?></td>

                    <td class="text-info text-bold">PR Category</td>
                    <td><?php echo $requisition['req_category'];?></td>

                    <td class="text-info text-bold">Affiliate</td>
                    <td><?php echo $requisition['affiliate'];?></td>
                </tr>
                <tr>

                    <td class="text-info text-bold">Fund Affiliate</td>
                    <td><?php echo $requisition['fund_affiliate'];?></td>

                    <td class="text-info text-bold">Project Affiliate</td>
                    <td><?php echo $requisition['project_affiliate'];?></td>

                    <td class="text-info text-bold">Project Affiliate</td>
                    <td><?php echo $requisition['project_affiliate'];?></td>
                </tr>
            
                <tr>

                <td class="text-info text-bold">Status</td>
                    <?php  
                    //1 - Draft 2 - Approved
                    if($requisition['status'] == 1){ 
                    ?>
                    <td class="text-lg"><span class="label label-warning">Draft</span></td>
                    <?php }
                    elseif($requisition['status'] == 2){ 
                    ?>
                     <td class="text-lg"><span class="label label-success">Approved</span></td>
                    <?php } ?>

                    <td class="text-info text-bold">Department</td>
                    <td><?php echo get_deparment($requisition['department']);?></td>

                    <td class="text-info text-bold">People Soft PR Extract <span class="text-danger">*</span> </td>
                    <td colspan="12">
                        <?php
                            if(empty($requisition['req_attachment'])){
                                echo "No file attached";
                            }
                            else{
                        
                         $file = str_replace($BASEPATH, '..', $requisition['req_attachment']);
                        ?>
                        <a href="<?php echo $file;?>" target="_blank" class="btn-link text-semibold text-success"><i class="fa fa-cloud-download icon-2x icon-fw"></i>View File</a><br/>
                       <?php } ?>
                    </td>
                </tr>


                <tr>
                    <td class="text-info text-bold">Requisition Description</td>
                    <td colspan="12"><?php echo $requisition['requisition_name'];?></td>
                </tr>
                <tr>
                    <td class="text-info text-bold">Procurement Category</td>
                    <td>
                        <?php 
                        $category = DB::queryFirstRow('SELECT * from procurement_categories where id=%s', $requisition['category']);
                        echo $category['name'];
                        ?>
                    </td>
                    <td class="text-info text-bold">Currency</td>
                    <td><?php echo $requisition['currency'];?></td>
                    <td class="text-info text-bold">Due Date</td>
                    <td><?php echo date_format(date_create($requisition['due_date']), 'd-M-Y'); ?></td>
                </tr>
            </tbody>
        </table>
    <hr/>
        <table class="table table-bordered table-striped pad-ver mar-no">
        <h4 style="color:#e4701e;">Requisition Items</h4><br/>
            <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>Category (Level 1)</th>
                <th>Category (Level 2)</th>
                <th>Category (Level 3)</th>
                <th>Category (Level 4)</th>
                <th>Unit of Measure</th>
                <th>Quantiy</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $items = DB::query('SELECT * from requisition_items where requisition_number=%s', $requisition['requisition_number']);
                $cnt = 1;
                $sum = 0;

                foreach($items as $item){
                    $cat1 = DB::queryFirstRow('SELECT * from level1_category where id=%s', $item['category_1']);
                    $cat2 = DB::queryFirstRow('SELECT * from level2_category where id=%s', $item['category_2']);
                    $cat3 = DB::queryFirstRow('SELECT * from level3_category where id=%s', $item['category_3']);
                    $cat4 = DB::queryFirstRow('SELECT * from level4_category where id=%s', $item['category_4']);

                    if(!empty($item['quantity']) || !empty($item['price'])){
                        $qty = $item['quantity'];
                        $price = $item['price'];
                        $value = $qty * $price;
                        $sum += $value;
                    }
                    else{
                        $qty = 0;
                        $price = 0;
                        $value = 0;
                    }
                ?>
                <tr>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $item['description'];?></td>
                    <td><?php echo $cat1['description'];?></td>
                    <td><?php echo $cat2['description'];?></td>
                    <td><?php echo $cat3['description'];?></td>
                    <td><?php echo $cat4['description'];?></td>
                    <td><?php echo $item['unit_of_measure'];?></td>
                    <td><?php echo number_format($qty);?></td>
                    <td><?php echo number_format($price);?></td>
                    <td><?php echo number_format($value);?></td>
                </tr>
                <?php 
            $cnt ++;  
            }
                ?>
            </tbody>
        </table>
        <br/>
        <h4 class="text-right">Requisition Total: <?php echo number_format($sum); ?></h4>
        <hr/>
        <?php 
       if(restrict_soc($current_user['role_id'])){
        ?>
            <div class="tab-footer clearfix">
<!--                 
<button class="btn btn-danger btn-sm delete-requisition" data-id="<?php echo $requisition_id; ?>"><i class="fa fa-trash"></i> Delete</button>	
                -->
            <a href="<?php echo $requisition_id;?>"><button class="btn btn-info btn-sm"><i class="demo-psi-pen-5 icon-sm"></i> Edit Requisition</button></a>
            </div>
            <?php } ?>
		</div>
        
		</div>
        
	</div>
</div>
<?php 
} //End User Query || Record found
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
//Confirm Delete
$('.delete-requisition').on('click', function(){
	var requisition_id = $(this).data("id");
    var token = 'delete_requisition';
	bootbox.dialog({
	//title: "Create New Department",
	message : "<br/><h4 style='color:#e47s01e' class='text-danger'>Are you sure you want to delete this Requisition and all it's items? </h4>",
	buttons: {
		success: {
			label: "Yes, Delete",
			className: "btn-primary",
			callback : function(result) {
                $.ajax({
					type : 'post',
					url : 'Requisitions.php',
					data :  'requisition_number='+ requisition_id+'&token='+token, 
					success : function(data){
						var result = JSON.parse(data);
                        if (result.Status == "Success") {
							$.niftyNoty({
								type: 'success',
								icon : 'pli-like-2 icon-2x',
								message : result.Message,
								container : 'floating',
								timer : 5000
							});
						}else{
							$.niftyNoty({
								type: 'danger',
								icon : 'pli-cross icon-2x',
								message : result.Message,
								container : 'floating',
                                timer : 5000
							});
						};
						setTimeout(function(){ window.location = "../requisitions"; }, 6000);
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
