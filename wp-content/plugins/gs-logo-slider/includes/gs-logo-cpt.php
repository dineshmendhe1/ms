<?php
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/

if ( ! function_exists('GS_Logo_Slider')) {

	function GS_Logo_Slider() {

		$labels = array(
			'name'               => _x( 'Logos', 'golamsamdani' ),
			'singular_name'      => _x( 'Logo', 'golamsamdani' ),
			'menu_name'          => _x( 'GS Logos', 'admin menu', 'golamsamdani' ),
			'name_admin_bar'     => _x( 'Logo', 'add new on admin bar', 'golamsamdani' ),
			'add_new'            => _x( 'Add New Logo', 'logo', 'golamsamdani' ),
			'add_new_item'       => __( 'Add New Logo', 'golamsamdani' ),
			'new_item'           => __( 'New Logo', 'golamsamdani' ),
			'edit_item'          => __( 'Edit Logo', 'golamsamdani' ),
			'view_item'          => __( 'View Logo', 'golamsamdani' ),
			'all_items'          => __( 'All Logos', 'golamsamdani' ),
			'search_items'       => __( 'Search Logos', 'golamsamdani' ),
			'parent_item_colon'  => __( 'Parent Logos:', 'golamsamdani' ),
			'not_found'          => __( 'No logos found.', 'golamsamdani' ),
			'not_found_in_trash' => __( 'No logos found in Trash.', 'golamsamdani' ),
		);

		$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'logo' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-screenoptions',
				'supports'           => array( 'title', 'thumbnail' )
			);

			register_post_type( 'gs-logo-slider', $args );
	}
}

add_action( 'init', 'GS_Logo_Slider' );

// Register Theme Features (feature image for Logo)
if ( ! function_exists('gs_logo_theme_support') ) {

	function gs_logo_theme_support()  {
		// Add theme support for Featured Images
		add_theme_support( 'post-thumbnails', array( 'gs-logo-slider' ) );
		add_theme_support( 'post-thumbnails', array( 'post' ) ); // Add it for posts
		add_theme_support( 'post-thumbnails', array( 'page' ) ); // Add it for pages
		add_theme_support( 'post-thumbnails', array( 'product' ) ); // Add it for products
		add_theme_support( 'post-thumbnails');
		// Add Shortcode support in text widget
		add_filter('widget_text', 'do_shortcode'); 
	}

	// Hook into the 'after_setup_theme' action
	add_action( 'after_setup_theme', 'gs_logo_theme_support' );
}