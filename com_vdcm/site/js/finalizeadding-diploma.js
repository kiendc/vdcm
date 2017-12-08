jQuery.noConflict();

jQuery(document).ready(function($) {
    $('button').button();
    
    
    $( "#create-request-cnfrm-dlg").dialog({
	resizable: false,
	width: 510,
	height: 200,
	modal: true,
	buttons : [{
	    text: Joomla.JText._('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_CREATE_REQUEST'),
	    click: function() {
		window.finalizeAddingDiplomaForm.elements['choice'].value = 'create-request'; 
		$(this).dialog('close');
		window.finalizeAddingDiplomaForm.submit();
	    }
	},
	{
	    text: Joomla.JText._('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_CREATE_DIPLOMA'),
	    click: function() {
		window.finalizeAddingDiplomaForm.elements['choice'].value = 'create-diploma'; 
		$(this).dialog('close');
		window.finalizeAddingDiplomaForm.submit();
	    }
	},
	{
	    text: Joomla.JText._('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_VIEW_DIPLOMAS'),
	    click: function() {
		//$('#create_request').prop('checked', false);
		//document.getElementById("create-request").checked = false;
		window.finalizeAddingDiplomaForm.elements['choice'].value = 'view-diploma';
		$(this).dialog('close');
		window.finalizeAddingDiplomaForm.submit();
	    }
	}]
    });
      
    
});
  
