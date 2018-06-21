function initializedTables()
{
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=reqemployee.getRequests";
    var data = {};
    data[frmTk] = 1;
    data["step"] = 2;
    submTabl = $('#recv-reqs-table').DataTable({
                                               ajax: {
                                                url: url,
                                                type: 'POST',
                                                data: data,
                                               dataSrc: function(servResp){
                                                console.log(servResp);
                                                return servResp.requests;
                                               }
                                               },
                                               columns :
                                               [
                                                {data: 'request_id', orderable: false},
                                                {data: 'code'},
                                                {data: 'created_date'},
                                                {data: 'holder_name'},
                                                {data: 'degree_name'},
                                                {data: 'route'}
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
    data["step"] = 3;
    procTabl = $('#proc-reqs-table').DataTable({
                                               ajax: {
                                               url: url,
                                               type: 'POST',
                                               data: data,
                                               dataSrc: function(servResp){
                                               console.log(servResp);
                                               return servResp.requests;
                                               }
                                               },
                                               columns :
                                               [
                                                {data: 'request_id', orderable: false},
                                                {data: 'code'},
                                                {data: 'created_date'},
                                                {data: 'holder_name'},
                                                {data: 'degree_name'},
                                                {data: 'route'}
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
    data["step"] = 5;
    compTabl = $('#comp-reqs-table').DataTable({
                                               ajax: {
                                               url: url,
                                               type: 'POST',
                                               data: data,
                                               dataSrc: function(servResp){
                                               console.log(servResp);
                                               return servResp.requests;
                                               }
                                               },
                                               columns :
                                               [
                                                {data: 'request_id', orderable: false},
                                                {data: 'code'},
                                                {data: 'created_date'},
                                                {data: 'holder_name'},
                                                {data: 'degree_name'},
                                                {data: 'route'}
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
}

function getRequest(step)
{
   if (step == 2)
       submTabl.ajax.reload(null, false);
}

function onReqsTabActivated(e)
{
    var tabId = e.target.attributes[0].value;
    console.log(e);
    if (tabId.localeCompare('#subm') == 0)
        submTabl.ajax.reload(null, false);
    else if (tabId.localeCompare('#proc') == 0)
        procTabl.ajax.reload(null, false);
    else if (tabId.localeCompare('#comp') == 0)
        procTabl.ajax.reload(null, false);
    else
        console.log(tabId + ' is activated');
}

function onDocumentReady()
{
    $.fn.editable.defaults.mode = 'inline';
    initializedTables();
    $('#req-tabs a[data-toggle="tab"]').on('show.bs.tab', onReqsTabActivated);
}

$(document).ready(onDocumentReady);
