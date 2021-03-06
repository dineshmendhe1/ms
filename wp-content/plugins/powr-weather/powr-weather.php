<?php
    /**
     * @package POWr Weather
     * @version 1.4
     */
    /*
    Plugin Name: POWr Weather
    Plugin URI: http://www.powr.io
    Description: Track and display a real-time forecast for any location!  Drop the widget anywhere in your theme. Or use the POWr icon in your WP text editor to add to a page or post. Edit on your live page by clicking the settings icon. More plugins and tutorials at POWr.io.
    Author: POWr.io
    Version: 1.4
    Author URI: http://www.powr.io
    */

    ///////////////////////////////////////GENERATE JS IN HEADER///////////////////////////////
    //For local mode (testing)
    if(!function_exists('powr_local_mode')){
        function powr_local_mode(){
          return false;
        }
    }
    //Generates an instance key
    if(!function_exists('generate_powr_instance')){
        function generate_powr_instance() {
          $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
          $pass = array(); //remember to declare $pass as an array
          $alphaLength = strlen($alphabet) - 1; // Put the length -1 in cache.
          for ($i = 0; $i < 10; $i++) { // Add 10 random characters.
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
          }
          $pass_string = implode($pass) . time(); // Add the current time to avoid duplicate keys.
          return $pass_string; // Turn the array into a string.
        }
    }
    //Adds script to the header if necessary
    if(!function_exists('initialize_powr_js')){
        function initialize_powr_js(){
          //No matter what we want the javascript in the header:
          add_option( 'powr_token', generate_powr_instance(), '', 'yes' );	//Add a global powr token: (This will do nothing if the option already exists)
          $powr_token = get_option('powr_token'); //Get the global powr_token
          if(powr_local_mode()){//Determine JS url:
            $js_url = '//localhost:3000/powr_local.js';
          }else{
            $js_url = '//www.powr.io/powr.js';
          }
          ?>
          <script>
            (function(d){
              var js, id = 'powr-js', ref = d.getElementsByTagName('script')[0];
              if (d.getElementById(id)) {return;}
              js = d.createElement('script'); js.id = id; js.async = true;
              js.src = '<?php echo $js_url; ?>';
              js.setAttribute('powr-token','<?php echo $powr_token; ?>');
              js.setAttribute('external-type','wordpress');
              ref.parentNode.insertBefore(js, ref);
            }(document));
          </script>
          <?php
        }
        //CALL INITIALIZE
        add_action( 'wp_enqueue_scripts', 'initialize_powr_js' );
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////Create Weather widget/////////////////////////////////
    class Powr_Weather extends WP_Widget{
      //Create the widget
      public function __construct(){
        parent::__construct( 'powr_weather',
                             __( 'POWr Weather' ),
                             array( 'description' => __( 'Weather by POWr.io') )
        );
      }
      //This prints the div
      public function widget( $args, $instance ){
        $label = $instance['label'];
        ?>
        <div class='widget powr-weather' label='<?php echo $label; ?>'></div>
        <?php
      }
      public function update( $new_instance, $old_instance ){
        //TODO: Figure out what needs to happen here
        $instance = $old_instance;
        //If no label, then set a label
        if( empty($instance['label']) ){
          $instance['label'] = 'wordpress_'.time();
        }
        return $instance;
      }
      public function form( $instance ){
        ?>
        <p>
          No need to edit here - just click the gears icon on your Weather.
        </p>
        <p>
          Learn more at <a href='http://www.powr.io'>POWr.io</a>
        </p>
        <?php
      }
    }
    //Register Widget With Wordpress
    function register_powr_weather() {
      register_widget( 'Powr_Weather' );
    }
    //Use widgets_init action hook to execute custom function
    add_action( 'widgets_init', 'register_powr_weather' );
    //Create short codes for adding plugins anywhere:
    function powr_weather_shortcode( $atts ){
      if(isset($atts['id'])){
        $id = $atts['id'];
      	return "<div class='powr-weather' id='$id'></div>";
      }else if(isset($atts['label'])){
        $label = $atts['label'];
		    return "<div class='powr-weather' label='$label'></div>";
      }else{
      	"<div class='powr-weather'></div>";
      }
    }
    add_shortcode( 'powr-weather', 'powr_weather_shortcode' );

    /* Add POWr Plug to tiny MCE */
    if( !function_exists('powr_tinymce_button') ){
      add_action( 'admin_init', 'powr_tinymce_button' ); //This calls the function below

      function powr_tinymce_button() {
           if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
                add_filter( 'mce_buttons', 'powr_register_tinymce_button' );
                add_filter( 'mce_external_plugins', 'powr_add_tinymce_button' );
           }
      }
      function powr_register_tinymce_button( $buttons ) {
           array_push( $buttons, 'powr');
           return $buttons;
      }
      function powr_add_tinymce_button( $plugin_array ) {
           $plugin_array['powr'] = plugins_url( '/powr_tinymce.js', __FILE__ ) ;
           return $plugin_array;
      }
      //CSS For icon
      function powr_tinymce_css() {
          wp_enqueue_style('powr_tinymce', plugins_url('/powr_tinymce.css', __FILE__));
      }
      add_action('admin_enqueue_scripts', 'powr_tinymce_css');
    }

    //ADD MENUS
    add_action( 'admin_menu', 'powr_weather_menu' );
    function powr_weather_menu() {
      add_menu_page( 'POWr Weather', 'POWr Weather', 'manage_options', 'powr-weather-settings', powr_weather_options, 'https://s3-us-west-1.amazonaws.com/powr/platforms/wordpress/16x16_icons/Weather.png');
      add_submenu_page( 'powr-weather-settings', 'POWr - Create', 'Create', 'manage_options', 'powr-weather-create', powr_weather_create_options);
      add_submenu_page( 'powr-weather-settings', 'POWr - Manage', 'Manage', 'manage_options', 'powr-weather-manage', powr_weather_manage_options);
      add_submenu_page( 'powr-weather-settings', 'POWr - Help', 'Help', 'manage_options', 'powr-weather-help', powr_weather_help_options);
    }
    function powr_weather_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      echo '<iframe id="powr-weather" src="https://www.powr.io/wp/weather" frameborder="0" width="100%" height="600px" style="position:absolute; top:0; left:0;z-index: 100;"></iframe>';
      echo '<script>';
      echo 'var ht = window.innerHeight - document.getElementById("wpadminbar").offsetHeight;';
      echo 'var iframe = document.getElementById("powr-weather"); iframe.style.height = ht+"px"; iframe.height = ht;';
      echo '</script>';
    }
    function powr_weather_create_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      echo '<iframe id="powr-create-weather" src="https://www.powr.io/wp-create/weather?powr_token='.get_option('powr_token').'" frameborder="0" width="100%" height="600px" style="position:absolute; top:0; left:0;z-index: 100;"></iframe>';
      echo '<script>';
      echo 'var ht = window.innerHeight - document.getElementById("wpadminbar").offsetHeight;';
      echo 'var iframe = document.getElementById("powr-create-weather"); iframe.style.height = ht+"px"; iframe.height = ht;';
      echo '</script>';
    }
    function powr_weather_manage_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      echo '<iframe id="powr-manage-weather" src="https://www.powr.io/wp-manage/weather" frameborder="0" width="100%" height="600px" style="position:absolute; top:0; left:0; z-index: 100;"></iframe>';
      echo '<script>';
      echo 'var ht = window.innerHeight - document.getElementById("wpadminbar").offsetHeight;';
      echo 'var iframe = document.getElementById("powr-manage-weather"); iframe.style.height = ht+"px"; iframe.height = ht;';
      echo '</script>';
    }
    function powr_weather_help_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      echo '<iframe id="powr-help-weather" src="https://www.powr.io/knowledge-base?src=wordpress" frameborder="0" width="100%" height="600px" style="position:absolute; top:0; left:0; z-index: 100;"></iframe>';
      echo '<script>';
      echo 'var ht = window.innerHeight - document.getElementById("wpadminbar").offsetHeight;';
      echo 'var iframe = document.getElementById("powr-help-weather"); iframe.style.height = ht+"px"; iframe.height = ht;';
      echo '</script>';
    }
  ?>