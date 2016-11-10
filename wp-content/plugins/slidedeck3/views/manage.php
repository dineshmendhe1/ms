<?php
/**
 * Overview list of SlideDecks
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck 3 Pro for WordPress
 * @author SlideDeck
 */

/*
Copyright 2012 HBWSL  (email : support@hbwsl.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
?>

<?php slidedeck2_flash(); ?>

<div class="slidedeck-wrapper">
    <div class="wrap" id="slidedeck-overview">
        <?php if( isset( $_GET['msg_deleted'] ) ): ?>
            <div id="slidedeck-flash-message" class="updated" style="max-width:964px;"><p><?php _e( "SlideDeck successfully deleted!", $namespace ); ?></p></div>
            <script type="text/javascript">(function($){if(typeof($)!="undefined"){$(document).ready(function(){setTimeout(function(){$("#slidedeck-flash-message").fadeOut("slow");},5000);});}})(jQuery);</script>
        <?php endif; ?>
        
        <div id="slidedeck-types">
            <?php echo $this->upgrade_button('manage'); ?>
            <h1><?php _e( "Manage SlideDeck 3", $namespace ); ?></h1>
            <?php
                $create_new_slide_slidedeck_block = apply_filters( "{$namespace}_create_new_slide_slidedeck_block", "" );
               echo $create_new_slide_slidedeck_block;
               
               $create_slide_using_template_block = apply_filters( "{$namespace}_create_slide_using_template_block", "" );
               echo $create_slide_using_template_block;
            ?>
        </div>
        <!--
       <div >
             <a href="https://slidedeck.com/pricing/"><img src="<?php echo plugin_dir_url( ).'slidedeck3/images/Slidedeck_Horizontal_Banner.jpg' ;?>"   ></a>
        </div>
    	-->
        <div id="slidedeck-table">
            <?php if( !empty( $slidedecks ) ): ?>
                <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" id="slidedeck-table-sort">
                    <fieldset>
                        <input type="hidden" value="<?php echo $namespace; ?>_sort_manage_table" name="action" />
                        <?php wp_nonce_field( "slidedeck-sort-manage-table" ); ?>
                        
                        <label for="slidedeck-table-sort-select"><?php _e( "Sort By:", $namespace ); ?></label> 
                        <select name="orderby" id="slidedeck-table-sort-select" class="fancy">
                            <?php foreach( $order_options as $value => $label ): ?>
                                <option value="<?php echo $value; ?>"<?php if( $value == $orderby ) echo ' selected="selected"'; ?>><?php _e( $label, $namespace ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </fieldset>
                </form>
            <?php endif; ?>
            
            <div class="float-wrapper">
                <div class="left">
                    <?php include( SLIDEDECK2_DIRNAME . '/views/elements/_manage-table.php' ); ?>
                </div>
                <div class="right">
                    <div class="right-inner">
                        <?php if( !SlideDeckLitePlugin::get_partner_data() ){ ?>
			<!--
                        <div id="manage-iab" class="iab">
                            <a href="https://slidedeck.com/pricing/"> <img src="<?php echo plugin_dir_url( ).'slidedeck3/images/Slidedeck_verticle_banner.jpg' ;?>"   ></a>

                        </div>
			-->
                        <?php } ?>
                        
                        <?php do_action( "{$namespace}_manage_sidebar_bottom" ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
