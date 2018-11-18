function str_replace(search, replace, str){
	var ra = replace instanceof Array, sa = str instanceof Array, l = (search = [].concat(search)).length, replace = [].concat(replace), i = (str = [].concat(str)).length;
	while(j = 0, i--)
		while(str[i] = str[i].split(search[j]).join(ra ? replace[j] || "" : replace[0]), ++j < l);
	return sa ? str : str[0];
}
 
function remove_vietnamese_accents(str)
{
	accents_arr= new Array(
		"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
		"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
		"ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
		"ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ",
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
		"Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ",
		"Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ"
	);
 
	no_accents_arr= new Array(
		"a","a","a","a","a","a","a","a","a","a","a",
		"a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o",
		"o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d",
		"A","A","A","A","A","A","A","A","A","A","A","A",
		"A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O",
		"O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D"
	);
 
	return str_replace(accents_arr,no_accents_arr,str);
}

function sleep(ms)
{
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++)
		if ((new Date().getTime() - start) > ms)
			break;	
}

function genByjsPDF()
{
	/*
	var columns = [
		{title: "Order", dataKey: 'ord'},
		{title: "Code", dataKey: 'cod'},
		{title: "Student", dataKey: 'stu'},
		{title: "School", dataKey: 'sch'}];
 	*/
 	var columns = ["Order", "Code", "Student", "School"];
 	var tbl = $('table#tbl_jalsa tbody tr').get().map(function(row) {
  		return $(row).find('td').get().map(function(cell) {
    		return $(cell).html();
  		});

	});
	console.log(tbl);
	var title = $('p#shipment').html();
	genPDF(title, tbl, "table");
	
}

function genPDFFromJson(reqs, expectedDate, requestType)
{
	if (reqs.length < 1)
	{
		alert('No request is sent to date ' + expectedDate);
		return;
	}
	rows = [];
	var ind = 1;
	var rowsBySch = new Map();
	reqs.forEach(function(req)
	{
		
		var student = remove_vietnamese_accents(req.holder_name).toUpperCase();
		if (requestType == 0)
		{

			rows.push([ind, req.code, student, req.school_name.toUpperCase()]);
			ind++;
		}
		if (!rowsBySch.has(req.school_id))
		{
			rowsBySch.set(req.school_id, {name: req.school_name,
						      address: req.school_address,
						      zipcode: req.school_zipcode,
						      phone: req.school_phone,
						      contact: req.school_contact,
						      rows: []});	
		}
		let schDesc = rowsBySch.get(req.school_id);
		schDesc.rows.push([schDesc.rows.length + 1, student, req.code, req.degree_name]);
	});
	if (rows.length > 0)
	{
		var title = "VJEEC to JALSA on " + expectedDate + ". Total " + rows.length + " shipment";
		genJALSAPDF(title, rows, "jalsa-coverpage-" + expectedDate);
	}
	console.log(rowsBySch);
	var doc = new jsPDF('p', 'mm');
	let fileName = 'schools-list-' + expectedDate;
	rowsBySch.forEach(function(value, key, map)
	{
		//let fileName = value.name + '-' + expectedDate;
		//genSchoolPDF(value, fileName);
		genSchoolPDF(value, doc);
		doc.addPage();
			
	});
  	doc.save(fileName + ".pdf");
	console.log('Save to ' + fileName);
}

//function genSchoolPDF(schDesc, fileName)
function genSchoolPDF(schDesc, doc)
{
	let columns = ['No.', 'Student Name', 'Reference No.', 'Qualification'];
	//var doc = new jsPDF('p', 'mm');

  	doc.setFontSize(13);
    	doc.text(schDesc.name, 15, 20);
	doc.text(schDesc.address, 15, 30);     	
	doc.text(schDesc.zipcode, 15, 40);
	doc.text(schDesc.phone, 15, 50);
	doc.text(schDesc.contact, 15, 60);
	doc.setFontSize(11);
  	doc.autoTable(columns, schDesc.rows,{
  		startY: 70, 
  		margin: {horizontal: 20,
  			vertical: 20},
        	bodyStyles: {valign: 'top'},
        	styles: {overflow: 'linebreak', columnWidth: 'wrap'},
        	columnStyles: {3: {columnWidth: 'auto'}},
        	theme: 'grid'
        });
  	//doc.save(fileName + ".pdf");
	//console.log('Save to ' + fileName);
	//sleep(2000);
}

function genJALSAPDF(title, rows, fileName)
{
	var columns = ["Order", "Code", "Student", "School"];
  	var doc = new jsPDF('p', 'mm');
  	doc.setFontSize(18);
    	doc.text(title, 14, 22);
    	doc.setFontSize(11);
  	doc.autoTable(columns, rows,{
  		startY: 50, 
  		margin: {horizontal: 20,
  			vertical: 20},
        	bodyStyles: {valign: 'top'},
        	styles: {overflow: 'linebreak', columnWidth: 'wrap'},
        	columnStyles: {3: {columnWidth: 'auto'}},
        	theme: 'grid'
        });
  	doc.save(fileName + ".pdf");
}

function collectRequests(cid, reqs)
{
	var cid1 = cid.slice(0, 500);
	cid.splice(0,500);
	$.ajax({
		url: "index.php?option=com_vjeecdcm&task=coverpage.JSONCoverPage",
		data: {cid: cid1},
		dataType: "json",
		type: 'POST',
		error: function (jqXHR, textStatus, errorThrown)
		{
		    	alert(errorThrown);
		},
		success: function (data, textStatus, jqXHR) 
		{
			//console.log(cid);
			Array.prototype.push.apply(reqs, data);
			//console.log(reqs);
			if (cid.length > 0)
			{
				collectRequests(cid, reqs);
			}
			else
			{
				genPDFFromJson(reqs, reqs[0].expected_date);
			}
		}
	});
	console.log("There are still " + cid.length + " request to collect");
}

$(document).ready(function() { 	
	$('#gen-pdf').click(function()
	{
		alert("Generate to table.pdf");
		genByjsPDF();
	});
	$('#req-coverpage-btn').click(function()
	{
		var formData = $('#adminForm').serializeArray();
		console.log(formData);
		cid = [];
		formData.forEach(function(elem){
			if (elem.name == 'cid[]')
				cid.push(elem.value);
		});
		//console.log(cid);
		if (cid.length < 1)
		{
			alert("None of request is chosen");
			return;
		}
		var reqs = [];
		collectRequests(cid, reqs);
		
	});
	$('#coverpage-date-select2').select2({
		placeholder: 'Select an expected date',
		ajax: {
			url: 'index.php?option=com_vjeecdcm&task=coverpage.JSONExpectedDates',
			dataType: 'json',
			results: function (data) {
				console.log(data);
            			return {
                			results: data.results
            			};
        		}
		}
	});
	$('#gen-coverpage-by-date-btn').click(function()
	{
		var expectedDate = $('#coverpage-date-select2').select2('data').text;
		var requestType = 0;
		console.log(expectedDate);
		$.ajax({
			url: "index.php?option=com_vjeecdcm&task=coverpage.JSONCoverPageByDate",
			data: {expectedDate: expectedDate,
			       requestType: requestType},
			dataType: "json",
			type: 'POST',
			error: function (jqXHR, textStatus, errorThrown)
			{
		    		alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) 
			{
				console.log(data);
				genPDFFromJson(data, expectedDate, requestType);
			
			}
		});
	});
	
	$('#gen-schl-coverpage-by-date-btn').click(function()
	{
		var expectedDate = $('#coverpage-date-select2').select2('data').text;
		var requestType = 1;
		console.log(expectedDate);
		$.ajax({
			url: "index.php?option=com_vjeecdcm&task=coverpage.JSONCoverPageByDate",
			data: {expectedDate: expectedDate,
			       requestType: requestType},
			dataType: "json",
			type: 'POST',
			error: function (jqXHR, textStatus, errorThrown)
			{
		    		alert(errorThrown);
			},
			success: function (data, textStatus, jqXHR) 
			{
				console.log(data);
				genPDFFromJson(data, expectedDate, requestType);
			
			}
		});
	});
});
