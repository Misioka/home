/**
 * Created by misio on 10.2.16.
 */
var mysql = require("mysql");

// First you need to create a connection to the db
var con = mysql.createConnection({
	host: "31.31.73.231",
	user: "borfik",
	password: "vpsRoot!@#$%",
	database: "home"
});

con.connect(function(err){
	if(err){
		console.log('Error connecting to Db');
		return;
	}
	console.log('Connection established');
});

con.end(function(err) {
	// The connection is terminated gracefully
	// Ensures all previously enqueued queries are still
	// before sending a COM_QUIT packet to the MySQL server.
});