<style type="text/css">
    .w3eden .wp-editor-tabs {
        display: none;
    }
    .ui-form a:hover{text-decoration: none;}
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
    .mx-row-box-layout{
        margin-bottom: 20px;
    }
    .mx-spacing-title{
        color: #777;
        font-weight: bold;
        line-height: 20px;
        padding-bottom: 15px;
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
    .mx-row-box-layout .top_set{
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 12px;
        width: 33px;
        text-align: center;
    }
    .mx-row-box-layout .bottom_set{
        left: 0;
        right:0;
        margin: 0 auto;
        bottom: 12px;
        width: 33px;
        text-align: center;
    }
    .mx-row-box-layout .right_set{
        top: calc(50% - 3px);
        right: 10px;
        width: 33px;
        margin-top: -10px;
        text-align: center;
    }
    .mx-row-box-layout .left_set{
        top: calc(50% - 3px);
        left: 10px;
        width: 33px;
        margin-top: -10px;
        text-align: center;
    }
    .mx-row-box-layout input[type="text"] {
        position: absolute;
        text-align: center;
        height: 24px!important;
        width: 34px!important;
        border: 1px solid #BDBDBD;
        font-size: 11px!important;
        line-height: 11px!important;
        padding: 3px 0px!important;
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
    .mx-row-box-layout .layout-zone{
        position: absolute;left: 3%;margin: 0px;font-weight: bold;color: #777;top: 5px;font-size: 12px;
    }
    #update-module-settings-form .nav.nav-tabs,#module-settings-form .nav.nav-tabs{margin: 0;}
    #update-module-settings-form .tab-content,#module-settings-form .tab-content{margin-bottom: 20px;}
</style>

<script type='text/javascript'>
    jQuery(document).ready(function($) {
        jQuery('.myclrpkr').wpColorPicker();
    });
</script>

<div class="mx-spacing-title">Module CSS Layout</div>
<div class="mx-row-box-layout">
    <div class="ms-margin">
        <p class="layout-zone">Margin</p>
        <input class="top_set" type="text" name="ms[margin_top]" value="<?php echo $ms['margin_top'] ? $ms['margin_top'] : ""; ?>">
        <input class="right_set" type="text" name="ms[margin_right]" value="<?php echo $ms['margin_right'] ? $ms['margin_right'] : ""; ?>">
        <input class="bottom_set" type="text" name="ms[margin_bottom]" value="<?php echo $ms['margin_bottom'] ? $ms['margin_bottom'] : ""; ?>">
        <input class="left_set" type="text" name="ms[margin_left]" value="<?php echo $ms['margin_left'] ? $ms['margin_left'] : ""; ?>">

        <div class="ms-border">
            <p class="layout-zone">Border</p>
            <input class="top_set" type="text" name="ms[border_top]" value="<?php echo $ms['border_top'] ? $ms['border_top'] : ""; ?>">
            <input class="right_set" type="text" name="ms[border_right]" value="<?php echo $ms['border_right'] ? $ms['border_right'] : ""; ?>">
            <input class="bottom_set" type="text" name="ms[border_bottom]" value="<?php echo $ms['border_bottom'] ? $ms['border_bottom'] : ""; ?>">
            <input class="left_set" type="text" name="ms[border_left]" value="<?php echo $ms['border_left'] ? $ms['border_left'] : ""; ?>">

            <div class="ms-padding">
                <p class="layout-zone">Padding</p>
                <input class="top_set" type="text" name="ms[padding_top]" value="<?php echo $ms['padding_top'] ? $ms['padding_top'] : ""; ?>">
                <input class="right_set" type="text" name="ms[padding_right]" value="<?php echo $ms['padding_right'] ? $ms['padding_right'] : ""; ?>">
                <input class="bottom_set" type="text" name="ms[padding_bottom]" value="<?php echo $ms['padding_bottom'] ? $ms['padding_bottom'] : ""; ?>">
                <input class="left_set" type="text" name="ms[padding_left]" value="<?php echo $ms['padding_left'] ? $ms['padding_left'] : ""; ?>">

                <div class="ms-core"><span>MiniMax Module</span></div>
            </div>
        </div>
    </div>
</div>
<div style='clear:both'></div>
<p>
    <label>CSS Animation</label>
    <select class="widefat rsbg" name="ms[animation]">
        <optgroup label="Animation Disabled">
            <option value="" <?php if ($ms['animation'] == '') echo 'selected=selected'; ?>>No Animation</option>
        </optgroup>

        <optgroup label="Attention Seekers">
            <option value="bounce" <?php if ($ms['animation'] == 'bounce') echo 'selected=selected'; ?>>bounce</option>
            <option value="flash" <?php if ($ms['animation'] == 'flash') echo 'selected=selected'; ?>>flash</option>
            <option value="pulse" <?php if ($ms['animation'] == 'pulse') echo 'selected=selected'; ?>>pulse</option>
            <option value="rubberBand" <?php if ($ms['animation'] == 'rubberBand') echo 'selected=selected'; ?>>
                rubberBand
            </option>
            <option value="shake" <?php if ($ms['animation'] == 'shake') echo 'selected=selected'; ?>>shake</option>
            <option value="swing" <?php if ($ms['animation'] == 'swing') echo 'selected=selected'; ?>>swing</option>
            <option value="tada" <?php if ($ms['animation'] == 'tada') echo 'selected=selected'; ?>>tada</option>
            <option value="wobble" <?php if ($ms['animation'] == 'wobble') echo 'selected=selected'; ?>>wobble</option>
        </optgroup>

        <optgroup label="Bouncing Entrances">
            <option value="bounceIn" <?php if ($ms['animation'] == 'bounceIn') echo 'selected=selected'; ?>>bounceIn
            </option>
            <option value="bounceInDown" <?php if ($ms['animation'] == 'bounceInDown') echo 'selected=selected'; ?>>
                bounceInDown
            </option>
            <option value="bounceInLeft" <?php if ($ms['animation'] == 'bounceInLeft') echo 'selected=selected'; ?>>
                bounceInLeft
            </option>
            <option value="bounceInRight" <?php if ($ms['animation'] == 'bounceInRight') echo 'selected=selected'; ?>>
                bounceInRight
            </option>
            <option value="bounceInUp" <?php if ($ms['animation'] == 'bounceInUp') echo 'selected=selected'; ?>>
                bounceInUp
            </option>
        </optgroup>

        <optgroup label="Bouncing Exits">
            <option value="bounceOut" <?php if ($ms['animation'] == 'bounceOut') echo 'selected=selected'; ?>>
                bounceOut
            </option>
            <option value="bounceOutDown" <?php if ($ms['animation'] == 'bounceOutDown') echo 'selected=selected'; ?>>
                bounceOutDown
            </option>
            <option value="bounceOutLeft" <?php if ($ms['animation'] == 'bounceOutLeft') echo 'selected=selected'; ?>>
                bounceOutLeft
            </option>
            <option value="bounceOutRight" <?php if ($ms['animation'] == 'bounceOutRight') echo 'selected=selected'; ?>>
                bounceOutRight
            </option>
            <option value="bounceOutUp" <?php if ($ms['animation'] == 'bounceOutUp') echo 'selected=selected'; ?>>
                bounceOutUp
            </option>
        </optgroup>

        <optgroup label="Fading Entrances">
            <option value="fadeIn" <?php if ($ms['animation'] == 'fadeIn') echo 'selected=selected'; ?>>fadeIn</option>
            <option value="fadeInDown" <?php if ($ms['animation'] == 'fadeInDown') echo 'selected=selected'; ?>>
                fadeInDown
            </option>
            <option value="fadeInDownBig" <?php if ($ms['animation'] == 'fadeInDownBig') echo 'selected=selected'; ?>>
                fadeInDownBig
            </option>
            <option value="fadeInLeft" <?php if ($ms['animation'] == 'fadeInLeft') echo 'selected=selected'; ?>>
                fadeInLeft
            </option>
            <option value="fadeInLeftBig" <?php if ($ms['animation'] == 'fadeInLeftBig') echo 'selected=selected'; ?>>
                fadeInLeftBig
            </option>
            <option value="fadeInRight" <?php if ($ms['animation'] == 'fadeInRight') echo 'selected=selected'; ?>>
                fadeInRight
            </option>
            <option value="fadeInRightBig" <?php if ($ms['animation'] == 'fadeInRightBig') echo 'selected=selected'; ?>>
                fadeInRightBig
            </option>
            <option value="fadeInUp" <?php if ($ms['animation'] == 'fadeInUp') echo 'selected=selected'; ?>>fadeInUp
            </option>
            <option value="fadeInUpBig" <?php if ($ms['animation'] == 'fadeInUpBig') echo 'selected=selected'; ?>>
                fadeInUpBig
            </option>
        </optgroup>

        <optgroup label="Fading Exits">
            <option value="fadeOut" <?php if ($ms['animation'] == 'fadeOut') echo 'selected=selected'; ?>>fadeOut
            </option>
            <option value="fadeOutDown" <?php if ($ms['animation'] == 'fadeOutDown') echo 'selected=selected'; ?>>
                fadeOutDown
            </option>
            <option value="fadeOutDownBig" <?php if ($ms['animation'] == 'fadeOutDownBig') echo 'selected=selected'; ?>>
                fadeOutDownBig
            </option>
            <option value="fadeOutLeft" <?php if ($ms['animation'] == 'fadeOutLeft') echo 'selected=selected'; ?>>
                fadeOutLeft
            </option>
            <option value="fadeOutLeftBig" <?php if ($ms['animation'] == 'fadeOutLeftBig') echo 'selected=selected'; ?>>
                fadeOutLeftBig
            </option>
            <option value="fadeOutRight" <?php if ($ms['animation'] == 'fadeOutRight') echo 'selected=selected'; ?>>
                fadeOutRight
            </option>
            <option
                value="fadeOutRightBig" <?php if ($ms['animation'] == 'fadeOutRightBig') echo 'selected=selected'; ?>>
                fadeOutRightBig
            </option>
            <option value="fadeOutUp" <?php if ($ms['animation'] == 'fadeOutUp') echo 'selected=selected'; ?>>
                fadeOutUp
            </option>
            <option value="fadeOutUpBig" <?php if ($ms['animation'] == 'fadeOutUpBig') echo 'selected=selected'; ?>>
                fadeOutUpBig
            </option>
        </optgroup>

        <optgroup label="Flippers">
            <option value="flip" <?php if ($ms['animation'] == 'flip') echo 'selected=selected'; ?>>flip</option>
            <option value="flipInX" <?php if ($ms['animation'] == 'flipInX') echo 'selected=selected'; ?>>flipInX
            </option>
            <option value="flipInY" <?php if ($ms['animation'] == 'flipInY') echo 'selected=selected'; ?>>flipInY
            </option>
            <option value="flipOutX" <?php if ($ms['animation'] == 'flipOutX') echo 'selected=selected'; ?>>flipOutX
            </option>
            <option value="flipOutY" <?php if ($ms['animation'] == 'flipOutY') echo 'selected=selected'; ?>>flipOutY
            </option>
        </optgroup>

        <optgroup label="Lightspeed">
            <option value="lightSpeedIn" <?php if ($ms['animation'] == 'lightSpeedIn') echo 'selected=selected'; ?>>
                lightSpeedIn
            </option>
            <option value="lightSpeedOut" <?php if ($ms['animation'] == 'lightSpeedOut') echo 'selected=selected'; ?>>
                lightSpeedOut
            </option>
        </optgroup>

        <optgroup label="Rotating Entrances">
            <option value="rotateIn" <?php if ($ms['animation'] == 'rotateIn') echo 'selected=selected'; ?>>rotateIn
            </option>
            <option
                value="rotateInDownLeft" <?php if ($ms['animation'] == 'rotateInDownLeft') echo 'selected=selected'; ?>>
                rotateInDownLeft
            </option>
            <option
                value="rotateInDownRight" <?php if ($ms['animation'] == 'rotateInDownRight') echo 'selected=selected'; ?>>
                rotateInDownRight
            </option>
            <option value="rotateInUpLeft" <?php if ($ms['animation'] == 'rotateInUpLeft') echo 'selected=selected'; ?>>
                rotateInUpLeft
            </option>
            <option
                value="rotateInUpRight" <?php if ($ms['animation'] == 'rotateInUpRight') echo 'selected=selected'; ?>>
                rotateInUpRight
            </option>
        </optgroup>

        <optgroup label="Rotating Exits">
            <option value="rotateOut" <?php if ($ms['animation'] == 'rotateOut') echo 'selected=selected'; ?>>
                rotateOut
            </option>
            <option
                value="rotateOutDownLeft" <?php if ($ms['animation'] == 'rotateOutDownLeft') echo 'selected=selected'; ?>>
                rotateOutDownLeft
            </option>
            <option
                value="rotateOutDownRight" <?php if ($ms['animation'] == 'rotateOutDownRight') echo 'selected=selected'; ?>>
                rotateOutDownRight
            </option>
            <option
                value="rotateOutUpLeft" <?php if ($ms['animation'] == 'rotateOutUpLeft') echo 'selected=selected'; ?>>
                rotateOutUpLeft
            </option>
            <option
                value="rotateOutUpRight" <?php if ($ms['animation'] == 'rotateOutUpRight') echo 'selected=selected'; ?>>
                rotateOutUpRight
            </option>
        </optgroup>

        <optgroup label="Specials">
            <option value="hinge" <?php if ($ms['animation'] == 'hinge') echo 'selected=selected'; ?>>hinge</option>
            <option value="rollIn" <?php if ($ms['animation'] == 'rollIn') echo 'selected=selected'; ?>>rollIn</option>
            <option value="rollOut" <?php if ($ms['animation'] == 'rollOut') echo 'selected=selected'; ?>>rollOut
            </option>
        </optgroup>

        <optgroup label="Zoom Entrances">
            <option value="zoomIn" <?php if ($ms['animation'] == 'zoomIn') echo 'selected=selected'; ?>>zoomIn</option>
            <option value="zoomInDown" <?php if ($ms['animation'] == 'zoomInDown') echo 'selected=selected'; ?>>
                zoomInDown
            </option>
            <option value="zoomInLeft" <?php if ($ms['animation'] == 'zoomInLeft') echo 'selected=selected'; ?>>
                zoomInLeft
            </option>
            <option value="zoomInRight" <?php if ($ms['animation'] == 'zoomInRight') echo 'selected=selected'; ?>>
                zoomInRight
            </option>
            <option value="zoomInUp" <?php if ($ms['animation'] == 'zoomInUp') echo 'selected=selected'; ?>>zoomInUp
            </option>
        </optgroup>

        <optgroup label="Zoom Exits">
            <option value="zoomOut" <?php if ($ms['animation'] == 'zoomOut') echo 'selected=selected'; ?>>zoomOut
            </option>
            <option value="zoomOutDown" <?php if ($ms['animation'] == 'zoomOutDown') echo 'selected=selected'; ?>>
                zoomOutDown
            </option>
            <option value="zoomOutLeft" <?php if ($ms['animation'] == 'zoomOutLeft') echo 'selected=selected'; ?>>
                zoomOutLeft
            </option>
            <option value="zoomOutRight" <?php if ($ms['animation'] == 'zoomOutRight') echo 'selected=selected'; ?>>
                zoomOutRight
            </option>
            <option value="zoomOutUp" <?php if ($ms['animation'] == 'zoomOutUp') echo 'selected=selected'; ?>>
                zoomOutUp
            </option>
        </optgroup>
    </select>
</p>
<p>
    <label> Background</label><br/>
    <input type="text" class="myclrpkr" name="ms[bg_color]" value="<?php echo $ms['bg_color'];?>"  >
</p>
<p>
    <label> Text Color</label> <br/>
    <input type="text" class="myclrpkr" name="ms[tx_color]" value="<?php echo $ms['tx_color'];?>"  >
</p>
<p>
    <label> Border Color</label> <br/>
    <input type="text" class="myclrpkr" name="ms[border_color]" value="<?php echo $ms['border_color'];?>"  >
</p>

<p>
    <label>Module CSS Class</label>
    <input class="widefat" type="text" name="ms[css_class]" value="<?php echo $ms['css_class']; ?>">
    ( If you want to apply CSS from specific class, write the class name in this field )
</p>

<script>
    jQuery(function(){
        jQuery('select.rsbg').chosen({ width: '100%' });
        jQuery('select.widefat').chosen();
    });
</script>