
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>

            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

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


    <!--DataTables Sample [ SAMPLE ]-->
    <script src="../js\demo\tables-datatables.js"></script>
    
     <!--Bootbox Modals [ OPTIONAL ]-->
    <script src="..\plugins\bootbox\bootbox.min.js"></script>

    <!--Chosen [ OPTIONAL ]-->
    <script src="..\plugins\chosen\chosen.jquery.min.js"></script>

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-validator\bootstrapValidator.min.js"></script>


    <!--Bootstrap Timepicker [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-timepicker\bootstrap-timepicker.min.js"></script>


    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-datepicker\bootstrap-datepicker.min.js"></script>
    
    <!--Bootstrap Wizard [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-wizard\jquery.bootstrap.wizard.min.js"></script>

    <!--Summernote [ OPTIONAL ]-->
    <script src="..\plugins\summernote\summernote.min.js"></script>



<script>
    $(document).on('nifty.ready', function() {
        $('#demo-tp-com').timepicker();
        $('#multiple_vendors').chosen({width:'100%'});
        $('#evaluation_committee').chosen({width:'100%'});
    });

</script>



<script>
//Preliminary Criteria
$(function(){
    $('#addPreCriteria').on('click', function() {
              var data = $("#pleliminary_item_table tr:eq(1)").clone(true).appendTo("#pleliminary_item_table");
              data.find("input").val('');
              data.find("select").val('');
              data.find("textarea").val('');
     });
     $('#delPreCriteria').on('click', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>=1) {
             $(this).closest("tr").remove();
           } else {
            $.niftyNoty({
            type: 'danger',
            container : 'floating',
            title : 'Error!',
            message : 'You need atleast one preliminary criteria for evaluation.',
            closeBtn : true,
            timer : 7000,
        });
           }
      });
});   
//Technical Criteriaa
$(function(){
    $('#addTechCriteria').on('click', function() {
              var data = $("#technical_item_table tr:eq(1)").clone(true).appendTo("#technical_item_table");
              data.find("input").val('');
              data.find("select").val('');
              data.find("textarea").val('');
     });
     $('#delTechCriteria').on('click', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>=1) {
             $(this).closest("tr").remove();
           } else {
            $.niftyNoty({
            type: 'danger',
            container : 'floating',
            title : 'Error!',
            message : 'You need atleast one technical criteria for evaluation.',
            closeBtn : true,
            timer : 7000,
        });
           }
      });
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