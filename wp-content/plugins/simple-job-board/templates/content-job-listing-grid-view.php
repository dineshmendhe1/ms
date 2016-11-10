<?php
/**
 * The template for displaying job content in grid view within loops.
 *
 * Override this template by copying it to yourtheme/simple_job_board/content-job-listing-grid-view.php
 *
 * @author      PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/Templates
 * @version     1.1.0
 * @since       2.2.0
 * @since       2.2.3   Added @hook sjb_job_listing_heading_after.
 * @since       2.3.0   Added "sjb_grid_view_template" filter.
 */
ob_start();
global $counter;

if ( 1 === $counter % 3 ) {
    echo '<section class="sjb-row">';
}

/**
 * Fires at start of a job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_grid_view_start'); ?>

<!-- Start Jobs Grid View 
================================================== -->
<article <?php post_class('sjb-col-md-4 sjb-job-grid-view'); ?> >
    <div id="sjb_job-visiable">
        <div class="sjb-row">

            <!-- Grid view header -->
            <header class="sjb-col-md-12">
                <div class="sjb-row">
                    <?php
                    /**
                     * Template -> Logo:
                     * 
                     * - Company Logo
                     */
                    get_simple_job_board_template('listing/grid-view/logo.php'); ?>
                    <div id="sjb-grid-view-heading">
                        <h4 id="sjb_job-heading" class="company-name">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                /**
                                 * Template -> Title:
                                 * 
                                 * - Job Title
                                 */
                                get_simple_job_board_template('listing/grid-view/title.php');

                                /**
                                 * Template -> Company:
                                 * 
                                 * - Company Name
                                 */
                                get_simple_job_board_template('listing/grid-view/company.php');

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
            get_simple_job_board_template('listing/grid-view/type.php');

            /**
             * Template -> Location:
             * 
             * - Job Location
             */
            get_simple_job_board_template('listing/grid-view/location.php');

            /**
             * Template -> Post Date:
             * 
             * - Job Post Date
             */
            get_simple_job_board_template('listing/grid-view/posted-date.php');

            /**
             * Template -> Short Description:
             * 
             * - Job Description
             */
            get_simple_job_board_template('listing/grid-view/short-description.php'); ?>
        </div>
    </div>
</article>
<!-- ==================================================
End Jobs Grid View -->

<?php
/**
 * Fires at the end of a job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_grid_view_end');

if (0 === $counter % 3)
    echo '</section>';
$counter++;

$html_grid_view = ob_get_clean();

/**
 * Modify the Job Listing Grid View Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_grid_view   Job Listing Grid View HTML.                   
 */
echo apply_filters( 'sjb_grid_view_template', $html_grid_view );