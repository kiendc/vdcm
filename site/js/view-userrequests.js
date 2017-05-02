jQuery.noConflict();



jQuery(document).ready(function($) {    

    
    $('#request-table').dataTable({
            'bJQueryUI': true,
            'sPaginationType': 'full_numbers',
	    'bSort': false,
    });
    
    $('#req-adding-dlg').dialog({
	autoOpen: false,
	modal: true,
	width: 1225,
	height: 760,
	title: 'Tạo yêu cầu',
	open: function(event, ui)
	{
	    $('.detail-sec').accordion({
	        collapsible: true,
		    heightStyle: "content"
	    });
	   
	    $('#req-detail-target-school').editable({
		type: 'select2',
		mode: 'inline',
		url: "/post",
		source: "index.php?option=com_vjeecdcm&task=employee.getSelect2SchoolList",
		select2: {
		    placeholder: "Chọn trường",
		    minimumInputLength: 1
		},
		emptytext: 'Chưa có thông tin'
	    });
	    
	     $('#req-detail-type').editable({
		type: 'select2',
		mode: 'inline',
		url: "/post",
		source: [{id: 0, text: "Gửi thường"}, {id: 1, text: "Gửi nhanh"}],
		select2: {
		    width: "200px"
		},
		emptytext: 'Chưa có thông tin'
	    });
	    
	    $('.req-editable').editable('option', 'validate', function(v) {
		    if(!v) return 'Thông tin bắt buộc!';
	    });
	     
	    $('#req-detail-diploma').editable({
		type: 'select2',
		mode: 'inline',
		url: "/post",
		source: "index.php?option=com_vjeecdcm&task=employee.getSelect2ExhibitList",
		select2: {
		    width: "380px",
		},
		emptytext: 'Chưa có thông tin'
	    });
	    
	    $('#req-detail-holder-info-4').editable({
		type: 'dateui',
		url: "/post",
		format: "dd/mm/yyyy",
		emptytext: 'Chưa có thông tin',
		datepicker: {
		    yearRange:"1960:2010"
		}
		
	    });
	    
	    $('#req-detail-holder-info-3').editable({
		type: 'textarea',
		url: "/post",
		mode: 'inline',
		emptytext: 'Chưa có thông tin'
	    });
	    
	    $('.editable-txt').editable({
		url: "/post",
		mode: 'inline',
		emptytext: 'Chưa có thông tin'
	    });
	    
	    $('.dplm-editable').editable('option', 'validate', function(v) {
		    if(!v) return 'Thông tin bắt buộc!';
		});
	    $('.dplm-editable').editable('option', 'emptytext', 'Chưa có thông tin');
	},
	close: function( event, ui ) { 
	}
    });
    
    $( ".buttonLink, button, .DTTT_button" )
      .button()
      .click(function( event ) {
        event.preventDefault();
    });
      
    $("#upload-form input[type=submit] ").button().click(function (e){
	progrDlg.dialog('open');
	var form = document.getElementById('upload-form');
	var formData = new FormData(form);
	var req = new XMLHttpRequest();
	req.open("POST", "index.php?option=com_vjeecdcm&task=client.uploadFile", true);
	req.onload = function(oEvent) {
	    if (req.status == 200) {			
		var upldResult = JSON.parse(req.responseText);
		$('#upload-path').val(upldResult.path);
		progrBar.progressbar('value', 100);	
	    } else {
		$('.progress-label').html("Error " + req.status + " occurred uploading your file.");
	    }
	};

	req.send(formData);
	event.preventDefault();

    }); 
      
    $('#req-view-create-btn').button().click(function (event)
    {
	$('#req-adding-dlg').dialog('open');
    });
    
    $('#create-req-btn').button().click(function (event)
    {
	var upldPath = $('#upload-path').val();
	if (upldPath == '') {
	    alert('Chưa tải hồ sơ');
	    return;
	}
	$('.dplm-editable').editable('submit', { 
	    url: 'index.php?option=com_vjeecdcm&task=client.createExhibit', 
	    ajaxOptions: {
	       dataType: 'json' //assuming json response
	    },
	    data: {
		fileName: upldPath
		},
	    success: function(dplm, config) {
		if (dplm == null ) {
		    return;
		}
	        if (dplm.id == -1)  {
		    alert('Không tạo được hồ sơ');
		    if (dplm.error == 1) { // Session exprired
			window.location.reload();
		    }
		    
		    return;
		}
		var reqType = $('req-detail-type').find(":selected").attr('value');
		$('.req-editable').editable('submit', {
		    
		    url: 'index.php?option=com_vjeecdcm&task=client.createRequest', 
		    ajaxOptions: {
			dataType: 'json' //assuming json response
		    },
		    data: {
			diploma_id: dplm.id
		    },
		    success: function(req, config) {
		        //alert('A new request is created');
			$('#req-adding-dlg').dialog('close');
			window.location.reload();
		    },
		    error: function(errors) {
		       alert('Không tạo được yêu cầu');
		   }
		});
		//$(this).editable('option', 'pk', data.id);
		//remove unsaved class
		//$(this).removeClass('editable-unsaved');
	    },
	    error: function(errors) {
	        alert('Không tạo được hồ sơ');
	    }
	});
	event.preventDefault();
    });
    
    progrDlg = $( "#progress-dlg" ).dialog({
        autoOpen: false,
        closeOnEscape: false,
        resizable: false,
        open: function() {
	    progrBar.progressbar("value", 50);
	    $('#upload-path').val("");
        }
    });
    
    progrBar = $( "#progressbar" ).progressbar({
	value: false,
	change: function() {
	},
	complete: function() {
	    $('.progress-label').html("Upload completed");
	    var path = $('#upload-path').val();
	    if (path != "") {
		$('#elec-doc').attr('src', 'http://vjeec.vn/portal/' + path);
	    }
	    $( "#progress-dlg" ).dialog('close');
	}
    });
     
    
});

