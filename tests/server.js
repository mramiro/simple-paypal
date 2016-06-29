#!/usr/bin/env node

require('dotenv').config({ path: __dirname+'/.env' });
var fs = require('fs');
var https = require('https');
var express = require('express');
var bodyParser = require('body-parser');

var port = process.env.TEST_SERVER_PORT ? process.env.TEST_SERVER_PORT : 8080;
var app = express();

app.use(bodyParser.urlencoded({ extended: true }));

app.route('/').all(function(req, res, next) {
  console.log('Serving:', req.method + ' ' + req.path);
  console.log('Headers:', req.headers);
  console.log('Body:', req.body);
  next();
})
.get(function(req, res) {
  res.json(req.query);
}).post(function(req, res) {
  res.json(req.body);
});

var options = {
  key: fs.readFileSync(__dirname + '/certs/dummy-prvkey.pem'),
  cert: fs.readFileSync(__dirname + '/certs/dummy-pubcert.pem')
};

https.createServer(options, app).listen(port, function() {
  console.log('Server listening on port ' + port);
});
