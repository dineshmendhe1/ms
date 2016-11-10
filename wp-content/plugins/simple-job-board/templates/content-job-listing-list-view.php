<?php
/**
 * The template for displaying job content in list view within loops.
 *
 * Override this template by copying it to yourtheme/simple_job_board/content-job-listing-list-view.php
 *
 * @author      PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/Templates
 * @version     1.1.0
 * @since       2.2.0
 * @since       2.2.3   Added @hook sjb_job_listing_heading_after.
 * @since       2.3.0   Added "sjb_list_view_template" filter.
 */
ob_start();
global $post;

/**
 * Fires before job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_list_view_before');
?>

<!-- Start Jobs List View 
================================================== -->
<article id="sjb_job-visiable" class="sjb-list-view">
    <div class="sjb-row">

        <!-- Jobs List view header -->
        <header class="sjb-col-md-6">
            <div class="sjb-row">
                <?php
                /**
                 * Template -> Logo:
                 * 
                 * - Company Logo
                 */
                get_simple_job_board_template('listing/list-view/logo.php');
                ?>    
                <div id="sjb-heading">
                    <h4 id="sjb_job-heading" class="company-name">
                        <a href="<?php the_permalink(); ?>"><?php
                            /**
                             * Template -> Title:
                             * 
                             * - Job Title
                             */
                            get_simple_job_board_template('listing/list-view/title.php');

                            /**
                             * Template -> Company:
                             * 
                             * - Company Name
                             */
                            get_simple_job_board_template('listing/list-view/company.php');

                            /**
                             * Fires after Job heading on job listing page.
                             * 
                             * @since   2.2.3
                             */
                            do_action('sjb_job_listing_heading_after'); ?>  
                        </a>                        
                    </h4>
                </div> 
            </div>
        </header>
        <?php
        /**
         * Template -> Type:
         * 
         * - Job Type
         */
        get_simple_job_board_template('listing/list-view/type.php');

        /**
         * Template -> Location:
         * 
         * - Job Location
         */
        get_simple_job_board_template('listing/list-view/location.php');

        /**
         * Template -> Posted Date:
         * 
         * - Job Posted Date
         */
        get_simple_job_board_template('listing/list-view/posted-date.php');?>
    </div>
    <?php
    /**
     * Template -> Short Description:
     * 
     * - Job Description
     */
    get_simple_job_board_template('listing/list-view/short-description.php');
    ?>
</article>
<!-- ==================================================
End Jobs List View -->

<?php
/**
 * Fires after job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_list_view_after');

$html_list_view = ob_get_clean();

/**
 * Modify the Job Listing List View Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_list_view   Job Listing List View HTML.                   
 */
echo apply_filters( 'sjb_list_view_template', $html_list_view );