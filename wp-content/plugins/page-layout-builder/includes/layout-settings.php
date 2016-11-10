<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$html = "<div><input class='rsbg form-control' style='width: 70%;float: left;margin-right: 5px' type='text' name='ls[image]' id='rsbg_image' value='{$ls['image']}' /><span class='input-group-button'><button rel='#rsbg_image' class='btn btn-media-upload' type='button'><i class='minimax-icons minimax-image'></i></button></span></div><div style='clear:both'></div>";
$html .= "<div class='input-append' style='margin-top:9px;margin-right:10px;float:left;'><select class='rsbg' style='width:90px' id='rsbg_position_h' name='ls[position_h]'><option value='left'>Left</option><option value='center' ".($ls['position_h']=='center'?'selected=selected':'').">Center</option><option value='right' ".($ls['position_h']=='right'?'selected=selected':'').">Right</option></select></div>";
$html .= "<div class='input-append' style='margin-top:9px;margin-right:10px;float:left;'><select class='rsbg' style='width:90px' id='rsbg_position_v' name='ls[position_v]'><option value='top'>Top</option><option value='center' ".($ls['position_v']=='center'?'selected=selected':'').">Center</option><option value='bottom' ".($ls['position_v']=='bottom'?'selected=selected':'').">Bottom</option></select></div>";
$html .= "<div class='input-append' style='margin-top:9px;margin-right:10px;float:left;'><select class='rsbg' style='width:150px;' id='rsbg_repeat' name='ls[repeat]'><option value='no-repeat'>No Repeat</option><option value='repeat' ".($ls['repeat']=='repeat'?'selected=selected':'').">Repeat</option><option value='repeat-x' ".($ls['repeat']=='repeat-x'?'selected=selected':'').">Repeat Horizontally</option><option value='repeat-y' ".($ls['repeat']=='repeat-y'?'selected=selected':'').">Repeat Vertically</option><option value='stretched' ".($ls['repeat']=='stretched'?'selected=selected':'').">Stretched</option></select></div>";
$html .= "<div class='input-append' style='margin-top:9px;margin-right:10px;float:left;'><select class='rsbg' style='width:90px' id='rsbg_attachment' name='ls[attachment]'><option value='scroll'>Scroll</option><option value='fixed' ".($ls['attachment']=='fixed'?'selected=selected':'').">Fixed</option></select></div>";
$html .= "<div style='clear:both'></div><div id='hfpz' title='Monitor Preview' style='width:48%;height:200px;margin:10px 10px 5px 0;float:left;border: 1px solid #555'></div>";

$bgs = scandir(MX_PLUG_DIR.'/images/bg/');
$html .= "<div id='prebgs' style='margin:10px 0 10px 0px;float:left;width:48%;height:200px;overflow:auto;background:#555;padding:5px;'>";
foreach($bgs as $file){
    if($file!='.'&&$file!='..') {
        $url = base_theme_url.'/images/bg/'.$file;
        $html .="<div data-url='$url' class='prebgl' style='cursor:pointer;background:#fff url($url) center center;height:30px;width:38px;margin:2px;float:left;'></div>";
    }
}
$html .="<div style='clear:both'></div></div><div style='clear:both'></div>";
?>
<style type="text/css">
    .nav-tabs{
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    .tab-content{
        border: 1px solid #dddddd;
        border-top: 0;
        padding: 20px;
        width: 100%;
    }
    .nav-tabs a{
        font-weight: 900;
        color: #aaaaaa;
    }
    .nav-tabs a:focus,
    .nav-tabs a:active{
        outline: none !important;
        box-shadow: none !important;
    }
    .mx-row-box-layout *{
        margin: 50px 50px;
    }
    .mx-spacing-title{
        color: #777;
        font-weight: bold;
        line-height: 20px;
        padding-bottom: 15px;
        text-align: right;
        font-size: 13px;
    }
    .ms-margin{
        border:2px dashed #7a7a7a;
        width: 100%;
        position: relative;
        margin: 1px;
    }
    .ms-border{
        border:2px dotted #00c6d7;
        background: #f3f3f3;
        position: relative;
    }
    .ms-padding{
        border:2px solid #fff;
        background: #e5e5e5;
        position: relative;
    }
    .ms-core{
        border:2px solid #fff;
        background: #00c6d7;
        text-align: center;
        color: #fff;
        height: 70px;;
        position: relative;
        display: flex;
        justify-content: center;
    }
    .ms-core span{
        align-self:center;
        margin: 0;
        font-weight: bold;
        font-size: 13px;
    }
    #layout-settings-form a:hover{text-decoration: none;}
    #layout-settings-form .top_set{
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 12px;
        width: 33px;
        text-align: center;
    }
    #layout-settings-form .bottom_set{
        left: 0;
        right:0;
        margin: 0 auto;
        bottom: 12px;
        width: 33px;
        text-align: center;
    }
    #layout-settings-form .right_set{
        top: calc(50% - 3px);
        right: 10px;
        width: 33px;
        margin-top: -10px;
        text-align: center;
    }
    #layout-settings-form .left_set{
        top: calc(50% - 3px);
        left: 10px;
        width: 33px;
        margin-top: -10px;
        text-align: center;
    }
    .mx-row-box-layout input[type="text"] {
        position: absolute;
        text-align: center;
        height: 24px;
        width: 34px;
        margin: 0px;
        border: 1px solid #BDBDBD;
        font-size: 11px;
        line-height: 11px;
        padding: 3px 0px;
        border-radius: 3px;
        background: #f1f1f1;
    }
    p{
        margin-top: 13px;
        margin-bottom: 13px;
    }
    p,p label{
        font-size: 13px;
    }
    .media-modal{
        z-index: 99999999;
    }
    .wp-picker-holder{
        position: absolute;
        z-index: 99;
    }
    #layout-settings-form .layout-zone{
        position: absolute;left: 3%;margin: 0px;font-weight: bold;color: #777;top: 5px;font-size: 12px;
    }
    #layout-settings-form .nav.nav-tabs{margin: 0;}
</style>

<script type='text/javascript'>
	jQuery(document).ready(function($) {
		$('.myclrpkr').wpColorPicker();
	});
</script>

<form method="post" class="w3eden" action="admin-ajax.php" id="layout-settings-form" rel="<?php echo $_GET['layout_settings_id']; ?>" >
    <ul class="nav nav-tabs">
        <li class="tab active"><a data-toggle="tab" href="#bg">General</a></li>
        <li class="tab"><a data-toggle="tab" href="#sp">Spacing</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="bg">
            <p>
                <label for="ls[row_style]"><?php _e('Row Style'); ?></label>
                <select class="widefat rsbg" name="ls[row_style]">
                    <option value="default" <?php selected('default', $ls['row_style']);?>> Default </option>
                    <option value="fwr" <?php selected('fwr', $ls['row_style']);?>> Full Width Row</option>
                    <option value="fwrc" <?php selected('fwrc', $ls['row_style']);?>> Full Width Row and Contents </option>
                </select>
            </p>
            <p class="settings-info"><i>Use Full Width page template when selecting 'Full Width Row' option. If parent div has overflow hidden property this setting may not work.</i></p>
            <p>
            <label> Background Color</label><br/>
            <input id="rsbg_color" type="text" class="myclrpkr" name="ls[bg_color]" value="<?php echo $ls['bg_color'];?>"  >
            </p>
            <p>
                <label> Background Image</label><br/>
                <?php echo $html; ?>
            </p>
            <div style="clear: both"></div>
            <p>
                <label>Border Color</label> <br/>
                <input type="text" class="myclrpkr" name="ls[border_color]" value="<?php echo $ls['border_color'];?>"  >
            </p>
            <p>
                <label>Row CSS ID</label>
                <input class="widefat" type="text" name="ls[css_id]" value="<?php echo isset($ls['css_id']) ? $ls['css_id'] : "";?>">
            </p>
            <p>
                <label>Row CSS Class</label>
                <input class="widefat" type="text" name="ls[css_class]" value="<?php echo $ls['css_class']; ?>">
                <p class="settings-info"><i>If you want to apply CSS from specific class, write the class name in this field</i></p>
            </p>
        </div>
        <div class="tab-pane" id="sp">
            <div class="mx-spacing-title">Row CSS Layout</div>
            <div class="mx-row-box-layout">
                <div class="ms-margin">
                    <p class="layout-zone">Margin</p>
                    <input class="top_set" type="text" name="ls[margin_top]" value="<?php echo $ls['margin_top']?$ls['margin_top']:"";?>"  >
                    <input class="right_set" type="text" name="ls[margin_right]" value="<?php echo $ls['margin_right']?$ls['margin_right']:"";?>"  >
                    <input class="bottom_set" type="text" name="ls[margin_bottom]" value="<?php echo $ls['margin_bottom']?$ls['margin_bottom']:"";?>"  >
                    <input class="left_set" type="text" name="ls[margin_left]" value="<?php echo $ls['margin_left']?$ls['margin_left']:"";?>"  >

                    <div class="ms-border">
                        <p class="layout-zone">Border</p>
                        <input class="top_set" type="text" name="ls[border_top]" value="<?php echo $ls['border_top']?$ls['border_top']:"";?>"  >
                        <input class="right_set" type="text" name="ls[border_right]" value="<?php echo $ls['border_right']?$ls['border_right']:"";?>"  >
                        <input class="bottom_set" type="text" name="ls[border_bottom]" value="<?php echo $ls['border_bottom']?$ls['border_bottom']:"";?>"  >
                        <input class="left_set" type="text" name="ls[border_left]" value="<?php echo $ls['border_left']?$ls['border_left']:"";?>"  >

                        <div class="ms-padding">
                            <p class="layout-zone">Padding</p>
                            <input class="top_set" type="text" name="ls[padding_top]" value="<?php echo $ls['padding_top']?$ls['padding_top']:"";?>"  >
                            <input class="right_set" type="text" name="ls[padding_right]" value="<?php echo $ls['padding_right']?$ls['padding_right']:"";?>"  >
                            <input class="bottom_set" type="text" name="ls[padding_bottom]" value="<?php echo $ls['padding_bottom']?$ls['padding_bottom']:"";?>"  >
                            <input class="left_set" type="text" name="ls[padding_left]" value="<?php echo $ls['padding_left']?$ls['padding_left']:"";?>"  >

                            <div class="ms-core"><span>MiniMax Row</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <p>
            <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" value="Save Settings" />
            <input type="button" onclick='jQuery("#dialog").dialog("close");jQuery("#dialog").html("Loading...");' class="ui-button ui-widget ui-state-default ui-corner-all" value="Cancel" />
        </p>

</form>
<script>
    jQuery(function(){
        rsbg_preview();
        jQuery('select.rsbg').chosen();

        jQuery('body').on('click','.prebgl', function(){
            jQuery('#rsbg_image').val(jQuery(this).attr('data-url'));
            rsbg_preview();
        });

    });

    jQuery('select.rsbg, #rsbg_opacity, #rsbg_color').change(function(){
        rsbg_preview();
    });

    function rsbg_preview(){
        if(jQuery('#rsbg_opacity').val()>0 && jQuery('#rsbg_color').val()!='')
        var ling = "linear-gradient(to bottom, "+convertHex(jQuery('#rsbg_color').val(), jQuery('#rsbg_opacity').val())+" 0%,"+convertHex(jQuery('#rsbg_color').val(), jQuery('#rsbg_opacity').val())+"100%),";
        else var ling = "";

        jQuery('#hfpz').css('background',ling+" url('"+jQuery('#rsbg_image').val()+"')")
            .css('background-position',jQuery('#rsbg_position_h').val()+" "+jQuery('#rsbg_position_v').val())
            .css('background-repeat',jQuery('#rsbg_repeat').val())
            .css('background-attachment',jQuery('#rsbg_attachment').val())
            .css('background-color',jQuery('#rsbg_color').val());
    }
    
    
    // Slect BG from Media
    var file_frame, dfield;
    jQuery('body').on('click', '.btn-media-upload' , function( event ){
        event.preventDefault();
        dfield = jQuery(jQuery(this).attr('rel'));

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' )
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            dfield.val(attachment.url);

        });

        // Finally, open the modal
        file_frame.open();
    });
    
    function convertHex(hex,opacity){
    var hex1 = hex.replace('#','');
    r = parseInt(hex1.substring(0,2), 16);
    g = parseInt(hex1.substring(2,4), 16);
    b = parseInt(hex1.substring(4,6), 16);

    result = 'rgba('+r+','+g+','+b+','+opacity+')';
    return result;
    }
</script>