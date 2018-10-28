function onTabActivated(e)
{
    //console.log('New tab' + e.target);
    //console.log(e.relatedTarget);
}

function onCreateReqButtonClick(e)
{
    console.log('Create button click');
}
function onReqCreationDlgOpen()
{
    getDiplomaDegree();
    getSchool();
    $('#req-detail-holder-info-4').datepicker()
    .on('show.bs.modal', function(event) {
        // prevent datepicker from firing bootstrap modal "show.bs.modal"
        event.stopPropagation();
        });
    //bs_input_file();
    $('#create-req-btn').on('click', onCreateReqButtonClick);
    console.log("Dialog open");
}

function onUploadFileChange(files) {
    if ( (typeof files === "undefined") || files.length == 0 )
        
        return;
    
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=client.uploadFile";
    var fileName = files[0].name;
    console.log(fileName);
    var formData = new FormData();
    formData.append('upload-file', files[0], fileName);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // File(s) uploaded.
            console.log('File is uploaded');
            
            $('#upload-file-info').html(fileName);
            $('#file-preview').html('<embed src="../tmp/' + fileName + '" frameborder="0" width="100%" height="400px"/>');
        } else {
            alert('An error occurred!');
        }
    };
    xhr.send(formData);
    
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

function getDiplomaDegree()
{
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=client.getDiplomaDegrees";
    data = {};
    data[frmTk] = 1;
    $.ajax({
           url: url,
           data: data,
           dataType: "json",
           type: 'POST',
           error: function (jqXHR, textStatus, errorThrown)
           {
           console.log("Error in sending ajax request to controller client");
           alert(errorThrown);
           },
           success: function (reponse, textStatus, jqXHR)
           {
                console.log(reponse);
                var selectData = [];
                for (degree of reponse.degrees)
                {
                    selectData.push({id : degree.id, text: degree.name});
                }
                $('#req-detail-diploma').select2({
                                                 data : selectData
                                                 });
           }
           });
}

function getRequest()
{
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=client.getRequests";
    data = {};
    data[frmTk] = 1;
    console.log(url);
    console.log(document.location.pathname);
    $.ajax({
           url: url,
           data: data,
           dataType: "json",
           type: 'POST',
           error: function (jqXHR, textStatus, errorThrown)
           {
           console.log("Error in sending ajax request to controller client");
           alert(errorThrown);
           },
           success: function (data, textStatus, jqXHR)
           {
           console.log(data);
           showRequestTable(data.requests);
           }
           });
}

function getSchool()
{
    var url = document.location.origin + "/index.php?option=com_vjeecdcm&task=client.getSchools";
    data = {};
    data[frmTk] = 1;
    $.ajax({
           url: url,
           data: data,
           dataType: "json",
           type: 'POST',
           error: function (jqXHR, textStatus, errorThrown)
           {
           console.log("Error in sending ajax request to controller client");
           alert(errorThrown);
           },
           success: function (reponse, textStatus, jqXHR)
           {
           console.log(reponse);
           var selectData = [];
           for (school of reponse.schools)
           {
           selectData.push({id : school.id, text: school.name});
           }
           $('#req-detail-target-school').select2({
                                                  data : selectData
                                                  });
           }
           });
}

function onDocumentReady()
{
    $('a[data-toggle="tab"]').on('show.bs.tab', onTabActivated);
    $('#req-adding-dlg').on('show.bs.modal', onReqCreationDlgOpen);
    getRequest();
}

$(document).ready(onDocumentReady);
