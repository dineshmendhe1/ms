<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Job_Board_Rewrite Class
 * 
 * This is used to define the job board rewrite rules. These rewrite rules prevent
 * the hotlinking of resumes & also force resumes to download rather than 
 * opening in browser.
 *
 * @link        https://wordpress.org/plugins/simple-job-board
 * @since       2.1.0
 * @since       2.2.3   Updated anti-hotlinking rules specific to uploads/jobpost.
 * 
 * @package    Simple_Job_Board
 * @subpackage Simple_Job_Board/includes
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Job_Board_Rewrite {

    /**
     * job_board_rewrite function.
     * 
     * @since   2.1.0
     */
    public function job_board_rewrite() {
        if (!function_exists('get_home_path')) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
        $root_path = get_home_path();
        $file_existing_permission = '';

        /* Getting Rules */
        $rules = ( 'yes' === get_option('job_board_anti_hotlinking')) ? $this->job_board_rewrite_rules() : '';

        /* Rules Force Files to be Downloaded  */
        $forcedownload_rule = "AddType application/octet-stream .pdf .txt\n";

        /* Changing File to Writable Mode  */
        if (file_exists($root_path . '.htaccess') && !is_writable($root_path . '.htaccess')) {
            $file_existing_permission = substr(decoct(fileperms($root_path . '.htaccess')), -4);
            chmod($root_path . '.htaccess', 0777);
        }

        /* Appending .htaccess  */
        if (file_exists($root_path . '.htaccess') && is_writable($root_path . '.htaccess')) {

            $rules = explode("\n", $rules);
            $forcedownload_rule = explode("\n", $forcedownload_rule);

            // Anti-Hotlinking Rules Writing in .htaccess file
            if (!function_exists('insert_with_markers')) {
                require_once( ABSPATH . 'wp-admin/includes/misc.php' );
            }

            insert_with_markers($root_path . '.htaccess', 'Hotlinking', $rules);

            // Force Download Rules Writing in .htaccess file 
            insert_with_markers($root_path . '.htaccess', 'Force Download', $forcedownload_rule);

            /* Revert File Permission  */
            if (!empty($file_existing_permission))
                chmod($root_path . '.htaccess', $file_existing_permission);
        }
    }

    /**
     * job_board_rewrite_rules function.
     *  
     * @since   2.1.0 
     * @since   2.2.3   Updated anti-hotlinking rules specific to uploads/jobpost 
     * 
     * @return  string  $rules  anti-hotlinking rules
     */
    public function job_board_rewrite_rules() {
        $site_url = get_site_url();
        $site_url = trailingslashit($site_url);
        $allowed_extensions = get_option('job_board_allowed_extensions');

        if ($allowed_extensions) {
            /* Retrieve String from Array Separated by |  */
            $hotlink_extension = implode('|', $allowed_extensions);
            $uploads_dir = wp_upload_dir();
            $uploads_url_path = parse_url($uploads_dir['baseurl'], PHP_URL_PATH);

            /* Anti Hotlinking Rules */
            $rules = "<IfModule mod_rewrite.c>\n"
                    . "RewriteEngine On\n"
                    . "RewriteCond %{HTTP_REFERER} !^$\n"
                    . "RewriteCond %{HTTP_REFERER} !^" . $site_url . ".*$ [NC]\n"
                    . "RewriteRule {$uploads_url_path}/jobpost/.*?\.(" . $hotlink_extension . ")$ [R=302,L]\n"
                    . "</IfModule>\n";

            return $rules;
        }
    }

}

new Simple_Job_Board_Rewrite();