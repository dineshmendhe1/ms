jQuery(function(){
    var obj = mcafeesecure_ajax_object;

    jQuery.post(obj.ajax_url, {action: 'mcafeesecure_get_data'}, function(data){
        console.log("MFES: Get Data");
        console.log(data);

        if(!data['completed_onboarding']){
            jQuery.post(obj.ajax_url, {
                action: 'mcafeesecure_save_data',
                data: { completed_onboarding: 1 }
            }, function(data){
                console.log("MFES: Save Data");
                console.log(data);

                showOnboardingModal();
            });
        }

    });

    function showOnboardingModal(){
        console.log("MFES: Showing onboarding modal");

        var styleCss = "min-width: 210px; margin-left: 25px; border-radius: 0;";
        var buttonStyle = "color: #555;border-color: #ccc;background: #f7f7f7;-webkit-box-shadow: 0 1px 0 #ccc;box-shadow: 0 1px 0 #ccc; vertical-align: top; "
        var $item = jQuery( "div.wp-menu-name:contains('McAfee SECURE')" );

        $item.popover({
            content: "You have installed <b>McAfee SECURE.</b> Please activate your account. <br/><br/><a id='mcafeesecure-activate-now' class='button button-primary' style='text-align: center; padding-top:4px;' href='/wp-admin/admin.php?page=mcafee-secure-settings&activate=1'>Activate Account</a>",
            title: "Congratulations!",
            template: '<div class="popover" role="tooltip" style="' + styleCss + '"><div class="arrow" style="border-top-color:white;"></div><h3 class="popover-title" style="background-color:#aa0828; border-radius: 0; color: #ffffff;"></h3><div class="popover-content" style="color:#222; text-decoration:none; font-size:13px;"></div></div>',
            html: true,
            trigger: 'manual',
            placement: 'top',
            viewport: { selector: 'body' }
        });

        $item.popover('show');
    }
})