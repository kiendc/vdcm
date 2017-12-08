jQuery.noConflict();

jQuery(document).ready(function($) {
    
    $('#diploma-table').dataTable({
	"iDisplayLength": 50,
	"bJQueryUI": true,
	"sScrollY": "480px",
	"sPaginationType": "full_numbers",
	"aaSorting": [[ 0, "desc" ]],
	"oLanguage": {
            "sLengthMenu": "Hiển thị _MENU_ hồ sơ trên một trang",
            "sZeroRecords": "Nothing found - sorry",
            "sInfo": "Hiển thị hồ sơ từ _START_ đến _END_ trong tổng số _TOTAL_",
            "sInfoEmpty": "Showing 0 to 0 of 0 records",
            "sInfoFiltered": "(filtered from _MAX_ total records)"
        },
	"sDom": '<"H"f>rt<"F"lp> ',
	"processing" : true,
        "serverSide" : true,
        "ajax" : {
               "url" : "index.php?option=com_vjeecdcm&task=employee.getJSONDiplomas",
               "type" : "POST",
	       "data" : function (d){
		    alert(d);
	       }
        },
	/*
	"aoColumns" : [{
		"mData": "dplm_id",
		"sClass" : "center",
		"sWidth": "24px",
		"bSortable": false,
		"mRender" : function(data, type, full){   			                
		    return '<input type="checkbox" name="check[]" value="' + data + '">';      
                }
	    },{
		"mData": "dpml_id"
	    },{
		"mData": "elec_doc",
		"sWidth": "200px",
		"sClass": "elec-doc-link",
		"mRender" : function(data, type, full){
		    if (data.type == 0) {
			return '<span class="elec-doc-img" path="' + data.path + '">' + data.name + '</span>';      
		    }
		    return '<span class="elec-doc-pdf" path="' + data.path + '">' + data.name + '</span>';      
                }
	    },{
		"mData": "requester_name",
		"sWidth": "240px"
	    },{
		"mData": "holder",
		"sWidth": "120px",
		"sClass": "holder",
		"mRender" : function(data, type, full){
		    return data.name;      
                }
	    },{
		"mData": "related_requests",
		"mRender" : function(data, type, full){
		    return '<input type="hidden" name="steps[' + full.request_id + ']" value="' + data.step_id +'">' +
                           data.name;
		},
		"sWidth": "100px"
	    },{
		"mData": "created_date",
		"sWidth": "60px"
	    },{
		"mData": "begin_date"
	    }
        ],*/
    });
    
    
          
    $('#del-confm-dlg').dialog({
	autoOpen: false,
        modal: true,
	buttons : {
            'Đồng ý' : function() {
                alert("Xoa day");
            },
            'Hủy bỏ' : function() {
                $(this).dialog('close');
            }
        }
    });
    
        
    $('#deleteDiplomas').click(function(e) {
        e.preventDefault();
        var targetUrl = $(this).attr('href');
        $('#del-confm-dlg').dialog('open');
    });
    
    $( "input[type=submit], .buttonLink, button, .DTTT_button" )
      .button()
      .click(function( event ) {
        event.preventDefault();
    });
});
