jQuery.noConflict();


jQuery(document).ready(function($) {
    $( '.vjeecdcm-frame' ).tooltip({
	items: "[proc-hist]",
	content: function(){
	    var element = $( this );
	    var reqID = element.attr("proc-hist");
	    return "Click on status to see full processing history of request " + reqID ;
	}
    });
    
    $( '.proc-hist-dlg' ).dialog({
	autoOpen: false,
	show: {
            effect: "blind",
            duration: 1000
	},
	hide: {
            effect: "explode",
            duration: 1000
	}
    });
    

    
    var oTable = $('#request-table').dataTable({
	"processing" : true,
        "serverSide" : true,
        "ajax" : {
               "url" : "index.php?option=com_vjeecdcm&task=school.getRequestData",
               "type" : "POST",
	       "data" : function (d){
		    d.step_id = $('#stepId').val();
		    //alert(d.step_id);
	       }
        },
        "aoColumns" : [
	    {"mData": "request_id",
	    "bVisible" : false,
	    "asSorting": [ "desc" ]},
            {"mData": "enable_confirm_by_user",
                "mRender": function(data, type, full){   			                
                        if (data === true)
			{
			    return '<input type="checkbox" name="check[]" value="' + full.request_id + '">';
                        }                        
                        return '';      
                    }
               },
               {"mData": "code"},
               {"mData": "requester_name"},
               {"mData": "holder_name"},
               {"mData": "name"},
               {"mData": "target_school_name"},
               {"mData": "route"},
               {"mData": "processing_status"},
               {"mData": "last_update"},
	       {"mData": "created_date"}
          ],
	  "iDisplayLength": 20,
	  "bJQueryUI": true,
	  "sScrollY": "560px",
	  "sDom": '<"H"<"table-toolbar"p>f>rt<"F"l>',
	  "sPaginationType": "full_numbers",
	  /*
	"oTableTools": {
		"sRowSelect": "multi",
		
		"aButtons": [
			{
			    "sExtends": "div",
			    "sButtonText": Joomla.JText._('VJEECDCM_RV_BT_CONFIRM_SELECTED'),
			    "fnClick": function (nButton, oConfig, oFlash){
				document.getElementById('buttonClicked').value = 'confirmSel';
				window.requestTableForm.submit();
			    }
			},
			{
			    "sExtends": "div",
			    "sButtonText": Joomla.JText._('VJEECDCM_RV_BT_CONFIRM_ALL'),
			    "fnClick": function (nButton, oConfig, oFlash) {
				$('#confm-all-dlg').dialog('open');
			    }
			}
		    ]
		    
	    }
	    */
    });
    
    
    
    $('#shown-requests-select').select2({
	'dropdownAutoWidth' : true
    }).on("change", function (e){
	 $('#stepId').val(e.val);
	oTable.api().ajax.reload( function (json){
	    //alert('Show request of state ' + $('#stepId').val());
	});	
    });
    
    $('#confm-all-dlg').dialog({
	autoOpen: false,
        modal: true,
	buttons : {
            'Yes' : function() {
                document.getElementById('buttonClicked').value = 'confirmAll';
		window.requestTableForm.submit();
	            },
            'No' : function() {
                $(this).dialog('close');
            }
        }
    });
     
    $('#confm-sel-dlg').dialog({
	autoOpen: false,
        modal: true,
	buttons : {
            'Yes' : function() {
                document.getElementById('buttonClicked').value = 'confirmSel';
		window.requestTableForm.submit();
	    },
            'No' : function() {
                $(this).dialog('close');
            }
        }
    });
    
    $('#confirm-selected-btn').button().click(function (event)
    {
	$('#confm-sel-dlg').dialog('open');
    });
    
    $('#confirm-all-btn').button().click(function (event)
    {
	$('#confm-all-dlg').dialog('open');
    });
    
    
    $(".DTTT_button" )
      .button()
      .click(function( event ) {
        event.preventDefault();
    });
});
