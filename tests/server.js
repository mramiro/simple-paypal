(function() {
  "use strict";
}());

var http = require('http');
var urlParser = require('url');
var qsParser = require('querystring');
var port = 8080;

function handleRequest(request, response)
{
  console.log("Incoming request: ", request.method + ' ' + request.url);

  var body = '';
  request.on('data', function(chunk) {
    body += chunk;
    if (body.length > 1e6) {
      request.connection.destroy();
    }
  });

  request.on('end', function() {
    response.setHeader('Content-Type', 'application/json');
    response.statusCode = 200;
    response.write(buildResponseText(request, body), 'utf-8');
    response.end();
  });

}

function buildResponseText(request, body)
{
  var data = {};
  console.log("Incoming request: ", request.method + ' ' + request.url);
  data.method = request.method;
  data.url = request.url;
  if (data.method == 'GET') {
    var url = urlParser.parse(data.url);
    data.params = qsParser.parse(url.query);
  }
  else if (data.method == 'POST') {
    data.params = qsParser.parse(body);
  }
  return JSON.stringify(data);
}

http.createServer(handleRequest).listen(port, function() {
  console.log('Server listening on http://localhost:' + port);
});
