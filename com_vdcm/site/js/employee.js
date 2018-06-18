function showRequestTable(step, data)
{
    if (step == 2)
    {
        $('#recv-reqs-table').DataTable(
                                        {
                                        data: data,
                                        columns :
                                        [
                                         {data: 'request_id', orderable: false},
                                         {data: 'code'},
                                         {data: 'created_date'},
                                         {data: 'holder_name'},
                                         {data: 'degree_name'},
                                         {data: 'route'},
                                         {data: 'name'}
                                         ],
                                        columnDefs:
                                        [{
                                         targets: 0,
                                         render: function (data, type, full, meta)
                                         {
                                         return '<input type="checkbox" name="id[]" value="' + data + '">';
                                         }
                                         }],
                                        });
        return;
    }
    //activateTab('taba');
    //$('main-client-tab a:first').tab('show');
}

function getRequest(step)
{
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=reqemployee.getRequests";
    data = {};
    data[frmTk] = 1;
    data["step"] = step;
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
           showRequestTable(step, data.requests);
           }
           });
}

function onTabActivated(e)
{
    console.log(e);
    getRequest(2);
}

function onDocumentReady()
{
    $.fn.editable.defaults.mode = 'inline';
    $('a[data-toggle="tab"]').on('show.bs.tab', onTabActivated);
}

$(document).ready(onDocumentReady);
