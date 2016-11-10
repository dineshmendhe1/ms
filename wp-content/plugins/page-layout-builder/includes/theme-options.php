<div id="minimax-settings">
    <h1><i class="mimx dashicons-before dashicons-layout"></i> MiniMax Page Layout Builder</h1>

<style>
    #minimax-settings{ font-family: 'Open Sans', serif; font-size: 10pt; color: #555555; }
    #minimax-settings ul.nav-tabs{ padding-left: 35px; margin-left:-20px; background: #f5f5f5; }
    #minimax-settings .nav-tabs li a{ font-weight: bold; border-radius: 0; border: 1px solid #ddd; }
    #minimax-settings .nav-tabs li.active a{ border-bottom: 1px solid transparent; }
    #minimax-settings .nav-tabs li a:focus, #minimax-settings .nav-tabs li a:active{ box-shadow: none; }
    #minimax-settings .mimx.dashicons-before:before{ font-size: 23px; line-height: 29px; }
    #minimax-settings h1{ font-size: 23px; font-weight: 400; line-height: 29px; margin-left: -20px; padding: 20px 15px 20px 35px; background: #f5f5f5; }
    #minimax-settings .panel{ border-radius: 0; }
    #minimax-settings #modpreview .btn, #minimax-settings #cache .btn, #minimax-settings #mxbootstrapcss .btn,#minimax-settings #mxbootstrapjs .btn{ border-radius: 0!important; opacity: 1 !important; width: 80px !important; }
    #minimax-settings .panel-heading .mod_status{ width:70px;font-size:9pt;font-weight:300;border-radius:2px; padding:5px; margin-top: -1px; }
    #minimax-settings .panel-heading{ font-size: 11pt; font-weight: 600; color: #666; line-height: normal; }
    #minimax-settings .icon-ok{ color: #008800; }
    #minimax-settings .icon-remove{ color: #ff0000; }
    #minimax-settings a{ outline: none !important; }
    #minimax-settings .panel-footer .btn{ width: 90px; font-size: 11px; padding: 1px 5px!important;}
    #minimax-settings .alert{font-size: 13px;line-height: 15px; border-radius: 0;padding: 10px; display: inline-block;}
    #minimax-settings .mxaddon{ max-width: 251px; padding: 10px; border-radius:0; margin-right: 30px; margin-left: 15px; border: 1px solid #ddd; display: inline-block; }
    #minimax-settings .alert{font-weight: bold;}
    #minimax-settings #settings label{width: 200px;}
    #minimax-settings #settings .col-md-12{margin-bottom: 20px;}
    #minimax-settings .minimax-icons.minimax-loader{color: #008800;font-size: 10px;}
</style>

<div class="w3eden">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#available" data-toggle="tab">Available Modules</a></li>
        <li><a href="#settings" data-toggle="tab">Settings</a></li>
        <li><a href="#store" data-toggle="tab">Widget Store</a></li>
    </ul>
    <div class="tab-content"><br/>
        <div class="container-fluid tab-pane fade in active" id="available">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" style="">
                        <a title="Link will open in new window" href="http://wordpress.org/support/view/plugin-reviews/page-layout-builder?rate=5#postform" target="_blank">If you like MiniMax please leave me a 5* review. It will be very inspiring :) Thanks in advance</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                $module_dir = MX_THEME_DIR . '/modules/';
                $modules = scandir($module_dir);
                $active_modules = get_option("minimax_allowed_modules");

                foreach ($modules as $module) {
                    $moduledata = array();

                    if ($module != "." && $module != "..") {
                        if (is_dir($module_dir . $module)) {
                            $moduledata = get_plugin_data($module_dir . $module . "/" . $module . ".php");
                            if($moduledata['Version']=='pro_only')
                                $um[] = $module;
                            else
                                $am[] = $module;
                        }}
                }
                $modules = array_merge($am, $um);

                foreach ($modules as $module) {
                    $moduledata = array();

                    if ($module != "." && $module != "..") {
                        if (is_dir($module_dir . $module)) {
                            $moduledata = get_plugin_data($module_dir . $module . "/" . $module . ".php");

                            if ($active_modules[$module]) {
                                $mod_status = "power_on";
                                $mod_status_appear = "Active";
                                $mod_status_cls = "success";
                                $mod_panel_cls = "default";
                                $icon = 'Deactivate';
                            } else {
                                $mod_status = "power_off";
                                $mod_status_appear = "Inactive";
                                $mod_status_cls = "danger";
                                $mod_panel_cls = "default";
                                $icon = 'Activate';
                            }
                            echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"><div class="panel panel-' . $mod_panel_cls . ' '. str_replace(".","_",$moduledata['Version']). '">'
                                . '<div class="panel-heading">' . $moduledata['Name'] . ' <small id="st_' . $module . '" class="label label-' . $mod_status_cls . ' mod_status mod_status_' . $mod_status_appear . '">' . $mod_status_appear . '</small></div>'
                                . '<div class="panel-body">' . substr($moduledata['Description'], 0, strpos($moduledata['Description'], "By")) . '</b></div>'
                                . '<div class = "panel-footer">';
                            if($moduledata['Version']=='pro_only'){
                                echo "<button disabled='disabled' class='btn btn-danger btn-xs' style='width: 150px'>Available With Pro Only</button> <a  href='http://wpeden.com/minimax-wordpress-page-layout-builder-plugin/' target='_blank' class='btn btn-primary btn-xs pull-right'>Get Pro <i class='icon icon-chevron-sign-right'></i></a> ";
                            } else {
                                echo '<button style="width:80px" status="' . $mod_status . '" type="button" class="mod_name btn btn-xs btn-default text-primary" rel="' . $module . '">' . $icon . '</button>';
                            }
                                echo '</div>'
                                . '</div>'
                                . '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div class="container-fluid  tab-pane fade" id="settings">
            <div class="row">
                <div class="col-md-12">
                    <label>&nbsp; Module Caching</label>
                    <div class="btn-group" id="cache">
                        <button class="btn btn-<?php echo get_option('minimax_cache_status',0)==1?'success':'default'; ?>" <?php echo get_option('minimax_cache_status',0)==1?'disabled=disabled':''; ?> data-cache="1" id="icahce_1">Active</button>
                        <button class="btn btn-<?php echo get_option('minimax_cache_status',0)==0?'danger':'default'; ?>" <?php echo get_option('minimax_cache_status',0)==0?'disabled=disabled':''; ?> data-cache="0" id="icache_0">Inactive</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>&nbsp; Module Preview</label>
                    <div class="btn-group" id="modpreview">
                        <button class="btn btn-<?php echo get_option('minimax_module_preview',0)==1?'success':'default'; ?>" <?php echo get_option('minimax_module_preview',0)==1?'disabled=disabled':''; ?> data-mpv="1" id="modpreview_1">Active</button>
                        <button class="btn btn-<?php echo get_option('minimax_module_preview',0)==0?'danger':'default'; ?>" <?php echo get_option('minimax_module_preview',0)==0?'disabled=disabled':''; ?> data-mpv="0" id="modpreview_0">Inactive</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>&nbsp; MiniMax Bootstrap CSS</label>
                    <div class="btn-group" id="mxbootstrapcss">
                        <button class="btn btn-<?php echo get_option('minimax_bootstrap_css',1)==1?'success':'default'; ?>" <?php echo get_option('minimax_bootstrap_css',1)==1?'disabled=disabled':''; ?> data-mbs="1" id="mxbootstrap_css_1">Active</button>
                        <button class="btn btn-<?php echo get_option('minimax_bootstrap_css',1)==0?'danger':'default'; ?>" <?php echo get_option('minimax_bootstrap_css',1)==0?'disabled=disabled':''; ?> data-mbs="0" id="mxbootstrap_css_0">Inactive</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>&nbsp; MiniMax Bootstrap JS</label>
                    <div class="btn-group" id="mxbootstrapjs">
                        <button class="btn btn-<?php echo get_option('minimax_bootstrap_js',1)==1?'success':'default'; ?>" <?php echo get_option('minimax_bootstrap_js',1)==1?'disabled=disabled':''; ?> data-mbs="1" id="mxbootstrap_js_1">Active</button>
                        <button class="btn btn-<?php echo get_option('minimax_bootstrap_js',1)==0?'danger':'default'; ?>" <?php echo get_option('minimax_bootstrap_js',1)==0?'disabled=disabled':''; ?> data-mbs="0" id="mxbootstrap_js_0">Inactive</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid  tab-pane fade" id="store">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        Extend your MiniMax by adding following premium modules bundle. Each of these bundle packed with some awesome widgets. Check details in product page. 
                    </div>
                </div>
            </div>
            <div class="row">

                    <div class="mxaddon">
                        <a target="blank" href="http://wpeden.com/product/minimax-modules-pack-1/">
                            <img style="height:auto; max-width: 100%;"class="" alt="MiniMax Modules Pack 1" src="http://cdn.wpeden.com/wp-content/uploads/2014/06/minimax-modules-pack-1-250x250.png">
                        </a>
                        <div class="addon-info" style="background:#F8F8F8;margin-top: 20px;padding: 10px;">
                            <a target="blank" href="http://wpeden.com/product/minimax-modules-pack-1/">
                                <b>MiniMax Modules Pack 1</b>
                            </a>
                        </div>
                    </div>

                    <div class="mxaddon">
                        <a target="blank" href="http://wpeden.com/product/minimax-modules-pack-2/">
                            <img style="height:auto; max-width: 100%;"class="" alt="MiniMax Modules Pack 2" src="http://cdn.wpeden.com/wp-content/uploads/2015/04/minimax-modules-pack-2-250x250.png">
                        </a>
                        <div class="addon-info" style="background:#F4F4F4;margin-top: 20px;padding: 10px;">
                            <a target="blank" style="text-decoration: none;" href="http://wpeden.com/product/minimax-modules-pack-2/">
                                <b>MiniMax Modules Pack 2</b>
                            </a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    jQuery('.dropdown-toggle').dropdown();
    jQuery('#cache .btn').on('click', function(){
        var cache = jQuery(this).attr('data-cache');
        var abc = jQuery(this);
        abc.html('<img style="width:15px;height:15px;" src="<?php echo plugins_url('/page-layout-builder/images/loading_n.gif'); ?>">').attr('disabled','disabled');
        if(cache==1){
            jQuery.post(ajaxurl, {action:'minimax_cache',cache_status:1}, function(){
                jQuery('#cache .btn-danger').addClass('btn-default').removeClass('btn-danger').removeAttr('disabled');
                abc.addClass('btn-success').removeClass('btn-default').html('Active');
            });
        }
        if(cache==0){
            jQuery.post(ajaxurl, {action:'minimax_cache',cache_status:0}, function(){
                jQuery('#cache .btn-success').addClass('btn-default').removeClass('btn-success').removeAttr('disabled');
                abc.addClass('btn-danger').removeClass('btn-default').html('Inactive');
            });

        }
    });

    jQuery('#modpreview .btn').on('click', function(){
        var cache = jQuery(this).attr('data-mpv');
        var abc = jQuery(this);
        abc.html('<img style="width:15px;height:15px;" src="<?php echo plugins_url('/page-layout-builder/images/loading_n.gif'); ?>">').attr('disabled','disabled');
        if(cache==1){
            jQuery.post(ajaxurl, {action:'minimax_module_preview',module_preview:1}, function(){
                jQuery('#modpreview .btn-danger').addClass('btn-default').removeClass('btn-danger').removeAttr('disabled');
                abc.addClass('btn-success').removeClass('btn-default').html('Active');
            });
        }
        if(cache==0){
            jQuery.post(ajaxurl, {action:'minimax_module_preview',module_preview:0}, function(){
                jQuery('#modpreview .btn-success').addClass('btn-default').removeClass('btn-success').removeAttr('disabled');
                abc.addClass('btn-danger').removeClass('btn-default').html('Inactive');
            });
        }
    });

    jQuery('#mxbootstrapcss .btn').on('click', function(){
        var mbs_status = jQuery(this).attr('data-mbs');
        var abc = jQuery(this);
        abc.html('<img style="width:15px;height:15px;" src="<?php echo plugins_url('/page-layout-builder/images/loading_n.gif'); ?>">').attr('disabled','disabled');
        if(mbs_status == 1){
            jQuery.post(ajaxurl, {action:'minimax_bootstrap_css',bootstrap_status:1}, function(){
                jQuery('#mxbootstrapcss .btn-danger').addClass('btn-default').removeClass('btn-danger').removeAttr('disabled');
                abc.addClass('btn-success').removeClass('btn-default').html('Active');
            });
        }
        if(mbs_status == 0){
            jQuery.post(ajaxurl, {action:'minimax_bootstrap_css',bootstrap_status:0}, function(){
                jQuery('#mxbootstrapcss .btn-success').addClass('btn-default').removeClass('btn-success').removeAttr('disabled');
                abc.addClass('btn-danger').removeClass('btn-default').html('Inactive');
            });
        }
    });

    jQuery('#mxbootstrapjs .btn').on('click', function(){
        var mbs_status = jQuery(this).attr('data-mbs');
        var abc = jQuery(this);
        abc.html('<img style="width:15px;height:15px;" src="<?php echo plugins_url('/page-layout-builder/images/loading_n.gif'); ?>">').attr('disabled','disabled');
        if(mbs_status == 1){
            jQuery.post(ajaxurl, {action:'minimax_bootstrap_js',bootstrap_status:1}, function(){
                jQuery('#mxbootstrapjs .btn-danger').addClass('btn-default').removeClass('btn-danger').removeAttr('disabled');
                abc.addClass('btn-success').removeClass('btn-default').html('Active');
            });
        }
        if(mbs_status == 0){
            jQuery.post(ajaxurl, {action:'minimax_bootstrap_js',bootstrap_status:0}, function(){
                jQuery('#mxbootstrapjs .btn-success').addClass('btn-default').removeClass('btn-success').removeAttr('disabled');
                abc.addClass('btn-danger').removeClass('btn-default').html('Inactive');
            });
        }
    });

</script>
