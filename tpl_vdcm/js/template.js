jQuery.noConflict();

jQuery(document).ready(function($) {
    $('#taskbar ul').buttonset();
    
    $('.blink').each(function() {
        var elem = $(this);
        setInterval(function() {
            /*
            if (elem.css('visibility') == 'hidden') {
                elem.css('visibility', 'visible');
            } else {
                elem.css('visibility', 'hidden');
            }
            */
            elem.fadeOut().fadeIn('slow');
        }, 1000);
    });
});