jQuery.noConflict();

jQuery(document).ready(function($) {
    //$('.vjeecdcm-frame-content').dialog();
    $('.date-input').datepicker();
    $('button').button();
    
    var validator = $('.form-validate').validate();
    
    $( "#add-diploma-dialog").dialog({
	width: 720,
	modal: true,
	close: function(){
	    window.history.back();
	}
    });   
    /*
    $( "#create-request-cnfrm-dlg").dialog({
	autoOpen: false,
	resizable: false,
	width: 510,
	height: 160,
	modal: true,
	buttons : [{
	    text: Joomla.JText._('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_BT_CONFIRM'),
	    click: function() {
		
		$('#create_request').prop('checked', true);
		//document.getElementById("create-request").checked = true;
		//window.addingDiplomaForm.elements['create_request'].value 
		$(this).dialog('close');
		window.addingDiplomaForm.submit();
	    }
	},
	{
	    text: Joomla.JText._('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_BT_CANCEL'),
	    click: function() {
		$('#create_request').prop('checked', false);
		//document.getElementById("create-request").checked = false;
		$(this).dialog('close');
		window.addingDiplomaForm.submit();
	    }
	}]
    });
    */
    
    /*
    $('#register_button').button().click(function( event ) {
        event.preventDefault();
	if (validator.valid()) {
	    $( "#create-request-cnfrm-dlg" ).dialog( "open" );
	}
	
    });
    */
    
    // This for adding dynamically fields to the form
   
    
    
});
  
