var x = document.cookie;
console.log('Cookie : ' + JSON.stringify(x));
var token = ""
var socket = undefined;
var config = {
	"domain": 'vjeec-monitor-vdcm-monitor.193b.starter-ca-central-1.openshiftapps.com/',
	"clientport": '8000',
	"protocol":   'ws://',
	
	"heartbeattmo": 1000, // milliseconds
	
	"wsclientopts": {
	reconnection: true,
	reconnectionDelay: 2000,
	reconnectionAttempts: 3,
	secure: false
	}
};

function connectToMonitor()
{
	
	var connString = config.protocol + config.domain + ':' + config.clientport;
	
	console.log("Websocket connection string:", connString, config.wsclientopts);
	socket = io.connect(connString, config.wsclientopts);

	socket.on('error', function (err) {
						console.log('Authentication failed ' + JSON.stringify(err));
    });
    socket.on('connect_error', function (err) {
        // handle server error here
        console.log('Error connecting to server');
							console.log(err);
    });
    socket.on('connect', function (data){
							console.log('Connect to monitor');
							
							});
    socket.on('connection', function (data) {
        console.log('on connection');
    })
}

