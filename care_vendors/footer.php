
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

    <script src="js\jquery.min.js"></script>

<!--BootstrapJS [ RECOMMENDED ]-->
<script src="js\bootstrap.min.js"></script>

<!--NiftyJS [ RECOMMENDED ]-->
<script src="js\nifty.min.js"></script>

<!--Sparkline [ OPTIONAL ]-->
<script src="plugins\sparkline\jquery.sparkline.min.js"></script>


<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!--Bootstrap Select [ OPTIONAL ]-->
<script src="plugins\bootstrap-select\bootstrap-select.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="plugins\datatables\media\js\jquery.dataTables.js"></script>
<script src="plugins\datatables\media\js\dataTables.bootstrap.js"></script>
<script src="plugins\datatables\extensions\Responsive\js\dataTables.responsive.min.js"></script>
<!-- BoostrapFileUpload -->
<script src="plugins/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>

<!--DataTables Sample [ SAMPLE ]-->
<script src="js\demo\tables-datatables.js"></script>

<!--Bootbox Modals [ OPTIONAL ]-->
<script src="plugins\bootbox\bootbox.min.js"></script>

<!--Bootstrap Validator [ OPTIONAL ]-->
<script src="plugins\bootstrap-validator\bootstrapValidator.min.js"></script>

<!--Bootstrap Datepicker [ OPTIONAL ]-->
<script src="plugins\bootstrap-datepicker\bootstrap-datepicker.min.js"></script>

<script>
    $(document).on('nifty.ready', function() {
    var faIcon = {
        valid: 'fa fa-check-circle fa-lg text-success',
        invalid: 'fa fa-times-circle fa-lg',
        validating: 'fa fa-refresh'
    }
    // FORM VALIDATION ON TABS
    // =================================================================
    $('#demo-bv-bsc-tabs').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: faIcon,
        fields: {
            first_name: {
            validators: {
                notEmpty: {
                    message: 'The full name is required'
                }
            }
        },
        company: {
            validators: {
                notEmpty: {
                    message: 'The company name is required'
                }
            }
        },
        memberType: {
            validators: {
                notEmpty: {
                    message: 'Please choose the membership type that best meets your needs'
                }
            }
        },
        address: {
            validators: {
                notEmpty: {
                    message: 'The address is required'
                }
            }
        },
        city: {
            validators: {
                notEmpty: {
                    message: 'The city is required'
                }
            }
        },
        country: {
            validators: {
                notEmpty: {
                    message: 'The city is required'
                }
            }
        }
        }
    }).on('status.field.bv', function(e, data) {
        var $form     = $(e.target),
        validator = data.bv,
        $tabPane  = data.element.parents('.tab-pane'),
        tabId     = $tabPane.attr('id');

        if (tabId) {
        var $icon = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent().find('i');

        // Add custom class to tab containing the field
        if (data.status == validator.STATUS_INVALID) {
            $icon.removeClass(faIcon.valid).addClass(faIcon.invalid);
        } else if (data.status == validator.STATUS_VALID) {
            var isValidTab = validator.isValidContainer($tabPane);
            $icon.removeClass(faIcon.valid).addClass(isValidTab ? faIcon.valid : faIcon.invalid);
        }
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
<!-- END Notifications -->  