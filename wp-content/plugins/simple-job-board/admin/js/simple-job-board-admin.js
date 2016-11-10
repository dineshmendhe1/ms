/* 
 *  Meta Boxes & Settings JS File
 *  v1.0.3
 */

(function ($) {
    'use strict';

    $(function () {

        /* Setting Page -> Tab Menu */
        $('.nav-tab-wrapper a').on("click", function (e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
            $('.sjb-admin-settings').hide();
            $('.nav-tab-active').removeClass('nav-tab-active');
            $($(this).attr('href')).show();
            $(this).addClass('nav-tab-active');
            return false;
        });

        /* Display Settings Tabs Previous State on Form Submit */
        if (window.location.hash.length > 0) {
            $('.sjb-admin-settings').hide();
            $('.nav-tab-active').removeClass('nav-tab-active');
            $(window.location.hash).show();
            $('a[href="' + window.location.hash + '"]').addClass('nav-tab-active');
        }

        var feature_form = $("#job_feature_form");
        var jobapp_form = $("#job_app_form");

        /* Setting Page -> Job Feature Settings */
        $('#settings_addFeature').on("click", function () {
            var field_name_raw = $('#settings_jobfeature_name').val(); // Get Raw value.
            var job_feature_value = $('#settings_jobfeature_value').val(); //Job Feature value
            var fieldName;

            field_name_raw = field_name_raw.trim();    // Remove White Spaces from both ends.
            fieldName = field_name_raw.split(' ').join('_').toLowerCase(); //Replace white space with _.            

            if (fieldName != '') {
                var jobfeature_value_textbox;

                if ('' == job_feature_value) {
                    jobfeature_value_textbox = '<input type="hidden" value="empty" name="jobfeature_' + fieldName + '[value]"/>';
                } else {
                    jobfeature_value_textbox = '<input type="text" value="' + job_feature_value + '" name="jobfeature_' + fieldName + '[value]" >';
                }
                $('#settings_job_features').append('<li class="' + fieldName + '"><label class="sjb-editable-label" for="' + fieldName + '">' + application_form.settings_jquery_alerts['field_name'] + ': ' + field_name_raw + '</label>\n\
                    <input type="hidden" name="jobfeature_' + fieldName + '[label]" value="' + field_name_raw + '"/>\n\
                    ' + jobfeature_value_textbox + ' &nbsp;\n\
                    <div class="button removeField" >' + application_form.settings_jquery_alerts['delete'] + '</div></li>');
                $('#settings_jobfeature_name').val(""); //Reset Field value.
                $('#settings_jobfeature_value').val(""); //Reset Field value.
            } else {

                /* Empty Job Feature Alert -> Making Translation Ready String Through Script Locaization */
                alert(application_form.settings_jquery_alerts['empty_feature_name']);
                $('#settings_jobfeature_name').focus(); //Keep focus on this input
            }
        });

        /* Remove Job App or Job Feature Fields */
        $('.settings-fields').on('click', 'li .removeField', function () {
            $(this).parent('li').remove();     // remove HTML
        });

        /* On Click Save button */
        $('#jobfeature_form').on('click', function () {
            feature_form.submit();
        });

        /* Add Color Picker to all inputs that have 'sjb-color-picker' class */
        $('.sjb-color-picker').wpColorPicker();

        /* Setting Page -> Job Application MetaBox */
        $('#app_add_field').on("click", function () {
            var app_field_raw = $('#setting_jobapp_name').val(); // Get Raw value.
            var app_field_raw = app_field_raw.trim(); // Remove White Spaces from both ends.
            var app_field_name = app_field_raw.split(' ').join('_').toLowerCase(); //Replace white space with _.          
            var app_sanitized_field_name = app_field_raw.replace(/[&\/\\#,+()$~%.^!'"@:=*?|؟<>{}]/g, '').replace(/[_\s]/g, '_').toLowerCase(); //Replace white space with _.
            var app_field_type = $('#settings-jobapp-field-types').val();
            var field_options = $('#settings_jobapp_field_options');
            var fieldOptions = field_options.val();
            var isRequired = $("#settings-jobapp-required-field").attr("checked") ? "checked" : "unchecked";
            var fieldTypeHtml = $('#settings-jobapp-field-types').html();

            if (app_field_name != '') {

                // Show Options for [Checkbox],[Radio] and [Dropdown]
                var application_field_option;
                if (!('checkbox' === app_field_type || 'dropdown' === app_field_type || 'radio' === app_field_type)) {
                    application_field_option = '<input type="text" name="jobapp_' + app_field_name + '[option]" value="' + fieldOptions + '" placeholder="Option1, option2, option3" style="display:none;">';
                } else {
                    application_field_option = '<input type="text" name="jobapp_' + app_field_name + '[option]" value="' + fieldOptions + '" placeholder="Option1, option2, option3">';
                }

                $('#settings_app_form_fields').append('<li class="jobapp_' + app_sanitized_field_name + '">\n\
                    <label for="' + app_field_name + '">' + app_field_raw + '</label>\n\
                    <input type="hidden" name="jobapp_' + app_field_name + '[label]" value="' + app_field_raw + '"/>\n\
                    <select class="settings_jobapp_field_type" name="jobapp_' + app_field_name + '[type]"  >\n\
                    ' + fieldTypeHtml +
                        '</select>\n\
                    ' + application_field_option + ' \n\
                    <input type="checkbox" class="settings-jobapp-required-field"  ' + isRequired + '/>\n\
                    <input type="hidden"   name="jobapp_' + app_field_name + '[optional]"  value="' + isRequired + '"/>' + application_form.settings_jquery_alerts['required'] + '\
                    &nbsp; <div class="button removeField">' + application_form.settings_jquery_alerts['delete'] + '</div></li>');
                $('.jobapp_' + app_sanitized_field_name + ' .' + app_field_type).attr('selected', 'selected');
                $('#setting_jobapp_name').val('');
                field_options.hide();
                field_options.val('');
                $('#settings-jobapp-field-types').val('text');
                $('#settings_jobapp_required_field').prop('checked', true);
            } else {

                /* Empty Form Field Name Alert -> Making Translation Ready String Through Script Locaization */
                alert(application_form.settings_jquery_alerts['empty_field_name']);
                $('#setting_jobapp_name').focus(); //Keep focus on this input
            }
        });

        // Settings Field Types on Change
        $('#settings_app_form_fields').on('change', 'li .settings_jobapp_field_type', function () {
            var fieldType = $(this).val();

            if ('checkbox' == fieldType || 'dropdown' == fieldType || 'radio' == fieldType) {
                $(this).next().show();
            } else {
                $(this).next().hide();
                $(this).next().val('');
            }
        });

        // Field Types on Change
        $('#settings-jobapp-field-types').on('change', function () {
            var fieldType = $(this).val();

            if ('checkbox' == fieldType || 'dropdown' == fieldType || 'radio' == fieldType) {
                $(this).next().show();
            } else {
                $(this).next().hide();
                $(this).next().val('');
            }
        });

        /* Change the Required & Optional Field Parameter*/
        $('#settings_app_form_fields').on("change", '.settings-jobapp-required-field', function () {
            var input = $(this);
            input.attr("checked") ? input.next().val("checked") : input.next().val("unchecked");
        });

        /* Job Application Form Submission */
        $('#jobapp_btn').on('click', function () {
            jobapp_form.submit();
        });

        /* Sortable Fields */
        if ($('#settings_job_features , #settings_app_form_fields , #job_features , #app_form_fields').length) {
            $("#settings_job_features , #settings_app_form_fields , #job_features , #app_form_fields").sortable();
        }

        // Upload logo & show url in textbox
        if ($('.simple-job-board-upload-button').length) {
            window.simple_job_board_uploadfield = '';

            // On upload button click -> Show media upload ifram.
            $('.simple-job-board-upload-button').live('click', function () {
                window.simple_job_board_uploadfield = $('.upload_field', $(this).parents('.file_url'));
                tb_show('Upload', 'media-upload.php?type=image&TB_iframe=true', false);

                return false;
            });

            // Show uploaded logo url in textbox
            window.simple_job_board_send_to_editor_backup = window.send_to_editor;
            window.send_to_editor = function (html) {
                if (window.simple_job_board_uploadfield) {
                    if ($('img', html).length >= 1) {
                        var image_url = $('img', html).attr('src');
                    } else {
                        var image_url = $($(html)[0]).attr('src');
                    }
                    $(window.simple_job_board_uploadfield).val(image_url);
                    window.simple_job_board_uploadfield = '';

                    tb_remove();
                } else {
                    window.simple_job_board_send_to_editor_backup(html);
                }
            }
        }


        /**
         * Meta Boxes JS
         */

        /*Job Application Field Type change*/
        $('#jobapp_field_type').on('change', function (e) {
            var fieldType = $(this).val();

            if (fieldType == 'checkbox' || fieldType == 'dropdown' || fieldType == 'radio') {
                $('#jobapp_field_options').show();
            } else {
                $('#jobapp_field_options').hide();
                $('#jobapp_field_options').val('');
            }
        });

        /*Add Application Field (Group Fields)*/
        $('#addField').on("click", function (e) {
            var fieldNameRaw = $('#jobapp_name').val(); // Get Raw value.
            var fieldNameRaw = fieldNameRaw.trim();    // Remove White Spaces from both ends.
            var fieldName = fieldNameRaw.split(' ').join('_').toLowerCase(); //Replace white space with _.
            var fieldNameOption = fieldNameRaw.replace(/[&\/\\#,+()$~%.^!'"@:=*?|ØŸ<>{}]/g, '').replace(/[_\s]/g, '_').toLowerCase(); //Replace white space with _.
            var fieldType = $('#jobapp_field_type').val();
            var fieldOptions = $('#jobapp_field_options').val();
            var fieldRequired = $("#jobapp_required_field").attr("checked") ? "checked" : "unchecked";
            var fieldTypeHtml = $('#jobapp_field_type').html();

            if (fieldName != '') {
                if (!(fieldType == 'checkbox' || fieldType == 'dropdown' || fieldType == 'radio')) {
                    $('#app_form_fields').append('<li class="' + fieldNameOption + '"><label for="' + fieldName + '">' + fieldNameRaw + '</label><input type="hidden"  name="jobapp_' + fieldName + '[label]" value="' + fieldNameRaw + '"/><select class="jobapp_field_type" name="jobapp_' + fieldName + '[type]">' + fieldTypeHtml + '</select>&nbsp;<input type="text" class="' + fieldName + ' jobapp_field_options" name="jobapp_' + fieldName + '[options]" value="' + fieldOptions + '" placeholder="Option1, option2, option3" style="display:none;" />&nbsp;<input type="checkbox" class="jobapp-required-field"  ' + fieldRequired + '/><input type="hidden" name="jobapp_' + fieldName + '[optional]" value="' + fieldRequired + '"/>' + application_form.settings_jquery_alerts['required'] + ' &nbsp; <div class="button removeField">' + application_form.settings_jquery_alerts['delete'] + '</div></li>');
                    $('.' + fieldNameOption + ' .' + fieldType).attr('selected', 'selected');
                    $('#jobapp_name').val('');
                    $('#jobapp_field_type').val('text');
                    $('#jobapp_required_field').prop("checked", true);
                } else {
                    $('#app_form_fields').append('<li class="' + fieldNameOption + '"><label for="' + fieldName + '">' + fieldNameRaw + '</label><input type="hidden"  name="jobapp_' + fieldName + '[label]" value="' + fieldNameRaw + '"/><select class="jobapp_field_type" name="jobapp_' + fieldName + '[type]">' + fieldTypeHtml + '</select>&nbsp;<input type="text" class="' + fieldName + ' jobapp_field_options" name="jobapp_' + fieldName + '[options]" value="' + fieldOptions + '" /><input type="checkbox" class="jobapp-required-field" ' + fieldRequired + ' /><input type="hidden" name="jobapp_' + fieldName + '[optional]" value="' + fieldRequired + '"/>' + application_form.settings_jquery_alerts['required'] + ' &nbsp; <div class="button removeField">' + application_form.settings_jquery_alerts['delete'] + '</div></li>');
                    $('.' + fieldNameOption + ' .' + fieldType).attr('selected', 'selected');
                    $('#jobapp_name').val('');
                    $('#jobapp_field_type').val('text');
                    $('#jobapp_field_options').val('');
                    $('#jobapp_field_options').hide();
                    $('#jobapp_required_field').prop("checked", true);
                }
            } else {
                alert(application_form.settings_jquery_alerts['empty_field_name']);
                $('#jobapp_name').focus(); //Keep focus on this input
            }

        });

        /* Job Application Field Type change (added) */
        $('#app_form_fields').on('change', 'li .jobapp_field_type', function () {
            var fieldType = $(this).val();

            if (fieldType == 'checkbox' || fieldType == 'dropdown' || fieldType == 'radio') {
                $(this).next().show();
            } else {
                $(this).next().hide();
            }
        });

        /* Change the Required & Optional Field Parameter*/
        $('#app_form_fields').on("change", 'li .jobapp-required-field', function () {
            var input = $(this);
            input.attr("checked") ? input.next().val("checked") : input.next().val("unchecked");
        });

        // Add Job Feature
        $('#addFeature').click(function () {
            var fieldNameRaw = $('#jobfeature_name').val(); // Get Raw value.
            var fieldNameRaw = fieldNameRaw.trim();    // Remove White Spaces from both ends.
            var fieldName = fieldNameRaw.split(' ').join('_').toLowerCase(); //Replace white space with _.
            var fieldVal = $('#jobfeature_value').val();
            var fieldVal = fieldVal.trim();

            if (fieldName != '' && fieldVal != '') {
                $('#job_features').append('<li class="' + fieldName + '"><label class="sjb-editable-label" for="' + fieldName + '">' + fieldNameRaw + '</label><input type="hidden" name="jobfeature_' + fieldName + '[label]" value="' + fieldNameRaw + '"/><input type="text" name="jobfeature_' + fieldName + '[value]" value="' + fieldVal + '" > &nbsp; <div class="button removeField">' + application_form.settings_jquery_alerts['delete'] + '</div></li>');
                $('#jobfeature_name').val(""); // Reset Field value
                $('#jobfeature_value').val(""); // Reset Field value
            } else {
                alert(application_form.settings_jquery_alerts['empty_feature_name']);
                $('#jobfeature_name').focus(); //Keep focus on this input
            }
        });

        // Remove Job app or job Feature Fields
        $('.jobpost_fields').on('click', 'li .removeField', function () {
            $(this).parent('li').remove();
        });

        // Edit Form Builder Labels with class 'sjb-editable-label'
        $(".sjb-editable-label").each(function () {

            // Reference the Label.
            var label = $(this);

            // Add a TextBox next to the Label.
            label.after('<input type = "text" style = "display:none;" />');

            // Reference the TextBox.
            var textbox = $(this).next();

            // Assign the value of Label to TextBox.
            textbox.val(label.html());

            //When Label is clicked, Hide Label & Show TextBox
            label.click(function () {
                label.hide();
                textbox.show();
                textbox.focus();
            });

            // When focus is lost from TextBox, hide TextBox and show Label.
            textbox.focusout(function () {
                $(this).hide();
                $(this).prev().html($(this).val());
                $(this).next().val($(this).val());
                $(this).prev().show();
            });
        });
    });

})(jQuery);