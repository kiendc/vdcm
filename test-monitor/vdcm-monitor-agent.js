var x = document.cookie;
console.log('Cookie : ' + JSON.stringify(x));
var token = ""
var socket = undefined;

function connectToMonitor()
{
    socket = io.connect('/', { query: 'token=' + token });
    socket.on('error', function (err) {
        console.log('Authentication failed ' + JSON.stringify(err));
    });
    socket.io.on('connect_error', function (err) {
        // handle server error here
        console.log('Error connecting to server');
        socketOff();
    });
    socket.on('connect', onSocketConnected);
    socket.on('connection', function (data) {
        console.log('on connection');
    })
}
//////////////////////////////////////////////////

$(document).ready(function () {
    console.log('DOM is ready');
    //var data = new google.visualization.DataTable();
    
    $(document).on('click', ".btn-submit-test", onBtnSubmitTest); // Button is added dynamically
    $(document).on('click', ".btn-detail-test", onBtnDetailTest); // Button is added dynamically

    
   
    $('#create-test-modal').on('show.bs.modal', onShowCreateModal);
    $('#btn-add-test').on('click', onBtnAddTest);
});

