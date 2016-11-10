<?php

//MiniMax Metaboxes
function minimax_layout_builder_meta_box() {
    add_meta_box( get_post_type().'-minimax-layout-builder', 'MiniMax Layout Builder', 'minimax_content_layout_builder', get_post_type(), 'normal', 'high' );
    add_meta_box( get_post_type().'-minimax-squeeze-page', 'MiniMax Blank Canvas', 'minimax_squeeze_page', 'page', 'side','core' );
}

// MiniMax Squeeze Page Options callback
function minimax_squeeze_page($post){
    ?>
    <input type="checkbox" value="1" id="bco" name="squeeze_page" <?php echo get_post_meta(isset($_GET['post'])?$_GET['post']:'','squeeze_page',true)=='1'?'checked=checked':''; ?> >
    <strong><?php _e('Use Blank Canvas Template','minimax'); ?></strong>
    <p><?php _e('Check this option if you want to build a page from ground on a blank canvas (will skip current theme header, footer, sidebars)','minimax'); ?></p>

    <div id="bc" <?php echo get_post_meta(isset($_GET['post']) ? $_GET['post'] : '', 'squeeze_page', true) != '1' ? 'style="display:none;"' : ''; ?> >

        <p><strong><?php _e('Select Squeeze Page Theme','minimax'); ?></strong></p>
        <select name="sptemplate">
            <?php
            $allthemes = scandir(MX_PLUG_DIR . '/canvas/');
            foreach ($allthemes as $theme) {
                if ($theme == '.' || $theme == '..') continue;
                $themeinfo = wp_get_theme($theme, MX_PLUG_DIR . '/canvas/');
                echo "<option " . (get_post_meta($_GET['post'], 'sptemplate', true) == $theme ? 'selected=selected' : '') . " value='{$theme}'>{$themeinfo->Name}</option>";
            } ?>
        </select>

        <p><strong><?php _e('Body Background Color','minimax'); ?></strong></p>
        <input size="28" type="text" name="bodybgcolor" class="miniColors" value="<?php echo get_post_meta(isset($_GET['post']) ? $_GET['post'] : '', 'bodybgcolor', true); ?>"/>

        <p><strong><?php _e('Body Background Image','minimax'); ?></strong></p>
        <input size="28" type="text" id="bodybgimage" name="bodybgimage" value="<?php echo get_post_meta(isset($_GET['post']) ? $_GET['post'] : '', 'bodybgimage', true); ?>"/><br/><br/>

        <input type="button" class="button button-primary button-large" value="<?php _e('Browse','minimax'); ?>" onclick="mediaupload('bodybgimage')"/>
    </div>

    <script>
      jQuery('#bco').click(function(){
          if(this.checked) jQuery('#bc').slideDown();
          else jQuery('#bc').slideUp();
      });
    </script>
    <?php
}

//MiniMax Layout Metabox callback
function minimax_content_layout_builder( $post ) {
    ?>
    <style type="text/css">
        #<?php echo get_post_type(); ?>-minimax-layout-builder {display: none;}
        #<?php echo get_post_type(); ?>-minimax-layout-builder h3{line-height: 30px !important;height:30px !important;font-size:14pt !important;}
        #TB_ajaxContent{width: 95% !important;height: 90% !important;overflow: auto;}
        #widgets li{width: 183px !important;}
        .layout-data li{margin-bottom: 5px;}
        .layout-data > li .row-container{width: 95%;}
    </style>
    <link href="<?php echo plugins_url('/page-layout-builder/css/tipTip.css');?>" rel="stylesheet"/>
    <script src="<?php echo plugins_url('/page-layout-builder/js/jquery.tipTip.minified.js');?>"></script>
    
    <div id="alll">
        <div id="layout_<?php echo get_post_type(); ?>">
            <?php
            global $minimax_layout_settings;
            if(isset($_GET['post']))
                $minimax_layout_settings = get_post_meta($_GET['post'],'minimax_layout_settings',true);
            else
                $minimax_layout_settings = array();
            ?>
            <ul class="layout-data">
                <?php minimax_render_layout_frames(get_post_type()); ?>
            </ul>
        </div>
    </div>
    <div style="clear: both;"></div>
    
    <div class="clear"></div> 
    <div id="dialog" title="MiniMax"><p>Loading...</p></div>
    <div id="childdialog" title="MiniMax"><p>Loading...</p></div>

    <div class="w3eden">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <div id="modalcontentarea"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
     
    jQuery(function() {
        jQuery( "#dialog" ).dialog({ zIndex: 100, position: 'top' , closeOnEscape: true, autoOpen: false,modal:true,width:'640px' });
        jQuery( ".dialog" ).dialog({ zIndex: 100, position: 'top' , closeOnEscape: true, autoOpen: false,modal:true,width:'640px' });
        jQuery( "#childdialog" ).dialog({ zIndex: 101, position: 'top' , closeOnEscape: true, autoOpen: false,width:'640px' });
        jQuery( '.ui-dialog' ).css('margin-top','28px');
        jQuery( ".tooltip,.mx-tooltip, .stooltip" ).tipTip({defaultPosition:'top'});
        jQuery( ".row-handler .dtooltip" ).tipTip({defaultPosition:'right'});
        jQuery( ".ctl .dtooltip" ).tipTip({defaultPosition:'top'});
    });
    </script>
 
    <?php
}

//Metabox action
add_action( 'add_meta_boxes', 'minimax_layout_builder_meta_box');
