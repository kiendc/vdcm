jQuery.noConflict();


jQuery(document).ready(function($) {    
    $('#request-table').dataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
               "url" : "index.php?option=com_vjeecdcm&task=school.getRequestData",
               "type" : "POST"
          },
          "aoColumns" : [
               {"mData": "request_id",
                "bVisible" : false,
                "asSorting": [ "desc" ]},
               {"mData": "enable_confirm_by_user",
                "mRender": function(data, type, full){
                    
                              if (data === 'true')
                              {
                                   return '<input type="checkbox" />';
                              }                        
                              return '';      
                    }
               },
               {"mData": "code"},
               {"mData": "created_date"},
               {"mData": "requester_name"},
               {"mData": "holder_name"},
               {"mData": "name"},
               {"mData": "target_school_name"},
               {"mData": "route"},
               {"mData": "processing_status"},
               {"mData": "last_update"}
          ],
	  "iDisplayLength": 20,
	  "bJQueryUI": true,
	  "sScrollY": "480px",
	  "sDom": '<"H"<"table-toolbar"T>f>rt<"F"lp>',
	  "sPaginationType": "full_numbers",
    });
    
    
    
    
    $( "input[type=submit], .buttonLink, button, .DTTT_button" )
      .button()
      .click(function( event ) {
        event.preventDefault();
    });
});
