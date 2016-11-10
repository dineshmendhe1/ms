<?php
/*
Plugin Name: Image with Text
Plugin URI: #
Description: Image module for minimax
Author: Shaon
Version: 1.6
Author URI: #
*/

class MiniMax_Image extends WP_Widget {
    function __construct() {
        parent::__construct( 'MiniMax_Image', 'Image with Text', array( 'description' => 'Image Widget' ) );
    }

    function widget( $args, $instance ) {
        wp_enqueue_style( "minimax-image", base_theme_url.'/modules/image/image.css');
        $style = $instance['style'] ;

        $imgurl = $instance['url'];

        if (strpos($imgurl, 'wp-content/') !== false) {
            $rurl = explode('wp-content/',$imgurl);
            $instance['url'] = content_url('/').$rurl[1];
        }

        $fn = "image_$style";
        $this->{$fn}($args, $instance);
    }

    function preview($instance){
        $args = array();
        $style = $instance['style'] ;

        $imgurl = $instance['url'];

        if (strpos($imgurl, 'wp-content/') !== false) {
            $rurl = explode('wp-content/',$imgurl);
            $instance['url'] = content_url('/').$rurl[1];
        }

        $fn = "image_$style";
        $this->{$fn}($args, $instance);
    }
    
    function image_2l($args, $instance){
        extract( $args );       
        extract($instance);             
        $id = uniqid();

        if(isset($imgw) && isset($imgh)){
            $imgpath = str_replace( site_url('/'), ABSPATH, $url );
            $thumbpath = minimax_dynamic_thumb( $imgpath, array( $imgw, $imgh ) );
            $thumburl = str_replace( ABSPATH, site_url('/'), $thumbpath );
        }
        else
            $thumburl = $url;
        
        echo isset($before_widget) ? $before_widget : '';
        ?>
        <div class="media">
            <div class="pull-left">
                <?php if($link !='') echo '<a href="'.$link.'">'; ?>
                <img class="img-responsive img-<?php echo $bootstrap_style;?>" src="<?php echo $thumburl; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>">
                <?php if($link !='') echo '</a>'; ?>
            </div>
            <div class="media-body" id="mx-img-txt-<?php echo $id; ?>">
            <?php
                echo '<'.$titleh.'>';
                    if($link !='') echo '<a href="'.$link.'">'; 
                        echo $title;
                    if($link !='') echo '</a>'; 
                echo '</'.$titleh.'>';        
                echo '<p>'.$desc.'</p>'; ?>         
            </div>
        </div> 
         
        <?php
        echo isset($after_widget) ? $after_widget : '';
    }
    
    function image_2r($args, $instance){
        extract( $args );       
        extract($instance);             
        $id = uniqid();
        if(isset($imgw) && isset($imgh)){
            $imgpath = str_replace(site_url('/'), ABSPATH, $url);
            $thumbpath = minimax_dynamic_thumb($imgpath, array($imgw, $imgh));
            $thumburl = str_replace(ABSPATH, site_url('/'), $thumbpath);
        }
        else
            $thumburl = $url;

        echo isset($before_widget) ? $before_widget : '';
        ?>
        <div class="media">
            <div class="pull-right">
                <?php if($link !='') echo '<a href="'.$link.'">'; ?>
                    <img class="img-responsive img-<?php echo $bootstrap_style;?>" src="<?php echo $thumburl;?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>">
                <?php if($link !='') echo '</a>'; ?>
            </div>
        
            <div  class="media-body" id="mx-img-txt-<?php echo $id; ?>">
                <?php 
                    echo '<'.$titleh.'>';
                    if($link !='') echo '<a href="'.$link.'">'; 
                        echo $title;
                    if($link !='') echo '</a>'; 
                    echo '</'.$titleh.'>';
                    echo '<p>'.$desc.'</p>';
                ?>         
            </div>
        </div>
        <?php
        echo isset($after_widget) ? $after_widget : '';
    }
    
    function image_1t($args, $instance){
        extract( $args );       
        extract($instance);             
        $id = uniqid();
        if(isset($imgw) && isset($imgh)){
            $imgpath = str_replace(site_url('/'), ABSPATH, $url);
            $thumbpath = minimax_dynamic_thumb($imgpath, array($imgw, $imgh));
            $thumburl = str_replace(ABSPATH, site_url('/'), $thumbpath);
        }
        else
            $thumburl = $url;

        echo isset($before_widget) ? $before_widget : '';
        ?>
           
        <?php if($link !='') echo '<a href="'.$link.'">'; ?>
            <img class="img-responsive img-<?php echo $bootstrap_style;?>" src="<?php echo $thumburl; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>">
        <?php if($link !='') echo '</a>';         
        
        echo '<'.$titleh.'>';
            if($link !='') echo '<a href="'.$link.'">'; 
                echo $title;
            if($link !='') echo '</a>'; 
        echo '</'.$titleh.'>';
        echo '<p>'.$desc.'</p>';

        echo isset($after_widget) ? $after_widget : '';
    }
    
    function image_1l($args, $instance){
        extract( $args );       
        extract($instance);             
        $id = uniqid();
        if( isset($imgw) && isset($imgh) ){
            $imgpath = str_replace(site_url('/'), ABSPATH, $url);
            $thumbpath = minimax_dynamic_thumb($imgpath, array($imgw, $imgh));
            $thumburl = str_replace(ABSPATH, site_url('/'), $thumbpath);
        }
        else
            $thumburl = $url;

        echo isset($before_widget) ? $before_widget : '';
        ?>
        <?php 
        echo '<'.$titleh.'>';
            if($link !='') echo '<a href="'.$link.'">'; 
                echo $title;
            if($link !='') echo '</a>'; 
        echo '</'.$titleh.'>';
        ?> 
            <div class="pull-left" style="margin-right: 10px; margin-top: 5px;">
                <?php if($link !='') echo '<a href="'.$link.'">'; ?>
                    <img class="img-responsive img-<?php echo $bootstrap_style; ?>" src="<?php echo $thumburl; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>">
                <?php if($link !='') echo '</a>'; ?>
            </div>
        <?php echo '<p>'.$desc.'</p>';
        echo '<div class="clear"></div>';

        echo isset($after_widget) ? $after_widget : '';
    }

    function image_1r($args, $instance){
        extract( $args );       
        extract($instance);             
        $id = uniqid();
        if(isset($imgw) && isset($imgh)){
            $imgpath = str_replace(site_url('/'), ABSPATH, $url);
            $thumbpath = minimax_dynamic_thumb($imgpath, array($imgw, $imgh));
            $thumburl = str_replace(ABSPATH, site_url('/'), $thumbpath);
        }
        else
            $thumburl = $url;

        echo isset($before_widget) ? $before_widget : '';
        ?>
        <?php 
        echo '<'.$titleh.'>';
            if($link !='') echo '<a href="'.$link.'">'; 
                echo $title;
            if($link !='') echo '</a>'; 
        echo '</'.$titleh.'>';        
        ?>
            <div class="pull-right" style="margin-left: 10px;margin-top: 5px;">
                <?php if($link !='') echo '<a href="'.$link.'">'; ?>
                <img class="img-responsive img-<?php echo $bootstrap_style; ?>" src="<?php echo $thumburl; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>">
                <?php if($link !='') echo '</a>'; ?>
            </div>
        <?php echo '<p>'.$desc.'</p>';
        echo '<div class="clear"></div>';

        echo isset($after_widget) ? $after_widget : '';
    }

    function update( $new_instance, $old_instance ) {
        $instance = $new_instance;       
        return $instance;
    }

    function form( $instance ) {

        if(isset($instance['url'])){
            $imgurl = $instance['url'];
            if (strpos($imgurl, 'wp-content/') !== false) {
                $rurl = explode('wp-content/',$imgurl);
                $instance['url'] = content_url('/').$rurl[1];
            }
        }

        if ( $instance ) { extract($instance); }
        else{ $imgw = 200; $imgh = 200; }
        ?>
        
        <div id="tabpane">
 
        <div class="row">    
            <div class="col-md-8">
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <label for="<?php echo $this->get_field_id('titleh'); ?>"><?php _e('Title Tag:'); ?></label> 
                    <select class="widefat" id="<?php echo $this->get_field_id('titleh'); ?>" name="<?php echo $this->get_field_name('titleh'); ?>">
                    <option value="h1">H1</option>
                    <option value="h2" <?php if($titleh=='h2') echo 'selected=selected'; ?> >H2</option>
                    <option value="h3" <?php if($titleh=='h3') echo 'selected=selected'; ?> >H3</option>
                    <option value="h4" <?php if($titleh=='h4') echo 'selected=selected'; ?> >H4</option>
                    </select>
                </p>
            </div>
        </div>
        <p>
            <label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Description:'); ?></label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" ><?php echo $desc; ?></textarea>
        </p>

        <p>
            <?php
            $html = "<div>
                        <input class='form-control' style='width: 90%;float: left;margin-right: 5px' type='text' name='{$this->get_field_name('url')}' id='{$this->get_field_id('url')}' value='{$url}' />
                        <span class='input-group-button'>
                            <button rel='#{$this->get_field_id('url')}' class='btn btn-media-upload ui-button' type='button' style='height: 34px'>
                                <i class='glyphicon glyphicon-link'></i>
                            </button>
                        </span>
                     </div>";
            ?>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Image URL:'); ?></label>
            <?php echo $html; ?>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
        </p>
        <div class="row">    
            <div class="col-md-6">
                <p>
                    <label><?php _e('Image Width :'); ?></label>
                    <input placeholder="i.e. 300" class="widefat" id="<?php echo $this->get_field_id('imgw'); ?>" name="<?php echo $this->get_field_name('imgw'); ?>" type="text" value="<?php echo $imgw; ?>" />
                </p>
            </div>
            <div class="col-md-6">
                <p>
                    <label><?php _e('Image Height :'); ?></label>
                    <input placeholder="i.e. 300" class="widefat" id="<?php echo $this->get_field_id('imgh'); ?>" name="<?php echo $this->get_field_name('imgh'); ?>" type="text" value="<?php echo $imgh; ?>" />
                </p>
            </div>
        </div>
        <div class="row">    
            <div class="col-md-6">
                <p>
                    <label><?php _e('Template :'); ?></label> <br/>
                    <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="text">
                    <option value="2l" <?php if($style=='2l') echo 'selected=selected'; ?> > Two Column, Image Left </option>
                    <option value="1t" <?php if($style=='1t') echo 'selected=selected'; ?> > One Column, Image Top </option>
                    <option value="2r" <?php if($style=='2r') echo 'selected=selected'; ?> > Two Column, Image Right </option>
                    <option value="1l" <?php if($style=='1l') echo 'selected=selected'; ?> > One Column, Image Left </option>
                    <option value="1r" <?php if($style=='1r') echo 'selected=selected'; ?> > One Column, Image Right </option>
                    </select>
                </p>
            </div>
            <div class="col-md-6">
                <p>
                    <label><?php _e('Image Style:'); ?></label> <br/>
                    <select class="widefat" id="<?php echo $this->get_field_id('bootstrap_style'); ?>" name="<?php echo $this->get_field_name('bootstrap_style'); ?>" type="text">
                    <option value="rounded" <?php if($bootstrap_style=='rounded') echo 'selected=selected'; ?> >Rounded</option>
                    <option value="circle" <?php if($bootstrap_style=='circle') echo 'selected=selected'; ?> >Circle </option>
                    <option value="thumbnail" <?php if($bootstrap_style=='thumbnail') echo 'selected=selected'; ?> >Thumbnail </option>
                    </select>
                </p>
            </div>
        </div>
        
        </div>
        <script>
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

        </script>
        <?php 
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget("minimax_image");' ) );