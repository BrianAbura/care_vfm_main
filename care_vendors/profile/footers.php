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

<!--DataTables Sample [ SAMPLE ]-->
<script src="../js\demo\tables-datatables.js"></script>

 <!--Bootbox Modals [ OPTIONAL ]-->
<script src="..\plugins\bootbox\bootbox.min.js"></script>

<!--Bootstrap Validator [ OPTIONAL ]-->
<script src="..\plugins\bootstrap-validator\bootstrapValidator.min.js"></script>

<!--Bootstrap Datepicker [ OPTIONAL ]-->
<script src="..\plugins\bootstrap-datepicker\bootstrap-datepicker.min.js"></script>
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