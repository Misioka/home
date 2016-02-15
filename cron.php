<?php
$url = 'www.temp.michalkarzel.eu/gettemp.php';
$fields = array(
	'key' => 'kfh54tpo',
	'temp' => '25'
);
$fields_string = '';
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);
var_dump($result);
curl_close($ch);
?>