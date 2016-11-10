<?php
//-------------- Enqueue Latest jQuery------------
function gs_ls_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'gs_ls_jquery');


//-------------- Include js files---------------
function gs_ls_enq_scripts() {
	if (!is_admin()) {
	
		wp_register_script('bxslider-js', plugins_url('js/jquery.bxslider.min.js', __FILE__),array('jquery'),'4.1.2', true);

		wp_register_script('jq-easing-js', plugins_url('js/jquery.easing.1.3.js', __FILE__),array('jquery'),'1.3', true);
		
		wp_enqueue_script('bxslider-js');
		wp_enqueue_script('jq-easing-js');
	}
}
add_action( 'wp_enqueue_scripts', 'gs_ls_enq_scripts' ); 


//------------ Include css files-----------------
function gs_ls_adding_style() {
	if (!is_admin()) {
		wp_register_style('bxslider-style', plugins_url('css/jquery.bxslider.css', __FILE__),'','4.1.2', false);
		wp_register_style('gs-main-style', plugins_url('css/gs-main.css', __FILE__),'','1.0.0', false);
				
		wp_enqueue_style('bxslider-style');
		wp_enqueue_style('gs-main-style');
		
	}
}
add_action( 'init', 'gs_ls_adding_style' );