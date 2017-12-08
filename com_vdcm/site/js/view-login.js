jQuery.noConflict();


jQuery(document).ready(function($) {
    $('#login-dlg-modal').dialog({
        modal: true
    });
    $( "input[type=submit], .buttonLink, button" )
      .button();
});