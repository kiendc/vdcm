$(document).ready(function(){
/*  
$('#example').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "http://dev.vjeec.org/index.php?option=com_vjeedcm&task=client.getRequests",
      "type": "POST"
    },
    "columns": [
      { "data": "code" },
      { "data": "name" },
      { "data": "file" },
      { "data": "school" },
      { "data": "status" },
      { "data": "update_date" }
    ]
  });
*/
	var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=client.getRequests";
	data = {};
	data[frmTk] = 1;
	console.log(url);
	$.ajax({
		url: url,
		data: data,
		dataType: "json",
		type: 'POST',
		error: function (jqXHR, textStatus, errorThrown)
		{
		    		alert(errorThrown);
		},
		success: function (data, textStatus, jqXHR) 
		{
			console.log(data);
			$('#example').DataTable({
				data: data.data
			});
		}
	});
});
