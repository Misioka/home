var express = require('express');
var path = require('path');
var fs = require('fs');
var session  = require('express-session');
var bodyParser = require('body-parser');
var passport = require('passport');
var flash    = require('connect-flash');
var mysql = require("mysql");
var port = 3456;

require('./config/passport')(passport);

var app = express();
app.set('view engine', 'jade');
app.use('/assets', express.static(path.join(__dirname, 'assets')));
app.set('views', path.join(__dirname, 'views'), {
	layout: false
});
app.use(bodyParser.json()); // support json encoded bodies
app.use(bodyParser.urlencoded({ extended: true }));
app.use(session({
	secret: 'misiojenejboceccotozrobil',
	resave: true,
	saveUninitialized: true
} )); // session secret
app.use(passport.initialize());
app.use(passport.session()); // persistent login sessions
app.use(flash());

require('./routes.js')(app, passport);

app.listen(port, function () {
	console.log('Example app listening on port ' + port + '!');
});

module.exports = app;