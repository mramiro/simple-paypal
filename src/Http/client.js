#!/usr/bin/env node

var urlParser = require('url');
var queryString = require('querystring');

var config = JSON.parse(process.argv[2]);
var url = urlParser.parse(config.url);
var options =  {
  method: config.method,
  hostname: url.hostname,
  path: url.path,
  protocol: url.protocol,
  port: url.port,
  headers: {
    'User-Agent': 'node ' + process.version + '-' + process.arch + '-' + process.platform
  }
};
if (config.debug !== undefined ) {
  options.rejectUnauthorized = !config.debug;
}

var client = options.protocol == 'https:' ? require('https') : require('http');

var data = config.data;
if (config.method == 'POST' && data !== undefined) {
  if (typeof data != 'string') {
    data = queryString.stringify(data);
    options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
  }
  options.headers['Content-Length'] = Buffer.byteLength(data, 'utf-8');
}

var req = client.request(options, function(res) {
  var responseText = '';
  res.on('data', function(chunk) {
    responseText += chunk;
  });
  res.on('end', function() {
    console.log(responseText);
  });
});

req.on('error', function(e) {
  console.log('I AM ERROR:', e);
  process.exitCode = 1;
});

if (data) {
  req.write(data);
}

req.end();
