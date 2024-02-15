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
		first_name: { validators: { notEmpty: { message: 'First Name is required' } } },
        last_name: { validators: { notEmpty: { message: 'Last Name is required' } } },
        email_address: {
            validators: {
                notEmpty: {
                    message: 'The email address is required and cannot be empty'
                },
                emailAddress: {
                    message: 'The input is not a valid email address'
                }
            }
        },
        password: {
            validators: {
                notEmpty: {
                    message: 'The password is required and can\'t be empty'
                },
                stringLength: {
                    min: 8,
                    message: 'The password must be more than 8 characters long'
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_\.\@\!]+$/,
                    message: 'The password can only consist of alphabets, numbers and these characters: . _ ! @'
                },
                identical: {
                    field: 'password_repeat',
                    message: 'The password and its confirmation are not the same'
                },
            }
        },
        password_repeat: {
            validators: {
                notEmpty: {
                    message: 'The confirm password is required and can\'t be empty'
                },
                identical: {
                    field: 'password',
                    message: 'The password and its confirm are not the same'
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
