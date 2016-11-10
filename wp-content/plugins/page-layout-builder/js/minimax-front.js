// MiniMax Full Width Row Dynamic CSS
jQuery(function () {

    var window_width = jQuery(window).width();

    jQuery('.mx-fullwidth-row').each(function () {
        var mxrow = jQuery(this);
        var padding_left = mxrow.offset().left;
        var padding_right = jQuery(window).width() - mxrow.width() - mxrow.offset().left;

        if (mxrow.hasClass('mx-fullwidth-content')) {
            mxrow.css({
                'width': window_width,
                'left': '-' + padding_left + 'px'
            });
        } else {
            mxrow.css({
                'width': window_width,
                'left': '-' + padding_left + 'px',
                'padding-left': +padding_left + 'px',
                'padding-right': +padding_right + 'px'
            });
        }
    });

});

jQuery(window).resize(function(){

    var window_width = jQuery(window).width();
    var layout_page_width = jQuery('#layout_page').width();
    var rw = layout_page_width + 30;

    jQuery('.mx-fullwidth-row').each(function () {
        var mxrow = jQuery(this);
        //var padding_left = mxrow.offset().left;
        var padding_left = jQuery('#layout_page').offset().left - 15;
        //var padding_right = jQuery(window).width() - mxrow.width() - mxrow.offset().left;
        var padding_right = jQuery(window).width() - rw - padding_left;

        if (mxrow.hasClass('mx-fullwidth-content')) {
            mxrow.css({
                'width': window_width,
                'left': '-' + padding_left + 'px'
            });
        } else {
            mxrow.css({
                'width': window_width,
                'left': '-' + padding_left + 'px',
                'padding-left': +padding_left + 'px',
                'padding-right': +padding_right + 'px'
            });
        }
    });

});