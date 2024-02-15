        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">
            <p class="pad-lft">&#0169; <?php echo date("Y");?> Care Uganda VMS</p>
        </footer>
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
    <script src="..\js\jquery.min.js"></script>

    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="..\js\bootstrap.min.js"></script>

    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="..\js\nifty.min.js"></script>

    <!--Sparkline [ OPTIONAL ]-->
    <script src="..\plugins\sparkline\jquery.sparkline.min.js"></script>


<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!--Bootstrap Select [ OPTIONAL ]-->
<script src="..\plugins\bootstrap-select\bootstrap-select.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="../plugins\datatables\media\js\jquery.dataTables.js"></script>
<script src="../plugins\datatables\media\js\dataTables.bootstrap.js"></script>
<script src="../plugins\datatables\extensions\Responsive\js\dataTables.responsive.min.js"></script>
<!-- BoostrapFileUpload -->
<script src="../plugins/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>



 <!--Bootbox Modals [ OPTIONAL ]-->
<script src="..\plugins\bootbox\bootbox.min.js"></script>

<!--Bootstrap Validator [ OPTIONAL ]-->
<script src="..\plugins\bootstrap-validator\bootstrapValidator.min.js"></script>

<!--Bootstrap Datepicker [ OPTIONAL ]-->
<script src="..\plugins\bootstrap-datepicker\bootstrap-datepicker.min.js"></script>

<script>
  //Image Validation
  var _validLogoExtensions = [".jpg", ".jpeg", ".png", ".doc", ".docx", ".pdf"];   
function ValidateDocs(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validLogoExtensions.length; j++) {
                var sCurExtension = _validLogoExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                $.niftyNoty({
                    type: 'danger',
                    container : 'floating',
                    title : 'Error!',
                    message : 'Sorry, the file type is invalid, accepted file formats are: jpg, jpeg, png, doc, docx and pdf',
                    closeBtn : true,
                    timer : 7000,
                });
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
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
    $('.var_prices').keyup(function(event) {
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
    $("#requisition_item_table").on("keyup",".item_price", function() {

        var sum = 0;
        var qty = $(this).closest('tr').find("#item_quantity").val();
        var price = $(this).closest('tr').find("#item_unit_price").val();
        total = qty.split(",").join("") * price.split(",").join("");

        $(this).closest('tr').find('#item_total_price').val(commaSeparateNumber(total));

        $('.item_total_price').each(function(){
        sum += parseFloat(this.value.replace(/,/g,""));
            });
        
        $('#items_total_value').val(commaSeparateNumber(sum));
        $('#items_grand_total').val(commaSeparateNumber(sum));

    });

    $("#requisition_item_table").on("keyup",".items_vat", function() {

    var sub_total = $('#items_total_value').val();
    var vat = $('#items_vat').val();
    var grand = 0;

    if(vat){
        sub_total = sub_total.split(",").join("");
        grand =  ((sub_total * (vat/100))) + Number(sub_total);        
        $('#items_grand_total').val(commaSeparateNumber(grand));
    }
    else{
        $('#items_grand_total').val(commaSeparateNumber(sub_total));
    }
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