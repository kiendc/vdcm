$(document).ready(function(){ 
	// Create diploma js
	$('#req-view-create-btn').click(function (e) { 
		e.preventDefault();
		$('#req-adding-dlg').dialog('open');
    });
    
	$( ".buttonLink, button, .DTTT_button" ).click(function(e) {
        e.preventDefault();
    });
    
	$('#req-adding-dlg').dialog({
		autoOpen: false,
		modal: true,
		width: 1225,
		height: 760,
		title: 'Tạo yêu cầu',
		open: function(event, ui) {
		    $('.detail-sec').accordion({
		        collapsible: true,
			    heightStyle: "content"
		    });
		   
		    $('#req-detail-target-school-create').editable({
				type: 'select2',
				mode: 'popup',
				url: "/post",
				title: 'Chọn trường gửi đến',
				source: "index.php?option=com_vjeecdcm&task=employee.getSelect2SchoolList",
				select2: {
			    	placeholder: "Chọn trường",
			    	minimumInputLength: 1,
			    	width: "230px"
				},
				emptytext: 'Chưa có thông tin'
		    });
		    
		    $('#req-detail-type-create').editable({
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
		     
		    $('#req-detail-diploma-create').editable({
				type: 'select2',
				mode: 'inline',
				url: "/post",
				source: "index.php?option=com_vjeecdcm&task=employee.getSelect2ExhibitList",
				select2: {
			    	width: "380px",
				},
				emptytext: 'Chưa có thông tin'
		    });
		    
		    $('#req-detail-holder-info-4-create').editable({
		    	mode : 'popup',
				type: 'date',
				url: "/post",
				format: "dd/mm/yyyy",
				emptytext: 'Chưa có thông tin',
				datepicker: {
			    	yearRange:"1970:2014"
				}
		    });
		    
		    $('#req-detail-holder-gender-create').editable({
		    	type: 'select2',
				mode: 'inline',
				url: "/post",
				emptytext: 'Chưa có thông tin',
				source: [{id: 'Male', text: "Nam"}, {id: 'Female', text: "Female"}],
				select2: {
			    	width: "130px"
				},
		    });
		    
		    $('#req-detail-holder-info-3-create').editable({
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
		close: function( event, ui ) { }
    });		

 	var progrDlg = $( "#progress-dlg" ).dialog({
	        autoOpen: false,
	        closeOnEscape: false,
	        resizable: false,
	        open: function() {
		    progrBar.progressbar("value", 50);
		    $('#upload-path').val("");
	        }
		});
    var progrBar = $( "#progressbar" ).progressbar({
		value: false,
		change: function() {},
		complete: function() {
	    	$('.progress-label').html("Upload completed");
	    	var path = $('#upload-path').val();
		    if (path != "") {
				$('#elec-doc-create').attr('src', site_url + 'tmp/'+ path);
		    }
		    $( "#progress-dlg" ).dialog('close');
		}
    });
    
	// click on upload diploma pdf file
    $("#uploadFilePdf").button().click(function (e) {
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
    
    $('#create-req-btn').button().click(function (event) {
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
		        if (dplm.id == -1)  { 
    		        console.log(dplm);
    			    alert('Không tạo được hồ sơ');
    			    return false;
    			    if (dplm.error == 1) { // Session exprired
	    				window.location.reload();
    			    }   
			    	return;
    			}
    			
    			//var reqType = $('#req-detail-type-create').find(":selected").attr('value');
    			$('.req-editable').editable('submit', { 
    			    url: 'index.php?option=com_vjeecdcm&task=client.createRequest', 
    			    ajaxOptions: {
    					dataType: 'json' //assuming json response
    			    },
    			    data: {
    					diploma_id: dplm.id
    			    },
    			    success: function(req, config) { 
    					$('#req-adding-dlg').dialog('close');
    					location.reload();
    			    },
    			    error: function(errors) {
    			       alert('Không tạo được yêu cầu');
    			   }
    			});
		    },
		    error: function(errors) {
		        alert('Không tạo được hồ sơ');
		    }
		});
    	event.preventDefault();
	});
})