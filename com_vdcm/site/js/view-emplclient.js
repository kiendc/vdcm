jQuery.noConflict();



jQuery(document).ready(function($) {
   
    var reqTable = $('#client-table').dataTable({
	    "iDisplayLength": 50,
	    "bJQueryUI": true,
	    "sDom": '<"H"f>rt<"F"lp>',
	    "sPaginationType": "full_numbers",
	    "aoColumns":[
		{"bVisible": false}, 
		{"bVisible": false},
                {'bSortable': false},
                null, null, null, null, null
	    ],
    });
    
    $( "input[type=submit], .buttonLink, button, .DTTT_button" ).button().click(function( event ) {
        event.preventDefault();
    });
       
    
});