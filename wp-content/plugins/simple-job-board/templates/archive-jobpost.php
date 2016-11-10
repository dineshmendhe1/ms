<?php
/**
 * The Template for displaying job archives, including the main jobpost page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/simple_job_board/archive-jobpost.php
 *
 * @author      PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/Templates
 * @version     1.1.0
 * @since       2.2.0
 * @since       2.2.3   Enqueued Front end Styles & Revised the HTML structure.
 * @since       2.2.4   Enqueued Front end Scripts.
 * @since       2.3.0   Added "sjb_archive_template" filter.
 */
get_header();

ob_start();
global $job_query, $wp_rewrite, $post;

/**
 * Enqueue Frontend Scripts.
 * 
 * @since   2.2.4
 */
do_action('sjb_enqueue_scripts');

/**
 * Hook -> sjb_before_main_content
 * 
 * @hooked sjb_job_listing_wrapper_start - 10 
 * - Output Opening div of Main Container.
 * - Output Opening div of Content Area.
 * 
 * @since  2.2.0
 * @since  2.2.3 Removed the content wrapper opening div
 */
do_action('sjb_before_main_content');
?>

<!-- Start Content Wrapper
================================================== -->
<div class="sjb-wrap">

    <!-- Start Job Title
    ================================================== -->
    <section id="sjb-page-detail">
        <h2 id="job-title"><?php echo apply_filters('sjb_jobs_archive_title', __('Job Archives', 'simple-job-board')); ?></h2>                 
    </section>
    <!-- ==================================================
    End Job Title -->

    <?php    
    // Retrieves Public Query Page Variable
    if (get_query_var('paged')) {
        $paged = get_query_var('paged');
    } elseif (get_query_var('page')) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }

    // WP_Query Default Arguments
    $args = apply_filters(
        'sjb_archive_output_jobs_args',
        array(
            'posts_per_page' => '15',
            'post_type'      => 'jobpost',
            'paged'          => $paged,
        )
    );

    // Merge WP_Query $args array on each $_GET element            
    $args['jobpost_category'] = (isset($_GET['selected_category']) && -1 != $_GET['selected_category']) ? esc_attr($_GET ['selected_category']) : '';
    $args['jobpost_job_type'] = (isset($_GET['selected_jobtype']) && -1 != $_GET['selected_jobtype']) ? esc_attr($_GET['selected_jobtype']) : '';
    $args['jobpost_location'] = (isset($_GET['selected_location']) && -1 != $_GET['selected_location']) ? esc_attr($_GET['selected_location']) : '';
    $args['s'] = (isset($_GET['search_keywords'])) ? sanitize_text_field($_GET['search_keywords']) : '';

    // Job Query
    $job_query = new WP_Query($args);

    /**
     * Template -> Job Listing Start:
     * 
     * - SJB Starting Content Wrapper div.
     */
    get_simple_job_board_template( 'listing/job-listings-start.php' );

    /**
     * Template -> Job Filters:
     * 
     * - Keywords Search.
     * - Job Category Filter.
     * - Job Type Filter.
     * - Job Location Filter.
     * 
     * Search jobs by category, location, type and keywords.
     */       
    get_simple_job_board_template( 'job-filters.php' );
    
    /**
     * Fires before listing jobs on jobs archive page.
     * 
     * @since   2.2.0
     */
    do_action('sjb_job_listing_before');
    
    if ( $job_query->have_posts() ):  
        global $counter;
        $counter = 1;
        while ( $job_query->have_posts() ): $job_query->the_post();

            /**
             * Hook -> sjb_job_listing_view
             * 
             * @hooked sjb_job_listing_view - 10  
             *              
             * Display the user defined job listing view:
             * 
             * - Either job listing grid view or list view.
             * 
             * @since   2.2.3
             */
            do_action('sjb_job_listing_view');
        endwhile;
        
        /**
         * Template -> Pagination:
         * 
         * - Add Pagination to Resulted Jobs.
         */
        get_simple_job_board_template( 'listing/job-pagination.php' );
    else:  
        
        /**
         * Template -> No Job Found:
         * 
         * - Display Message on No Job Found.
         */
        get_simple_job_board_template( 'listing/content-no-jobs-found.php' );
    endif;
    wp_reset_postdata();
            
    /**
     * Fires after listing jobs on job archive page.
     * 
     * @since   2.2.0
     */
    do_action('sjb_job_listing_after');   

    /**
     * Template -> Job Listing End:
     * 
     * - SJB Ending Wrapper div.
     */
    get_simple_job_board_template( 'listing/job-listings-end.php' );
    ?>
</div>
<!-- ==================================================
End Content Wrapper -->

<div class="sjb-clearfix"></div>

<?php
/**
 * Hook -> sjb_after_main_content
 *  
 * @hooked sjb_job_listing_wrapper_end - 10
 * 
 * - Output Closing div of Main Container.
 * - Output Closing div of Content Area.
 * 
 * @since   2.2.0
 * @since   2.2.3 Removed the content wrapper closing div
 */
do_action( 'sjb_after_main_content' );

$html_archive = ob_get_clean();

/**
 * Modify the Jobs Archive Page Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_archive   Jobs Archive Page HTML.                   
 */
echo apply_filters( 'sjb_archive_template', $html_archive );

get_footer();