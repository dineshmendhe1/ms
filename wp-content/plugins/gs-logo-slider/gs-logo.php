<?php
/**
 *
 * @package   GS_Logo_Slider
 * @author    Golam Samdani <samdani1997@gmail.com>
 * @license   GPL-2.0+
 * @link      https://www.gsamdani.com
 * @copyright 2014 Golam Samdani
 *
 * @wordpress-plugin
 * Plugin Name:			GS Logo Slider
 * Plugin URI:			https://www.gsamdani.com/wordpress-plugins
 * Description:       	Best Responsive Logo slider to display partners, clients or sponsors Logo on Wordpress site. Display anywhere at your site using shortcode like [gs_logo] Check more shortcode examples and documention at <a href="https://logo.gsamdani.com">GS Logo Slider Docs</a> 
 * Version:           	1.0.4
 * Author:       		Golam Samdani
 * Author URI:       	https://www.gsamdani.com
 * Text Domain:       	golamsamdani
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//--------- CPT Logo ----------------------- 
require_once dirname( __FILE__ ) . '/includes/gs-logo-cpt.php';

//--------- CPT's MetaBox ------------------ 
require_once dirname( __FILE__ ) . '/includes/gs-logo-metabox.php';

//--------- CPT's Columns ------------------ 
require_once dirname( __FILE__ ) . '/includes/gs-logo-column.php';

//--------- CPT's Shortcode ---------------- 
require_once dirname( __FILE__ ) . '/includes/gs-logo-shortcode.php';

//--------- Enqueue Scripts & Style Files --
require_once dirname( __FILE__ ) . '/gs-ls-script.php';

//--------- GS Plugins ---------------- 
require_once dirname( __FILE__ ) . '/includes/gs-plugins/gs-plugins.php';

add_action('do_meta_boxes', 'gsl_fea_img_box');
function gsl_fea_img_box()
{
    remove_meta_box( 'postimagediv', 'gs-logo-slider', 'side' );
    add_meta_box('postimagediv', __('Company Logo'), 'post_thumbnail_meta_box', 'gs-logo-slider', 'normal', 'high');
}


//add_action('do_meta_boxes', 'change_image_box2');

function change_image_box2()
{
    remove_meta_box( 'postimagediv', 'gs-logo-slider', 'side' );
    add_meta_box(
        'postimagediv',             // id
        __('Company Logo'),         // title
        'post_thumbnail_meta_boxes',  // callback
        'gs-logo-slider',           // screen
        'advance',                  // context 
        'high'                      // priority
        );
}