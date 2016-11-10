<?php

/**
 * Simple_Job_Board_Admin Class
 * 
 * The admin-specific functionality of the plugin. Defines the plugin name,
 * version and two examples hooks for how to enqueue the admin-specific 
 * stylesheet and JavaScript.
 *
 * @link       https://wordpress.org/plugins/simple-job-board
 * @since      1.0.0
 * @since      2.3.2    Admin Footer Text Branding
 * 
 * @package    Simple_Job_Board
 * @subpackage Simple_Job_Board/admin
 * @author     PressTigers <support@presstigers.com>
 */
class Simple_Job_Board_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $simple_job_board    The ID of this plugin.
     */
    private $simple_job_board;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $simple_job_board       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($simple_job_board, $version) {
        $this->simple_job_board = $simple_job_board;
        $this->version = $version;

        /**
         * The class responsible for defining all the meta options under custom post type in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-job-board-admin-meta-boxes-init.php';

        /**
         * The class responsible for writing rules in htaccess file and to protect the file from direct link.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-job-board-rewrite.php';

        /**
         * The class responsible for defining all the plugin settings that occur in the front end area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-job-board-admin-settings-init.php';

        /**
         * The class responsible for Applicant's detail in the back end area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-job-board-applicants.php';

        /**
         * The class responsible for creating the job board shortcode generator functionality in TinyMCE through its button.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-job-board-admin-shortcode-generator.php';

        /**
         * The class responsible for creating the add-ons page in admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-job-board-admin-add-ons.php';

        // Filter -> Footer Branding - with PressTigers Logo
        add_filter('admin_footer_text', array($this, 'admin_powered_by'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Simple_Job_Board_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Simple_Job_Board_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->simple_job_board . '-jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', array(), $this->version, 'all');
        wp_enqueue_style($this->simple_job_board . '-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans', array(), $this->version, 'all');
        wp_enqueue_style($this->simple_job_board . '-fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->simple_job_board, plugin_dir_url(__FILE__) . 'css/simple-job-board-admin.css', array('wp-color-picker'), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Simple_Job_Board_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Simple_Job_Board_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (is_admin()) {
            wp_enqueue_script($this->simple_job_board . '-admin', plugin_dir_url(__FILE__) . 'js/simple-job-board-admin.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker'), $this->version, FALSE); //           
            // Localize Script for Making jQuery Stings Translation Ready
            wp_localize_script($this->simple_job_board . '-admin', 'application_form', array(
                'settings_jquery_alerts' => array(
                    'delete' => __('Delete', 'simple-job-board'),
                    'required' => __('Required', 'simple-job-board'),
                    'field_name' => __('Field Name', 'simple-job-board'),
                    'empty_feature_name' => __('Please fill out job feature.', 'simple-job-board'),
                    'empty_field_name' => __('Please fill out application form field name.', 'simple-job-board'),
                ),
                    )
            );
        }
    }

    /**
     * Replace admin footer text with PressTigers branding.
     *
     * @since    2.3.2
     */
    public function admin_powered_by($text) {
        $screen = get_current_screen();
        
        // SJB Admin Pages Ids
        $sjb_pages = array(
            'jobpost_page_job-board-settings',
            'jobpost_page_sjb-add-ons',
            'edit-jobpost_applicants',
            'jobpost_applicants',
            'edit-jobpost',
            'jobpost',
            'edit-jobpost_category',
            'edit-jobpost_job_type',
            'edit-jobpost_location',
        );

        if (is_admin() && ( in_array($screen->id, apply_filters('sjb_pages', $sjb_pages)) )) {
            $text = '<a href="http://www.presstigers.com/" target="_blank"><img src="' . SIMPLE_JOB_BOARD_PLUGIN_URL . '/admin/images/powerByIcon.png" alt="Powered by PressTigers"></a>';
        }
        return $text;
    }

}
