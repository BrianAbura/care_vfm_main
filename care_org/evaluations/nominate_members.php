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
<li class="active text-lg">Nominate Evaluation Secretary</li>
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
                    <form id="demo-bv-wz-form" action="nominate_committee" method="POST" enctype="multipart/form-data"> 
                    <input type="hidden" name="tender_id" value="<?php echo $tender_id;?>" readonly/>   
                    <input type="hidden" name="token" value="add_committee" readonly/>         
                    <table class="table table-bordered table-striped pad-ver mar-no">
                    <tbody>
                    <tr>
                    <td class="text-bold text-uppercase text-lg text-mint text-center active" colspan="4"><?php echo $tender['tender_title'];?> <br/><small>Ref: <?php echo "# ".$tender_id;?></small></td>
                    </tr>
                    <td class="text-bold text-lg" style="color:#FF8C00" colspan="4">Nominate Members of the Evaluation Committee</td>
                        <tr>
                        <th rowspan="4" class="text-center" style="vertical-align:middle;border-left:5px solid #FF8C00;width:2s0%">Select Member</th>
                        </tr>
                      
                        <tr>
                        <td class="text-info text-semibold text-lg">
                            <div class="col-sm-12">
                            <div class="form-group">
                            <select data-placeholder="Select Evaluation Committee Members..." tabindex="4" id="evaluation_committee" required>
                            <?php 
                            $users = DB::query('SELECT * from org_users where acc_status=%s order by first_name', 'Active');
                            ?>	
                            <option></option>
                            <?php
                            foreach($users as $user){
                            ?>
                            <option value="<?php echo $user['user_id'];?>"><?php echo $user['first_name']." ".$user['last_name'];?></option>
                            <?php } ?>
                            </select>
                            <small class="text-muted text-mint">Min 3.</small>
                            
                            </div>
                            </div>
                        </td>
                        <td>
                        <div class="panel panel-bordered panel-mint">
                                <div class="panel-heading">
                                <h4 class="panel-title">Selected Committee Members</h4>
                                </div>
                                <div class="panel-body">
                                <table id="dynamic_field"></table>
                                </div>
                            </div>
                        </td>
                        </tr>


                        <tr>
                        <td class="text-info text-semibold text-lg">
                            <div class="col-sm-12">
                            <div class="form-group">
                            <small class="text-muted text-info">Evaluation Committee Secretary</small>
                                <select class="form-control" id="evaluation_secretary" required>
                                <option></option>
                                </select>
                            </div>
                            </div>
                        </td>
                        <td>
                            <div class="panel panel-bordered panel-info">
                            <div class="panel-heading">
                            <h class="panel-title">Selected Committee Secretary</h3>
                            </div>
                            <div class="panel-body">
                            <table id="dynamic_field_sec"></table>
                            </div>
                            </div>
                        </td>
                        </tr>


                        <tr>
                        <td class="text-info text-semibold text-lg">
                       <button type="submit" class="finish btn btn-mint">Nominate </button>
                        </td>
                        </tr>
                        
                </tbody>
             </table>
             </form>
                        <!--Financials Criteria-->
                </div>
                <br/>  
                <p class="text-muted text-bold text-primary">Note: Evaluation Committee members must more than 3 and an odd number.</p>
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
//Evaluation Committee Secretary
var y = 0;  
	$('#evaluation_secretary').on('change', function() {
	var sec_selected = $(this).find('option:selected');
	sec_selected.each(function(){
        y++;
		var n_id = y - 1;
        var sec_name = $("#evaluation_secretary").val();
		var sec_member = $(this).text();
        var sec_markup = '<tr id="row_sec'+y+'"><td> - &nbsp <input type="hidden" value="'+sec_name+'" name="evaluation_secretary">'+sec_member+' &nbsp &nbsp&nbsp &nbsp</td>'+ 
		'<td> <button type="button" id="'+y+'" class="btn-xs btn-default text-purple"> ~ Secretary</button></td></tr>';
        $('#row_sec'+n_id+'').remove();  
    	$('#dynamic_field_sec').append(sec_markup);
    });
}); 
</script>

<script>
	//Evaluation Committee Members
	var i = 0;  	
	$('#evaluation_committee').on('change', function() {
	var $selectedOptions = $(this).find('option:selected');
		$selectedOptions.each(function(){
        i++;
        var name = $("#evaluation_committee").val();
		var member = $(this).text();
        var markup = '<tr id="row'+name+'"><td> - &nbsp <input type="hidden" value="'+name+'" name="evaluation_committee[]">'+member+' &nbsp &nbsp&nbsp &nbsp</td>'+ 
		'<td> <button type="button" name="remove" id="'+name+'" class="btn_remove btn-xs btn-danger">Remove</button></td></tr>';
		var select = $("#evaluation_secretary");
			//$("#test_members").html($select);
        if(name != '-') {
            var isExists=false;
            $("#dynamic_field tr input").each(function(){
              var val=$(this).val();
              if(val==name)
                isExists=true;
            }).val()
            if (isExists) {
                $.niftyNoty({
				type: 'danger',
				icon : 'pli-cross icon-2x',
				message : 'Committee Member already selected.',
				container : 'floating',
				timer : 5000
			});
            } else {
                $('#dynamic_field').append(markup);
				select.append('<option value="'+name+'">'+member+'</option>')
            }
        }
    });
    $(document).on('click', '.btn_remove', function(){  
     var button_id = $(this).attr("id");   
     $('#row'+button_id+'').remove();
     $("#evaluation_secretary option[value='"+button_id+"']").remove();
     $('#dynamic_field_sec').empty();
 });  
}); 
</script>
<script>
	//Tender Notice Tab
    $('#demo-mail-compose').summernote('disable');
</script>

</body>
</html>
