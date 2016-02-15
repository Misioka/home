var express = require('express');
var app = express();
app.set('view engine', 'jade');
app.use('/assets', express.static('assets'));

app.get('/', function (req, res) {
	res.render('index', { title: 'Hey', message: 'Hello there!'});
});

app.post('/', function (req, res) {
	console.log(req.query);
	/*con.query('INSERT INTO temperature SET ?', ,function(err,rows){
		if(err) throw err;

		console.log('Data received from Db:\n');
		console.log(rows);
	});*/
	res.send('Got a POST temperature ;)!');
});

app.listen(3000, function () {
	console.log('Example app listening on port 3000!');
});