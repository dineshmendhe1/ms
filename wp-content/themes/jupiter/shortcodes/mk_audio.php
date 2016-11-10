<?php

extract( shortcode_atts( array(
            'mp3_file' => '',
            'ogg_file' => '',
            'audio_author' => '',
            'thumb'=> '',
            'el_class' => '',
        ), $atts ) );
wp_enqueue_script( 'jquery-jplayer' );
$output = $has_image = '';
$audio_id = uniqid();
$output .= '<script type="">
        jQuery(document).ready(function($) {

                jQuery("#jquery_jplayer_'.$audio_id.'").jPlayer({
                    ready: function () {
                        $(this).jPlayer("setMedia", {';
            if ( $mp3_file ) {
                $output .= 'mp3: "'.$mp3_file.'",';
            }
            if ( $ogg_file ) {
                $output .= 'ogg: "'.$ogg_file.'",';
            }

            $output .= ' });
                    },
                    play: function() { // To avoid both jPlayers playing together.
                        $(this).jPlayer("pauseOthers");
                    },
                    swfPath: "'.THEME_JS.'",
                    supplied: "mp3, ogg",
                    cssSelectorAncestor: "#jp_container_'.$audio_id.'",
                    wmode: "window"
                });

        })

        </script>';


        /* Random Color variations for Audio box background */
        $audio_box_color  = array( '#00c8d7', '#e1ba05', '#da4c26', '#f56a5f', '#00b89a', '#95c76a', '#acacac', '#d19760' );
        $random_colors = array_rand( $audio_box_color, 1 );

        

        $output .='<div class="mk-audio-section mk-audio-shortcode" style="background-color:'.$audio_box_color[$random_colors].'">';
        if ( $thumb ) {
            $image_src = bfi_thumb( $thumb, array('width' => 150, 'height' => 150)); 
            $output .='<img class="audio-thumb" alt="" title="" src="'.$image_src.'" />';
            $has_image = 'audio-has-img';
        }

        $output .='<div id="jquery_jplayer_'.$audio_id.'" class="jp-jplayer mk-blog-audio"></div>
            <div id="jp_container_'.$audio_id.'" class="jp-audio '.$has_image.'">
                <div class="jp-type-single">
                    <div class="jp-gui jp-interface">
                        <div class="jp-time-holder">
                            <div class="jp-current-time"></div>
                            <div class="jp-duration"></div>
                        </div>

                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                        </div>
                        <div class="jp-volume-bar">
                            <i class="mk-moon-volume-mute"></i><div class="inner-value-adjust"><div class="jp-volume-bar-value"></div></div>
                        </div>
                        <ul class="jp-controls">
                            <li><a href="javascript:;" class="jp-play" tabindex="1"><i class="mk-icon-play"></i></a></li>
                            <li><a href="javascript:;" class="jp-pause" tabindex="1"><i class="mk-icon-pause"></i></a></li>
                        </ul>';
        if ( $audio_author ) {
            $output .='<span class="mk-audio-author">'.$audio_author.'</span>';
        }
        $output .= '</div>
                    <div class="jp-no-solution">
                        <span>Update Required</span>
                        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                    </div>
                </div>
        </div>';
        $output .='<div class="clearboth"></div></div>';           
echo $output;
