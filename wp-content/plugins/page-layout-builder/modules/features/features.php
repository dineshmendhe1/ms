<?php
/*
Plugin Name: Feature Box 2
Plugin URI: #
Description: Stylish Feature Box Module
Author: Shahriar
Version: 1.0
Author URI: #
*/

class MiniMax_WPDM_FeatureBox extends WP_Widget {
    public $icons = array();
    function __construct() {
        parent::__construct( 'MiniMax_WPDM_FeatureBox', 'Feature Box', array( 'description' => 'Feature Box Module' ) );
        wp_enqueue_style('font-awesome',"//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
        $fontawesome = array('fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o','fa-user','fa-film','fa-th-large','fa-th','fa-th-list','fa-check','fa-remove','fa-close','fa-times','fa-search-plus','fa-search-minus','fa-power-off','fa-signal','fa-gear','fa-cog','fa-trash-o','fa-home','fa-file-o','fa-clock-o','fa-road','fa-download','fa-arrow-circle-o-down','fa-arrow-circle-o-up','fa-inbox','fa-play-circle-o','fa-rotate-right','fa-repeat','fa-refresh','fa-list-alt','fa-lock','fa-flag','fa-headphones','fa-volume-off','fa-volume-down','fa-volume-up','fa-qrcode','fa-barcode','fa-tag','fa-tags','fa-book','fa-bookmark','fa-print','fa-camera','fa-font','fa-bold','fa-italic','fa-text-height','fa-text-width','fa-align-left','fa-align-center','fa-align-right','fa-align-justify','fa-list','fa-dedent','fa-outdent','fa-indent','fa-video-camera','fa-photo','fa-image','fa-picture-o','fa-pencil','fa-map-marker','fa-adjust','fa-tint','fa-edit','fa-pencil-square-o','fa-share-square-o','fa-check-square-o','fa-arrows','fa-step-backward','fa-fast-backward','fa-backward','fa-play','fa-pause','fa-stop','fa-forward','fa-fast-forward','fa-step-forward','fa-eject','fa-chevron-left','fa-chevron-right','fa-plus-circle','fa-minus-circle','fa-times-circle','fa-check-circle','fa-question-circle','fa-info-circle','fa-crosshairs','fa-times-circle-o','fa-check-circle-o','fa-ban','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrow-down','fa-mail-forward','fa-share','fa-expand','fa-compress','fa-plus','fa-minus','fa-asterisk','fa-exclamation-circle','fa-gift','fa-leaf','fa-fire','fa-eye','fa-eye-slash','fa-warning','fa-exclamation-triangle','fa-plane','fa-calendar','fa-random','fa-comment','fa-magnet','fa-chevron-up','fa-chevron-down','fa-retweet','fa-shopping-cart','fa-folder','fa-folder-open','fa-arrows-v','fa-arrows-h','fa-bar-chart-o','fa-bar-chart','fa-twitter-square','fa-facebook-square','fa-camera-retro','fa-key','fa-gears','fa-cogs','fa-comments','fa-thumbs-o-up','fa-thumbs-o-down','fa-star-half','fa-heart-o','fa-sign-out','fa-linkedin-square','fa-thumb-tack','fa-external-link','fa-sign-in','fa-trophy','fa-github-square','fa-upload','fa-lemon-o','fa-phone','fa-square-o','fa-bookmark-o','fa-phone-square','fa-twitter','fa-facebook-f','fa-facebook','fa-github','fa-unlock','fa-credit-card','fa-rss','fa-hdd-o','fa-bullhorn','fa-bell','fa-certificate','fa-hand-o-right','fa-hand-o-left','fa-hand-o-up','fa-hand-o-down','fa-arrow-circle-left','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-circle-down','fa-globe','fa-wrench','fa-tasks','fa-filter','fa-briefcase','fa-arrows-alt','fa-group','fa-users','fa-chain','fa-link','fa-cloud','fa-flask','fa-cut','fa-scissors','fa-copy','fa-files-o','fa-paperclip','fa-save','fa-floppy-o','fa-square','fa-navicon','fa-reorder','fa-bars','fa-list-ul','fa-list-ol','fa-strikethrough','fa-underline','fa-table','fa-magic','fa-truck','fa-pinterest','fa-pinterest-square','fa-google-plus-square','fa-google-plus','fa-money','fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right','fa-columns','fa-unsorted','fa-sort','fa-sort-down','fa-sort-desc','fa-sort-up','fa-sort-asc','fa-envelope','fa-linkedin','fa-rotate-left','fa-undo','fa-legal','fa-gavel','fa-dashboard','fa-tachometer','fa-comment-o','fa-comments-o','fa-flash','fa-bolt','fa-sitemap','fa-umbrella','fa-paste','fa-clipboard','fa-lightbulb-o','fa-exchange','fa-cloud-download','fa-cloud-upload','fa-user-md','fa-stethoscope','fa-suitcase','fa-bell-o','fa-coffee','fa-cutlery','fa-file-text-o','fa-building-o','fa-hospital-o','fa-ambulance','fa-medkit','fa-fighter-jet','fa-beer','fa-h-square','fa-plus-square','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-double-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-angle-down','fa-desktop','fa-laptop','fa-tablet','fa-mobile-phone','fa-mobile','fa-circle-o','fa-quote-left','fa-quote-right','fa-spinner','fa-circle','fa-mail-reply','fa-reply','fa-github-alt','fa-folder-o','fa-folder-open-o','fa-smile-o','fa-frown-o','fa-meh-o','fa-gamepad','fa-keyboard-o','fa-flag-o','fa-flag-checkered','fa-terminal','fa-code','fa-mail-reply-all','fa-reply-all','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-location-arrow','fa-crop','fa-code-fork','fa-unlink','fa-chain-broken','fa-question','fa-info','fa-exclamation','fa-superscript','fa-subscript','fa-eraser','fa-puzzle-piece','fa-microphone','fa-microphone-slash','fa-shield','fa-calendar-o','fa-fire-extinguisher','fa-rocket','fa-maxcdn','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-circle-down','fa-html5','fa-css3','fa-anchor','fa-unlock-alt','fa-bullseye','fa-ellipsis-h','fa-ellipsis-v','fa-rss-square','fa-play-circle','fa-ticket','fa-minus-square','fa-minus-square-o','fa-level-up','fa-level-down','fa-check-square','fa-pencil-square','fa-external-link-square','fa-share-square','fa-compass','fa-toggle-down','fa-caret-square-o-down','fa-toggle-up','fa-caret-square-o-up','fa-toggle-right','fa-caret-square-o-right','fa-euro','fa-eur','fa-gbp','fa-dollar','fa-usd','fa-rupee','fa-inr','fa-cny','fa-rmb','fa-yen','fa-jpy','fa-ruble','fa-rouble','fa-rub','fa-won','fa-krw','fa-bitcoin','fa-btc','fa-file','fa-file-text','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-thumbs-up','fa-thumbs-down','fa-youtube-square','fa-youtube','fa-xing','fa-xing-square','fa-youtube-play','fa-dropbox','fa-stack-overflow','fa-instagram','fa-flickr','fa-adn','fa-bitbucket','fa-bitbucket-square','fa-tumblr','fa-tumblr-square','fa-long-arrow-down','fa-long-arrow-up','fa-long-arrow-left','fa-long-arrow-right','fa-apple','fa-windows','fa-android','fa-linux','fa-dribbble','fa-skype','fa-foursquare','fa-trello','fa-female','fa-male','fa-gittip','fa-gratipay','fa-sun-o','fa-moon-o','fa-archive','fa-bug','fa-vk','fa-weibo','fa-renren','fa-pagelines','fa-stack-exchange','fa-arrow-circle-o-right','fa-arrow-circle-o-left','fa-toggle-left','fa-caret-square-o-left','fa-dot-circle-o','fa-wheelchair','fa-vimeo-square','fa-turkish-lira','fa-try','fa-plus-square-o','fa-space-shuttle','fa-slack','fa-envelope-square','fa-wordpress','fa-openid','fa-institution','fa-bank','fa-university','fa-mortar-board','fa-graduation-cap','fa-yahoo','fa-google','fa-reddit','fa-reddit-square','fa-stumbleupon-circle','fa-stumbleupon','fa-delicious','fa-digg','fa-pied-piper','fa-pied-piper-alt','fa-drupal','fa-joomla','fa-language','fa-fax','fa-building','fa-child','fa-paw','fa-spoon','fa-cube','fa-cubes','fa-behance','fa-behance-square','fa-steam','fa-steam-square','fa-recycle','fa-automobile','fa-car','fa-cab','fa-taxi','fa-tree','fa-spotify','fa-deviantart','fa-soundcloud','fa-database','fa-file-pdf-o','fa-file-word-o','fa-file-excel-o','fa-file-powerpoint-o','fa-file-photo-o','fa-file-picture-o','fa-file-image-o','fa-file-zip-o','fa-file-archive-o','fa-file-sound-o','fa-file-audio-o','fa-file-movie-o','fa-file-video-o','fa-file-code-o','fa-vine','fa-codepen','fa-jsfiddle','fa-life-bouy','fa-life-buoy','fa-life-saver','fa-support','fa-life-ring','fa-circle-o-notch','fa-ra','fa-rebel','fa-ge','fa-empire','fa-git-square','fa-git','fa-hacker-news','fa-tencent-weibo','fa-qq','fa-wechat','fa-weixin','fa-send','fa-paper-plane','fa-send-o','fa-paper-plane-o','fa-history','fa-genderless','fa-circle-thin','fa-header','fa-paragraph','fa-sliders','fa-share-alt','fa-share-alt-square','fa-bomb','fa-soccer-ball-o','fa-futbol-o','fa-tty','fa-binoculars','fa-plug','fa-slideshare','fa-twitch','fa-yelp','fa-newspaper-o','fa-wifi','fa-calculator','fa-paypal','fa-google-wallet','fa-cc-visa','fa-cc-mastercard','fa-cc-discover','fa-cc-amex','fa-cc-paypal','fa-cc-stripe','fa-bell-slash','fa-bell-slash-o','fa-trash','fa-copyright','fa-at','fa-eyedropper','fa-paint-brush','fa-birthday-cake','fa-area-chart','fa-pie-chart','fa-line-chart','fa-lastfm','fa-lastfm-square','fa-toggle-off','fa-toggle-on','fa-bicycle','fa-bus','fa-ioxhost','fa-angellist','fa-cc','fa-shekel','fa-sheqel','fa-ils','fa-meanpath','fa-buysellads','fa-connectdevelop','fa-dashcube','fa-forumbee','fa-leanpub','fa-sellsy','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-cart-plus','fa-cart-arrow-down','fa-diamond','fa-ship','fa-user-secret','fa-motorcycle','fa-street-view','fa-heartbeat','fa-venus','fa-mars','fa-mercury','fa-transgender','fa-transgender-alt','fa-venus-double','fa-mars-double','fa-venus-mars','fa-mars-stroke','fa-mars-stroke-v','fa-mars-stroke-h','fa-neuter','fa-facebook-official','fa-pinterest-p','fa-whatsapp','fa-server','fa-user-plus','fa-user-times','fa-hotel','fa-bed','fa-viacoin','fa-train','fa-subway','fa-medium');
        $this->icons = $fontawesome;
    }

    function widget( $args, $instance ) {
        wp_enqueue_style('minimax-feature-box', base_theme_url.'/modules/features/features.css');
        $style =  $instance['style'] ;
        $fn = "feature_box_{$style}";
        $this->{$fn}($args, $instance);
    }
    
    function feature_box_simple($args, $instance){
        extract( $args );       
        extract( $instance );
        
        echo isset($before_widget) ? $before_widget : '';
        ?>
        <div class="minimax-feature-box" <?php if( isset($text_align) ) echo 'style="text-align:'.$text_align.';"'; ?>>
            <span class="minimax-feature-icon" style="color:<?php echo $iconcolor; ?>;">
                <i class="fa <?php echo $icon; ?>"></i>
            </span>
            <h3 class="minimax-feature-name">
                <?php
                if( isset($link) && $link != '' )
                    echo '<a class="" href="'.$link.'">'.$title.'</a>';
                else
                    echo $title;
                ?>
            </h3>
            <p class="minimax-feature-des"><?php echo $desc; ?></p>
        </div>
        
        <?php
        echo isset($after_widget) ? $after_widget : '';
    }

    function feature_box_bordered($args, $instance){
        extract( $args );
        extract( $instance );

        $tagged = (isset($tag) && $tag != '') ? 'tagged' : '';
        $id = (isset($id) && $id != '') ? 'mfb-'.$id : '';
        $tag_bg = (isset($iconcolor) && $iconcolor != '') ? $iconcolor : '#1ec0c9';

        echo isset($before_widget) ? $before_widget : '';
        ?>
        <style>
            <?php echo '.'.$id; ?>.minimax-feature-box-bordered.tagged:after{
                content: '<?php echo $tag; ?>';
                background: <?php echo $tag_bg; ?>;
            }
            <?php echo '.'.$id; ?>.minimax-feature-box-bordered{
                border: 1px solid <?php echo $tag_bg; ?>;
            }
        </style>
        <?php if( isset($link) && $link != '' ) echo '<a class="" href="'.$link.'">'; ?>
        <div class="minimax-feature-box-bordered <?php echo $tagged.' '.$id; ?>">
            <span class="minimax-feature-icon" style="color:<?php echo $iconcolor; ?>;">
                <i class="fa <?php echo $icon; ?>"></i>
            </span>
            <h3 class="minimax-feature-name"> <?php echo $title; ?></h3>
            <p class="minimax-feature-des"><?php echo $desc; ?></p>
        </div>
        <?php if( isset($link) && $link != '' ) echo '</a>'; ?>

        <?php
        echo isset($after_widget) ? $after_widget : '';
    }


    function update( $new_instance, $old_instance ) {
        $instance = $new_instance;       
        return $instance;
    }

    function form( $instance ) {
        if ( $instance ) {
            extract($instance);
        }
        ?>
        <style type="text/css">
            label.xdicon{
                float: left;
                min-width: 35px !important;
                max-width: 35px !important;
                min-height: 30px !important;
                max-height: 30px !important;
                text-align: center;
                line-height: 16px;
                font-size: 14px;
                padding:5px 7px;
                display: inline-table;
                border: 1px solid #dddddd;
                border-radius: 2px;
                transition: all 0.3s ease-in-out;
                margin: 3px;
            }
            label.xdicon i{
                width: 16px !important;
            }
            .xdicon:hover{
                border: 1px solid #1E8CBE;
                transition: all 0.3s ease-in-out;
            }
            .xdicon.active{
                border: 1px solid #1E8CBE;
                background: #1E8CBE;
                color: #ffffff;
                transition: all 0.3s ease-in-out;
            }
            #tabpane .row{
                margin-bottom: 20px;
            }
        </style>
        <script>
            jQuery(function($){
                $('.xdicon').click(function(){
                    $('.xdicon').removeClass('active');
                    $(this).addClass('active');
                });
            });
	        jQuery(document).ready(function($) {
		        $('.myclrpkr').wpColorPicker();
	        });
        </script>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
        
        <div id="tabpane">
            <input id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="hidden" value="<?php echo uniqid(); ?>" />
            <p>
                <label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Category Icon'); ?></label>
                <div style="height: 200px;padding: 10px;border: 1px solid #ddd">
                    <div style="height: 180px;overflow: auto;">
                        <div style="clear: both"></div>
                        <?php
                        if(isset($icon) && $icon != '')
                            echo "<label class='xdicon active' title='{$icon}'><input style='display:none;' type=radio name='".$this->get_field_name('icon')."' value='{$icon}' checked=checked ><i class='fa {$icon}'></i></label>";
                        foreach($this->icons  as $dx => $class){
                            if($icon != $class)
                                echo "<label class='xdicon' title='$class'><i class='fa $class'></i><input style='display:none' type='radio' name='".$this->get_field_name('icon')."' value='{$class}' ".checked($class, $icon, false)." title='{$class}' /></label>";
                        }
                        ?>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </p>

            <div class="row">
                <div class="col-md-6">
                    <p>
                        <label for="<?php echo $this->get_field_id('iconcolor'); ?>"><?php _e('Feature Icon Color'); ?></label><br/>
                        <input type="text" class="myclrpkr" name="<?php echo $this->get_field_name('iconcolor'); ?>" value="<?php echo $iconcolor?$iconcolor:'#1fc670'; ?>" />
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        <label for="<?php echo $this->get_field_id('hcolor'); ?>"><?php _e('Feature Title Color'); ?></label><br/>
                        <input type="text" class="myclrpkr" name="<?php echo $this->get_field_name('hcolor'); ?>" value="<?php echo $hcolor?$hcolor:'#777777'; ?>" />
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p>
                        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Feature Title'); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                    </p>
                </div>
                <div class="col-md-6">
                    <p>

                    </p>
                </div>
            </div>

            <p>
                <label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Feature Description'); ?></label>
                <textarea rows="5" class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" ><?php echo $desc; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Feature Box Template'); ?></label>
                <select class="widefat fbstyle" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="text">
                    <option value="simple" <?php if($style=='simple') echo 'selected=selected'; ?> >Simple</option>
                    <option value="bordered" <?php if($style=='bordered') echo 'selected=selected'; ?> >Bordered</option>
                </select>
            </p>

            <div class="text-align hidden">
                <label for="<?php echo $this->get_field_id('text_align'); ?>"><?php _e('Text Align'); ?></label>
                <select class="rsbg" id="<?php echo $this->get_field_id('text_align'); ?>" name="<?php echo $this->get_field_name('text_align'); ?>" type="text">
                    <option value="left" <?php if($text_align=='left') echo 'selected=selected'; ?> >Left</option>
                    <option value="center" <?php if($text_align=='center') echo 'selected=selected'; ?> >Center</option>
                    <option value="right" <?php if($text_align=='right') echo 'selected=selected'; ?> >Right</option>
                </select>
            </div>

            <div class="bordered hidden">
                <p>
                    <label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Tag/Banner Text for Bordered Box'); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $tag; ?>" />
                </p>
            </div>

            <script>
                // Simple
                var ta = jQuery('.fbstyle').val();
                if(ta == 'simple') jQuery('.text-align').removeClass('hidden');
                jQuery('.fbstyle').on('change', function(){
                    var ta = jQuery('.fbstyle').val();
                    if(ta == 'simple')
                        jQuery('.text-align').removeClass('hidden');
                    else
                        jQuery('.text-align').addClass('hidden');
                });
                // Bordered
                var ta = jQuery('.fbstyle').val();
                if(ta == 'bordered') jQuery('.bordered').removeClass('hidden');
                jQuery('.fbstyle').on('change', function(){
                    var ta = jQuery('.fbstyle').val();
                    if(ta == 'bordered')
                        jQuery('.bordered').removeClass('hidden');
                    else
                        jQuery('.bordered').addClass('hidden');
                });
            </script>

        </div>
   
        <?php 
    }

}

add_action( 'widgets_init', create_function( '', 'register_widget("MiniMax_WPDM_FeatureBox");' ) );