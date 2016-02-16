var http = require('http');
var RequestCaching = require('./node_modules/node-request-caching/lib/request-caching');
var parser = require('xml2json');
var mysql = require('mysql');
var cookieParser = require('cookie-parser');
var dbconfig = require('./config/database');
var WHconfig = require('./config/weather');
var connection = mysql.createConnection(dbconfig.connection);
connection.query('USE ' + dbconfig.database);
var rc = new RequestCaching();

module.exports = function(app, passport) {

	app.get('/', function (req, res) {
		connection.query('SELECT t.sensorId, s.sensorName, temperature FROM temperature as t  ' +
			'INNER JOIN sensor as s on s.sensorId = t.sensorId ' +
			'ORDER BY `timestamp` DESC LIMIT 2', function(err,rows){
			if(err) throw err;

			rc.get(
				WHconfig.options.host + WHconfig.options.path + WHconfig.options.queryString,  // URI
				{},                    // query string params
				60*60*3,	           // TTL in seconds 60*60*3 = 3H
				function(err, resp, body, cache) {
					var wd = {
						weatherdata: {
							sun: {
								rise: '',
								set: ''
							},
							forecast: {
								time: []
							}
						}
					};
					if (err === null) {
						console.log(body);
						wd = JSON.parse(parser.toJson(body));
						res.render('index', {title: 'Home Temperature', tempRows: rows, wd: wd});
					} else {
						console.log(err);
						res.render('index', {title: 'Home Temperature', tempRows: rows, wd: wd});
					}
				}
			);
		});
	});

	app.post('/', function (req, res) {
		for (var key in req.body) {
			// skip loop if the property is from prototype
			if (!req.body.hasOwnProperty(key)) continue;

			var value = req.body[key];
			connection.query("INSERT INTO `temperature` (`sensorId`, `temperature`) VALUES ('" + key + "','" + req.body[key] +"')",function(err,rows){
				if(err) throw err;
			});
		}
		res.send('Got a POST temperature ;)!');
	});

	// =====================================
	// LOGIN ===============================
	// =====================================
	// show the login form
	app.get('/login', function(req, res) {
		// render the page and pass in any flash data if it exists
		res.render('login.jade', { message: req.flash('loginMessage') });
	});

	// process the login form
	app.post('/login', passport.authenticate('local-login', {
            successRedirect : '/admin', // redirect to the secure profile section
            failureRedirect : '/login', // redirect back to the signup page if there is an error
            failureFlash : true // allow flash messages
		}),
        function(req, res) {
			console.log("hello" + req.user.username);
			req.session.cookie.maxAge = 1000 * 60 * 3;
    });

	// =====================================
	// SIGNUP ==============================
	// =====================================
	// show the signup form
	app.get('/signup', function(req, res) {
		// render the page and pass in any flash data if it exists
		console.log('get signup');
		res.render('signup.jade', { message: req.flash('signupMessage') });
	});

	// process the signup form
	app.post('/signup', passport.authenticate('local-signup', {
		failureRedirect : '/signup', // redirect back to the signup page if there is an error
		failureFlash : true // allow flash messages
	}));

	// =====================================
	// PROFILE SECTION =========================
	// =====================================
	// we will want this protected so you have to be logged in to visit
	// we will use route middleware to verify this (the isLoggedIn function)
	app.get('/admin', isLoggedIn, function(req, res) {
		res.render('admin.jade', {
			user : req.user // get the user out of session and pass to template
		});
	});

	// =====================================
	// LOGOUT ==============================
	// =====================================
	app.get('/logout', function(req, res) {
		req.logout();
		res.redirect('/');
	});
};

// route middleware to make sure
function isLoggedIn(req, res, next) {

	// if user is authenticated in the session, carry on
	if (req.isAuthenticated())
		return next();

	// if they aren't redirect them to the home page
	if (req.url != '/') {
		res.redirect('/');
	}
}
