<?php
/*
Plugin Name: Notice
Plugin URI: #
Description: Add notice in layout area
Author: Shaon
Version: 1.6
Author URI: #
*/ 
/**
 * Notice Class
 */
class MiniMax_Notice extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct('MiniMax_Notice', 'Notice', array( 'description' => 'Add notice or alter in layout area' ) );
    }

    /** @see WP_Widget::widget */
    function widget( $args, $instance ) {
        
        extract( $args );
        
        $minimax_options = get_option("wpeden_admin");
        $ui = $minimax_options['general']['ui'];
         
        $title = apply_filters( 'widget_title', $instance['title'] );
        
         if($ui=="jquery")include("jquery_notice.php");
        else include("bootstrap_notice.php");
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['notice'] = strip_tags($new_instance['notice']);
        $instance['ntype'] = strip_tags($new_instance['ntype']);
        $button_label =  $instance[ 'button_label' ] ;
        $button_url =  $instance[ 'button_url' ] ;
        $close_button =  $instance[ 'close_button' ] ;
        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {
        if ( $instance ) {
            $title = esc_attr( $instance[ 'title' ] );
            $notice =  $instance[ 'notice' ] ;
            $type =  $instance[ 'ntype' ] ;
            $button_label =  $instance[ 'button_label' ] ;
            $button_url =  $instance[ 'button_url' ] ;
            $close_button =  $instance[ 'close_button' ] ;
        }
        else {
            $title = 'Notice';            
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('notice'); ?>"><?php _e('Message:'); ?></label> 
        <textarea id="<?php echo $this->get_field_id('notice'); ?>" name="<?php echo $this->get_field_name('notice'); ?>" cols="20" rows="4" style="width: 100%"><?php echo $notice; ?></textarea>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type:'); ?></label> 
        <select class="widefat" id="<?php echo $this->get_field_id('ntype'); ?>" name="<?php echo $this->get_field_name('ntype'); ?>">
        <option value="info">Information</option>
        <option value="error" <?php if($type=='error') echo 'selected=selected'; ?> >Error</option>
        <option value="warning" <?php if($type=='warning') echo 'selected=selected'; ?> >Warning</option>        
        <option value="success" <?php if($type=='success') echo 'selected=selected'; ?> >Success</option>        
        </select>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('button-label'); ?>"><?php _e('Button Label:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button_label'); ?>" name="<?php echo $this->get_field_name('button_label'); ?>" type="text" value="<?php echo $button_label; ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('button-url'); ?>"><?php _e('Button URL:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo $button_url; ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('close-button'); ?>"><?php _e('Close Button:'); ?></label>
        <input type="checkbox" <?php if($close_button==1)echo 'checked="checked"';else echo ''; ?> name="<?php echo $this->get_field_name('close_button'); ?>" id="<?php echo $this->get_field_id('close_button'); ?>" value="1"> Enable/Disable
        </p>
        <?php 
    }

} 

add_action( 'widgets_init', create_function( '', 'register_widget("MiniMax_Notice");' ) );