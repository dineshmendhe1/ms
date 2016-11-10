/* 
 *  Core Job Board CSS File
 *  v1.0.3
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        
        $(".jobpost_form").on("submit", function (event) {
            var jobpost_submit_button = $('#jobpost-submit-button');
            var jobpost_form_status = $('#jobpost_form_status');
            var datastring = new FormData(document.getElementById("sjb-application-form"));

            /** 
             * Application Form Submit -> Validate Email & Phone 
             * @since 2.2.0          
             */
            var is_valid_email = sjb_is_valid_input(event, "email", "sjb-email-address");
            var is_valid_phone = sjb_is_valid_input(event, "phone", "sjb-phone-number");
            var is_attachment = sjb_is_attachment(event);            

            /* Stop Form Submission on Invalid Phone, Email & File Attachement */
            if (!is_valid_email || !is_valid_phone || !is_attachment) {
                return false;
            }

            $.ajax({
                url: application_form.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: datastring,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    jobpost_form_status.html('Submitting.....');
                    jobpost_submit_button.attr('disabled', 'diabled');
                },
                success: function (response) {
                    if (response['success'] == true) {
                        $('.jobpost_form').slideUp();

                        /* Translation Ready String Through Script Locaization */
                        jobpost_form_status.html(application_form.jquery_alerts['application_submitted']);
                    }
                    
                    if (response['success'] == false) {

                        /* Translation Ready String Through Script Locaization */
                        jobpost_form_status.html(response['error'] + application_form.jquery_alerts['application_not_submitted'] + '</div>');
                        jobpost_submit_button.removeAttr('disabled');
                    }

                }
            });
            return false;
        });

        /* Date-Time Picker */
        $('.sjb-datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true
        });

        // Hide & Show Job Listing Logo & Description According to Settings
        if ('logo-detail' === application_form.job_listing_content) {
            $(".company-logo").show();
            $(".job-description").show();
        }

        if ('without-logo' === application_form.job_listing_content) {
            $(".job-description").show();
            $(".company-logo").hide();
        }

        if ('without-logo-detail' === application_form.job_listing_content) {
            $(".company-logo").hide();
            $(".job-description").hide();
        }

        if ('without-detail' === application_form.job_listing_content) {
            $(".company-logo").show();
            $(".job-description").hide();
        }
        
        // Hide & Show Job Post Logo According to Settings
        if ('with-logo' === application_form.jobpost_content) {
            $(".sjb-company-logo").show();
        }
        
        if ('without-logo' === application_form.jobpost_content) {
            $(".sjb-company-logo").hide();
        }

        /** 
         * Application Form -> On Input Email Validation
         *  
         * @since   2.2.0          
         */
        $('.sjb-email-address').on('input', function () {
            var input = $(this);
            var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var is_email = re.test(input.val());
            var error_element = $("span", $(this).parent());
            if (is_email) {
                input.removeClass("invalid").addClass("valid");
                error_element.removeClass("error-show").addClass("sjb-invalid-email");
            } else {
                input.removeClass("valid").addClass("invalid");
            }
        });
        
        /**
         * Initialise TelInput Plugin
         * 
         * @since   2.2.0
         */
        if( $('.sjb-phone-number').length ){
            var telInput_id = $('.sjb-phone-number').map(function () {
                return this.id;
            }).get();

            for (var input_ID in telInput_id) {
                var telInput = $('#' + telInput_id[input_ID]);
                telInput.intlTelInput({
                    initialCountry: "auto",
                    geoIpLookup: function (callback) {
                        $.get('http://ipinfo.io', function () {
                        }, "jsonp").always(function (resp) {
                            var countryCode = (resp && resp.country) ? resp.country : "";
                            callback(countryCode);
                        });
                    },
                });
            }
        } 

        /**
         * Application Form -> Phone Number Validation
         * 
         * @since 2.2.0
         */
        $('.sjb-phone-number').on('input', function () {
            var telInput = $(this);
            var telInput_id = $(this).attr('id');
            var error_element = $("#" + telInput_id + "-invalid-phone");

            // Validate Phone Number
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    telInput.removeClass("invalid").addClass("valid");
                    error_element.removeClass("error-show").addClass("sjb-invalid-phone");
                } else {
                    telInput.removeClass("valid").addClass("invalid");
                }
            }
        });
        
        /** 
         * Check for Allowable Extensions of Uploaded File
         *  
         * @since   2.3.0          
         */
        $('.sjb-attachment').on('change', function () {
            var jobpost_submit_button = $('#jobpost-submit-button');
            var input = $(this);
            var file = $("#" + $(this).attr("id"));
            var error_element = file.next("span");
            error_element.text('');
            error_element.removeClass("error-show").addClass("sjb-invalid-attachment");                   
            
            // Validate on File Attachment
            if ( 0 != file.get(0).files.length ) {
                /**
                 *  Uploded File Extensions Checks
                 *  Get Uploded File Ext
                 */
                var file_ext = file.val().split('.').pop().toLowerCase();

                // All Allowed File Extensions
                var allowed_file_exts = application_form.allowed_extensions;

                // Settings File Extensions && Getting value From Script Localization
                var settings_file_exts = application_form.setting_extensions;
                var selected_file_exts = (('yes' === application_form.all_extensions_check) || null == settings_file_exts) ? allowed_file_exts : settings_file_exts;

                // File Extension Validation
                if ($.inArray(file_ext, selected_file_exts) > -1) {
                    jobpost_submit_button.attr('disabled', false);
                    input.removeClass("invalid").addClass("valid");
                } else {

                    /* Translation Ready String Through Script Locaization */
                    error_element.text( application_form.jquery_alerts['invalid_extension'] );
                    error_element.removeClass("sjb-invalid-attachment").addClass("error-show");
                    input.removeClass("valid").addClass("invalid");
                }   
            }

        });
        
        /** 
         * Stop Form Submission -> On Required Attachments
         *  
         * @since 2.3.0          
         */
        function sjb_is_attachment( event ) {
            var error_free = true;
            
            $(".sjb-attachment").each(function() {

                var element = $("#" + $(this).attr("id"));
                var valid = element.hasClass("valid");
                var is_required_class = element.hasClass("sjb-not-required");

                /* Empty File Upload Validation */
                if ( 0 === element.get(0).files.length && !is_required_class  ) {                    
                    var error_element = element.next("span");                
                    error_element.text(application_form.jquery_alerts['empty_attachment']);
                    error_element.removeClass("sjb-invalid-attachment").addClass("error-show");
                    error_free = false;
                }
                
                // Set Error Indicator on Invalid Attachment
                if( !valid ) {
                    if( !( is_required_class && 0 === element.get(0).files.length ) ){
                        error_free = false;
                    }
                }
                
                // Stop Form Submission
                if ( !error_free ) {
                    event.preventDefault();
                }               
            });
            
            return error_free;
        }
        
        /** 
         * Stop Form Submission -> On Invalid Email/Phone
         *  
         * @since 2.2.0          
         */
        function sjb_is_valid_input(event, input_type, input_class) {
            var jobpost_form_inputs = $("." + input_class).serializeArray();
            var error_free = true;

            for (var i in jobpost_form_inputs) {
                var element = $("#" + jobpost_form_inputs[i]['name']);
                var valid = element.hasClass("valid");
                var is_required_class = element.hasClass("sjb-not-required");
                if (!(is_required_class && "" === jobpost_form_inputs[i]['value'])) {
                    if ("email" === input_type) {
                        var error_element = $("span", element.parent());
                    } else if ("phone" === input_type) {
                        var error_element = $("#" + jobpost_form_inputs[i]['name'] + "-invalid-phone");
                    }
                    
                    // Set Error Indicator on Invalid Input
                    if (!valid) {
                        error_element.removeClass("sjb-invalid-" + input_type).addClass("error-show");
                        error_free = false;
                    }
                    else {
                        error_element.removeClass("error-show").addClass("sjb-invalid-" + input_type);
                    }
                    
                    // Stop Form Submission
                    if (!error_free) {
                        event.preventDefault();
                    }
                }
            }
            return error_free;
        }

        /**
         * Remove Search Button -> When no Filter to Show.
         * 
         * @since 2.2.3 
         */
        if (!($('.sjb-search-button')).prevAll().length) {
            $('.sjb-search-button').remove();
        }

        /**
         * Job Filters -> Remove Background Color When no Filter to Show.
         * 
         * @since 2.2.0 
         */
        if (!($('.sjb-job-filters-form')).children().length) {
            $('.sjb-job-filters').removeAttr('id');
        }
        
        /**
         * Remove Required Attribute from Checkbox Group -> When one of the option is selected.
         * Add Required Attribute from Checkboxes Group -> When none of the option is selected.
         * 
         * @since   2.3.0
         */
        var requiredCheckboxes = $(':checkbox[required]');
        requiredCheckboxes.on('change', function () {
            var checkboxGroup = requiredCheckboxes.filter('[name="' + $(this).attr('name') + '"]');
            var isChecked = checkboxGroup.is(':checked');
            checkboxGroup.prop('required', !isChecked);
        });

    });
})(jQuery);