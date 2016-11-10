// //////////////////////////////////////////////////////
// 
//  Polyfills for legacy browsers
// 
// ///////////////////////////////////////////////////

// .bind() 
if (!Function.prototype.bind) { 
  Function.prototype.bind = function(oThis) {
    if (typeof this !== 'function') {
      // closest thing possible to the ECMAScript 5
      // internal IsCallable function
      throw new TypeError('Function.prototype.bind - what is trying to be bound is not callable');
    }

    var aArgs   = Array.prototype.slice.call(arguments, 1),
        fToBind = this,
        fNOP    = function() {},
        fBound  = function() {
          return fToBind.apply(this instanceof fNOP && oThis
                 ? this
                 : oThis,
                 aArgs.concat(Array.prototype.slice.call(arguments)));
        };

    fNOP.prototype = this.prototype;
    fBound.prototype = new fNOP();

    return fBound;
  };
}

// RequestAnimationFrame
var rafPolyfill = function() {
    var lastTime, vendors, x;
    lastTime = 0;
    vendors = ["webkit", "moz"];
    x = 0;
    while (x < vendors.length && !window.requestAnimationFrame) {
      window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
      window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] || window[vendors[x] + "CancelRequestAnimationFrame"];
      ++x;
    }
    if (!window.requestAnimationFrame) {
      window.requestAnimationFrame = function(callback, element) {
        var currTime, id, timeToCall;
        currTime = new Date().getTime();
        timeToCall = Math.max(0, 16 - (currTime - lastTime));
        id = window.setTimeout(function() {
          callback(currTime + timeToCall);
        }, timeToCall);
        lastTime = currTime + timeToCall;
        return id;
      };
    }
    if (!window.cancelAnimationFrame) {
      window.cancelAnimationFrame = function(id) {
        clearTimeout(id);
      };
    }
};
rafPolyfill();


// //////////////////////////////////////////////////////
// 
//  Helpers
// 
// ///////////////////////////////////////////////////

function is_touch_device() {
    return ('ontouchstart' in document.documentElement);
}
jQuery.exists = function(selector) {
    return (jQuery(selector).length > 0);
};

var isMobile = {
Android: function() {
    var check;
    if(navigator.userAgent.match(/Android/i)) check = true;
    return check;
},
BlackBerry: function() {
    var check;
    if(navigator.userAgent.match(/BlackBerry/i)) check = true;
    return check;
},
iOS: function() {
    var check;
    if(navigator.userAgent.match(/iPhone|iPad|iPod/i)) check = true;
    return check;
},
Opera: function() {
    var check;
    if(navigator.userAgent.match(/Opera Mini/i)) check = true;
    return check;
},
Windows: function() {
    var check;
    if(navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i)) check = true;
    return check;
},
any: function() {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
}
};



//////////////////////////////////////////////////////////////////////////
//
//   Defining global variables for cross app use
//
//////////////////////////////////////////////////////////////////////////

var scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop, // Updated in global event handler
    global_window_width = $(window).width(),
    global_window_height = $(window).height(),
    global_admin_bar,
    global_admin_bar_height = 0;


$(window).load(function() {
    if ($.exists("#wpadminbar")) {
        global_admin_bar = $("#wpadminbar");
        global_admin_bar_height = global_admin_bar.height();
    } 
});
        
function mk_update_globals() {
    global_window_width = $(window).width();
    global_window_height = $(window).height();
}

window.scroll = function() {
        scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
}

//////////////////////////////////////////////////////////////////////////
//
//   Global scroll handler
//
//////////////////////////////////////////////////////////////////////////


var animationThrottle = function(toThrottle, wait) {
    var lastTick = Date.now(),
        endTimeout = null;
    
    return function run() {
        if(Date.now() - lastTick > wait) {
            lastTick = Date.now();
            clearTimeout(endTimeout);
            window.requestAnimationFrame(toThrottle);
        }
        else {
            clearTimeout(endTimeout);
            endTimeout = setTimeout(run, wait);
        }
    };
};

// rAF 
var scrollAnimations = {
    sets: [],

    init: function() {
        this.update();
        this.attachEvents();
        // console.table(this.sets);
    },

    attachEvents: function() {
        window.addEventListener('scroll', animationThrottle(
            this.play.bind(this), 0
        ));
    },

    add: function(handler) {
        this.sets.push(handler);
    },

    play: function() {
        this.update();
        this.sets.forEach( function(animationSet) {
            animationSet(scrollY);
        }.bind(this));
    },

    update: function() {
        scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
    },

};
scrollAnimations.init();

// Throttled 200 ms
var debouncedScrollAnimations = {
    sets: [],

    init: function() {
        this.attachEvents();
    },

    attachEvents: function() {
        window.addEventListener('scroll', animationThrottle(
            this.play.bind(this), 200
        ));
    },

    add: function(handler) {
        this.sets.push(handler);
    },

    play: function() {
        this.sets.forEach( function(animationSet) {
            animationSet(scrollY);
        }.bind(this));
    },
};
debouncedScrollAnimations.init();


//////////////////////////////////////////////////////////////////////////
//
//   Init all scripts
//
//////////////////////////////////////////////////////////////////////////


$(document).ready(function() {
    mk_animated_contents();
    mk_lightbox_init();
    mk_login_form();
    mk_backgrounds_parallax();
    mk_flexslider_init();
    mk_event_countdown();
    mk_skill_meter();
    mk_charts();
    mk_milestone();
    mk_ajax_search();
    mk_hover_events();
    mk_portfolio_ajax();
    mk_love_post();
    product_loop_add_cart();
    mk_social_share();
    mk_section_intro_effects();
    mk_newspaper_comments_share();
    mk_responsive_fix();
    loop_audio_init();
    mk_portfolio_widget();
    mk_contact_form();
    mk_blog_carousel();
    mk_responsive_nav();
    mk_header_searchform();
    mk_click_events();
    mk_theme_toggle_box();
    mk_google_maps();
    mk_google_maps_height();
    mk_portfolio_animations();
    mk_text_typer();
    mk_tab_slider();
    mk_one_page_scroller();
    mk_one_pager_resposnive();
    mk_sidebar_navigation();
    mk_clients_mobile();
});

$(window).load(function() {
    mk_edge_slider_init();
    mk_edge_slider_resposnive();
    mk_edge_parallax();
    mk_smooth_scroll_events();
    mk_swipe_slider();
    mk_load_isotop_enabled_scripts();
    mk_animated_columns();
    mk_unfold_footer();
    mk_blur_boxes();
    shop_isotop_init();
    mk_tabs();
    mk_accordion_toggles_tooltip();
    section_to_full_height();
    mk_page_title_parallax();
    mk_header_scripts();
    mk_parallax();
    mk_gallery();
    mk_edge_fullpage_pagination();
    mk_theatre_responsive_calculator();
    mk_mobile_tablet_responsive_calculator();
    mk_tabs_responsive();
});


$(window).on("debouncedresize", function() {
    section_to_full_height();
    mk_responsive_fix();
    mk_google_maps_height();
    mk_page_title_parallax();
    mk_section_intro_effects();
    mk_theatre_responsive_calculator();
    mk_mobile_tablet_responsive_calculator();
    mk_tabs_responsive();

    setTimeout(function() {
        mk_edge_slider_resposnive();
        mk_one_pager_resposnive();
        mk_header_scripts();
        mk_unfold_footer();
    }, 300);

});

new ChopScroll(function() {
    mk_skill_meter();
    mk_charts();
    mk_milestone();
}, 200);

//  if (is_touch_device() || $(window).width() < 780) {
if (is_touch_device()) {
    $('body').addClass('no-transform');
    $('.mk-animate-element').removeClass('mk-animate-element');
} else {
    choppedjs.onResize(mk_animated_contents, 2000);
    choppedjs.onScroll(mk_animated_contents, 700);
}


/* Typer */
/* -------------------------------------------------------------------- */
function mk_text_typer() {

    "use strict";

    $('[data-typer-targets]').each(function() {
        var $this = $(this),
            $first_string = [$this.text()],
            $rest_strings = $this.attr('data-typer-targets').split(','),
            $strings = $first_string.concat($rest_strings);

        $this.text('');

        $this.typed({
            strings: $strings,
            typeSpeed: 30, // typing speed
            backDelay: 1200, // pause before backspacing
            loop: true, // loop on or off (true or false)
            loopCount: false, // number of loops, false = infinite
        });
    });
}



/* Tab Slider */
/* -------------------------------------------------------------------- */

function mk_tab_slider() {

    "use strict";

    $('.mk-tab-slider').each(function() {

        var $this = $(this),
            id = $this.data('id'),
            $autoplayTime = $this.data('autoplay');

        var mk_tab_slider = $(this).swiper({
            wrapperClass: 'mk-tab-slider-wrapper',
            slideClass: 'mk-tab-slider-item',
            calculateHeight: true,
            speed: 500,
            autoplay: $autoplayTime,
            onSlideChangeStart: function() {
                $('.mk-tab-slider-nav[data-id="' + id + '"]').find(".active").removeClass('active')
                $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").eq(mk_tab_slider.activeIndex).addClass('active')
            }
        });

        $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").first().addClass('active');

        $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").on('touchstart mousedown', function(e) {
            e.preventDefault()
            $('.mk-tab-slider-nav[data-id="' + id + '"]').find(".active").removeClass('active')
            $(this).addClass('active')
            mk_tab_slider.swipeTo($(this).index())
        });

        $('.mk-tab-slider-nav[data-id="' + id + '"]').find("a").click(function(e) {
            e.preventDefault()
        });

    });

}



/* Edge One Pager */
/* -------------------------------------------------------------------- */
function mk_one_page_scroller() {

    "use strict";

    $('.mk-edge-one-pager').each(function() {

        var $this = $(this),
            $tooltip_txt = [];

        $this.find('.section').each(function() {
            $tooltip_txt.push($(this).attr('data-title'));
        });

        $this.fullpage({
            verticalCentered: false,
            resize: true,
            slidesColor: ['#ccc', '#fff'],
            anchors: $tooltip_txt,
            scrollingSpeed: 600,
            easing: 'easeInQuart',
            menu: false,
            navigation: true,
            navigationPosition: 'right',
            navigationTooltips: false,
            slidesNavigation: true,
            slidesNavPosition: 'bottom',
            loopBottom: false,
            loopTop: false,
            loopHorizontal: true,
            autoScrolling: true,
            scrollOverflow: false,
            css3: true,
            paddingTop: 0,
            paddingBottom: 0,
            normalScrollElements: '#mk-header, #mk-responsive-wrap',
            normalScrollElementTouchThreshold: 5,
            keyboardScrolling: true,
            touchSensitivity: 15,
            continuousVertical: false,
            animateAnchor: true,

            onLeave: function(index, nextIndex, direction) {

                $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($this.find('.one-pager-slide').eq(nextIndex - 1).attr('data-header-skin') + '-header-skin');
                $('#fullPage-nav').removeClass('light-skin dark-skin').addClass($this.find('.one-pager-slide').eq(nextIndex - 1).attr('data-header-skin') + '-skin');

            },
            afterRender: function() {
                setTimeout(function() {
                    $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($this.find('.one-pager-slide').eq(0).attr('data-header-skin') + '-header-skin');
                    $('#fullPage-nav').removeClass('light-skin dark-skin').addClass($this.find('.one-pager-slide').eq(0).attr('data-header-skin') + '-skin');
                }, 300);

            },
        });

        var eventtype = mobilecheck() ? 'touchstart' : 'click';
        $('.mk-nav-responsive-link').on(eventtype, function() {
            if ($('body').hasClass('mk-opened-nav')) {
                $.fn.fullpage.setAutoScrolling(false);
                $('body, html').css({ 'overflow' : 'hidden'});
            } else {
                $.fn.fullpage.setAutoScrolling(true);
            }    
        });
    });

}


function mk_one_pager_resposnive() {

    "use strict";

    $('.mk-edge-one-pager').each(function() {
        var $this = $(this),
            $header_height = 0;

        var $window_height = $(window).outerHeight();

        if ($(window).width() <= 1165) {
            $header_height = $('#mk-header').data('height');
        }

        $this.find('.one-pager-slide').each(function() {


            var $this = $(this),
                $content = $this.find('.edge-slide-content');

            if ($this.hasClass('left_center') || $this.hasClass('center_center') || $this.hasClass('right_center')) {

                var $this_height_half = $content.outerHeight() / 2,
                    $window_half = $window_height / 2;

                $content.css('marginTop', ($window_half - $this_height_half - $header_height / 2));
            }

            if ($this.hasClass('left_bottom') || $this.hasClass('center_bottom') || $this.hasClass('right_bottom')) {

                var $distance_from_top = $window_height - $content.outerHeight() - 90;

                $content.css('marginTop', ($distance_from_top));
            }

        });
    });

}

/* Gets IE version */
/* -------------------------------------------------------------------- */

function mk_detect_ie() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');
    if (msie > 0) {
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }
    if (trident > 0) {
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }
    return false;
}



/* Greensock wrapper for animation scroll */
/* -------------------------------------------------------------------- */

function tweenScroll(startPoint, endPoint, tweenName, endFix) {
    progressVal = (1 / (endPoint - startPoint) * (scrollY - startPoint));

    if (progressVal >= 0 && progressVal <= 1) {
        tweenName.progress(progressVal * endFix);
        // TweenLite.to(tweenName, 0, {progress:progressVal * endFix, ease:Linear.easeNone}); // also cool with ease:Strong.easeOut
        TweenLite.set(tweenName, {
            progress: progressVal * endFix
        });
        // console.log(progressVal);
    }

}

/* Image Gallery */
/* -------------------------------------------------------------------- */

function mk_gallery() {

    "use strict";

    $('.mk-gallery-shortcode .mk-gallery-item.hover-overlay_layer .item-holder').each(function() {
        var itemHolder = $(this),
            galleryDesc = itemHolder.find('.gallery-desc');

        function updatePosition() {
            var parentHeight = itemHolder.outerHeight(),
                contentHeight = galleryDesc.outerHeight();

            var paddingVal = (parentHeight - contentHeight) / 2;
            galleryDesc.css({
                'padding-top': paddingVal,
                'padding-bottom': paddingVal
            });
        }
        updatePosition();

        $(window).on('debouncedresize', function() {
            updatePosition();
        });
    });
}


/* Page Title Box */
/* -------------------------------------------------------------------- */


function mk_page_title_parallax() {

    "use strict";

    if (!is_touch_device()) {
        $('.mk-effect-wrapper').each(function() {

            var progressVal,
                currentPoint,
                ticking = false,
                scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
                $this = $(this),
                $window = $(window),
                windowHeight = $(window).height(),
                parentHeight = $this.outerHeight(),
                startPoint = 0,
                endPoint = $this.offset().top + parentHeight,
                effectLayer = $this.find('.mk-effect-bg-layer'),
                gradientLayer = effectLayer.find('.mk-effect-gradient-layer'),
                cntLayer = $this.find('.mk-page-title-box-content'),
                animation = effectLayer.attr('data-effect'),
                top = $this.offset().top,
                height = $this.outerHeight();

            var parallaxSpeed = .7,
                zoomFactor = 1.3;

            var animationSet = function() {
                scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;

                if (animation == "parallax") {
                    currentPoint = (startPoint + scrollY) * parallaxSpeed;
                    effectLayer.css({
                        'transform': 'translateY(' + currentPoint + 'px)'
                    });
                }

                if (animation == "parallaxZoomOut") {
                    currentPoint = (startPoint + scrollY) * parallaxSpeed;
                    progressVal = (1 / (endPoint - startPoint) * (scrollY - startPoint));
                    var zoomCalc = zoomFactor - ((zoomFactor - 1) * progressVal);

                    effectLayer.css({
                        'transform': 'translateY(' + currentPoint + 'px), scale(' + zoomCalc + ')'
                    });
                }

                if (animation == "gradient") {
                    progressVal = (1 / (endPoint - startPoint) * (scrollY - startPoint));
                    gradientLayer.css({
                        opacity: progressVal * 2
                    });
                }

                if (animation != "gradient") {
                    progressVal = (1 / (endPoint - startPoint) * (scrollY - startPoint));
                    cntLayer.css({
                        opacity: 1 - (progressVal * 4)
                    });
                }

                // Stop ticking
                ticking = false;
            }
            animationSet();

            // This will limit the calculation of the background position to
            // 60fps as well as blocking it from running multiple times at once
            var requestTick = function() {
                if (!ticking) {
                    requestAnimationFrame(animationSet);
                    ticking = true;
                }
            };


            // RequestAnimationFrame polyfill for older browsers
            var rafPolyfill = function() {
                var lastTime = 0;
                var vendors = ['ms', 'moz', 'webkit', 'o'];
                for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
                    window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
                    window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
                }
                if(!window.requestAnimationFrame)
                    window.requestAnimationFrame = function (callback, element) {
                      var currTime = new Date().getTime();
                      var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                      var id = window.setTimeout(function () {
                        callback(currTime + timeToCall);
                      },
                      timeToCall);
                      lastTime = currTime + timeToCall;
                      return id;
                    };
                if(!window.cancelAnimationFrame)
                    window.cancelAnimationFrame = function (id) {
                        clearTimeout(id);
                    };
            };
            rafPolyfill();

            $window.on('scroll', requestTick);

        });
    }
}



/* Adds pagination style in fullpage Edge Slider */
/* -------------------------------------------------------------------- */
function mk_edge_fullpage_pagination() {

    "use strict";

    var style = $('#fullpage').attr('data-pagination');
    // console.log(style);
    $('#fullPage-nav').addClass('pagination-' + style);
}



/* Clients Shortcode mobile fix */
/* -------------------------------------------------------------------- */
function mk_clients_mobile() {

    "use strict";

    $('.mk-clients-shortcode.column-style').each(function() {
        var group = $(this),
            list = group.find('li'),
            listStyle = group.find('ul').attr('style'),
            fullRowColumns = group.find('ul:first-of-type li').length,

            viewport = $(window),
            viewportWidth = viewport.innerWidth(),
            breakPoint1 = 960 - 25,
            breakPoint2 = 767 - 25,
            breakPoint3 = 550 - 25;

        function recreateGrid() {
            if (viewportWidth > breakPoint1) {
                list.unwrap();
                for (var i = 0; i < list.length; i += fullRowColumns) {
                    list.slice(i, i + fullRowColumns)
                        .wrapAll('<ul style="' + listStyle + '"></ul>');
                }
            } else if (viewportWidth < breakPoint3) {
                list.unwrap();
                for (var i = 0; i < list.length; i += 1) {
                    list.slice(i, i + 1).wrapAll('<ul class="mk-clients-fixed-list" style="' + listStyle + '"></ul>');
                }
            } else if (viewportWidth < breakPoint2) {
                list.unwrap();
                for (var i = 0; i < list.length; i += 2) {
                    list.slice(i, i + 2).wrapAll('<ul class="mk-clients-fixed-list" style="' + listStyle + '"></ul>');
                }
            } else if (viewportWidth < breakPoint1) {
                list.unwrap();
                for (var i = 0; i < list.length; i += 3) {
                    list.slice(i, i + 3).wrapAll('<ul class="mk-clients-fixed-list" style="' + listStyle + '"></ul>');
                }
            }
        }
        recreateGrid();

        $(window).on('debouncedresize', function() {
            viewportWidth = viewport.innerWidth();
            recreateGrid();
        });

    });
}



/* Theatre Slider Responsive Calculator */
/* -------------------------------------------------------------------- */

function mk_theatre_responsive_calculator() {
    var $laptopContainer = $(".laptop-theatre-slider");
    var $computerContainer = $(".computer-theatre-slider");
    $laptopContainer.each(function() {
        var $this = $(this),
            $window = $(window),
            $windowWidth = $window.outerWidth(),
            $windowHeight = $window.outerHeight(),
            $width = $this.outerWidth(),
            $height = $this.outerHeight(),
            $paddingTop = 38,
            $paddingRight = 143,
            $paddingBottom = 78, 
            $paddingLeft = 143;

        var $player = $this.find('.player-container');

        if ($windowWidth > $width) {
            $player.css({
                'padding-left': ($width * $paddingLeft) / 1200,
                'padding-right': ($width * $paddingRight) / 1200,
                'padding-top': ($height * $paddingTop) / 690,
                'padding-bottom': ($height * $paddingBottom) / 690,
            });
        }

    });

    $computerContainer.each(function() {
        var $this = $(this),
            $window = $(window),
            $windowWidth = $window.outerWidth(),
            $windowHeight = $window.outerHeight(),
            $width = $this.outerWidth(),
            $height = $this.outerHeight(),
            $paddingTop = 60,
            $paddingRight = 52,
            $paddingBottom = 290, 
            $paddingLeft = 49;

        var $player = $this.find('.player-container');

        if ($windowWidth > $width) {
            $player.css({
                'padding-left': ($width * $paddingLeft) / 1200,
                'padding-right': ($width * $paddingRight) / 1200,
                'padding-top': ($height * $paddingTop) / 969,
                'padding-bottom': ($height * $paddingBottom) / 969,
            });
        }

    });

}

/* Mobile and Tablet Slideshow Responsive Calculator */
    /* -------------------------------------------------------------------- */
    function mk_mobile_tablet_responsive_calculator() {
        var $laptopSlideshow = $(".mk-laptop-slideshow-shortcode");
        var $lcdSlideshow = $(".mk-lcd-slideshow");

        if ($.exists(".mk-laptop-slideshow-shortcode")) {
            $laptopSlideshow.each(function() {
                var $this = $(this),
                    $window = $(window),
                    $windowWidth = $window.outerWidth(),
                    $windowHeight = $window.outerHeight(),
                    $width = $this.outerWidth(),
                    $height = $this.outerHeight(),
                    $paddingTop = 28,
                    $paddingRight = 102,
                    $paddingBottom = 52,
                    $paddingLeft = 102;

                var $player = $this.find(".slideshow-container");

                $player.css({
                    "padding-left": ($width * $paddingLeft) / 836,
                    "padding-right": ($width * $paddingRight) / 836,
                    "padding-top": ($height * $paddingTop) / 481,
                    "padding-bottom": ($height * $paddingBottom) / 481,
                });

            });
        }

        if ($.exists(".mk-lcd-slideshow")) {
            $lcdSlideshow.each(function() {
                var $this = $(this),
                    $window = $(window),
                    $windowWidth = $window.outerWidth(),
                    $windowHeight = $window.outerHeight(),
                    $width = $this.outerWidth(),
                    $height = $this.outerHeight(),
                    $paddingTop = 35,
                    $paddingRight = 39,
                    $paddingBottom = 213,
                    $paddingLeft = 36;

                var $player = $this.find(".slideshow-container");
                $player.css({
                    "padding-left": ($width * $paddingLeft) / 886,
                    "padding-right": ($width * $paddingRight) / 886,
                    "padding-top": ($height * $paddingTop) / 713,
                    "padding-bottom": ($height * $paddingBottom) / 713,
                });
            });
        }
    }

;/* Blog, Portfolio Audio */
/* -------------------------------------------------------------------- */

function loop_audio_init() {
  if ($.exists('.jp-jplayer')) {
    $('.jp-jplayer.mk-blog-audio').each(function () {
      var css_selector_ancestor = "#" + $(this).siblings('.jp-audio').attr('id');
      var ogg_file, mp3_file, mk_theme_js_path;
      ogg_file = $(this).attr('data-ogg');
      mp3_file = $(this).attr('data-mp3');
      $(this).jPlayer({
        ready: function () {
          $(this).jPlayer("setMedia", {
            mp3: mp3_file,
            ogg: ogg_file
          });
        },
        play: function () { // To avoid both jPlayers playing together.
          $(this).jPlayer("pauseOthers");
        },
        swfPath: mk_theme_js_path,
        supplied: "mp3, ogg",
        cssSelectorAncestor: css_selector_ancestor,
        wmode: "window"
      });
    });
  }
}

;/* jQuery prettyPhoto lightbox */
/* -------------------------------------------------------------------- */

function mk_lightbox_init() {
  $(".mk-lightbox").fancybox({
            padding: 15,
            margin: 15,

            width: 800,
            height: 600,
            minWidth: 100,
            minHeight: 100,
            maxWidth: 9999,
            maxHeight: 9999,
            pixelRatio: 1, // Set to 2 for retina display support

            autoSize: true,
            autoHeight: false,
            autoWidth: false,

            autoResize: true,
            fitToView: true,
            aspectRatio: false,
            topRatio: 0.5,
            leftRatio: 0.5,

            scrolling: 'auto', // 'auto', 'yes' or 'no'
            wrapCSS: '',

            arrows: true,
            closeBtn: true,
            closeClick: false,
            nextClick: false,
            mouseWheel: true,
            autoPlay: false,
            playSpeed: 3000,
            preload: 3,
            modal: false,
            loop: true,
            // Properties for each animation type
            // Opening fancyBox
            openEffect: 'fade', // 'elastic', 'fade' or 'none'
            openSpeed: 200,
            openEasing: 'swing',
            openOpacity: true,
            openMethod: 'zoomIn',

            // Closing fancyBox
            closeEffect: 'fade', // 'elastic', 'fade' or 'none'
            closeSpeed: 200,
            closeEasing: 'swing',
            closeOpacity: true,
            closeMethod: 'zoomOut',

            // Changing next gallery item
            nextEffect: 'none', // 'elastic', 'fade' or 'none'
            nextSpeed: 350,
            nextEasing: 'swing',
            nextMethod: 'changeIn',

            // Changing previous gallery item
            prevEffect: 'none', // 'elastic', 'fade' or 'none'
            prevSpeed: 350,
            prevEasing: 'swing',
            prevMethod: 'changeOut',
            helpers : {
                 media : {}
            },

            tpl: {
                wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                image: '<img class="fancybox-image" src="{href}" alt="" />',
                error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"><i class="mk-moon-close-2"></i></a>',
                next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span><i class="mk-jupiter-icon-arrow-right"></i></span></a>',
                prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span><i class="mk-jupiter-icon-arrow-left"></i></span></a>',
                loading: '<div id="fancybox-loading"><div></div></div>'
            },

        });
}

;/* Event Count Down */
/* -------------------------------------------------------------------- */

function mk_event_countdown() {
  if ($.exists('.mk-event-countdown')) {
    $('.mk-event-countdown').each(function () {
      var $this = $(this),
        $date = $this.attr('data-date'),
        $offset = $this.attr('data-offset');

      $this.downCount({
        date: $date,
        offset: $offset
      });
    });
  }
};/* Flexslider init */
/* -------------------------------------------------------------------- */

function mk_flexslider_init() {

  $('.mk-flexslider.mk-script-call').each(function () {

    if ($(this).parents('.mk-tabs').length || $(this).parents('.mk-accordion').length) {
      $(this).removeData("flexslider");
    }

    var $this = $(this),
      $selector = $this.attr('data-selector'),
      $animation = $this.attr('data-animation'),
      $easing = $this.attr('data-easing'),
      $direction = $this.attr('data-direction'),
      $smoothHeight = $this.attr('data-smoothHeight') == "true" ? true : false,
      $slideshowSpeed = $this.attr('data-slideshowSpeed'),
      $animationSpeed = $this.attr('data-animationSpeed'),
      $controlNav = $this.attr('data-controlNav') == "true" ? true : false,
      $directionNav = $this.attr('data-directionNav') == "true" ? true : false,
      $pauseOnHover = $this.attr('data-pauseOnHover') == "true" ? true : false,
      $isCarousel = $this.attr('data-isCarousel') == "true" ? true : false,
      $arrowLeft = $this.attr('data-arrow-left'),
      $arrowRight = $this.attr('data-arrow-right');

      if($arrowLeft == undefined) {
        $arrowLeft = '<i class="mk-jupiter-icon-arrow-left"></i>';
      } else {
        $arrowLeft = '<i class="'+$arrowLeft+'"></i>';
      }

      if($arrowRight == undefined) {
        $arrowRight = '<i class="mk-jupiter-icon-arrow-right"></i>';
      } else {
        $arrowRight = '<i class="'+$arrowRight+'"></i>';
      }


    if ($selector != undefined) {
      var $selector_class = $selector;
    } else {
      var $selector_class = ".mk-flex-slides > li";
    }

    if ($isCarousel == true) {
      var $itemWidth = parseInt($this.attr('data-itemWidth')),
        $itemMargin = parseInt($this.attr('data-itemMargin')),
        $minItems = parseInt($this.attr('data-minItems')),
        $maxItems = parseInt($this.attr('data-maxItems')),
        $move = parseInt($this.attr('data-move'));
    } else {
      var $itemWidth = $itemMargin = $minItems = $maxItems = $move = 0;
    }

    $this.flexslider({
      selector: $selector_class,
      animation: $animation,
      easing: $easing,
      direction: $direction,
      smoothHeight: $smoothHeight,
      slideshow: true,
      slideshowSpeed: $slideshowSpeed,
      animationSpeed: $animationSpeed,
      controlNav: $controlNav,
      directionNav: $directionNav,
      pauseOnHover: $pauseOnHover,
      prevText: "",
      nextText: "",
      directionNavArrowsLeft: $arrowLeft,
      directionNavArrowsRight:$arrowRight,
      itemWidth: $itemWidth,
      itemMargin: $itemMargin,
      minItems: $minItems,
      maxItems: $maxItems,
      move: $move,
    });

  });

}


//
;/* Background Parallax Effects */
/* -------------------------------------------------------------------- */

function mk_backgrounds_parallax() {

  "use strict";

  if (mk_header_parallax == true) {
    $('.mk-header-bg').addClass('mk-parallax-enabled');
  }
  if (mk_body_parallax == true) {
    $('body').addClass('mk-parallax-enabled');
  }
  if (mk_banner_parallax == true) {
    $('#mk-header').addClass('mk-parallax-enabled');
  }
  if (mk_page_parallax == true) {
    $('#theme-page').addClass('mk-parallax-enabled');
  }
  if (mk_footer_parallax == true) {
    $('#mk-footer').addClass('mk-parallax-enabled');
  }

  $('.mk-parallax-enabled').each(function () {
    if (!is_touch_device()) {
      $(this).parallax("49%", 0.3);
    }
  });

  $('.mk-fullwidth-slideshow.parallax-slideshow').each(function () {
    if (!is_touch_device()) {
      var speed_factor = $(this).attr('data-speedFactor');
      $(this).parallax("49%", speed_factor);
    }
  });

};/* Animated Contents */
/* -------------------------------------------------------------------- */

function mk_animated_contents() {

  "use strict";

  $(".mk-animate-element").filter(":in-viewport").each(function (i) {
    var $this = $(this);
    if (!$this.hasClass('mk-in-viewport')) {
      setTimeout(function () {
        $this.addClass('mk-in-viewport');
      }, 100 * i);
    }
  });

}
;/* Box Blur effect */
/* -------------------------------------------------------------------- */

function mk_blur_boxes() {

  "use strict";

  var viewportWidth = $(window).width(),
  	  overlayColor = "rgba(255,255,255,0.6)";


  if ($.exists('.icon-box-boxed.blured-box, .mk-employee-item.employee-item-blur') && !is_touch_device() && viewportWidth > 1024 ) {

  		var bg = $('.mk-blur-parent'),
  			i = 0;

  		var setLoop = setInterval( function(){ 
			var	mk_blur_parent = bg.eq(i),
		  		mk_blur_elements = mk_blur_parent.find('.icon-box-boxed.blured-box, .mk-employee-item.employee-item-blur');

				mk_blur_elements.blurjs({
				  source: mk_blur_parent,
				  radius: 18,
				  overlay: overlayColor,
				});
				i++;
				if(i === bg.length) {
					clearInterval(setLoop);
					// console.log('blur-stop-propagation');
				}
  		}, 1000);
	
  }  else if ($.exists('.icon-box-boxed.blured-box, .mk-employee-item.employee-item-blur')) {
		if ( viewportWidth <= 935 ) {
		  	$('.mk-blur-parent .icon-box-boxed').css({ 'background-color' : overlayColor });
		  	$('.mk-blur-parent .mk-employee-item').css({ 'background-color' : overlayColor });
		} 
  }
}
;


function mk_portfolio_animations() {

    // $('.mk-portfolio-grid-item').each(function(){
    //     $this = $(this);

    //     $this.hover(function () {
    //         $this = $(this);
    //         TweenLite.to($this.find('.image-hover-overlay'), 0.01, {
    //             'opacity':0.4,
    //         });
    //         TweenLite.to($this.find('.portfolio-meta'), 0.3, {
    //             bottom:0,
    //             ease:Quad.easeOut
    //         });
    //         TweenLite.to($this.find('.featured-image img'), 0.3, {
    //             'top':'-30px',
    //             ease:Bounce.easeOut
    //         });

    //     }, 
    //     function() {
    //         TweenLite.to($this.find('.image-hover-overlay'), 0.01, {
    //             'opacity':0
    //         });
    //         TweenLite.to($this.find('.portfolio-meta'), 0.2, {
    //             bottom:-99,
    //             ease:Quad.easeOut
    //         });
    //         TweenLite.to($this.find('.featured-image img'), 0.3, {
    //             'scale':1.1,
    //             top:0
    //         });

    //     });
    // });
}


var equalheight = function(container){

var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPosition = $el.position().top;

   if (currentRowStart != topPosition) {
     for (var currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPosition;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

/* Animated Columns */
    /* -------------------------------------------------------------------- */
    function mk_animated_columns() {

        function prepareCols() {

            equalheight('.vc_row .animated-column-title');
            equalheight('.vc_row .animated-column-desc');

            $('.mk-animated-columns').each(function() {
                var $this = $(this);

                if ($this.hasClass('full-style')) {
                    $this.find('.animated-column-item').each(function() {
                        var $this = $(this),
                            contentHeight = $this.find('.animated-column-icon').innerHeight() + $this.find('.animated-column-title').innerHeight() + $this.find('.animated-column-desc').innerHeight() + $this.find('.animated-column-btn').innerHeight();

                        $this.height(contentHeight * 1.5 + 50);

                        var $box_height = $this.outerHeight(true),
                            $icon_height = $this.find('.animated-column-icon').height();

                        $this.find('.animated-column-holder').css({
                            'paddingTop': $box_height / 2 - $icon_height
                        });


                        $this.animate({opacity:1}, 300);
                    });
                } else {
                    $this.find('.animated-column-item').each(function() {
                        var $this = $(this),
                            $half_box_height = $this.outerHeight(true) / 2,
                            $icon_height = $this.find('.animated-column-icon').outerHeight(true)/2,
                            $title_height = $this.find('.animated-column-simple-title').outerHeight(true)/2;

                        $this.find('.animated-column-holder').css({
                            'paddingTop': $half_box_height - $icon_height
                        });
                        $this.find('.animated-column-title').css({
                            'paddingTop': $half_box_height - $title_height + ($icon_height * 2)
                        });

                        $this.animate({opacity:1}, 300);

                    });
                }

            });
        }
        prepareCols();

        $(window).on("resize", function() {
            prepareCols();
        });

        $(".mk-animated-columns.full-style .animated-column-item").hover(
            function() {
                TweenLite.to($(this).find(".animated-column-holder"), 0.5, {
                    top: '-15%',
                    ease: Back.easeOut
                });
                TweenLite.to($(this).find(".animated-column-desc"), 0.5, {
                    top: '-50%',
                    ease: Expo.easeOut
                }, 0.4);
                TweenLite.to($(this).find(".animated-column-btn"), 0.3, {
                    top: '-50%',
                    ease: Expo.easeOut
                }, 0.6);
            },
            function() {

                TweenLite.to($(this).find(".animated-column-holder"), 0.5, {
                    top: '0%',
                    ease: Back.easeOut, easeParams:[3]
                });
                TweenLite.to($(this).find(".animated-column-desc"), 0.5, {
                    top: '100%',
                    ease: Back.easeOut
                }, 0.4);
                TweenLite.to($(this).find(".animated-column-btn"), 0.5, {
                    top: '100%',
                    ease: Back.easeOut
                }, 0.2);
            }
        );

        $(".mk-animated-columns.simple-style .animated-column-item").hover(
            function() {
                TweenLite.to($(this).find(".animated-column-holder"), 0.7, {
                    top: '100%',
                    ease: Expo.easeOut
                });
                TweenLite.to($(this).find(".animated-column-title"), 0.7, {
                    top: '0%',
                    ease: Back.easeOut
                }, 0.2);
            },
            function() {
                TweenLite.to($(this).find(".animated-column-holder"), 0.7, {
                    top: '0%',
                    ease: Expo.easeOut
                });
                TweenLite.to($(this).find(".animated-column-title"), 0.7, {
                    top: '-100%',
                    ease: Back.easeOut
                }, 0.2);
            }
        );

    };/* Tabs */
/* -------------------------------------------------------------------- */

function mk_tabs() {

  "use strict";

  if ($.exists('.mk-tabs, .mk-news-tab, .mk-woo-tabs')) {
    $(".mk-tabs, .mk-news-tab, .mk-woo-tabs").tabs();

    $('.mk-tabs').on('click', function () {
      $('.mk-theme-loop').isotope('reLayout');
    });

    $('.mk-tabs.vertical-style').each(function () {
      $(this).find('.mk-tabs-pane').css('minHeight', $(this).find('.mk-tabs-tabs').height() - 1);
    });

  }
}

function mk_tabs_responsive(){
  if ($.exists('.mk-tabs, .mk-news-tab, .mk-woo-tabs')) {
    if (window.matchMedia('(max-width: 767px)').matches)
    {
        $(".mk-tabs, .mk-news-tab, .mk-woo-tabs").tabs("destroy");
    }
  }
}

;/* Parallax for page sections 
 * Thx to Olafur Nielsen (http://form5.is)
/* -------------------------------------------------------------------- */

function mk_parallax_improved() {


  // var parallax_parent = $('.parallax-true'),
  //     $bgElm = [],
  //     $offset = [],
  //     $height = [];

  // parallax_parent.each(function() {
      
  //     var scrollTop = 0,
  //         ticking = false,
  //         windowHeight = $(window).height(),
  //         $speedFactor = $(this).attr('data-speedFactor'),
  //         speedDivider = 1 + (.2 * $speedFactor);

  //     $bgElm.push($(this).find('.swiper-slide-bg, .mk-section-video'));
  //     $offset.push($(this).offset().top);
  //     $height.push($(this).outerHeight());

      
  //     // Update background position
  //     var updatePosition = function() {
  //       $($bgElm).each(function(i) {
  //         var offset = $offset[i],
  //             height = $height[i],
  //             translateValue =  (offset - scrollTop) / speedDivider;

  //         $(this).height(height * speedDivider);

  //             console.log(translateValue)

  //         // Check if above or below viewport
  //         if (offset + height <= scrollTop || offset >= scrollTop + windowHeight) {
  //           return;
  //         }

  //         // We don't want parallax to happen if scrollpos is below 0
  //         // if (translateValue < 0)
  //         //   translateValue = 0;

  //         this.each(function() {
  //           translateY(this, translateValue);
  //         })
  //       })

  //       // Stop ticking
  //       ticking = false;
  //     };

  //     // Translates an element on the Y axis using translate3d to ensure
  //     // that the rendering is done by the GPU
  //     var translateY = function(elm, value) {
  //       var translate = 'translate3d(0px,' + value + 'px, 0px)';
  //       elm.style['-webkit-transform'] = translate;
  //       elm.style['-moz-transform'] = translate;
  //       elm.style['-ms-transform'] = translate;
  //       elm.style['-o-transform'] = translate;
  //       elm.style.transform = translate;
  //     };

  //     // This will limit the calculation of the background position to
  //     // 60fps as well as blocking it from running multiple times at once
  //     var requestTick = function() {
  //       if (!ticking) {
  //         window.requestAnimationFrame(updatePosition);
  //         ticking = true;
  //       }
  //     };

  //     // Update scroll value and request tick
  //     var doScroll = function() {
  //       scrollTop = $(window).scrollTop();
  //       requestTick();
  //     };

  //     // Initialize on domready
  //     (function() {
  //       var loaded = 0;
  //       var bootstrap = function() {
  //         if (loaded) return;
  //         loaded = 1;

  //         rafPolyfill();
  //         window.onscroll = doScroll;
  //       };

  //       if ( document.readyState === 'complete' ) {
  //         setTimeout( bootstrap );
  //       } else {
  //         document.addEventListener( 'DOMContentLoaded', bootstrap, false );
  //         window.addEventListener( 'load', bootstrap, false );
  //       }
  //     })();

  //     // RequestAnimationFrame polyfill for older browsers
  //     var rafPolyfill = function() {
  //       var lastTime, vendors, x;
  //       lastTime = 0;
  //       vendors = ["webkit", "moz"];
  //       x = 0;
  //       while (x < vendors.length && !window.requestAnimationFrame) {
  //         window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
  //         window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] || window[vendors[x] + "CancelRequestAnimationFrame"];
  //         ++x;
  //       }
  //       if (!window.requestAnimationFrame) {
  //         window.requestAnimationFrame = function(callback, element) {
  //           var currTime, id, timeToCall;
  //           currTime = new Date().getTime();
  //           timeToCall = Math.max(0, 16 - (currTime - lastTime));
  //           id = window.setTimeout(function() {
  //             callback(currTime + timeToCall);
  //           }, timeToCall);
  //           lastTime = currTime + timeToCall;
  //           return id;
  //         };
  //       }
  //       if (!window.cancelAnimationFrame) {
  //         window.cancelAnimationFrame = function(id) {
  //           clearTimeout(id);
  //         };
  //       }
  //     };
  // })
};/* Parallx for page sections */
/* -------------------------------------------------------------------- */

function mk_parallax() {

  "use strict";


  if (!is_touch_device()) {
    $('.mk-page-section.parallax-true').each(function () {
      var $this = $(this),
        $speedFactor = $this.attr('data-speedFactor');

      $($this).parallax("49%", $speedFactor);
    });
  }

  // if (!is_touch_device()) {

  // 	var $parallaxLayer = [];

  //   $('.mk-page-section.parallax-true').each(function () {

  //     var lastScrollY = 0,
  //         ticking = false,
  //         $this = $(this),
  //         windowHeight = $(window).height(),
  //         parentHeight = $this.outerHeight(),
  //         $speedFactor = $this.attr('data-speedFactor'),
  //         parallaxSpeed = 0.1*$speedFactor/2;

  //     $parallaxLayer.push($this.find('.parallax-layer'));

  //     // Update background position
  //     var updatePosition = function() {
  //       var translateValue = lastScrollY / $speedFactor;

  //       // We don't want parallax to happen if scrollpos is below 0
  //       if (translateValue < 0)
  //           translateValue = 0;

	 //    $($parallaxLayer).each(function() {
	 //    	var $this = $(this);

	 //    	// $this.height(parentHeight + Math.abs(parentHeight*parallaxSpeed*2));

	 //    	if($speedFactor > 0) {
	 //    		$this.css('top', -(parentHeight * parallaxSpeed));
	 //    		$this.height(parentHeight * (1 + Math.abs(parallaxSpeed)*4));
	 //    	} else {
	 //    		$this.height(parentHeight + parentHeight / (1 + Math.abs(parallaxSpeed)*4));
	 //    	}

	 //    	var scrollTop = $(window).scrollTop();
  //           var offset = $this.offset().top;
  //           var height = $this.outerHeight();

  //           if (offset + height <= scrollTop || offset >= scrollTop + windowHeight) {
		// 		return;
		// 	}

	 //      	translateY(this[0], (offset - scrollTop) * parallaxSpeed);
		// });

  //       // Stop ticking
  //       ticking = false;
  //     };

  //     // Translates an element on the Y axis using translate3d to ensure
  //     // that the rendering is done by the GPU
  //     var translateY = function(elm, value) {
  //       var translate = 'translate3d(0px,' + value + 'px, 0px)';
  //       elm.style['-webkit-transform'] = translate;
  //       elm.style['-moz-transform'] = translate;
  //       elm.style['-ms-transform'] = translate;
  //       elm.style['-o-transform'] = translate;
  //       elm.style.transform = translate;
  //     };

  //     // This will limit the calculation of the background position to
  //     // 60fps as well as blocking it from running multiple times at once
  //     var requestTick = function() {
  //       if (!ticking) {
  //         window.requestAnimationFrame(updatePosition);
  //         ticking = true;
  //       }
  //     };

  //     // Update scroll value and request tick
  //     var doScroll = function() {
  //       lastScrollY = window.scrollY;
  //       requestTick();
  //     };

  //     // Initialize on domready
  //     (function() {
  //       var loaded = 0;
  //       var bootstrap = function() {
  //         if (loaded) return;
  //         loaded = 1;

  //         rafPolyfill();
  //         window.onscroll = doScroll;

  //         // Repair position on refresh
  //         doScroll();
  //       };

  //       if ( document.readyState === 'complete' ) {
  //         setTimeout( bootstrap );
  //       } else {
  //         document.addEventListener( 'DOMContentLoaded', bootstrap, false );
  //         window.addEventListener( 'load', bootstrap, false );
  //       }
  //     })();

  //     // RequestAnimationFrame polyfill for older browsers
  //     var rafPolyfill = function() {
  //       var lastTime, vendors, x;
  //       lastTime = 0;
  //       vendors = ["webkit", "moz"];
  //       x = 0;
  //       while (x < vendors.length && !window.requestAnimationFrame) {
  //         window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
  //         window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] || window[vendors[x] + "CancelRequestAnimationFrame"];
  //         ++x;
  //       }
  //       if (!window.requestAnimationFrame) {
  //         window.requestAnimationFrame = function(callback, element) {
  //           var currTime, id, timeToCall;
  //           currTime = new Date().getTime();
  //           timeToCall = Math.max(0, 16 - (currTime - lastTime));
  //           id = window.setTimeout(function() {
  //             callback(currTime + timeToCall);
  //           }, timeToCall);
  //           lastTime = currTime + timeToCall;
  //           return id;
  //         };
  //       }
  //       if (!window.cancelAnimationFrame) {
  //         window.cancelAnimationFrame = function(id) {
  //           clearTimeout(id);
  //         };
  //       }
  //     };

  //   });
  // }
}


;/* Ajax Search */
/* -------------------------------------------------------------------- */

function mk_ajax_search() {

  "use strict";

  if ($.exists('.main-nav-side-search') && mk_ajax_search_option == "beside_nav") {
    $("#mk-ajax-search-input").autocomplete({
      delay: 40,
      minLength: 2,
      appendTo: $("#mk-nav-search-wrapper"),
      search: function (event, ui) {
        $(this).parent('form').addClass('ajax-searching');
      },
      source: function (req, response) {
        $.getJSON(ajaxurl + '?callback=?&action=mk_ajax_search', req, response);
      },
      select: function (event, ui) {
        window.location.href = ui.item.link;
      },
      response: function (event, ui) {
        $(this).parent('form').removeClass('ajax-searching').addClass('ajax-search-complete');
      }

    }).data("ui-autocomplete")._renderItem = function (ul, item) {


      return $("<li>").append("<a>" + item.image + "<span class='search-title'>" + item.label + "</span><span class='search-date'>" + item.date + "</span></a>").appendTo(ul);

    };
  }
}


;/* Hover Events */
/* -------------------------------------------------------------------- */

function mk_hover_events() {

  "use strict";

  $('.shopping-cart-header').hoverIntent({
    over: function() {
      $('.mk-shopping-cart-box').show();
    },
    out: function() {
      $('.mk-shopping-cart-box').hide();
    },
    timeout: 500
  });


  $('.widget-sub-navigation > ul > li, .widget_nav_menu ul.menu > li, .widget_product_categories ul > .cat-item').each(function() {

    var $this = $(this),
      $subLevel = $this.find('ul');

    if ($this.hasClass('page_item_has_children') || $this.hasClass('menu-item-has-children') || $this.hasClass('cat-parent')) {
      $this.hoverIntent({
        over : function() {
          $subLevel.slideDown(500);
        }, 
        out : function() {
          $subLevel.slideUp(500);
        },
        timeout: 1000
        });
    }

  });  

  var eventtype = mobilecheck() ? 'touchstart' : 'click';

  $('.mk-fullscreen-trigger').on(eventtype, function(e) {
    $('.mk-fullscreen-search-overlay').addClass('mk-fullscreen-search-overlay-show');
    setTimeout(function(){
      $("#mk-fullscreen-search-input").focus();
    }, 300);
    e.preventDefault();
  });

  $('.mk-fullscreen-close').on(eventtype, function(e) {
    $('.mk-fullscreen-search-overlay').removeClass('mk-fullscreen-search-overlay-show');
    e.preventDefault();
  });

}

function mk_unfold_footer() {
  var $this = $('#mk-footer'),
    $themePage = $('#theme-page'),
    $footerHeight = $this.outerHeight()
  $winWidth = $(window).outerWidth();
  if ($winWidth > 767) {
    if ($this.hasClass('mk-footer-unfold')) {
      $themePage.css('margin-bottom', $footerHeight);
    }
  } else {
    $themePage.css('margin-bottom', 0);
  }
};/* Ajax portfolio */
/* -------------------------------------------------------------------- */

function mk_portfolio_ajax() {

  "use strict";

  var headerHeight = 0;
  if ($.exists("#wpadminbar")) {
    headerHeight += $("#wpadminbar").height();
  }
  if (!$.exists('.mk-vm-menuwrapper')) {
    headerHeight += parseInt($('#mk-header').attr('data-sticky-height'));
  }

  $('.portfolio-grid.portfolio-ajax-enabled').ajaxPortfolio({
  	extraOffset: headerHeight
  });
}


;/* Love This */
/* -------------------------------------------------------------------- */

function mk_love_post() {

  "use strict";

  $('body').on('click', '.mk-love-this', function () {
    var $this = $(this),
      $id = $this.attr('id');

    if ($this.hasClass('item-loved')) return false;

    if ($this.hasClass('item-inactive')) return false;

    var $sentdata = {
      action: 'mk_love_post',
      post_id: $id
    }

    $.post(ajaxurl, $sentdata, function (data) {
      $this.find('span').html(data);
      $this.addClass('item-loved');
    });

    $this.addClass('item-inactive');
    return false;
  });

}



;/* Woocommerce Add to card */
/* -------------------------------------------------------------------- */

function product_loop_add_cart() {

  "use strict";

  var $body = $('body');
  $body.on('click', '.add_to_cart_button', function () {
    var product = $(this).parents('.product:eq(0)').addClass('adding-to-cart').removeClass('added-to-cart');
  })

  $body.bind('added_to_cart', function () {
    $('.adding-to-cart').removeClass('adding-to-cart').addClass('added-to-cart');
  });
}



;/* Woocommerce Loop Scripts */
/* -------------------------------------------------------------------- */

function shop_isotop_init() {

  "use strict";

  if ($.exists('.products') && !$('.products').hasClass('related')) {
    $('.products').each(function () {

      if (!$(this).parents('.mk-woocommerce-carousel').length) {
        var $woo_container = $(this),
          $container_item = '.products .product';

        $woo_container.isotope({
          itemSelector: $container_item,
          masonry: {
            columnWidth: 1
          }

        });


      }
    });
  }
}



;/* Social Share */
/* -------------------------------------------------------------------- */

function mk_social_share() {

  "use strict";

  $('.twitter-share').on('click', function () {
    var $url = $(this).attr('data-url'),
      $title = $(this).attr('data-title');

    window.open('http://twitter.com/intent/tweet?text=' + $title + ' ' + $url, "twitterWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  $('.pinterest-share').on('click', function () {
    var $url = $(this).attr('data-url'),
      $title = $(this).attr('data-title'),
      $image = $(this).attr('data-image');
    window.open('http://pinterest.com/pin/create/button/?url=' + $url + '&media=' + $image + '&description=' + $title, "twitterWindow", "height=320,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  $('.facebook-share').on('click', function () {
    var $url = $(this).attr('data-url');
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + $url, "facebookWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  $('.googleplus-share').on('click', function () {
    var $url = $(this).attr('data-url');
    window.open('https://plus.google.com/share?url=' + $url, "googlePlusWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  $('.linkedin-share').on('click', function () {
    var $url = $(this).attr('data-url'),
      $title = $(this).attr('data-title'),
      $desc = $(this).attr('data-desc');
    window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + $url + '&title=' + $title + '&summary=' + $desc, "linkedInWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });
}



;/* Floating Go to top Link */
/* -------------------------------------------------------------------- */

function mk_smooth_scroll_events() {


  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';

  $('.mk-go-top, .mk-back-top-link, .single-back-top a, .divider-go-top, .comments-back-top').on(eventtype, function () {
    //TweenLite.to(window, 1, {scrollTo:{y:0}, ease:Expo.easeInOut});
    $("html, body").animate({
      scrollTop: 0
      }, 1500);
    return false;
  });

  $('.mk-classic-comments').on(eventtype, function () {
    $("html, body").animate({
      scrollTop: $('#comments').offset().top
    }, 800);
    //TweenLite.to(window, 1, {scrollTo:{y:$('#comments').offset().top}, ease:Expo.easeInOut});

  });

  $(".mk-smooth").on(eventtype, function () {
    var header_height = 0,
         wp_admin_height = 0;

    if ($.exists("#wpadminbar")) {
      wp_admin_height = $("#wpadminbar").height();
    }
    if($.exists('#mk-header .mk-header-holder')) {
      header_height = parseInt($('#mk-header').attr('data-sticky-height'));  
    }
    
    if ( $(window).width() <= mk_responsive_nav_width ) {
      header_height = 0;
    }
    // console.log(header_height); // test

    // $("body, html").animate({
    //   scrollTop: $($(this).attr("href")).offset().top - (header_height + wp_admin_height) + "px"
    // }, {
    //   duration: 1200,
    //   easing: "easeInOutExpo"
    // });

    var offsetTop = $($(this).attr("href")).offset().top - (header_height + wp_admin_height);

    $('html, body').stop().animate({
      scrollTop: offsetTop
    }, {
      duration: 1200,
      easing: "easeInOutExpo"
    });
    
    //TweenLite.to(window, 1, {scrollTo: { y: offsetTop }, ease:Expo.easeInOut });

    return false;

    // Above script creates an error in some users : Uncaught TypeError: Failed to execute 'scrollTo' on 'Window': 2 arguments required, but only 0 present. 

  });

}


new ChopScroll(function () {
  if ($(window).scrollTop() > 400) {
    $('.mk-go-top, .mk-quick-contact-wrapper').removeClass('off').addClass('on');
  } else {
    $('.mk-go-top, .mk-quick-contact-wrapper').removeClass('on').addClass('off');
  }
}, 800);
;/* Page Section full height feature */
/* -------------------------------------------------------------------- */

function section_to_full_height() {


  "use strict";

  $('.full-height-true.mk-page-section').each(function () {
    var $this = $(this),
      $content_height = $this.find('.page-section-content').outerHeight(true),
      $window_height = $(window).outerHeight();
 
   /* if ($('.mk-header-toolbar').length > 0) {
      var mk_header_toolbar = $(".mk-header-toolbar").outerHeight();
    } else {
      var mk_header_toolbar = 0;
    }*/

    if ($.exists("#wpadminbar")) {
      var wp_admin_height = $("#wpadminbar").outerHeight();
    } else {
      wp_admin_height = 0;
    }
  


    $window_height = $window_height - wp_admin_height;


    if ($content_height > $window_height) {
      $this.css('height', 'auto');
      $this.find('.page-section-content').css({
        'padding-top': 30,
        'padding-bottom': 30
      });
    } else { 
      $this.css('height', $window_height);
      var $this_height_half = $this.find('.page-section-content').outerHeight() / 2,
        $window_half = $window_height / 2;

      $this.find('.page-section-content').css('marginTop', ($window_half - $this_height_half));
    }

    $this.find('.mk-page-section-loader').fadeOut();

  });

}



/* Page Section Intro Effects */
/* -------------------------------------------------------------------- */

function mk_section_intro_effects() {
  if ( !is_touch_device() ) {
    if($.exists('.mk-page-section.intro-true')) {

      $('.mk-page-section.intro-true').each(function() {
          var $this = $(this),
              $pageCnt = $this.nextAll('div'),
              windowHeight = $(window).height(),
              effectName = $this.attr('data-intro-effect'),
              $header = $('#mk-header');

              var effect = {
                    fade :    new TimelineLite({paused: true})
                              .set($pageCnt, { opacity: 0, y: windowHeight * 0.3 })
                              .to($this, 1, { opacity: 0, ease:Power2.easeInOut })
                              .to($pageCnt, 1, { opacity: 1, y: 0, ease:Power2.easeInOut}, "-=.7")
                              .set($this, { zIndex: '-1'}),

                    zoom_out : new TimelineLite({paused: true})
                              .set($pageCnt, { opacity: 0, y: windowHeight * 0.3})
                              .to($this, 1.5, { opacity: .8, scale: 0.8, y: -windowHeight - 100, ease:Strong.easeInOut })
                              .to($pageCnt, 1.5, { opacity: 1, y:  0, ease:Strong.easeInOut}, "-=1.3"),

                    shuffle : new TimelineLite({paused: true})
                              .to($this, 1.5, { y: -windowHeight/2, ease:Strong.easeInOut })
                              .to($this.nextAll('div').first(), 1.5, { paddingTop: windowHeight/2, ease:Strong.easeInOut }, "-=1.3")
              }
      

          $this.sectiontrans({
            effect : effectName,
          });

          if($this.hasClass('shuffled')) {
            TweenLite.set($this, { y: -windowHeight/2 });
            TweenLite.set($this.nextAll('div').first(), { paddingTop: windowHeight/2 });
          }

          $('body').on('page_intro', function() {
            $(this).data('intro', true);
            effect[effectName].play();
            setTimeout(function() {
              $header.addClass('header-sticky-prepare');
              $header.addClass('header-sticky-ready');
              $('.mk-header-padding-wrapper').addClass('enable-padding');
              $('body').data('intro', false);
              if(effectName === 'shuffle') $this.addClass('shuffled');
            }, 1000);
          });

          $('body').on('page_outro', function() {
            $(this).data('intro', true);
            effect[effectName].reverse();
            setTimeout(function() {
              $header.removeClass('header-sticky-prepare');
              $header.removeClass('header-sticky-ready');
              $('.mk-header-padding-wrapper').removeClass('enable-padding');
              $('body').data('intro', false);
              if($this.hasClass('shuffled')) $this.removeClass('shuffled');
            }, 1000);
          });

      });
    }
  } else {
    $('.mk-page-section.intro-true').each(function() {
      $(this).attr('data-intro-effect', '');
    });
  }
};/* Accordions & Toggles */
/* -------------------------------------------------------------------- */


/* Accordions */

function mk_accordion_toggles_tooltip() {

  "use strict";

  var accordion = $(".mk-accordion"),
    accordionPaneClass = "mk-accordion-pane",
    actionClass = "accordion-action",
    accordionTabClass = "mk-accordion-tab",
    togglePaneClass = "mk-toggle-pane",
    activeToggleClass = "active-toggle";

  if ($.exists(accordion)) {

    $.tools.toolsTabs.addEffect("slide", function(i, done) {
      this.getPanes().slideUp(250);
      this.getPanes().eq(i).slideDown(250, function() {
        done.call();
      });
    });

    accordion.each(function() {

      if ($(this).hasClass(actionClass)) {

        var $initialIndex = parseInt($(this).attr('data-initialindex'));
        if ($initialIndex == undefined) {
          $initialIndex = 0;
        }
        if (window.matchMedia('(max-width: 767px)').matches){
          
        }else{
          $(this).toolsTabs("div.mk-accordion-pane", {
            toolsTabs: accordionTabClass,
            effect: 'slide',
            initialIndex: $initialIndex,
            slideInSpeed: 400,
            slideOutSpeed: 400
          });
        }
      } else {
        $(".toggle-action .mk-accordion-tab").toggle(
          function() {
            $(this).parent('.mk-accordion-single').addClass('current');
            $(this).siblings('.' + accordionPaneClass).slideDown(150);
          }, function() {
            $(this).parent('.mk-accordion-single').removeClass('current');
            $(this).siblings('.' + accordionPaneClass).slideUp(150);
          });
      }
    });

  }


  /* Toggles */

  if ($.exists('.mk-toggle-title')) {
    if (window.matchMedia('(max-width: 767px)').matches){

    }else{ 
      $(".mk-toggle-title").toggle(
        function() {
          $(this).addClass(activeToggleClass);
          $(this).siblings('.' + togglePaneClass).slideDown(200);
        }, function() {
          $(this).removeClass(activeToggleClass);
          $(this).siblings('.' + togglePaneClass).slideUp(200);
        }
      );
    }
  } 


  /* Message Boxes */
  /* -------------------------------------------------------------------- */

  $('.box-close-btn').on('click', function() {
    $(this).parent().fadeOut(300);
    return false;

  });


  $('.mk-tooltip').each(function() {
    $(this).find('.tooltip-init').hover(function() {
      $(this).siblings('.tooltip-text').animate({
        'opacity': 1
      }, 400);

    }, function() {
      $(this).siblings('.tooltip-text').animate({
        'opacity': 0
      }, 400);
    });
  });

}
;/* Newspaper Comments & Share section */
/* -------------------------------------------------------------------- */

function mk_newspaper_comments_share() {

  "use strict";

  $('.newspaper-item-footer').each(function () {

    $(this).find('.newspaper-item-comment').click(function () {

      $(this).parents('.newspaper-item-footer').find('.newspaper-social-share').slideUp(200).end().find('.newspaper-comments-list').slideDown(200);
      setTimeout(function () {
        $('.mk-theme-loop').isotope('reLayout');
      }, 300);
    });

    $(this).find('.newspaper-item-share').click(function () {

      $(this).parents('.newspaper-item-footer').find('.newspaper-comments-list').slideUp(200).end().find('.newspaper-social-share').slideDown(200);
      setTimeout(function () {
        $('.mk-theme-loop').isotope('reLayout');
      }, 300);

    });

  });

}


;/* Main Navigation */
/* -------------------------------------------------------------------- */

function mk_main_navigation_init() {

  "use strict";

  var $body = $('body');

  if (!$body.hasClass('navigation-initialised')) {

    $(".main-navigation-ul").MegaMenu({
      type: "vertical",
      delay: 200
    });

    $('#mk-vm-menu').dlmenu();

    $body.addClass('navigation-initialised');

  }



}



function mk_responsive_nav() {

  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';

  $('.mk-nav-responsive-link').stop(true).on(eventtype, function() {
    var $body = $('body'),
      $res_nav = $('#mk-responsive-nav');
    if ($body.hasClass('mk-opened-nav')) {
      $body.removeClass('mk-opened-nav').addClass('mk-closed-nav');
      $res_nav.slideUp(300);
    } else {
      $body.removeClass('mk-closed-nav').addClass('mk-opened-nav');
      $res_nav.slideDown(300);
    }
  });

  $('.mk-toolbar-resposnive-icon').stop(true).on(eventtype,function() {
    var $body = $('body'),
      $toolbar = $('.mk-header-toolbar');


    if ($body.hasClass('toolbar-oppend')) {
      $body.removeClass('toolbar-oppend').addClass('toolbar-closed');
      $toolbar.slideUp();
    } else {
      $body.removeClass('toolbar-closed').addClass('toolbar-oppend');
      $toolbar.slideDown();
    }
  });

}


/* Responsive Fixes */
/* -------------------------------------------------------------------- */


function mk_responsive_fix() {


  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';


  if ($(window).width() > mk_responsive_nav_width) {


    $('#mk-responsive-nav').hide();
    mk_main_navigation_init();


  } else {

    if (!$.exists('#mk-responsive-nav')) {
      $('.main-navigation-ul, .mk-vm-menu').clone().attr({
        id: "mk-responsive-nav",
        "class": ""
      }).insertAfter('.mk-header-inner');

      $('#mk-responsive-nav > li').each(function() {
        var $this = $(this);

        $this.removeClass('has-mega-menu').addClass('no-mega-menu');

        $this.children('ul').siblings('a').append('<span class="mk-moon-arrow-down mk-nav-arrow mk-nav-sub-closed"></span>').end().attr("style", '');
      });


      $('.mk-header-inner').attr('style', '');

      $('#mk-responsive-nav').append($('.responsive-searchform'));


      $('.mk-nav-arrow').stop(true).on(eventtype, function(e) {
        var $this = $(this);
        if ($this.hasClass('mk-nav-sub-closed')) {
          $this.parent().siblings('ul').slideDown(450).end().end().removeClass('mk-nav-sub-closed').addClass('mk-nav-sub-opened');
        } else {
          $this.parent().siblings('ul').slideUp(450).end().end().removeClass('mk-nav-sub-opened').addClass('mk-nav-sub-closed');
        }
        e.preventDefault();
      });


    var $header_height = 0;
    var $window_height = $(window).outerHeight();

    if ($.exists('#wpadminbar')) {
      $header_height += $('#wpadminbar').outerHeight();
    }

    $header_height += $('.mk-header-inner').height();

    var nav_height = $window_height - $header_height;

    $('#mk-responsive-nav').wrap('<div id="mk-responsive-wrap" style="max-height:'+nav_height+'px"></div>');
    
    setTimeout(function() {
      document.getElementById("mk-responsive-wrap").addEventListener('touchstart', function(event){});
    }, 300)

    }
  }

}



function mk_sidebar_navigation() {

  "use strict";

  var $header = $('#mk-header'),
    $sticky_style = $header.attr('data-header-style');

  if ($sticky_style == 3) {

    $('.sidedash-navigation-ul > li').each(function() {
      var $this = $(this);

      $this.children('ul').siblings('a').after('<span class="mk-moon-arrow-down mk-nav-arrow mk-nav-sub-closed"></span>');
    });


     $('.mk-nav-arrow').stop(true).on('click', function(e) {
        var $this = $(this);
        if ($this.hasClass('mk-nav-sub-closed')) {
          $this.siblings('ul').slideDown(450).end().removeClass('mk-nav-sub-closed').addClass('mk-nav-sub-opened');
        } else {
          $this.siblings('ul').slideUp(450).end().removeClass('mk-nav-sub-opened').addClass('mk-nav-sub-closed');
        }
        e.preventDefault();
      });

  }



};function loops_iosotop_init() {

  "use strict";

  $('.loop-main-wrapper').each(function() {
    var $this = $(this),
      $mk_container = $this.find('.mk-theme-loop'),
      $mk_container_item = '.' + $mk_container.attr('data-style') + '-' + $mk_container.attr('data-uniqid'),
      $load_button = $this.find('.mk-loadmore-button'),
      $pagination_items = $this.find('.mk-pagination');

    //console.log($mk_container.find('.mk-isotop-item').width());


    $mk_container.isotope({
      itemSelector: $mk_container_item,
      animationEngine: "best-available",
      masonry: {
        columnWidth: 1
      } 

    });

    $mk_container.isotope('reLayout');

    //console.log($mk_container_item);


    $('#mk-filter-portfolio ul li a').click(function() {
      var $this;
      $this = $(this);

      /* Removes ajax container when filter items get triggered */
      $this.parents('.portfolio-grid').find('.ajax-container').animate({
        'height': 0,
        opacity: 0
      }, 500);

      if ($this.hasClass('.current')) {
        return false;
      }
      var $optionSet = $this.parents('#mk-filter-portfolio ul');
      $optionSet.find('.current').removeClass('current');
      $this.addClass('current');

      var selector = $(this).attr('data-filter');

      $mk_container.isotope({
        filter: ''
      });
      $mk_container.isotope({
        filter: selector
      });

      setTimeout(function() {
        $mk_container.isotope('reLayout');
      }, 400);

      return false;
    });


    $load_button.hide();

    if ($this.find('.mk-theme-loop').hasClass('scroll-load-style') || $this.find('.mk-theme-loop').hasClass('load-button-style')) {
      if ($pagination_items.length > 0) {
        $load_button.css('display', 'block');
      }
      $pagination_items.hide();


      $load_button.on('click', function() {
        if (!$(this).hasClass('pagination-loading')) {
          $(this).addClass('pagination-loading');
        }

      });

      $mk_container.infinitescroll({
          navSelector: $pagination_items,
          nextSelector: $this.find('.mk-pagination a:first'),
          itemSelector: $mk_container_item,
          bufferPx: 70,
          loading: {
            finishedMsg: "",
            img: mk_images_dir + "/load-more-loading.gif",
            msg: null,
            msgText: "",
            selector: $load_button,
            speed: 300,
            start: undefined
          },
          errorCallback: function() {

            $load_button.html(mk_no_more_posts).addClass('disable-pagination');

          },

        },

        function(newElements) {

          var $newElems = $(newElements);
          $newElems.hide();
          $newElems.imagesLoaded(function() {
            $load_button.removeClass('pagination-loading');


            var selected_item = $('#mk-filter-portfolio ul').find('.current').attr('data-filter');
            
            $mk_container.isotope('appended', $newElems);
            $mk_container.isotope({
              filter: ''
            });
            $mk_container.isotope({
              filter: selected_item
            });
            setTimeout(function() {
              $newElems.show();

              loop_audio_init();
              mk_portfolio_ajax();
              mk_newspaper_comments_share();
              mk_ajax_lightbox_init();
              mk_social_share();
              mk_theme_toggle_box();
              mk_swipe_slider();

              $mk_container.isotope('reLayout');
            }, 450);
          });
        }

      );

      /* Loading elements based on scroll window */
      if ($this.find('.mk-theme-loop').hasClass('load-button-style')) {
        $(window).unbind('.infscr');
        $load_button.click(function() {

          $mk_container.infinitescroll('retrieve');

          return false;

        });
      }

    } else {
      $load_button.hide();
    }
  });
}


$('.filter-faq li a').click(function() {

  $(this).parent().siblings().children().removeClass('current');
  $(this).addClass('current');

  var filterVal = $(this).attr('data-filter');

  if (filterVal === '') {
    $('.mk-faq-container .mk-faq-toggle').slideDown(200).removeClass('hidden');
  } else {
    $('.mk-faq-container .mk-faq-toggle').each(function() {
      if (!$(this).hasClass(filterVal)) {
        $(this).slideUp(200).addClass('hidden');
      } else {
        $(this).slideDown(200).removeClass('hidden');
      }
    });
  }
  return false;
});;/* reload elements on reload */
/* -------------------------------------------------------------------- */

function mk_load_isotop_enabled_scripts() {
if ($.exists('.mk-blog-container') || $.exists('.mk-portfolio-container') || $.exists('.mk-news-container') || $.exists('.mk-gallery-shortcode')) {
    $(window).unbind('keydown');
    loops_iosotop_init();
    isotop_load_fix();
}
}
;/* Fix isotop layout */
/* -------------------------------------------------------------------- */

function isotop_load_fix() {

  "use strict";



  if ($.exists('.mk-blog-container') || $.exists('.mk-portfolio-container') || $.exists('.mk-news-container') || $.exists('.mk-gallery-shortcode')) {
    $('.mk-blog-container, .mk-portfolio-container, .mk-news-container, .mk-gallery-shortcode').each(function () {
      $(this).animate({
        'opacity': 1
      }, 1000).siblings('.mk-preloader').fadeOut(300);

    });
    
  }

}



;/* Recent Works Widget */
/* -------------------------------------------------------------------- */

function mk_portfolio_widget() {

  "use strict";

  $('.widget_recent_portfolio li').each(function () {

    $(this).find('.portfolio-widget-thumb').hover(function () {

      $(this).siblings('.portfolio-widget-info').animate({
        'opacity': 1
      }, 200);
    }, function () {

      $(this).siblings('.portfolio-widget-info').animate({
        'opacity': 0
      }, 200);
    });

  });
}


;/* Contact Form */
/* -------------------------------------------------------------------- */

function mk_contact_form() {

  "use strict";

  if ($.tools.validator != undefined) {
    $.tools.validator.addEffect("contact_form", function(errors) {
      $.each(errors, function(index, error) {
        var input = error.input;

        input.addClass('mk-invalid');
      });
    }, function(inputs) {
      inputs.removeClass('mk-invalid');
    });

    $(".captcha-change-image").on("click", function(e) {
        e.preventDefault();
        changeCaptcha();
    });

    $(".captcha-form").each(function() {
      $(this).on("focus", function() {
        $(this).attr("placeholder", mk_captcha_placeholder).removeClass('contact-captcha-invalid contact-captcha-valid');
      });
    })

    var changeCaptcha = function() {
        $(".captcha-image").attr("src", mk_theme_dir + "/captcha/captcha.php?"+Math.random()); 
    }

    var sendForm;
    var checkCaptcha = function(form, enteredCaptcha) {
        $.get( mk_theme_dir + "/captcha/captcha-check.php", { captcha: enteredCaptcha }).done(function( data ) {
          if(data != "ok") {
            changeCaptcha();
            form.find(".captcha-form").val("").addClass('contact-captcha-invalid').attr("placeholder", mk_captcha_invalid_txt);
          } else {
            sendForm();
            changeCaptcha();
            form.find(".captcha-form").val("").addClass('contact-captcha-valid').attr("placeholder", mk_captcha_correct_txt);
          }
        });
    }

    $('.mk-contact-form').validator({
      effect: 'contact_form'
    }).submit(function(e) {
      var form = $(this);
      if (!e.isDefaultPrevented()) {

        var data = {
          action: 'mk_contact_form',
          to: form.find('input[name="contact_to"]').val().replace("*", "@"),
          name: form.find('input[name="contact_name"]').val(),
          first_name: form.find('input[name="contact_first_name"]').val(),
          last_name: form.find('input[name="contact_last_name"]').val(),
          phone: form.find('input[name="contact_phone"]').val(),
          email: form.find('input[name="contact_email"]').val(),
          website: form.find('input[name="contact_website"]').val(),
          content: form.find('textarea[name="contact_content"]').val()
        };

        sendForm = function() {
          progressButton.loader(form);
          $.post(ajaxurl, data, function(response) {
            form.find('.mk-contact-loading').fadeOut('slow');
            form.find('input#contact_email, input#contact_name, textarea').val("");
            progressButton.success(form);
          });
        }; 

        var enteredCaptcha = form.find('input[name="captcha"]').val();

        if(form.find('.captcha-form').length){
          checkCaptcha(form, enteredCaptcha);
        } else {
          sendForm();
        }

        e.preventDefault();
      }
    });

  }
}

/* Ajax Login Form */
/* -------------------------------------------------------------------- */

function mk_login_form() {

  $('form.mk-login-form').each(function() {
    var $this = $(this);
    $this.on('submit', function(e) {
      $('p.mk-login-status', $this).show().text(ajax_login_object.loadingmessage);
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajax_login_object.ajaxurl,
        data: {
          'action': 'ajaxlogin',
          'username': $('#username', $this).val(),
          'password': $('#password', $this).val(),
          'security': $('#security', $this).val()
        },
        success: function(data) {
          $('p.mk-login-status', $this).text(data.message);
          if (data.loggedin === true) {
            document.location.href = ajax_login_object.redirecturl;
          }
        }
      });
      e.preventDefault();
    });
  });
}


/* Progress Button */
/* -------------------------------------------------------------------- */

var progressButton = {
    loader: function(form) {
        var $form = form,
            progressBar = $form.find(".mk-progress-button .mk-progress-inner"),
            buttonText = $form.find(".mk-progress-button .mk-progress-button-content"),
            progressButton = new TimelineLite();

        progressButton
            .to(progressBar, 0, {
                width: "100%",
                scaleX: 0,
                scaleY: 1
            })
            .to(buttonText, .3, {
                y: -5
            })
            .to(progressBar, 1.5, {
                scaleX: 1,
                ease: Power2.easeInOut
            }, "-=.1")
            .to(buttonText, .3, {
                y: 0
            })
            .to(progressBar, .3, {
                scaleY: 0
            });
    },

    success: function(form) {
        var $form = form,
            buttonText = $form.find(".mk-button .mk-progress-button-content, .mk-contact-button .mk-progress-button-content"),
            successIcon = $form.find(".mk-progress-button .state-success"),
            progressButtonSuccess = new TimelineLite({
                onComplete: hideSuccessMessage
            });

        progressButtonSuccess
            .to(buttonText, .3, {
                paddingRight: 20,
                ease: Power2.easeInOut
            }, "+=1")
            .to(successIcon, .3, {
                opacity: 1
            })
            .to(successIcon, 2, {
                opacity: 1
            });

        function hideSuccessMessage() {
            progressButtonSuccess.reverse()
        }
    },

    error: function(form) {
        var $form = form,
            buttonText = $form.find(".mk-button .mk-progress-button-content, .mk-contact-button .mk-progress-button-content"),
            errorIcon = $form.find(".mk-progress-button .state-error"),
            progressButtonError = new TimelineLite({
                onComplete: hideErrorMessage
            });

        progressButtonError
            .to(buttonText, .3, {
                paddingRight: 20
            }, "+=1")
            .to(errorIcon, .3, {
                opacity: 1
            })
            .to(errorIcon, 2, {
                opacity: 1
            });

        function hideErrorMessage() {
            progressButtonError.reverse()
        }
    }
};/* Blog Loop Carousel Shortcode */
/* -------------------------------------------------------------------- */


function mk_blog_carousel() {

  "use strict";

  if (!$.exists('.mk-blog-showcase')) {
    return;
  }
  $('.mk-blog-showcase ul li').each(function () {

    $(this).on('hover', function () {

      $(this).siblings('li').removeClass('mk-blog-first-el').end().addClass('mk-blog-first-el');

    });

  });


}



;/* Header Fixed */
function mk_header_scripts() {


  if($(window).width() < mk_responsive_nav_width || !($.exists('#mk-header .mk-header-holder'))) return false;



  var $header = $('#mk-header'),
    $sticky_style = $header.attr('data-sticky-style'),
    $sticky_offset = $header.attr('data-sticky-offset'),
    $header_style = $header.attr('data-header-style'),
    $ChopScroll_speed = 100;   

//if ($header.hasClass('header-style-4')) return false;
  

if($header_style != 2) {
  if ($sticky_offset == 'header') {
    $sticky_offset = parseInt($header.attr('data-height'));
  } else {
    $sticky_offset = String($sticky_offset);
    $sticky_offset = $sticky_offset;
    
  }
} else {

  var wp_admin_height = $header_toolbar_h = 0;

  if ($.exists('.mk-header-toolbar')) {
      $header_toolbar_h = $('.mk-header-toolbar').outerHeight();
  }

  $ChopScroll_speed = 10;
  $sticky_offset = parseInt($header.attr('data-height')) + $header_toolbar_h;
  $sticky_offset = $sticky_offset;

}

  var scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
      viewportPercent = $(window).height()/100,
      $sticky_offset = Math.abs(parseInt($sticky_offset));
      
      if($header.data('sticky-offset') == 'header') {    
          var offsetCalc = $sticky_offset;
          // console.log($sticky_offset);
      } else {
          var offsetCalc = (viewportPercent * $sticky_offset);
      }
   
  var pageIntro;
  var stick_it = function() {
      scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
      pageIntro = $('body').data('intro');
      //console.log(scrollY);

      // console.log('Page Intro:' + pageIntro);
      //console.log(scrollY);
      if (!pageIntro) {
        if (scrollY > offsetCalc && scrollY > 0) {
            if ($sticky_style == 'fixed' || $header_style == 2) {
              $header.addClass('header-sticky-ready');
            } else {
              if(!$header.hasClass('header-sticky-ready')) {
                $header.addClass('header-sticky-prepare');
                $('.mk-header-padding-wrapper').addClass('enable-padding');
                setTimeout(function() {
                  $header.addClass('header-sticky-ready');
                }, 300);  
              }
            }
        } else {
              
              $header.removeClass('header-sticky-ready');
              $header.removeClass('header-sticky-prepare');
              $('.mk-header-padding-wrapper').removeClass('enable-padding');
        }
      }
      // console.log(scrollY);
      // console.log((viewportPercent * $sticky_offset));
    }

    new ChopScroll(stick_it, $ChopScroll_speed);
};/* Header Search Form */
/* -------------------------------------------------------------------- */

function mk_header_searchform() {


  "use strict";

  $('.mk-header-toolbar #mk-header-searchform .text-input').on('focus', function () {

    if ($('.mk-header-toolbar #mk-header-searchform .text-input').hasClass('on-close-state')) {
      $('.mk-header-toolbar #mk-header-searchform .text-input').removeClass('on-close-state').animate({
        'width': '200px'
      }, 200);
      return false;
    }
  });

  $(".mk-header-toolbar .mk-header-searchform").click(function (event) {
    if (event.stopPropagation) {
      event.stopPropagation();
    } else if (window.event) {
      window.event.cancelBubble = true;
    }
  });


  $("html").click(function () {
    $(this).find(".mk-header-toolbar #mk-header-searchform .text-input").addClass('on-close-state').animate({
      'width': 90
    }, 300);
  });

}



;/* Milestone Number Shortcode */
/* -------------------------------------------------------------------- */

function mk_milestone() {

  "use strict";

  if ($.exists('.mk-milestone')) {

    var play;
    if ( !is_touch_device() ) {
      play = ':in-viewport';
    }  else {
      play = '';
    }

    $('.mk-milestone'+play).each(function () {
      var el_this = $(this),
        stop_number = el_this.find('.milestone-number').attr('data-stop'),
        animation_speed = parseInt(el_this.find('.milestone-number').attr('data-speed'));

      if (!$(this).hasClass('scroll-animated')) {
        $(this).addClass('scroll-animated');

        $({
          countNum: el_this.find('.milestone-number').text()
        }).animate({
          countNum: stop_number
        }, {
          duration: animation_speed,
          easing: 'linear',
          step: function () {
            el_this.find('.milestone-number').text(Math.floor(this.countNum));
          },
          complete: function () {
            el_this.find('.milestone-number').text(this.countNum);
          }
        });
      }
    });

  }
}



;/* Skill Meter and Charts */
/* -------------------------------------------------------------------- */

function mk_skill_meter() {

  "use strict";

  if ($.exists('.mk-skill-meter')) {
    if ( !is_touch_device() ) {
      $(".mk-skill-meter .progress-outer:in-viewport").each(function () {
        var $this = $(this);
        if (!$this.hasClass('scroll-animated')) {
          $this.addClass('scroll-animated');
          $this.animate({
            width: $(this).attr("data-width") + '%'
          }, 2000);
        }
      });
    } else {
      $(".mk-skill-meter .progress-outer").each(function () {
        var $this = $(this);
        if (!$this.hasClass('scroll-animated')) {
          $this.addClass('scroll-animated');
          $this.css({
            width: $(this).attr("data-width") + '%'
          });
        }
      });
    }
  }
}


function mk_charts() {

  "use strict";

  if ($.exists('.mk-chart')) {
    $(window).on("load", function () {
      $('.mk-chart').each(function () {
        var $this, $parent_width, $chart_size;
        $this = $(this);
        $parent_width = $(this).parent().width();
        $chart_size = $this.attr('data-barSize');
        if ($parent_width < $chart_size) {
          $chart_size = $parent_width;
          $this.css('line-height', $chart_size);
          $this.find('i').css({
            'line-height': $chart_size + 'px'
          });
        }
        if (!$this.hasClass('chart-animated')) {
          $this.easyPieChart({
            animate: 1300,
            lineCap: 'butt',
            lineWidth: $this.attr('data-lineWidth'),
            size: $chart_size,
            barColor: $this.attr('data-barColor'),
            trackColor: $this.attr('data-trackColor'),
            scaleColor: 'transparent',
            onStep: function (value) {
              this.$el.find('.chart-percent span').text(Math.ceil(value));
            }
          });
        }
      });
    });
  }
}


;/* Google Maps */
/* -------------------------------------------------------------------- */

function mk_google_maps() {


  "use strict";

  $('.mk-advanced-gmaps').each(function() {

    var $this = $(this),
      $id = $this.attr('id'),
      $zoom = parseInt($this.attr('data-zoom')),
      $latitude = $this.attr('data-latitude'),
      $longitude = $this.attr('data-longitude'),
      $address = $this.attr('data-address'),
      $latitude_2 = $this.attr('data-latitude2'),
      $longitude_2 = $this.attr('data-longitude2'),
      $address_2 = $this.attr('data-address2'),
      $latitude_3 = $this.attr('data-latitude3'),
      $longitude_3 = $this.attr('data-longitude3'),
      $address_3 = $this.attr('data-address3'),
      $map_type = $this.attr('data-map-type'),
      $pin_icon = $this.attr('data-pin-icon'),
      $pan_control = $this.attr('data-pan-control') === "true" ? true : false,
      $map_type_control = $this.attr('data-map-type-control') === "true" ? true : false,
      $scale_control = $this.attr('data-scale-control') === "true" ? true : false,
      $draggable = $this.attr('data-draggable') === "true" ? true : false,
      $zoom_control = $this.attr('data-zoom-control') === "true" ? true : false,
      $modify_coloring = $this.attr('data-modify-coloring') === "true" ? true : false,
      $saturation = $this.attr('data-saturation'),
      $hue = $this.attr('data-hue'),
      $lightness = $this.attr('data-lightness'),
      $styles;


    if ($modify_coloring == true) {
      var $styles = [{
        stylers: [{
          hue: $hue
        }, {
          saturation: $saturation
        }, {
          lightness: $lightness
        }, {
          featureType: "landscape.man_made",
          stylers: [{
            visibility: "on"
          }]
        }]
      }];
    }

    

    var map;

    function initialize() {


      var bounds = new google.maps.LatLngBounds();



      var mapOptions = {
        zoom: $zoom,
        panControl: $pan_control,
        zoomControl: $zoom_control,
        mapTypeControl: $map_type_control,
        scaleControl: $scale_control,
        draggable: $draggable,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId[$map_type],
        styles: $styles
      };

      map = new google.maps.Map(document.getElementById($id), mapOptions);
      map.setTilt(45);

      // Multiple Markers

      var markers = [];
      var infoWindowContent = [];

      if ($latitude != '' && $longitude != '') {
        markers[0] = [$address, $latitude, $longitude];
        infoWindowContent[0] = [$address];
      }

      if ($latitude_2 != '' && $longitude_2 != '') {
        markers[1] = [$address_2, $latitude_2, $longitude_2];
        infoWindowContent[1] = [$address_2];
      }

      if ($latitude_3 != '' && $longitude_3 != '') {
        markers[2] = [$address_3, $latitude_3, $longitude_3];
        infoWindowContent[3] = [$address_3];
      }


      var infoWindow = new google.maps.InfoWindow(),
        marker, i;


   
      for (i = 0; i < markers.length; i++) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
          position: position,
          map: map,
          title: markers[i][0],
          icon: $pin_icon
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            if(infoWindowContent[i][0].length > 1) {
              infoWindow.setContent('<div class="info_content"><p>'+infoWindowContent[i][0]+'</p></div>');  
            }
            
            infoWindow.open(map, marker);
          }
        })(marker, i));

        map.fitBounds(bounds);

      }



      var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom($zoom);
        google.maps.event.removeListener(boundsListener);
      });
    }

    google.maps.event.addDomListener(window, "load", initialize);

 
    function googleMapsResize() {
      //google.maps.event.trigger(map, 'resize');
      initialize();
    } 

    var temporaryTabsContainer = $('.mk-tabs');
    if (temporaryTabsContainer.length) {
      temporaryTabsContainer.each(function() {
        google.maps.event.addDomListener($(this)[0], "click", googleMapsResize);
      });

    }


  });


}

function mk_google_maps_height() {


  "use strict";

    $('.mk-advanced-gmaps.full-height-map').each(function() {

      var $this = $(this),
          $window_height = $(window).outerHeight(),
           wp_admin_height = 0,
           header_height = 0;

    if($.exists('#mk-header .mk-header-holder')) {
      header_height = parseInt($('#mk-header').attr('data-sticky-height'));  
    }   
       
    if ($.exists("#wpadminbar")) {
      var wp_admin_height = $("#wpadminbar").outerHeight();
    }
  
      $window_height = $window_height - wp_admin_height - header_height;

      $this.css('height', $window_height);

    });


  };/* Scroll function for main navigation on one page concept */
/* -------------------------------------------------------------------- */


function mk_main_nav_scroll() {


  "use strict";


  //console.log(window.location.href.split('#')[0]);
  var lastId, topMenu = $("#mk-main-navigation, .mk-vm-menu"),
    menuItems = topMenu.find(".menu-item a");


  menuItems.each(function() {
    //console.log();
    var href_attr = $(this).attr('href');


    if (typeof href_attr !== 'undefined' && href_attr !== false) {
      var href = $(this).attr("href").split('#')[0];
      $(this).addClass("one-page-nav-item");
    } else {
      href = "";
    }


    /* if(typeof $(this).attr("href").split('#')[1] != 'undefined') {
      $(this).addClass("one-page-nav-item");
    }*/

    if (href == window.location.href.split('#')[0] && (typeof $(this).attr("href").split('#')[1] != 'undefined')) {
      //console.log($(this).attr("href").split('#')[1]);
      $(this).attr("href", "#" + $(this).attr("href").split('#')[1]);
      $(this).parent().removeClass("current-menu-item");
    }


  });

  var onePageMenuItems = $('.one-page-nav-item'),
    wp_admin_height = 0,
    header_height = 0;

  var scrollItems = onePageMenuItems.map(function() {
    var item = $(this).attr("href");
    if (/^#\w/.test(item) && $(item).length) {
      return $(item);
    }
  });


  if ($.exists("#wpadminbar")) {
    wp_admin_height = $("#wpadminbar").height();
  }

  if (!$.exists('.mk-vm-menuwrapper')) {
    header_height = parseInt($('#mk-header').attr('data-sticky-height'));
  }


  var offsetTop = 0;
  onePageMenuItems.click(function(e) {
    var href = $(this).attr("href");
    if (typeof $(href).offset() != 'undefined') {
      var href_top = $(href).offset().top;
    } else {
      var href_top = 0;
    }

    if ($.exists("#wpadminbar")) {
      var wp_admin_height = $("#wpadminbar").height();
      // console.log(wp_admin_height);
    } else {
      wp_admin_height = 0;
    }

    if ($(window).width() <= mk_responsive_nav_width) {
      header_height = 0;
    }

    offsetTop = href === "#" ? 0 : href_top - (wp_admin_height + header_height - 1);

    $('html, body').stop().animate({
      scrollTop: offsetTop
    }, {
      duration: 1200,
      easing: "easeInOutExpo"
    });
    //TweenLite.to(window, 1.2, {scrollTo:{y: offsetTop}, ease:Expo.easeInOut});

    e.preventDefault();
  });


  function hashLink() {
    var hash = window.location.hash;
    if(hash.length) {
        setTimeout(function() {
          window.scrollTo(0, 0);
        }, 1);

        $(window).load(function() {

          if ($.exists("#wpadminbar")) {
            var wp_admin_height = $("#wpadminbar").height();
          } else {
            wp_admin_height = 0;
          }

         var $target = $('#'+hash.substring(1)),
              offsetHash = $target.offset().top,
              offset;

          offset = offsetHash - (wp_admin_height + header_height - 1);

          setTimeout(function() {
            $('html, body').stop().animate({
              scrollTop: offset
            }, {
              duration: 1200,
              easing: "easeInOutExpo"
            });
          }, 500);
        });
    }
  }
  hashLink();

  new ChopScroll(function() {
    if (!scrollItems.length) return false;
    var fromTop = $(window).scrollTop() + (wp_admin_height + header_height);
    var cur = scrollItems.map(function() {
      if ($(this).offset().top - 200 < fromTop) return this; // This is purely empirical - we don't look at the top of screen but a little bit lower, so lets switch section at this point.
    });
    cur = cur[cur.length - 1];
    var id = cur && cur.length ? cur[0].id : "";

    if (lastId !== id) {
      lastId = id;

      onePageMenuItems.parent().removeClass("current-menu-item current-menu-ancestor current-menu-parent");
      if (id.length) {
        onePageMenuItems.filter("[href=#" + id + "]").parent().addClass("current-menu-item");
      }
    }
  }, 200);
}


mk_main_nav_scroll();;/* Swipe Slideshow */
/* -------------------------------------------------------------------- */


function mk_swipe_slider() {


  "use strict";

  $('.mk-swiper-slider').each(function() {

      if ($(this).data('state') != 'init') {

        $(this).data('state', 'init');

        var $this = $(this),
          $thumbs = $this.parent().siblings('.gallery-thumbs-small'),
          $next_arrow = $this.find('.mk-swiper-next'),
          $prev_arrow = $this.find('.mk-swiper-prev'),
          $direction = $this.attr('data-direction'),
          $pagination = $this.attr('data-pagination') == "true" ? true : false,
          $slideshowSpeed = $this.attr('data-slideshowSpeed'),
          $animationSpeed = $this.attr('data-animationSpeed'),
          $controlNav = $this.attr('data-controlNav') == "true" ? true : false,
          $directionNav = $this.attr('data-directionNav') == "true" ? true : false,
          $freeModeFluid = $this.attr('data-freeModeFluid') == "true" ? true : false,
          $freeMode = $this.attr('data-freeMode') == "true" ? true : false,
          $mousewheelControl = $this.attr('data-mousewheelControl') == "true" ? true : false,
          $loop = $this.attr('data-loop') == "true" ? true : false,
          $slidesPerView = $this.attr('data-slidesPerView'),
          vieportWidth = $(window).width();

        if ($pagination === true) {
          var $pagination_class = '#' + $this.attr('id') + ' .swiper-pagination';
        } else {
          var $pagination_class = false;
        }

        if ($slidesPerView != 'auto' && $slidesPerView > 2) {
          if (vieportWidth > 768) {
            var slidesOnInit = $slidesPerView;
          }
          if (vieportWidth <= 768) {
            var slidesOnInit = 2;
          }
          if (vieportWidth <= 568) {
            var slidesOnInit = 1;
          }
        } else {
          slidesOnInit = $slidesPerView;
        }

        var mk_swiper = $(this).swiper({
          mode: $direction,
          loop: $loop,
          freeMode: $freeMode,
          pagination: $pagination_class,
          freeModeFluid: $freeModeFluid,
          autoplay: $slideshowSpeed,
          speed: $animationSpeed,
          calculateHeight: true,
          grabCursor: true,
          useCSS3Transforms: false,
          mousewheelControl: $mousewheelControl,
          paginationClickable: true,
          slidesPerView: slidesOnInit,
          resizeReInit: true,
          onSwiperCreated: function(swiper) {

          },
          onSlideChangeStart: function() {
            $thumbs.find('.active-item').removeClass('active-item');
            $thumbs.find('a').eq(mk_swiper.activeIndex).addClass('active-item');
          }
        });


        $prev_arrow.click(function(e) {
          mk_swiper.swipePrev();
        });

        $next_arrow.click(function(e) {
          mk_swiper.swipeNext();
        });


        $thumbs.find('a').on('touchstart mousedown', function(e) {
          e.preventDefault();
          $thumbs.find('.active-item').removeClass('active-item');
          $(this).addClass('active-item');
          mk_swiper.swipeTo($(this).index());
        });

        $thumbs.find('a').click(function(e) {
          e.preventDefault();
        });


        // change per view size for smaller screens to fixed value on resize
        if ($slidesPerView != 'auto' && $slidesPerView > 2) {
          $(window).on("resize", function() {
            vieportWidth = $(window).width();

            if (vieportWidth > 768) {
              mk_swiper.params.slidesPerView = $slidesPerView;
              setTimeout(function() {
                mk_swiper.reInit();
                setTimeout(mk_swiper.swipePrev(), 200);
              }, 200);
              // console.log('full'); 
            }
            if (vieportWidth <= 768) {
              mk_swiper.params.slidesPerView = 2;
              setTimeout(function() {
                mk_swiper.reInit();
                setTimeout(mk_swiper.swipePrev(), 200);
              }, 200);
              // console.log('medium');
            }
            if (vieportWidth <= 568) {
              mk_swiper.params.slidesPerView = 1;
              setTimeout(function() {
                mk_swiper.reInit();
                setTimeout(mk_swiper.swipePrev(), 200);
              }, 200);
              // console.log('small');
            }

          });
      }


        }

      });




};/* Edge Slideshow */
/* -------------------------------------------------------------------- */


function mk_edge_slider_init() {


  "use strict";

  $('.mk-edge-slider').each(function() {
    var $slider_wrapper = $(this),
      $theme_container = $('#mk-theme-container'),
      $next_arrow = $slider_wrapper.find('.mk-edge-next'),
      $prev_arrow = $slider_wrapper.find('.mk-edge-prev'),
      $pause = $slider_wrapper.attr('data-pause'),
      $first_el = $slider_wrapper.attr('data-first'),
      $speed = $slider_wrapper.attr('data-speed'),
      $animation = $slider_wrapper.attr('data-animation'),
      $height = $slider_wrapper.attr('data-height'),
      $fullHeight = $slider_wrapper.attr('data-fullHeight'),
      $header_height = 0,
      edge_height = 0,
      loop = true,
      $pagination = $slider_wrapper.attr('data-pagination') == "true" ? true : false;

    if ($pagination === true) {
      var $pagination_class = '#' + $slider_wrapper.attr('id') + ' .swiper-pagination';

      $($pagination_class).on('click', 'span', function() {
        mk_swiper.swipeTo($(this).index(), 500);
      });

      $slider_wrapper.find('.edge-skip-slider').css('bottom', '14%');
    } else {
      var $pagination_class = false;
    }


    if($animation == "kenburned") {
      loop = false;
    }


    var animationDimensions = function() {
        if ($.exists('#mk-header.sticky-header') && !$('#mk-header').hasClass('transparent-header')) {
            $header_height = parseInt($('#mk-header.sticky-header').attr('data-sticky-height'));
        }
        if ($fullHeight === 'true') {
            edge_height = global_window_height - $header_height - global_admin_bar_height;
        } else {
            edge_height = $height;
        }
        // console.log('edge_height: ' + edge_height);
    }
    animationDimensions();

    $(window).on("debouncedresize", function(event) {
        setTimeout(function() {
            mk_edge_slider_resposnive();
            animationDimensions();
        }, 50);
    });


    $slider_wrapper.find('.mk-animate-element').removeClass('mk-animate-element fade-in scale-up right-to-left left-to-right bottom-to-top top-to-bottom flip-x flip-y');

    var mk_swiper = $slider_wrapper.swiper({
      mode: 'horizontal',
      loop: loop,
      grabCursor: true,
      useCSS3Transforms: true,
      mousewheelControl: false,
      pagination: $pagination_class,
      paginationClickable: true,
      freeModeFluid: true,
      speed: $speed,
      autoplay: $pause,
      progress: true,
      autoplayDisableOnInteraction: false,
      onSwiperCreated: function(swiper) {

        var current_eq, prev_eq, next_eq;
        if(loop == false) {
          current_eq = 0;
          prev_eq = swiper.slides.length - 1;
          next_eq = 1;
        } else {
          current_eq = 1;
          prev_eq = 0;
          next_eq = 2;           
        }

        if ($theme_container.hasClass('mk-transparent-header')) {
          if ($first_el == 'true') {
            $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($slider_wrapper.find('.swiper-slide').eq(current_eq).attr('data-header-skin') + '-header-skin');
          }
        }

        var currentSlide = $slider_wrapper.find('.swiper-slide').eq(current_eq),
          currentSkin = $slider_wrapper.find('.swiper-slide').eq(current_eq).attr("data-header-skin");

        //if ($first_el == 'true') {    
        $('.mk-edge-nav a').attr('data-skin', currentSkin);
        $('.swiper-pagination').attr('data-skin', currentSkin);
        $('.edge-skip-slider').attr('data-skin', currentSkin);
        //}

        // if (mk_detect_ie() == false) {

          var prev_active_slide = $slider_wrapper.find('.swiper-slide').eq(prev_eq).find('.edge-slide-content .edge-title').text(),
            next_active_slide = $slider_wrapper.find('.swiper-slide').eq(next_eq).find('.edge-slide-content .edge-title').text();
          //console.log(prev_active_slide + "---" + next_active_slide);
          var prev_active_slide_bg = $slider_wrapper.find('.swiper-slide').eq(prev_eq).find('.mk-section-image').css('background-image'),
            next_active_slide_bg = $slider_wrapper.find('.swiper-slide').eq(next_eq).find('.mk-section-image').css('background-image');
          // console.log(prev_active_slide_bg + "---" + next_active_slide_bg);
          var prev_active_slide_bg_video = $slider_wrapper.find('.swiper-slide').eq(prev_eq).find('.mk-video-section-touch').css('background-image'),
            next_active_slide_bg_video = $slider_wrapper.find('.swiper-slide').eq(next_eq).find('.mk-video-section-touch').css('background-image');

          //console.log(prev_active_slide_bg_video);
          //console.log(next_active_slide_bg_video);

          var prev_active_slide_bg_color = $slider_wrapper.find('.swiper-slide').eq(prev_eq).find('.mk-section-image').css('background-color'),
            next_active_slide_bg_color = $slider_wrapper.find('.swiper-slide').eq(next_eq).find('.mk-section-image').css('background-color');

          if (prev_active_slide.length > 1) {
            $prev_arrow.find('.prev-item-caption').show().text(prev_active_slide);
            // console.log(prev_active_slide);
          }

          if (typeof prev_active_slide_bg !== 'undefined' && prev_active_slide_bg != "none") {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-image': prev_active_slide_bg
            });
            // console.log(prev_active_slide_bg);
          } else if (typeof prev_active_slide_bg_video !== 'undefined' && prev_active_slide_bg_video != "none") {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-image': prev_active_slide_bg_video
            });
            // console.log(prev_active_slide_bg_video);
          } else if (prev_active_slide_bg_color !== 'undefined') {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-color': prev_active_slide_bg_color
            });
            // console.log(prev_active_slide_bg_color);
          }

          if (typeof next_active_slide !== 'undefined') {
            $next_arrow.find('.next-item-caption').show().text(next_active_slide);
          }

          if (typeof next_active_slide_bg !== 'undefined' && next_active_slide_bg != "none") {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-image': next_active_slide_bg
            });
          } else if (typeof next_active_slide_bg_video !== 'undefined' && next_active_slide_bg_video != "none") {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-image': next_active_slide_bg_video
            });
          } else if (typeof next_active_slide_bg_color !== 'undefined') {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-color': next_active_slide_bg_color
            });
          }

          if (!$('#mk-header').hasClass('transparent-header-sticky')) {
            if ($first_el == 'true') {
              $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($slider_wrapper.find('.swiper-slide').eq(current_eq).attr('data-header-skin') + '-header-skin');
            }
          }



        // } else {
        //   $next_arrow.find('.next-item-caption, .edge-nav-bg').css('display', 'none');
        //   $prev_arrow.find('.prev-item-caption, .edge-nav-bg').css('display', 'none');
        // }

        if ($pagination === true) {
          $('#' + $slider_wrapper.attr('id') + ' .swiper-pagination span').append('<a href="#"></a>');
        }


      },
      onSlideChangeEnd: function() {


        var current_eq, prev_eq, next_eq;
        if(loop == false) {
          current_eq = mk_swiper.activeIndex;
          prev_eq = mk_swiper.activeIndex -1 ;
          next_eq = mk_swiper.activeIndex + 1;

          if(prev_eq < 0) {
            prev_eq = mk_swiper.slides.length - 1;
          }
          if(next_eq > mk_swiper.slides.length - 1) {
            next_eq = 0;
          }
        } else {
          current_eq = mk_swiper.activeLoopIndex + 1;
          prev_eq = mk_swiper.activeLoopIndex;
          next_eq = mk_swiper.activeLoopIndex + 2;           
        }

        if ($theme_container.hasClass('mk-transparent-header')) {
          if ($first_el == 'true') {
            $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($(mk_swiper.getSlide(current_eq)).attr('data-header-skin') + '-header-skin');
          }
        }

        if (mk_detect_ie() == false) {

          var currentSlide = $(mk_swiper.activeSlide()),
            currentSkin = currentSlide.attr("data-header-skin");

          //if ($first_el == 'true') {    
          $('.mk-edge-nav a').attr('data-skin', currentSkin);
          $('.swiper-pagination').attr('data-skin', currentSkin);
          $('.edge-skip-slider').attr('data-skin', currentSkin);
          //  }

          var prev_active_slide = $(mk_swiper.getSlide(prev_eq)).find('.edge-slide-content .edge-title').text(),
            next_active_slide = $(mk_swiper.getSlide(next_eq)).find('.edge-slide-content .edge-title').text();

          var prev_active_slide_bg = $(mk_swiper.getSlide(prev_eq)).find('.mk-section-image').css('background-image'),
            next_active_slide_bg = $(mk_swiper.getSlide(next_eq)).find('.mk-section-image').css('background-image');

          var prev_active_slide_bg_video = $(mk_swiper.getSlide(prev_eq)).find('.mk-video-section-touch').css('background-image'),
            next_active_slide_bg_video = $(mk_swiper.getSlide(next_eq)).find('.mk-video-section-touch').css('background-image');

          var prev_active_slide_bg_color = $(mk_swiper.getSlide(prev_eq)).find('.mk-section-image').css('background-color'),
            next_active_slide_bg_color = $(mk_swiper.getSlide(next_eq)).find('.mk-section-image').css('background-color');

          if (typeof prev_active_slide !== 'undefined') {
            $prev_arrow.find('.prev-item-caption').show().text(prev_active_slide);
            // console.log(prev_active_slide);
          }

          if (typeof prev_active_slide_bg !== 'undefined' && prev_active_slide_bg != "none") {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-image': prev_active_slide_bg
            });
            // console.log(prev_active_slide_bg);
          } else if (typeof prev_active_slide_bg_video !== 'undefined' && prev_active_slide_bg_video != "none") {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-image': prev_active_slide_bg_video
            });
            // console.log(prev_active_slide_bg_video);
          } else if (typeof prev_active_slide_bg_color !== 'undefined') {
            $prev_arrow.find('.edge-nav-bg').show().css({
              'background-color': prev_active_slide_bg_color
            });
            // console.log(prev_active_slide_bg_color);
          }

          if (typeof next_active_slide !== 'undefined') {
            $next_arrow.find('.next-item-caption').show().text(next_active_slide);
          }

          if (typeof next_active_slide_bg !== 'undefined' && next_active_slide_bg != "none") {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-image': next_active_slide_bg
            });
          } else if (typeof next_active_slide_bg_video !== 'undefined' && next_active_slide_bg_video != "none") {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-image': next_active_slide_bg_video
            });
          } else if (typeof next_active_slide_bg_color !== 'undefined') {
            $next_arrow.find('.edge-nav-bg').show().css({
              'background-color': next_active_slide_bg_color
            });
          }

          if (!$('#mk-header').hasClass('transparent-header-sticky')) {
            if ($first_el == 'true') {
              $('#mk-header.transparent-header').removeClass('light-header-skin dark-header-skin').addClass($(mk_swiper.getSlide(current_eq)).attr('data-header-skin') + '-header-skin');
            }
          }


        } else {
          $next_arrow.find('.next-item-caption, .edge-nav-bg').css('display', 'none');
          $prev_arrow.find('.prev-item-caption, .edge-nav-bg').css('display', 'none');
        }

      },    
      onProgressChange: function(swiper){
                for (var i = 0; i < swiper.slides.length; i++){

                    var slide = swiper.slides[i];
                    var progress = slide.progress;

                    // SLIDER ANIMATION EFFECTS

                    if($animation == "horizontal_curtain") {
                        var translateX, zIndex, transitionTiming;
                            translateX = progress*swiper.width;

                        if (progress<=0) {
                            zIndex = 1;
                            translateX = 0;
                            transitionTiming = 'ease';
                        }
                        else if (progress>0){
                            zIndex = 0;
                            translateX = (progress*swiper.width)/2;
                            transitionTiming = 'ease';
                        }

                        swiper.setTransform(slide,'translate3d('+(translateX/2)+'px,0,0)');
                        slide.style.webkitTransitionTimingFunction = transitionTiming;
                        slide.style.zIndex = zIndex;
                    }

                    if($animation == "perspective_flip") {
                        var translateX, translateY, rotateX;

                            translateX = progress*swiper.width;
                            translateY = progress*edge_height;

                        if (progress>=0) {
                            rotateX = 0;
                        }
                        else if (progress<0){
                            rotateX = 70;
                        }

                        swiper.setTransform(slide,'translate3d('+translateX+'px,'+ (-translateY) +'px,0) rotateX('+rotateX+'deg)');
                    }

                    if($animation == "vertical_slide") {
                        var translateX, translateY;

                            translateX = progress*swiper.width;
                            translateY = progress*edge_height;

                        swiper.setTransform(slide,'translate3d('+translateX+'px,'+ (-translateY) +'px,0)');
                    }

                    if($animation == "fade") {
                        var translate, opacity, zIndex;

                        if (progress == 0) {
                            zIndex = 1;
                        }
                        else {
                            zIndex = 0;
                        }
                            opacity = 1 - Math.min(Math.abs(progress),1);
                            translate = progress*swiper.width;

                        swiper.setTransform(slide,'translate3d('+translate+'px,0,0)');
                        slide.style.opacity = opacity;
                        slide.style.zIndex = zIndex;
                    }

                    if($animation == "kenburned") {
                        var translateX, opacity, zIndex;

                        if (progress == 0) {
                            zIndex = 1;
                        }
                        else {
                            zIndex = 0;
                        }

                            translateX = progress*swiper.width;
                            opacity = 1 - Math.min(Math.abs(progress),1);

                        swiper.setTransform(slide,'translate3d('+translateX+'px,0,0)');
                        slide.style.opacity = opacity;
                        slide.style.zIndex = zIndex;

                    }

                    if($animation == "zoom_out") {
                        var scale, translateX, translateY, opacity, zIndex;

                            translateX = progress*swiper.width;

                        if (progress<=0) {
                            opacity = 1;
                            scale = 1;
                            zIndex = 1;
                            translateY = progress*edge_height;
                        }
                        else if (progress>0){
                            opacity = (1 - Math.min(Math.abs(progress),1))/2;
                            scale = 1 - Math.min(Math.abs(progress/2),1);
                            zIndex = 0;
                            translateY = 0;
                        }

                        swiper.setTransform(slide,'translate3d('+translateX+'px,'+ -translateY +'px,0)  scale('+scale+')');
                        slide.style.opacity = opacity;
                        slide.style.zIndex = zIndex;
                    }

                    if($animation == "zoom") {
                        var scale, scaleContent, translate, opacity, zIndex;

                        if (progress == 0) {
                            zIndex = 1;
                        }
                        else {
                            zIndex = 0;
                        }

                        if (progress<=0) {
                            opacity = 1 - Math.min(Math.abs(progress),1);
                            scale = 1 - Math.min(Math.abs(progress/12),1);
                            scaleContent = 1;
                            translate = progress*swiper.width;
                        }
                        else {
                            opacity = 0;
                            scale = 1 + Math.min(Math.abs(progress/6),1);
                            translate = progress*swiper.width;
                        }

                        console.log(progress)

                        slide.style.opacity = opacity;
                        slide.style.zIndex = zIndex;
                        swiper.setTransform(slide,'translate3d('+translate+'px,0,0) scale('+scale+')');
                    }

                }
              },
              onTouchStart:function(swiper){
                for (var i = 0; i < swiper.slides.length; i++){
                  swiper.setTransition(swiper.slides[i], 0);
                }
              },
              onSetWrapperTransition: function(swiper, speed) {
                for (var i = 0; i < swiper.slides.length; i++){
                  swiper.setTransition(swiper.slides[i], speed);
                }
              }
    });

    if(loop == true) {
      $prev_arrow.click(function(e) {
        mk_swiper.swipePrev();
        e.preventDefault();
      });

      $next_arrow.click(function(e) {
        mk_swiper.swipeNext();
        e.preventDefault();
      });
    } else {
      var first_eq = 0,
          last_eq = mk_swiper.slides.length - 1;

      $prev_arrow.click(function(e) {
        var current_eq = mk_swiper.activeIndex;
        if(current_eq == first_eq) {
          mk_swiper.swipeTo(last_eq);
        } else {
          mk_swiper.swipePrev();
        }
        e.preventDefault();
      });

      $next_arrow.click(function(e) {
        var current_eq = mk_swiper.activeIndex;
        if(current_eq == last_eq) {
          mk_swiper.swipeTo(first_eq);
        } else {
          mk_swiper.swipeNext();
        }
        e.preventDefault();
      });
    }


  });

}


function mk_edge_slider_resposnive() {

  "use strict";

  $('.mk-edge-slider').each(function() {


    var $this = $(this),
      $items = $this.find('.edge-slider-holder, .swiper-slide'),
      $height = $this.attr('data-height'),
      $fullHeight = $this.attr('data-fullHeight'),
      $skip_header_fix = 0,
      $header_height = 0;

    var $window_height = $(window).outerHeight();


    if ($.exists('#wpadminbar')) {
      $header_height += $('#wpadminbar').outerHeight();
    }


    /* if ($.exists('.mk-header-toolbar')) {
      $header_height += $('.mk-header-toolbar').outerHeight();
    }*/


    if (!$('#mk-theme-container').hasClass('mk-transparent-header') && $.exists('.mk-header-holder')) {
      $skip_header_fix += parseInt($('#mk-header').attr('data-height'));
      $header_height += $skip_header_fix;
    }


    if ($(window).width() < 780) {

      $window_height = 600;

    } else if ($fullHeight == 'true') {

      $window_height = $window_height - $header_height;

    } else {

      $window_height = $height;
    }

    $items.css('height', $window_height);

    $this.find('.swiper-slide').each(function() {


      var $this = $(this),
        $content = $this.find('.edge-slide-content');

      if ($this.hasClass('left_center') || $this.hasClass('center_center') || $this.hasClass('right_center')) {

        var $this_height_half = $content.outerHeight() / 2;
        if ($content.outerHeight() < $window_height) {
          var $window_half = $window_height / 2;
          $content.css('marginTop', ($window_half - $this_height_half));
        }

      }

      if ($this.hasClass('left_bottom') || $this.hasClass('center_bottom') || $this.hasClass('right_bottom')) {

        if ($content.outerHeight() < $window_height) {
          var $distance_from_top = $window_height - $content.outerHeight() - 90;
          $content.css('marginTop', ($distance_from_top));
        }
      }


    });

    var header_padding_fix = 0;
    header_padding_fix = parseInt($('#mk-header').attr('data-sticky-height'));

    if ($('.mk-nav-responsive-link').css('display') != 'none') {
      $skip_header_fix += parseInt($('#mk-header').attr('data-height'));
      header_padding_fix = 0;
    }

    // console.log($header_height);
    var skip = $window_height - header_padding_fix + $skip_header_fix;


    $this.find('.edge-skip-slider').bind("click", function(e) {
      $("html, body").stop().animate({ scrollTop: skip }, 1000, "easeInOutExpo");
      e.preventDefault();
    });

    $this.find('.edge-slider-loading').fadeOut();
  });

}



function mk_flexslider_init() {

  "use strict";

  jQuery('.mk-flexslider.mk-script-call').each(function() {
    var $this = jQuery(this),
      $selector = $this.attr('data-selector'),
      $animation = $this.attr('data-animation'),
      $easing = $this.attr('data-easing'),
      $direction = $this.attr('data-direction'),
      $smoothHeight = $this.attr('data-smoothHeight') == "true" ? true : false,
      $slideshowSpeed = $this.attr('data-slideshowSpeed'),
      $animationSpeed = $this.attr('data-animationSpeed'),
      $controlNav = $this.attr('data-controlNav') == "true" ? true : false,
      $directionNav = $this.attr('data-directionNav') == "true" ? true : false,
      $pauseOnHover = $this.attr('data-pauseOnHover') == "true" ? true : false,
      $isCarousel = $this.attr('data-isCarousel') == "true" ? true : false;

    if ($selector != undefined) {
      var $selector_class = $selector;
    } else {
      var $selector_class = ".mk-flex-slides > li";
    }

    if ($isCarousel == true) {
      var $itemWidth = parseInt($this.attr('data-itemWidth')),
        $itemMargin = parseInt($this.attr('data-itemMargin')),
        $minItems = parseInt($this.attr('data-minItems')),
        $maxItems = parseInt($this.attr('data-maxItems')),
        $move = parseInt($this.attr('data-move'));
    } else {
      var $itemWidth = $itemMargin = $minItems = $maxItems = $move = 0;
    }

    $this.flexslider({
      selector: $selector_class,
      animation: $animation,
      easing: $easing,
      direction: $direction,
      smoothHeight: $smoothHeight,
      slideshow: true,
      slideshowSpeed: $slideshowSpeed,
      animationSpeed: $animationSpeed,
      controlNav: $controlNav,
      directionNav: $directionNav,
      pauseOnHover: $pauseOnHover,
      prevText: "",
      nextText: "",
      itemWidth: $itemWidth,
      itemMargin: $itemMargin,
      minItems: $minItems,
      maxItems: $maxItems,
      move: $move,
    });

  });

}


function mk_ajax_lightbox_init() {

  "use strict";

  jQuery(".mk-lightbox").fancybox({
    padding: 15,
    margin: 15,

    width: 800,
    height: 600,
    minWidth: 100,
    minHeight: 100,
    maxWidth: 9999,
    maxHeight: 9999,
    pixelRatio: 1, // Set to 2 for retina display support

    autoSize: true,
    autoHeight: false,
    autoWidth: false,

    autoResize: true,
    fitToView: true,
    aspectRatio: false,
    topRatio: 0.5,
    leftRatio: 0.5,

    scrolling: 'auto', // 'auto', 'yes' or 'no'
    wrapCSS: '',

    arrows: true,
    closeBtn: true,
    closeClick: false,
    nextClick: false,
    mouseWheel: true,
    autoPlay: false,
    playSpeed: 3000,
    preload: 3,
    modal: false,
    loop: true,
    // Properties for each animation type
    // Opening fancyBox
    openEffect: 'fade', // 'elastic', 'fade' or 'none'
    openSpeed: 200,
    openEasing: 'swing',
    openOpacity: true,
    openMethod: 'zoomIn',

    // Closing fancyBox
    closeEffect: 'fade', // 'elastic', 'fade' or 'none'
    closeSpeed: 200,
    closeEasing: 'swing',
    closeOpacity: true,
    closeMethod: 'zoomOut',

    // Changing next gallery item
    nextEffect: 'none', // 'elastic', 'fade' or 'none'
    nextSpeed: 350,
    nextEasing: 'swing',
    nextMethod: 'changeIn',

    // Changing previous gallery item
    prevEffect: 'none', // 'elastic', 'fade' or 'none'
    prevSpeed: 350,
    prevEasing: 'swing',
    prevMethod: 'changeOut',

    tpl: {
      wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
      image: '<img class="fancybox-image" src="{href}" alt="" />',
      error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
      closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"><i class="mk-moon-close-2"></i></a>',
      next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span><i class="mk-jupiter-icon-arrow-right"></i></span></a>',
      prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span><i class="mk-jupiter-icon-arrow-left"></i></span></a>',
      loading: '<div id="fancybox-loading"><div></div></div>'
    },

  });
}



function mk_swipe_slider_init() {


  "use strict";

  jQuery('.mk-swiper-slider').each(function() {

    if ($(this).data('state') != 'init') {

      $(this).data('state', 'init');

      var $this = jQuery(this),
        $thumbs = $this.parent().siblings('.gallery-thumbs-small'),
        $next_arrow = $this.find('.mk-swiper-next'),
        $prev_arrow = $this.find('.mk-swiper-prev'),
        $direction = $this.attr('data-direction'),
        $pagination = $this.attr('data-pagination') == "true" ? true : false,
        $slideshowSpeed = $this.attr('data-slideshowSpeed'),
        $animationSpeed = $this.attr('data-animationSpeed'),
        $controlNav = $this.attr('data-controlNav') == "true" ? true : false,
        $directionNav = $this.attr('data-directionNav') == "true" ? true : false,
        $freeModeFluid = $this.attr('data-freeModeFluid') == "true" ? true : false,
        $freeMode = $this.attr('data-freeMode') == "true" ? true : false,
        $mousewheelControl = $this.attr('data-mousewheelControl') == "true" ? true : false,
        $loop = $this.attr('data-loop') == "true" ? true : false,
        $slidesPerView = $this.attr('data-slidesPerView');

      if ($pagination === true) {
        var $pagination_class = '#' + $this.attr('id') + ' .swiper-pagination';
      } else {
        var $pagination_class = false;
      }


      var mk_swiper = jQuery(this).swiper({
        mode: $direction,
        loop: $loop,
        freeMode: $freeMode,
        pagination: $pagination_class,
        freeModeFluid: $freeModeFluid,
        autoplay: $slideshowSpeed,
        speed: $animationSpeed,
        calculateHeight: true,
        grabCursor: true,
        useCSS3Transforms: false,
        mousewheelControl: $mousewheelControl,
        paginationClickable: true,
        slidesPerView: $slidesPerView,
        onSwiperCreated: function(swiper) {

        },
        onSlideChangeStart: function() {
          $thumbs.find('.active-item').removeClass('active-item');
          $thumbs.find('a').eq(mk_swiper.activeIndex).addClass('active-item');
        }
      });


      $prev_arrow.click(function(e) {
        mk_swiper.swipePrev();
      });

      $next_arrow.click(function(e) {
        mk_swiper.swipeNext();
      });


      $thumbs.find('a').on('touchstart mousedown', function(e) {
        e.preventDefault();
        $thumbs.find('.active-item').removeClass('active-item');
        jQuery(this).addClass('active-item');
        mk_swiper.swipeTo($(this).index());
      });

      $thumbs.find('a').click(function(e) {
        e.preventDefault();
      });

    }
  });

}



/* Parallax for edge slider */
/* -------------------------------------------------------------------- */

function mk_edge_parallax() {

  "use strict";

  if (!isMobile.any()) {


    var $parallaxLayer = [];


    $('.mk-edge-wrapper .mk-edge-slider').each(function() {
      var progressVal,
        currentPoint,
        ticking = false,
        scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
        $this = $(this),
        $window = $(window),
        windowHeight = $(window).height(),
        parentHeight = $this.outerHeight(),
        startPoint = 0,
        endPoint = $this.offset().top + parentHeight,
        effectLayer = $this,
        cntLayer = $this.find('.mk-grid'),
        height = $this.outerHeight();

      var parallaxSpeed = .7;

      var animationSet = function() {
        scrollY = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;

        currentPoint = (startPoint + scrollY) * parallaxSpeed;
        progressVal = (1 / (endPoint - startPoint) * (scrollY - startPoint));

        effectLayer.css({
          '-webkit-transform': 'translateY(' + currentPoint + 'px)',
          '-moz-transform': 'translateY(' + currentPoint + 'px)',
          '-ms-transform': 'translateY(' + currentPoint + 'px)',
          '-o-transform': 'translateY(' + currentPoint + 'px)',
          'transform': 'translateY(' + currentPoint + 'px)'
        });
        // console.log(currentPoint);

        cntLayer.css({
          opacity: (1 - (progressVal * 2))
        });
        // console.log(progressVal);

        ticking = false;
      }
      animationSet();

      var requestTick = function() {
        if (!ticking) {
          window.requestAnimationFrame(animationSet);
          ticking = true;
        }
      };

      $window.on('scroll', requestTick);
    });
  }
};/* Element Click Events */
/* -------------------------------------------------------------------- */
  function mobilecheck() {
    var check = false;
    (function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  }

function mk_click_events() {


  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';





  jQuery(".mk-header-login, .mk-header-signup, .mk-side-dashboard, .mk-quick-contact-wrapper, .mk-dashboard-trigger, .blog-share-container, .news-share-buttons, .main-nav-side-search, #mk-fullscreen-search-wrapper").on(eventtype, function(event) {
    if (event.stopPropagation) {
      event.stopPropagation();
    } else if (window.event) {
      window.event.cancelBubble = true;
    }
  });
  jQuery("html").on(eventtype, function() {
    jQuery(this).find(".mk-login-register, #mk-header-subscribe, #mk-quick-contact, .single-share-buttons, .single-share-box, .blog-social-share, .news-share-buttons, #mk-nav-search-wrapper").fadeOut(100);
    jQuery('.mk-quick-contact-link').removeClass('quick-contact-active');
    jQuery('.mk-toggle-trigger').removeClass('mk-toggle-active');
    jQuery('body').removeClass('dashboard-opened');
    jQuery('.mk-dashboard-trigger').removeClass('dashboard-active');
  });

  jQuery('.mk-fullscreen-search-overlay').on(eventtype,function(){
    $(this).removeClass('mk-fullscreen-search-overlay-show')
  });

  jQuery('.mk-forget-password').on(eventtype, function() {
    jQuery('#mk-forget-panel').siblings().hide().end().show();
  });

  jQuery('.mk-create-account').on(eventtype, function() {
    jQuery('#mk-register-panel').siblings().hide().end().show();
  });

  jQuery('.mk-return-login').on(eventtype, function() {
    jQuery('#mk-login-panel').siblings().hide().end().show();
  });


  jQuery('.mk-quick-contact-link').on(eventtype, function() {
    var $this = jQuery(this),
        $quickContact = jQuery('#mk-quick-contact');
    if (!$this.hasClass('quick-contact-active')) {
      $quickContact.addClass('quick-contact-anim').fadeIn(250);
      $this.addClass('quick-contact-active');
    } else {
      $quickContact.removeClass('quick-contact-anim').fadeOut(100);
      $this.removeClass('quick-contact-active');
    }
    return false;
  });


  jQuery('.mk-dashboard-trigger').on(eventtype, function(e) {

      var $this = jQuery(this),
          $body = jQuery('body');

    if (!$this.hasClass('dashboard-active')) {
      $this.addClass('dashboard-active');
      $body.addClass('dashboard-opened');

    } else {
      $this.removeClass('dashboard-active');
      $body.removeClass('dashboard-opened');
    }
    e.preventDefault();
  });

}


function mk_theme_toggle_box() {

  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';

  jQuery('.mk-toggle-trigger').on(eventtype, function() {
      var $this = jQuery(this);

    if (!$this.hasClass('mk-toggle-active')) {

      jQuery('.mk-box-to-trigger').fadeOut(100);
      $this.parent().find('.mk-box-to-trigger').fadeIn(150);
      jQuery('.mk-toggle-trigger').removeClass('mk-toggle-active');
      $this.addClass('mk-toggle-active');

    } else {

      jQuery('.mk-box-to-trigger').fadeOut(100);
      $this.removeClass('mk-toggle-active');

    }
    return false;
  });
}

function mk_social_share_global() {

  "use strict";

  var eventtype = mobilecheck() ? 'touchstart' : 'click';


  jQuery('.twitter-share').on(eventtype, function() {
    var $this = jQuery(this),
        $url = $this.attr('data-url'),
        $title = $this.attr('data-title');

    window.open('http://twitter.com/intent/tweet?text=' + $title + ' ' + $url, "twitterWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  jQuery('.pinterest-share').on(eventtype, function() {
    var $this = jQuery(this),
        $url = $this.attr('data-url'),
        $title = $this.attr('data-title'),
        $image = $this.attr('data-image');
    window.open('http://pinterest.com/pin/create/button/?url=' + $url + '&media=' + $image + '&description=' + $title, "twitterWindow", "height=320,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  jQuery('.facebook-share').on(eventtype, function() {
    var $url = jQuery(this).attr('data-url');
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + $url, "facebookWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  jQuery('.googleplus-share').on(eventtype, function() {
    var $url = jQuery(this).attr('data-url');
    window.open('https://plus.google.com/share?url=' + $url, "googlePlusWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });

  jQuery('.linkedin-share').on(eventtype, function() {
    var $this = jQuery(this),
        $url = $this.attr('data-url'),
        $title = $this.attr('data-title'),
        $desc = $this.attr('data-desc');
    window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + $url + '&title=' + $title + '&summary=' + $desc, "linkedInWindow", "height=380,width=660,resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0");
    return false;
  });
}

