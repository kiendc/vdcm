var express = require('express');
var app = express();
var http = require('http').Server(app);


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
http.listen(8000, function(){
    console.log('listening on *: 8000');
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
