var express = require('express');
var app = express();
var http = require('http').Server(app);
var port = 2111;

///////////////////////////////////////////////
app.use(express.static(__dirname));

app.get('/client', function (req, res) {
    console.log('Process routing / with cookie', req.cookie);
    res.sendFile(__dirname + '/vdcm-client.html');
    
});


app.get('/client', function (req, res) {
    console.log('Process routing / with cookie', req.cookie);
    res.sendFile(__dirname + '/vdcm-client.html');

});

app.get('/employee', function (req, res) {
    console.log('Process routing / with cookie', req.cookie);
    res.sendFile(__dirname + '/vdcm-employee.html');

});
http.listen(port, function(){
    console.log('listening on *: ' + port);
    console.log("Socket.io version: " + require("socket.io/package").version);
});



function exitHandler(options, err) {
    if (options.cleanup)
    {
    }
        
    if (err)
    {
        console.log(err.stack);
    }
        
    if (options.exit)
    {
        console.log('Exit here');
        process.exit();
    }
        
}

//do something when app is closing
process.on('exit', exitHandler.bind(null, { cleanup: true }));

//catches ctrl+c event
process.on('SIGINT', exitHandler.bind(null, { exit: true }));

//catches uncaught exceptions
process.on('uncaughtException', exitHandler.bind(null, { exit: true }));
