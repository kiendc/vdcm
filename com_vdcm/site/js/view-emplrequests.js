jQuery.noConflict();



jQuery(document).ready(function($) {
    
    var currentDirId = document.getElementById('directorySelected').value;

    var menu = $( '#req-context-menu' ).menu({
		    select : function (event, ui){
			menu.hide();
			if (ui.item.attr('value') == 3){
			    var rowIndex = ui.item.attr('dataIndex');
			    var rowData = $('#request-table').dataTable().fnGetData( rowIndex );
			    var rowInfo = jQuery.parseJSON(rowData[2]);
			    var today = new Date();

			    var day = ("0" + today.getDate()).slice(-2);
			    var month = ("0" + (today.getMonth() + 1)).slice(-2);
			    var todayStr = today.getFullYear()+"-"+(month)+"-"+(day) ;

			    //alert(today.format('yyyy-mm-dd'));
			    $('#req-view-cert-dlg #certDate').val(todayStr);
			    $('#req-view-cert-dlg #certRef input').val(rowInfo.request.code);
			    
			    //$('#req-view-cert-dlg #certDate').attr('value',
			    //					   today.getFullYear() + '-' + mm + '-' + today.getDate());
			    $('#req-view-cert-dlg #holderName input').val(rowInfo.holder.name);
			    $('#req-view-cert-dlg #holderId input').val(rowInfo.holder.info[0].value);
			    var birthday = rowInfo.holder.info[1].value.split("/");
			    var birthdayStr = birthday[2] + '-' + birthday[1] + '-' + birthday[0];
			    //alert(birthdayStr);
			    $('#req-view-cert-dlg #holderBirthday input').val(birthdayStr);
			    //alert(rowData[2]);
			    $('#req-view-cert-dlg').dialog('open');
			}
		    }
		}).hide();
    
    var dirSelectMenu = $('#req-dir-select-menu').menu({
			select: function (event, ui){
			    document.getElementById('directorySelected').value = ui.item.attr("value");
			    //alert('Action: ' + $('#buttonClicked').attr('value') + ' Directory: ' + $('#directorySelected').attr('value'));
			    //dirSelectMenu.hide();
			    event.stopPropagation();
			    window.requestTableForm.submit();
			    
			}
    }).hide();
        
    var procStepSelectMenu = $('#proc-step-select-menu').menu({
	    select: function (event, ui){
		procStepSelectMenu.hide();
		document.getElementById('buttonClicked').value = 'updateSel';
		document.getElementById('nextUpdatingStep').value = ui.item.attr("value");
		//alert('Update proc select ' + ui.item.attr("value"));
		event.stopPropagation();
		window.requestTableForm.submit();
	}
    }).hide(); 
   
    
    $('.dropdown-select-menu a').click(function(event){
	event.preventDefault();
    });
    
    
    // Register a click outside the menu to close it
    $( document ).on( "click", function() {
        if (menu.is( ":visible" ) ) {
	    menu.hide();
	}
	
	if (dirSelectMenu.is( ":visible" ) ) {
	    dirSelectMenu.hide();
	}
	
	if (procStepSelectMenu.is( ":visible" ) ) {
	    procStepSelectMenu.hide();
	}
	
    });
    
    $('#req-view-move-btn').button({icons:{secondary: "ui-icon-triangle-1-s"}})
    .click(function(event){
	document.getElementById('buttonClicked').value = 'moveSel';
	dirSelectMenu.show().position({
	    my: "left top",
	    at: "left bottom",
	    of: this
	});
	event.stopPropagation();
    });
    
    $('#req-view-update-btn').button({icons:{secondary: "ui-icon-triangle-1-s"}})
    .click(function(event){
	procStepSelectMenu.show().position({
	    my: "left top",
	    at: "left bottom",
	    of: this
	});
	event.stopPropagation();
	//window.requestTableForm.submit();
    });
    
    
    $("#update-proc-confm-dialog").dialog({
	modal: true,
	bgiframe: true,
	width: 500, 
	height: 200,
	autoOpen: false
    });
    
    $('#req-context-menu li#menu-item-update a').click(function (event) {
	event.preventDefault();
	var href = $(this).attr('href');
	
	$("#update-proc-confm-dialog").dialog({
	    buttons: [ {
		text: Joomla.JText.strings.VJEECDCM_RV_UCD_BT_CONFIRM,
		click: function () {
		    window.location.href = href;
		}
	    },
	    {
		text: Joomla.JText._('VJEECDCM_RV_UCD_BT_CANCEL'),
		click : function () {
		    $(this).dialog("close");
		}
	    }]
	});
	
	$("#update-proc-confm-dialog").dialog('open');
    });
    
    
    $('#req-detail-dlg').dialog({
	autoOpen: false,
	modal: true,
	width: 1225,
	height: 760,
	title: 'Thong tin chi tiet',
	open: function(event, ui)
	{
	    $('.detail-sec').accordion({
		collapsible: true,
		heightStyle: "content"
	    });
	    //alert('Detail dialog is opening');
	    fnInitEditableDetails();
	    
	},
	close: function( event, ui ) {
	    oTable.fnDraw();   
	}
    });
    
    oTable = $('#request-table').dataTable({
	"processing" : true,
        "serverSide" : true,
        "ajax" : {
               "url" : "index.php?option=com_vjeecdcm&task=employee.getJSONRequests",
               "type" : "POST",
	       "data" : function (d){
		    d.dir_id = $('#directorySelected').val();
		    d.step_id = $('#stepId').val();
		    //alert(d.step_id);
	       }
        },
        "aoColumns" : [{
		"mData": "context_info",
		"sClass" : "center",
		"sWidth": "24px",
		"bSortable": false,
		"mRender" : function(data, type, full){   			                
		    return '<input type="checkbox" name="check[]" value="' + data.req_id + '">';      
                }
	    },{
		"mData": "request_id"
	    },{
		"mData": "code",
		"sClass": "req-detail"
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
		"mData": "route",
		"sWidth": "360px",
		"mRender" : function(data, type, full){
		    return '<span class="editable-route" data-pk="' + full.request_id  + '">' +
		    data.full_name + '</span>';
		}
	    },{
		"mData": "state",
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
        ],
	"iDisplayLength": 50,
	"bJQueryUI": true,
	"sScrollY": "640px",
	"sDom": '<"H"<"table-toolbar"p>f>rt<"F"l>',
	"sPaginationType": "full_numbers",
	
    });
    
  
    
     
    function fnFormatDetails ( oTable, nTr )
    {
        var holder = oTable.fnGetData( nTr, 4 );
	
	var sOut = '';
	var holderInfo = holder.info;
	if( !$.isArray(holderInfo) ||  !holderInfo.length ) {
	    sOut += '<p style="padding-left:450px;">Khong co thong tin ve nguoi dung ten tren ho so</p>'
	}
	else{
	    sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:450px;">';
	    sOut += '<tr><td>' + holderInfo[0].name + ': </td><td>' + holderInfo[0].value + '</td></tr>';
	    sOut += '<tr><td>' + holderInfo[2].name + ': </td><td>' + holderInfo[2].value + '</td></tr>';
	    sOut += '<tr><td>' + holderInfo[3].name + ': </td><td>' + holderInfo[3].value + '</td></tr>';
	    sOut += '<tr><td>' + holderInfo[1].name + ': </td><td>' + holderInfo[1].value + '</td></tr>';	
	    sOut += '</table>'; 
	}	
	return sOut;
    }
    
    function fnFormatRequestType(reqType)
    {
	if (reqType == 0) {
	    return 'Gui thuong';
	}
	return 'Gui nhanh';
	
    }
    
    function fnFormatReqDetailsDlg(req)
    {
	
	$('#req-detail-id').html(req.id);
	
	$('#req-detail-requester').html(req.requester_name);
	$('#req-detail-target-school').html(req.targetSchool.name);
	//$('#req-detail-target-school').attr('data-pk', req.id);
    
	$('#req-detail-type').attr('data-value',req.request_type);
	$('#req-detail-type').html(fnFormatRequestType(req.request_type));
	//$('#req-detail-type').attr('data-pk', req.id);
	
	$('#req-detail-created-date').html(req.created_date);
	
	$('#req-detail-dplm-id').html(req.dplm.id);
	
	//$('#req-detail-holder').attr('data-pk', req.dplm.id);
	$('#req-detail-holder').html(req.dplm.holder_name);
	
	//$('#req-detail-diploma').attr('data-pk', req.dplm.id);
	$('#req-detail-diploma').html(req.dplm.name);
	
	$('#req-detail-holder-info-label-1').html(req.holderInfo[0].name);
	$('#req-detail-holder-info-1').html(req.holderInfo[0].value);
	$('#req-detail-holder-info-1').attr('data-pk', req.holderInfo[0].info_id);
	//$('#req-detail-holder-info-1').editable('setValue', req.holderInfo[0].value);
	
	$('#req-detail-holder-info-label-2').html(req.holderInfo[1].name);
	$('#req-detail-holder-info-2').html(req.holderInfo[1].value);
	$('#req-detail-holder-info-2').attr('data-pk', req.holderInfo[1].info_id);
	//$('#req-detail-holder-info-2').editable('setValue', req.holderInfo[2].value);
	
	$('#req-detail-holder-info-label-3').html(req.holderInfo[2].name);
	$('#req-detail-holder-info-3').html(req.holderInfo[2].value);
	$('#req-detail-holder-info-3').attr('data-pk', req.holderInfo[2].info_id);
	//$('#req-detail-holder-info-3').editable('setValue', req.holderInfo[3].value);
	
	$('#req-detail-holder-info-label-4').html(req.holderInfo[3].name);
	$('#req-detail-holder-info-4').html(req.holderInfo[3].value);
	$('#req-detail-holder-info-4').attr('data-pk', req.holderInfo[3].info_id);
	//$('#req-detail-holder-info-4').editable('setValue', req.holderInfo[1].value);
	    
	var tabStr = '';
	var procStep;
	for (var i = 0; i < req.procHistory.length; i++) {
	    procStep = req.procHistory[i];
	    tabStr = tabStr + '<tr><td>' + procStep.translated_name +
	                     '</td><td>' + procStep.begin_date + '</td></tr>';
	    if (procStep.id == req.current_processing_step) {
		$('#req-detail-process').html(procStep.translated_name);
	    }
	}
	$('#req-detail-proc-history').html(tabStr);
	$('#elec-doc').attr('src', req.elec_doc.path);
	
	//alert('Finish formatting detail dialog by ' + req.id + ' - ' + req.dplm.id);
    }

    function fnInitEditableDetails()
    {
	//var reqId = $('#req-detail-target-school').attr('data-pk');
	//var dplmId = $('#req-detail-diploma').attr('data-pk');
	//alert('Reset all editables elements by ' + reqId + '-' + dplmId);
    	$('#req-detail-target-school').editable({
	    type: 'select2',
	    mode: 'inline',
	    url: "index.php?option=com_vjeecdcm&task=employee.editRequestDetail",
	    ajaxOptions: {
	        dataType: 'json'
	    },
	    params : function (params) {		    
		params.pk = parseInt($('#req-detail-id').html());
	        return params;
	    },
	    source: "index.php?option=com_vjeecdcm&task=employee.getSelect2SchoolList",
	    select2: {
	        placeholder: "Chon truong",
	        minimumInputLength: 1
	    }
	});
	    
	$('#req-detail-type').editable({
	    type: 'select2',
	    mode: 'inline',
	    url: "index.php?option=com_vjeecdcm&task=employee.editRequestDetail",
	    ajaxOptions: {
		dataType: 'json'
	    },
	    params : function (params) {		    
		params.pk = parseInt($('#req-detail-id').html());
	        return params;
	    },
	    source: [{id: 0, text: fnFormatRequestType(0)}, {id: 1, text: fnFormatRequestType(1)}],
	    select2: {
		dropdownAutoWidth: true,
	    }
	});
	    
	$('#req-detail-diploma').editable({
	    type: 'select2',
	    mode: 'inline',
	    url: "index.php?option=com_vjeecdcm&task=employee.editDiplomaDetail",
	    ajaxOptions: {
		dataType: 'json'
	    },
	    params : function (params) {		    
		params.pk = parseInt($('#req-detail-dplm-id').html());
	        return params;
	    },
	    source: "index.php?option=com_vjeecdcm&task=employee.getSelect2ExhibitList",
	    select2: {
		width: "380px",
	    }
	});
	    
	$('#req-detail-holder').editable({
	    mode: 'inline',
	    url: "index.php?option=com_vjeecdcm&task=employee.editDiplomaDetail",
	    ajaxOptions: {
		dataType: 'json'
	    },
	    params : function (params) {		    
		params.pk = parseInt($('#req-detail-dplm-id').html());
	        return params;
	    }
	});
	    
	$('#req-detail-sec-2 .editable-info-txt').editable({
	    mode: 'inline',
	    url: "index.php?option=com_vjeecdcm&task=employee.editDiplomaHolderInfo",
	    ajaxOptions: {
		dataType: 'json'
	    },
	    params : function (params) {
		//alert(params.value);
		params.dplmId = parseInt($('#req-detail-dplm-id').html());
	        return params;
	    },
	});
	
	
	 
	$('#req-detail-sec-2 .editable-info-date').editable({
	    type: 'date',
	    url: "index.php?option=com_vjeecdcm&task=employee.editDiplomaHolderInfo",
	    ajaxOptions: {
	        dataType: 'json'
	    },
	    format: "dd/mm/yyyy",
	    params : function (params) {
	        params.dplmId = parseInt($('#req-detail-dplm-id').html());
	        return params;
	    }
	});
	
	
	
	// Set value for editable inputs that was 
	$('#req-detail-type').each(function (index, value){
	    $(this).editable('setValue', $(this).attr('data-value'));
	});
	
	$('#req-detail-sec-2 .editable-txt').each(function (index, value){
	    var infoValue = $(this).text();
	    $(this).editable('setValue', infoValue);
	});
	
	$('#req-detail-sec-2 .editable-info-txt').each(function (index, value){
	    var infoValue = $(this).text();
	    $(this).editable('setValue', infoValue);
	});
	
	$('#req-detail-sec-2 .editable-info-date').each(function (index, value){
	    var infoValue = $(this).text();
	    $(this).editable('setValue', infoValue, true);
	});
	
	
    }
    
    $('#request-table').on( 'draw.dt', function () {
	
	$('#request-table tbody td.holder').on('click', function (){
	   
	    var nTr = $(this).parents('tr')[0];
	    if ( oTable.fnIsOpen(nTr) )
	    {    
		oTable.fnClose( nTr );
	    }
	    else
	    {
		//alert('A cell of holder is clicked');
		oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
	    }
    
	    
	} );
	
	$('#request-table td.elec-doc-link span').click(function(event) {
	    event.preventDefault();
	
	    var path = $(this).attr('path');

	    if ($(this).hasClass('elec-doc-img')) {
		$('#elec-doc-image').attr('src', path).load(function (){ 	
	        $('#image-viewer-dialog').dialog({
		    modal: true,
		    width: 640,
		    title: 'Ho so'
		});
	    });
	    }
	    else {
		$('#elec-doc-pdf').attr('src', path);
		//alert($('#elec-doc-pdf').attr('src'));
		$('#pdf-viewer-dialog').dialog({
		    modal: true,
		    width: 640,
		    height:800,
		    title: 'Ho so'
		});	
	    }
	    
	});
	
	$('.editable-route').editable({
	    type: 'select2',
	    title: 'Chon truong muon gui yeu cau',
	    url: "index.php?option=com_vjeecdcm&task=employee.modifyTargetSchool",
	    ajaxOptions: {
	        dataType: 'json'
	    },
	    success : function(response, newValue) {
	        if (response.success) {
		oTable.fnDraw();
		return;
		}
		else
		{
		    return;
		}
	    },
	    source: "index.php?option=com_vjeecdcm&task=employee.getSelect2SchoolList",
	    select2: {
		placeholder: "Chon truong",
		minimumInputLength: 1
	    }
	});
    
	$('#request-table tbody td.req-detail').on('click', function(){
	    var nTr = $(this).parents('tr')[0];
	    var reqId = oTable.fnGetData( nTr, 1 );
	    $.ajax({
		url: "index.php?option=com_vjeecdcm&task=employee.getRequestDetail",
		data: {req_id: reqId},
		dataType: "json",
		type: 'POST',
		error: function (jqXHR, textStatus, errorThrown){
		    alert(errorThrown);
		},
		success: function (data, textStatus, jqXHR){
		    if (data != null) {
			fnFormatReqDetailsDlg(data);
			 $('#req-detail-dlg').dialog('open');
		    }
		}
	    });
	});
    });
    
    /*
    $('#request-table tbody').on( 'contextmenu','tr', function () {
	//alert('A row is clicked');
	//event.preventDefault();
	menu.show().position({
              my: "left top",
              at: "left bottom",
              of: event
        });
	return false;
	//event.stopPropagation();
    } );
    */
    
    // Directory tree
    $("#dir-table").treetable({ expandable: true});    
    $("#dir-table").treetable("expandAll");
    $("#dir-table tr[dirId='" + currentDirId + "']").toggleClass("selected");
    
    $("#dir-table td a").click(function(event){
	event.preventDefault();
    });
    
    $("#dir-table tbody").on("mousedown", "tr", function() {
	if (!$(this).hasClass('selected')) {
	    $(".selected").not($(this)).removeClass("selected");
	    $(this).toggleClass("selected");
	    document.getElementById('directorySelected').value = $(this).attr('dirID');
		
	    oTable.api().ajax.reload( function (json){
	        //alert('Table is successfully reloaded');
	    });	
	}
    });

    $('#shown-requests-select').select2({
	'dropdownAutoWidth' : true
    }).on("change", function (e){
	$('#stepId').val(e.val);
	oTable.api().ajax.reload( function (json){
	    //alert('Show request of state ' + $('#stepId').val());
	});	
    
    });
   
    $('#req-rem-cfm-dlg').dialog({
	autoOpen: false,
        modal: true,
	buttons : [{
		//text: Joomla.JText.strings.VJEECDCM_RV_UCD_BT_CONFIRM,
		text: '??ng ?',
		click: function() {
		    window.requestTableForm.submit();
		}
	    },
	    {
		//text: Joomla.JText._('VJEECDCM_RV_UCD_BT_CANCEL'),
		text: 'Kh™ng ??ng ?',
		click: function() {
		    $(this).dialog('close');
		}
	    }]
    });

    $('#cert-gen-form').ajaxForm({
	target: '#preview-output',
	beforeSubmit: function showRequest(formData, jqForm, options) { 
	    // formData is an array; here we use $.param to convert it to a string to display it 
	    // but the form plugin does this for you automatically when it submits the data 
	    var queryString = $.param(formData); 
 
	    // jqForm is a jQuery object encapsulating the form element.  To access the 
	    // DOM element for the form do this: 
	    // var formElement = jqForm[0]; 
 
	    alert('About to submit: \n\n' + queryString); 
 
	    // here we could return false to prevent the form from being submitted; 
	    // returning anything other than false will allow the form submit to continue 
	    return true; 
	}, 
	success: function showResponse(responseText, statusText, xhr, $form)  {
	    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
	     '\n\nThe output div should have already been updated with the responseText.');
	},
	type: 'post'	
    });
    
    $('#req-view-cert-dlg').dialog({
	autoOpen: false,
	modal: true,
	width: 1225,
	height: 620,
	title: 'Tao chung chi'
    });

    $( ".DTTT_button, .buttonLink" ).button().click(function( event ) {
        event.preventDefault();
    });

    $("input[type=submit]").button();
    
    
});