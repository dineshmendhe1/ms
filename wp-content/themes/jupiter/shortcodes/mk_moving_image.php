<?php

extract( shortcode_atts( array(
			'src' => '',
			'axis' => '',
			'animation' => '',
			'link' => '',
			'align' => 'left',
			'title' => '',
			'visibility' => '',
			'el_class' => '',
		), $atts ) );




$animation_css = $image_html = '';


if ( $animation != '' ) {
	$animation_css = 'mk-animate-element ' . $animation . ' ';
}

$image_html = '<img class="mk-floating-'.$axis.'" alt="'.$title.'" title="'.$title.'" src="'.$src.'" />';

$output .= '<div class="mk-moving-image-shortcode mk-shortcode align-'.$align.' '.$visibility.' '.$animation_css.$el_class.'">';
$output .= !empty($link) ? '<a href="'.$link.'">'.$image_html.'</a>' : $image_html;
$output .= '</div>';
$output .= '<div class="clearboth"></div>';

echo $output;
