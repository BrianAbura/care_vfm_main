
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

    
    <!--Flot Chart [ OPTIONAL ]-->
    <script src="../plugins\flot-charts\jquery.flot.min.js"></script>
	<script src="../plugins\flot-charts\jquery.flot.resize.min.js"></script>
	<script src="../plugins\flot-charts\jquery.flot.tooltip.min.js"></script>


    <!--Sparkline [ OPTIONAL ]-->
    <script src="../plugins\sparkline\jquery.sparkline.min.js"></script>


    
    <!--DataTables [ OPTIONAL ]-->
    <script src="../plugins\datatables\media\js\jquery.dataTables.js"></script>
	<script src="../plugins\datatables\media\js\dataTables.bootstrap.js"></script>
	<script src="../plugins\datatables\extensions\Responsive\js\dataTables.responsive.min.js"></script>


    <!--DataTables Sample [ SAMPLE ]-->
    <script src="../js\demo\tables-datatables.js"></script>
    
     <!--Bootbox Modals [ OPTIONAL ]-->
    <script src="..\plugins\bootbox\bootbox.min.js"></script>

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="..\plugins\bootstrap-validator\bootstrapValidator.min.js"></script>

        <!--Buttons [ SAMPLE ]-->
        
    <script>
           

    $('#active_vendors_table').dataTable( {
    "responsive": true,
    "language": {
        "paginate": {
            "previous": '<i class="demo-psi-arrow-left"></i>',
            "next": '<i class="demo-psi-arrow-right"></i>'
        }
    }
    } );   

    $('#pending_vendors_table').dataTable( {
    "responsive": true,
    "language": {
        "paginate": {
            "previous": '<i class="demo-psi-arrow-left"></i>',
            "next": '<i class="demo-psi-arrow-right"></i>'
        }
    }
    } );   

    </script>
    
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