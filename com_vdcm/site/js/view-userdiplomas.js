jQuery.noConflict();
jQuery(document).ready(function($) {    
    $('#diploma-table').dataTable({
	"bJQueryUI": true,
	"sPaginationType": "full_numbers",
	/*
	"oTableTools": {
	    "sRowSelect": "multi",
	    "aButtons": [
			{
			    "sExtends": "div",
			    "sButtonText": "Bổ sung hồ sơ",
			    "fnClick": function (nButton, oConfig, oFlash) {
				window.location.href = 'index.php/diploma-management?view=diploma&layout=add';
				//window.location.href = 'index.php?option=com_vjeecdcm&view=diploma&layout=add';
			    }
			},
			{
			    "sExtends": "div",
			    "sButtonText": "Xoá hồ sơ đã chọn",
			    "fnClick": function (nButton, oConfig, oFlash) {
				$('#del-confm-dlg').dialog('open');
			    }
			}
		]
	},*/
	"oLanguage": {
            "sLengthMenu": "Hiển thị _MENU_ hồ sơ trên một trang",
            "sZeroRecords": "Nothing found - sorry",
            "sInfo": "Hiển thị hồ sơ từ _START_ đến _END_ trong tổng số _TOTAL_",
            "sInfoEmpty": "Showing 0 to 0 of 0 records",
            "sInfoFiltered": "(filtered from _MAX_ total records)"
        },
	"sDom": '<"H"f>rt<"F"lp> ',
	//"sDom": '<"H"<"table-toolbar"T>f>rt<"F"lp> ',
	//"sDom": '<"fg-toolbar ui-widget-header ui-toolbar ui-corner-tl ui-corner-tr ui-helper-clearfix"<"table-toolbar"T>f>rt<"fg-toolbar ui-widget-header ui-toolbar ui-corner-bl ui-corner-br ui-helper-clearfix"lp> ',
    });
    
    //$("div.table-toolbar").html(' <a id="addDiploma" class="buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateAllRequestProcessing">Bổ sung hồ sơ</a><a id="deleteDiplomas" class="buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateRequestProcessing">Xoá hồ sơ đã chọn</a>');
    
    $('#system-message-container').dialog({
        modal: true
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
