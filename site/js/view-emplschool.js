

jQuery.noConflict();

jQuery(document).ready(function($) {
   
    var reqTable = $('#school-table').dataTable({
	    "iDisplayLength": 50,
	    "bJQueryUI": true,
	    "sDom": '<"H"f>rt<"F"lp>',
	    "sPaginationType": "full_numbers",
	    "aoColumns":[
		{"bVisible": false}, 
		{"bVisible": false},
                {'bSortable': false},
                null, null, null, null
	    ],
	    
    });
    
    $('#school-adding-dlg').dialog({
	autoOpen: false,
	modal: true,
	width: 840,
	height: 680,
	title: 'Them truong'
    });
    /*
    "oTableTools": {
		"sRowSelect": "multi",
                "aButtons": [
			{
			    "sExtends": "div",
			    "sButtonText": Joomla.JText._('VJEECDCM_SV_BT_ADD_SCHOOL'),
			    "fnClick": function (nButton, oConfig, oFlash){
				//document.getElementById('buttonClicked').value = 'sendMessage';
				//window.schoolTableForm.submit();
                                //alert(JURI_BASE );
                                window.location.href = 'index.php/quanly-truong?view=school&layout=add';
			    }
			}
		    ]
            }
    
    */
    $( ".buttonLink, button, .DTTT_button" ).button().click(function( event ) {
        event.preventDefault();
    });
    
    $("#school-adding-dlg input[type=submit], #school-adding-dlg input[type=reset]").button();
       
    $('#schl-view-create-btn').button().click(function(event){
	 //window.location.href = 'index.php/quanly-truong?view=school&layout=add';
	 $('#school-adding-dlg').dialog('open');
    });
    
});