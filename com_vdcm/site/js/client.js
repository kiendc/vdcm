function activateTab(tab)
{
    console.log('activate ' + tab); 
    $('#main-client-tab a[href="#' + tab + '"]').tab('show');
}

function onTabActivated(e)
{
    //console.log('New tab' + e.target);
    //console.log(e.relatedTarget);
}

function onReqCreationDlgOpen()
{
	$('#req-detail-target-school').editable();
	$('#req-detail-type').editable();
	console.log("Dialog open");
	
}

function showRequestTable(data)
{
    $('#example').DataTable({
       data: data,
       columns : [
	   {data: 'request_id', orderable: false},	
           {data: 'code'},
           {data: 'created_date'},
           {data: 'holder_name'},
           {data: 'degree_name'},
           {data: 'route'},
           {data: 'name'},
           {data: 'begin_date'}
       ],
       columnDefs: [{
	   targets: 0,
           render: function (data, type, full, meta){
               return '<input type="checkbox" name="id[]" value="' + data + '">';
           }
       }],
    });
    //activateTab('taba');
    //$('main-client-tab a:first').tab('show');
}

function getRequest()
{
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
             console.log(data.requests);
	     showRequestTable(data.requests);
         }
     });   
}

$(document).ready(function(){
									
   $('a[data-toggle="tab"]').on('show.bs.tab', onTabActivated);
									$('#req-adding-dlg').on('show.bs.modal', onReqCreationDlgOpen);
   getRequest();	
	
});
