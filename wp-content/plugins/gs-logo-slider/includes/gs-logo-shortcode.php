<?php 
/**
 * 
 */
add_action('wp_footer','gs_ls_slider_trigger');

function gs_ls_slider_trigger(){
?>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.gs_logo_container').bxSlider({
  	slideWidth: 200,
    minSlides: 1,
    maxSlides: 5,
    slideMargin: 10,
  	moveSlides: 1,
  	speed: 750,
  	controls: true,
  	autoHover: true,
  	pager: false,
  	auto: true
  });
 
});
</script>
<?php
}


// ---------- Shortcode [gs_logo] -------------

add_shortcode( 'gs_logo', 'gs_logo_shortcode' );

function gs_logo_shortcode( $atts ) {

	extract(shortcode_atts( 
			array(
			'posts' 	=> 20,
			'order'		=> 'DESC',
			'orderby'   => 'date',
			'title'		=> 'no'
			), $atts 
		));

	$loop = new WP_Query(
		array(
			'post_type'	=> 'gs-logo-slider',
			'order'		=> 'DESC',
			'orderby'	=> 'date',
			'posts_per_page'	=> $posts
			)
		);

	$output = '<div class="gs_logo_container">';
		if ( $loop->have_posts() ) {
			
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$meta = get_post_meta( get_the_id() );
				
				$gs_logo_id = get_post_thumbnail_id();
				$gs_logo_url = wp_get_attachment_image_src($gs_logo_id, array(200,200), true);
				$gs_logo = $gs_logo_url[0];
				$gs_logo_alt = get_post_meta($gs_logo_id,'_wp_attachment_image_alt',true);

				$output .= '<div class="gs_logo_single">';

					if ($meta['client_url'][0]) :
				 		$output .= '<a href="'. $meta['client_url'][0] .'" target="_blank">';
				 	endif;

				 	if ($gs_logo) :
						$output .= '<img src="'.$gs_logo.'" alt="'.$gs_logo_alt.'" >';
					endif;

					if ($meta['client_url'][0]) :
						$output .= '</a>';
					endif;
					
					if ( $title == "yes" ) :
						$output .= '<h3 class="gs_logo_title">'. get_the_title() .'</h3>';
					endif;
				$output .= '</div>';
			}

		} else {
			$output .= "No Logo Added!";
		}

		wp_reset_postdata();
		wp_reset_query();

	$output .= '</div>';

	return $output;
}

