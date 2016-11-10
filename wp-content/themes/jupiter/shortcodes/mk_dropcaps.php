<?php

extract( shortcode_atts( array(
			'style' => 'simple-style',
			'size' => '', 
			'padding' => '', 
			'background_color' => '',
			'text_color' => '',
			'el_class' => '', 
		), $atts ) );


$id = uniqid();

$output = '';

$style_css = '<style type="text/css">'; 
$style_css .= !empty( $background_color ) ? ( '#drop-caps-'.$id.' {background-color:'.$background_color.' !important;}' ) : '';
$style_css .= !empty( $padding ) ? ( '#drop-caps-'.$id.' {padding:'.$padding.'px !important;}' ) : '';
$style_css .= !empty( $text_color ) ? ( '#drop-caps-'.$id.' {color:'.$text_color.' !important;}' ) : ''; 
$style_css .= !empty( $size ) ? ( '#drop-caps-'.$id.' {font-size:'.$size.'px !important;}' ) : ''; 
$style_css .= '</style>';

$output .= '<span id="drop-caps-'.$id.'" class="mk-dropcaps mk-shortcode '.$style.' '.$el_class.'">'.do_shortcode( strip_tags( $content ) ).'</span>';
$output .= $style_css;

echo $output;
