(function() {
  "use strict";
}());

var https = require('https');
var options = getOptions(process.argv[2]);
// console.log(options);
var req = https.request(options, function(res) {
  var responseText = '';
  res.on('data', function(chunk) {
    responseText += chunk;
  });
  res.on('end', function() {
    console.log(responseText);
    if (res.statusCode <= 200 || res.statusCode >= 300) {
      // Exit 1
    }
  });
}).on('error', function(e) {
  console.log('I AM ERROR');
  console.log(e);
}).end();

function getOptions(params)
{
  var config = JSON.parse(params);
  var url = config.url.replace(/^https?:\/\//, '');
  var host, path;
  var splitAt = url.indexOf('/');
  if (splitAt != -1) {
    host = url.substring(0, splitAt);
    path = url.substring(splitAt);
  }
  else {
    host = url;
    path = '';
  }
  return {
    hostname: host,
    method: config.method,
    path: path
  };
}
