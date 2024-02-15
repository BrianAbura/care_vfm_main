
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <p class="pad-lft">&#0169; <?php echo date("Y");?> Care Uganda VMS</p>

        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src="../js\jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js\bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="../js\nifty.min.js"></script>

    <!--Bootstrap Select [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-select\bootstrap-select.min.js"></script>
    
    <!--DataTables [ OPTIONAL ]-->
    <script src="../plugins\datatables\media\js\jquery.dataTables.js"></script>
	<script src="../plugins\datatables\media\js\dataTables.bootstrap.js"></script>
	<script src="../plugins\datatables\extensions\Responsive\js\dataTables.responsive.min.js"></script>
    
    <!-- BoostrapFileUpload -->
    <script src="../plugins/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>


    <!--DataTables Sample [ SAMPLE ]-->
    <script src="../js\demo\tables-datatables.js"></script>
    
     <!--Bootbox Modals [ OPTIONAL ]-->
    <script src="..\plugins\bootbox\bootbox.min.js"></script>

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-validator\bootstrapValidator.min.js"></script>

    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-datepicker\bootstrap-datepicker.min.js"></script>


<script>
//Script to add more rows to the end of venues table
$(function(){
    $('#addMoreDesc').on('click', function() {
              var data = $("#requisition_item_table tr:eq(1)").clone(true).appendTo("#requisition_item_table");
              data.find("input").val('');
              data.find("select").val('');
              data.find("textarea").val('');
     });
     $('.DeleteRow').on('click', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>=1) {
             $(this).closest("tr").remove();
           } else {
            $.niftyNoty({
            type: 'danger',
            container : 'floating',
            title : 'Error!',
            message : 'You cannot delete the first row.',
            closeBtn : true,
            timer : 7000,
        });
           }
      });
});      
</script>

<script>
//Add comma to numbers
function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }
    
    //Data
    $(document).ready(function(){
    $('.prices').keyup(function(event) {
  if(event.which >= 37 && event.which <= 40) return;
  $(this).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    ;
  });
});
    });

    //Get the Data and calculate Accordingly
    $("#requisition_item_table").on("keyup",".item_quantity, .item_price", function() {

        var items_total_value = document.getElementById('items_total_value');
        var sum = 0;
        var qty = $(this).closest('tr').find("#item_quantity").val();
        var price = $(this).closest('tr').find("#item_unit_price").val();
        total = qty.split(",").join("") * price.split(",").join("");

        $(this).closest('tr').find('#item_total_price').val(commaSeparateNumber(total));

        $('.item_total_price').each(function(){
        sum += parseFloat(this.value.replace(/,/g,""));
            });

        items_total_value.innerHTML = "Total: "+commaSeparateNumber(sum);
    });
</script>


<!-- Notifications -->    
    <?php 
	if(isset($_SESSION['action_response'])){
		$response = $_SESSION['action_response'];
		$response = json_decode($response);
		$res_msg = $response->Message;
		if($response->Status == "Success"){
?>
<script>
	 $.niftyNoty({
            type: 'success',
            container : 'floating',
            title : 'Success',
			message : '<?php echo $res_msg; ?>',
            closeBtn : true,
            timer : 7000,
        });
</script>
<?php 
		}
	elseif($response->Status == "Error"){
?>
<script>
	 $.niftyNoty({
            type: 'danger',
            container : 'floating',
            title : 'Error!',
            message : '<?php echo $res_msg; ?>',
            closeBtn : true,
            timer : 7000,
        });
</script>

<?php 
	} //End Error Response
}//End Alert || Unset Session
unset($_SESSION['action_response']);
?>
<!-- END Notifications -->  