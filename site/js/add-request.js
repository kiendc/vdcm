 jQuery.noConflict();

jQuery(document).ready(function($) {
	//$('.vjeecdcm-frame-content').dialog();
        $('.date-input').datepicker();
        $('button').button();
        $('#add-request-form').validate({
		submitHandler: function (form){
			form.submit();
		}
	});

	// This for adding dynamically fields to the form
	$( "#add-request-dialog").dialog({
		width: 720,
		modal: true,
		close: function(){
		window.history.back();
		}
	});
	$( "#trgSchool" ).combobox();
});
