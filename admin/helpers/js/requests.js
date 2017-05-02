$(document).ready(function() { 	
	//click on học sinh/sinh viên
	$('span.holder_info').click(function(e) { 
		e.preventDefault();
		var diploma_id = $(this).attr('diploma_id');
		var parentRow = $(this).parent().parent();
		var holder_info = $('#holder_info_' + diploma_id);
		
		if (holder_info.length) { 
			if (holder_info.css('display') == 'none') { 
				holder_info.show();
			} else { 
				holder_info.hide();
			}
		} else {  
			$.ajax({
				url: "index.php?option=com_vjeecdcm&task=common.getHolderInfo",
				data: {"diploma_id": diploma_id},
				dataType: "json",
				type: 'POST',
				error: function (jqXHR, textStatus, errorThrown) {
				    alert(errorThrown);
				},
				success: function (data, textStatus, jqXHR){ 
					var html = '';
					if (data.length) { 
						$.each(data, function(i, item) { 
							html += '<tr><td style="width:150px;font-weight:bold;">' + item[0] + '</td>' + '<td>' + item[1] + '</td></tr>';
						});
					} else { 
						html = '<tr><td> Không có thông tin của học sinh/sinh viên </td></tr>';
					}
					html = '<table style="width:50%">' + html + '</table>';
					html = '<tr id="holder_info_' + diploma_id + '"><td colspan="11" align="center">'+ html +'</td></tr>';
					$(html).insertAfter(parentRow);
				}
		    });
		}
	})
	
	// click on trường gửi tới
	$('.editable-route').editable({ 
		mode: 'popup',
	    type: 'select2',
	    tooltip: 'Click to edit',
	    title: 'Chọn trường muốn gửi yêu cầu',
	    url: "index.php?option=com_vjeecdcm&task=employee.modifyTargetSchool",
	    ajaxOptions: {
	        dataType: 'json'
	    },
	    success : function(response, newValue) {
	        if (response.success) { 
		        //refresh list
	        	location.reload();
	        } else {
	        	// do something
	        }
	    },
	    source: "index.php?option=com_vjeecdcm&task=employee.getSelect2SchoolList",
	    select2: {
			placeholder: "Chọn trường",
			minimumInputLength: 1
	    }
	});

	// click on hồ sơ
	$('span.diploma_info').click(function(event) { 
		event.preventDefault();
	    var path = $(this).attr('path');
	    var downLoadLink = $(this).attr('download_link');
	    if (path != '') { 
	    	if (downLoadLink != '') { 
	    		$('#down_diploma1').attr('href', downLoadLink);
	    	}
		    if ($(this).hasClass('elec-doc-img')) {
				$('#elec-doc-image').attr('src', path).load(function (){ 	
			        $('#image-viewer-dialog').dialog({
				    	modal: true,
				    	width: 640,
				    	title: 'Thông tin hồ sơ'
					});
			    });
		    } else { 
				$('#elec-doc-pdf').attr('src', path);
				$('#pdf-viewer-dialog').dialog({
				    modal: true,
				    width: 640,
				    height:800,
				    title: 'Thông tin hồ sơ'
				});	
		    }
	    }
	});

	function fnFormatRequestType(reqType) {
		if (reqType == 0) {
	    	return 'Gửi thường';
		}
		return 'Gửi nhanh';
    }
	
	function fnFormatGender(gender) {
		if (gender == 'Male') {
	    	return 'Nam';
		}
		return 'Nữ';
    }

	function fnFormatReqDetailsDlg(req) { 
		$('#req-detail-id').html(req.id);
		$('#req-detail-requester').html(req.requester_name);
		$('#req-detail-target-school').html(req.targetSchool.name);
		$('#req-detail-type').attr('data-value',req.request_type);
		$('#req-detail-type').html(fnFormatRequestType(req.request_type));
		$('#req-detail-created-date').html(req.created_date);
		$('#req-detail-dplm-id').html(req.dplm.id);
		$('#req-detail-holder').html(req.dplm.holder_name);
		$('#req-detail-diploma').html(req.dplm.name);
		$('#req-detail-holder-info-label-1').html(req.holderInfo[0].name);
		$('#req-detail-holder-info-1').html(req.holderInfo[0].value);
		$('#req-detail-holder-info-1').attr('data-pk', req.holderInfo[0].info_id);
		$('#req-detail-holder-info-label-2').html(req.holderInfo[1].name);
		$('#req-detail-holder-info-2').html(req.holderInfo[1].value);
		$('#req-detail-holder-info-2').attr('data-pk', req.holderInfo[1].info_id);
		$('#req-detail-holder-info-label-3').html(req.holderInfo[2].name);
		$('#req-detail-holder-info-3').html(req.holderInfo[2].value);
		$('#req-detail-holder-info-3').attr('data-pk', req.holderInfo[2].info_id);
		$('#req-detail-holder-info-label-4').html(req.holderInfo[3].name);
		$('#req-detail-holder-info-4').html(req.holderInfo[3].value);
		$('#req-detail-holder-info-4').attr('data-pk', req.holderInfo[3].info_id);
		var tabStr = '';
		var procStep;
		for (var i = 0; i < req.procHistory.length; i++) {
		    procStep = req.procHistory[i];
		    tabStr = tabStr + '<tr><td style="width: 130px;">' + procStep.translated_name +
		                     '</td><td>' + procStep.begin_date + '</td></tr>';
		    if (procStep.id == req.current_processing_step) {
				$('#req-detail-process').html(procStep.translated_name);
		    }
		}
		$('#req-detail-proc-history').html(tabStr);
		$('#elec-doc').attr('src', req.elec_doc.path);
    }

	function fnInitEditableDetails() {
		$('#req-detail-target-school').editable({
	    	type: 'select2',
	    	mode: 'popup',
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
	        	minimumInputLength: 1,
	        	width: "300px"
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
    			width: "350px",
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
    			params.dplmId = parseInt($('#req-detail-dplm-id').html());
    	        	return params;
    	    },
    	});
    	 
    	$('#req-detail-sec-2 .editable-info-date').editable({ 
	    	mode : 'inline',
    	    type: 'date',
    	    url: "index.php?option=com_vjeecdcm&task=employee.editDiplomaHolderInfo",
    	    ajaxOptions: {
    	        dataType: 'json'
    	    },
    	    format: "dd/mm/yyyy",
    	    datepicker: {
		    	yearRange:"1970:2014"
			},
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

	$('#req-detail-dlg').dialog({
	    	autoOpen: false,
	    	modal: true,
	    	width: 1225,
	    	height: 760,
	    	title: 'Thông tin chi tiết',
	    	open: function(event, ui) {
	    		$('.detail-sec').accordion({
	    			collapsible: true,
	    			heightStyle: "content"
	    		});
	    		fnInitEditableDetails();
	    	},
	    	close: function( event, ui ) { 
	    		location.reload();
	    	}
	    });

	$('span.request_detail').on('click', function(){
	    
	    var request_id = $(this).attr('request_id');
	    var downloadLink = $(this).attr('download_link');
	    $.ajax({
			url: "index.php?option=com_vjeecdcm&task=employee.getRequestDetail",
			data: {req_id: request_id},
			dataType: "json",
			type: 'POST',
			error: function (jqXHR, textStatus, errorThrown){
		    	alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) {
		    	if (data != null) { 
					fnFormatReqDetailsDlg(data);
					if (downloadLink != '') { 
						$('#req_download_dip').attr('href', downloadLink).show();
					}
			    	$('#req-detail-dlg').dialog('open');
	    		}
			}
	    });
	});

	$('#certificate_info_dlg').dialog({
    	autoOpen: false,
    	modal: true,
    	width: 700,
    	//position: 'center',
    	height: 500,
    	title: 'Tạo certification',
    	open: function(event, ui) {
    	},
    	close: function( event, ui ) { 
    		location.reload();
    	}
    });

    //tuyenvh
	$('#select_type').change(function() { 
		var val = $(this).val();
		if (val == "1") { 
			$('#level_study_lbl').html("Type of degree:");
			$('#issued_by_lbl').html("Degree granting institution:");
		} else { 
			$('#level_study_lbl').html("Level of study:");
			$('#issued_by_lbl').html("Issued by:");
		}
    });

    $('.datetime').datepicker({
	      changeMonth: true,
	      changeYear: true,
	      dateFormat: 'dd/mm/yy',
	      yearRange : '1964:2016'
    });

	function makeSelected(selector, val) { 
		$('option:selected', selector).removeAttr('selected');
		$('option', selector).each(function() { 
			if ($(this).val() == val) { console.log("type" + val);
				$(this).attr('selected', 'selected'); 
			}
		});
		$('#select_type').val(val);
		if (val == 1) { 
			$('#level_study_lbl').html("Type of degree:");
			$('#issued_by_lbl').html("Degree granting institution:");
		} else { 
			$('#level_study_lbl').html("Level of study:");
			$('#issued_by_lbl').html("Issued by:");
		}
	}
		    
	$('.get_certificate_info').click(function(e){ 
		e.preventDefault();
		$('#certificate_form')[0].reset();
		var diploma_id = $(this).attr('diploma_id');
		var holder_name = $(this).attr('full_name');
		var school_name = $(this).attr('school_name');
		var sender_name = $(this).attr('sender_name');
		var referenceNo = $(this).attr('reference');
		$.ajax({
			url: "index.php?option=com_vjeecdcm&task=diplomas.getCertificateInfo",
			data: {"diploma_id": diploma_id},
			dataType: "json",
			type: 'POST',
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) {  
				//update field in form
				if (data != null) { 
					var type = data.certificate_type;
					//if (type != '') { 
					makeSelected($('#select_type'), type);
					//}
					$('#certificate_date').val(data.expected_send_date);
					if (data.reference) { 
						$('#certificate_reference').val(data.reference);
					} else { 
						$('#certificate_reference').val(referenceNo);
					}
					 
					$('#student_name').val(data.holder_name);
					$('#student_birthday').val(data.birthday);
					$('#student_gender').val(data.gender);
					$('#level_study').val(data.degree_type);
					$('#study_mode').val(data.study_mode);
					$('#student_ranking').val(data.ranking);
					$('#student_idno').val(data.id_no);
					$('#student_major').val(data.major);
					$('#reg_no').val(data.reg_no);
					$('#issued_date').val(data.issue_date);
					$('#issued_by').val(data.issuer);
					var isFinish = data.certificate_finished;
					if (isFinish == "2") { 
						$('#is_finished').attr('checked', 'checked');
					} else { 
						$('#is_finished').removeAttr('checked');
					}
				}
			}
	    });
		$('#sender_name').val(sender_name);
		$('#orinal_student_name').val(holder_name);
		$('#school_name').val(school_name);
		$('#diploma_id').val(diploma_id);
		
		$('#certificate_info_dlg').dialog('open');
	});

	$('#is_finished').click(function(){ 
		if ($(this).is(":checked")) { 
			$('#certifi_finished').val(2);
		} else { 
			$('#certifi_finished').val(1);
		}
	})

	$('#student_major').autocomplete({ 
		source: 'index.php?option=com_vjeecdcm&task=diplomas.getSelectMajors',
		minlength: 2
	});

	var $certificate_form = $('#certificate_form');
	// update certificate info
	$('.update_certificate').click(function() { 
		var formData = $certificate_form.serialize();
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=diplomas.updateDiploma',
			type : 'POST',
			data: formData,
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) {
				var ret = $.parseJSON(data);
				if (ret.status == 'success') { 
					alert("Cập nhật thành công!");
				} else { 
					alert("Đã có lỗi xảy ra :" + ret.msg);
				}
			}
		});
		return false;
	});

	//submit form create certification
	/* */
	
	$('#btn_submit_form').click(function() { 
		var url = $certificate_form.attr('action');
		var data = $certificate_form.serialize();
		$certificate_form.submit();
		$('#certificate_info_dlg').dialog('close');
	});
	
	// edit assigner transcipts and certificate
	$('.assign_work').editable({
		type: 'select2',
    	mode: 'popup',
    	title: 'Chọn nhân viên',
    	url: "index.php?option=com_vjeecdcm&task=employee.modifyAssignee",
	    ajaxOptions: {
			dataType: 'json'
	    },
	    params : function (params) {		    
			params.pk = parseInt($(this).attr('data-pk'));
			params.type = $(this).attr('type');
	        return params;
	    },
	    success: function(response, newValue) {
	        if (response.success == true) { 
    	    }
	    },
	    source: "index.php?option=com_vjeecdcm&task=employee.getSelectEmployees",
	    emptytext: 'Chưa chọn nhân viên',
	    select2: {
			width: "200px",
	    }
	});
	
	$('#transcript_dlg').dialog({
    	autoOpen: false,
    	modal: true,
    	width: 1280,
    	//position: 'center',
    	height: 500,
    	title: 'Tạo bảng điểm',
    	open: function(event, ui) {
    	},
    	close: function( event, ui ) { 
    		location.reload();
    	}
    });
	
	$('.detail-sec').accordion({
		collapsible: true,
		heightStyle: "content"
	});
	
	$.fn.updateSchoolYear = function(diplomaId) { 
		var $selecor = $(this);
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=transcript.getListSchoolYear',
			type : 'POST',
			data: {'diploma_id': diplomaId},
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) { 
				if (data != null) { 
					data = $.parseJSON(data);
					var html = '';
					$.each(data, function(i, item) { 
						html += '<option value="' + item.value + '">' + item.text + '</option>';
					});
					if (html != '') { 
						$selecor.append(html);
					}
				}
			}
		});
	}
	
	function initTranscriptInfo(diplomaId) { 
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=transcript.initData',
			type : 'POST',
			data: {'diploma_id': diplomaId},
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) { 
				if (data != null) { 
					data = $.parseJSON(data);
					$('#tran_fullname').val(data.holder_name);
					$('#no').val(data.no);
					$('#tran_birthday').val(data.holder_birthday);
					$('#tran_gender').val(data.gender);
					$('#place_birth').val(data.place_birth);
					$('#ethnic_group').val(data.ethnic_group);
					if (data.invalid_soldier) { 
						$('#invalid_soldier').attr('checked', 'checked');
					} else { 
						$('#invalid_soldier').removeAttr('checked');
					}
					
					$('#current_residence').val(data.current_residence);
					$('#father_name').val(data.father_name);
					$('#father_occupation').val(data.father_occupation);
					$('#mother_name').val(data.mother_name);
					$('#mother_occupation').val(data.mother_occupation);
					$('#trans_date').val(data.dated);
					if (data.id) { 
						$('#trans_id').val(data.id);
					}
				}
			}
		});
		$('#tran_diploma_id').val(diplomaId);
	}
	
	//click on update transcript info
	$('#update_tran_info').click(function() { 
		var formData = $('#frm_transcript_info').serialize();
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=transcript.updateTranscriptInfo',
			type : 'POST',
			data: formData,
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) { 
				var ret = $.parseJSON(data);
				if (ret.status == 'success') { 
					alert("Cập nhật thành công!");
				} else { 
					alert("Đã có lỗi xảy ra :" + ret.msg);
				}
			}
		});
		return false;
	});
	
	//click on transcript icon
	$('.get_transcipt_info').click(function(e) {
		e.preventDefault();
		return false;
		var diplomaId = $(this).attr('diploma_id');
		
		//update transcript info
		initTranscriptInfo(diplomaId);
		//update list school year
		$('#select_school_year').updateSchoolYear(diplomaId);
		$('#transcript_dlg').dialog('open');
	});
	
	//change school year
	$('#select_school_year').change(function() { 
		var schoolYearId = parseInt($(this).val());
		var $formStudySumary = $('#frm_study_sumary');
		if (schoolYearId > 0) { 
			$formStudySumary.show();
			initStudySumary(schoolYearId);
		} else { 
			$formStudySumary.hide();
			//clear all form data
			$formStudySumary[0].reset();
			$('#study_process_id').val('');
			$('#study_process_id').val('');
		}
		
	});
	
	function initStudySumary(schoolYearId) { 
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=transcript.getStudySumaryBySchoolYear',
			type : 'POST',
			data: {'study_process_id': schoolYearId},
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) { 
				if (data != null) { 
					data = $.parseJSON(data);
					$('#head_teacher').val(data.head_teacher);
					$('#school_administrator').val(data.school_administrator);
					$('#retaken_conduct').val(data.retaken_conduct);
					$('#nb_days_absent').val(data.nb_days_absent);
					$('#district').val(data.district);
					$('#city').val(data.city);
					$('#conduct1').val(data.conduct1);
					$('#conduct2').val(data.conduct2);
					$('#conduct3').val(data.conduct3);
					$('#learning1').val(data.learning1);
					$('#learning2').val(data.learning2);
					$('#learning3').val(data.learning3);
					$('#learning3').val(data.learning3);
					$('#learning3').val(data.learning3);
					$('#nb_days_absent').val(data.nb_days_absent);
					$('#retaken_conduct').val(data.retaken_conduct);
					$('#retaken_learning').val(data.retaken_learning);
					$('#straightly_move_up').val(data.straightly_move_up);
					$('#qualified_move_up').val(data.qualified_move_up);
					$('#unqualified').val(data.unqualified);
					$('#holding_certificate').val(data.holding_certificate);
					$('#award_contests').val(data.award_contests);
					$('#other_special_comment').val(data.other_special_comment);
					$('#head_teacher_remark').val(data.head_teacher_remark);
					$('#principal_approve').val(data.principal_approve);
					$('#sumary_dated').val(data.dated);
					$('#sumary_id').val(data.id);
				}
			}
		});
		$('#school_year_id').val(schoolYearId);
	}
	
	// click on Update sumary tab
	$('#btn_update_sumary').click(function() { 
		var formData = $('#frm_study_sumary').serialize();
		console.log('index.php?option=com_vjeecdcm&task=transcript.updateStudySumary&' + formData);return false;
		$.ajax({ 
			url: 'index.php?option=com_vjeecdcm&task=transcript.updateStudySumary',
			type : 'POST',
			data: formData,
			error: function (jqXHR, textStatus, errorThrown) {
			    alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) { 
				var ret = $.parseJSON(data);
				if (ret.status == 'success') { 
					alert("Cập nhật thành công!");
				} else { 
					alert("Đã có lỗi xảy ra :" + ret.msg);
				}
			}
		});
		return false;
	});
	
})
