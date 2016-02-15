<?
if (!empty($_POST['temp'])) {
	file_put_contents('data/temp', urldecode($_POST['temp']));
	file_put_contents('data/tempLog',  date('Y-m-d H:i:s') . ' -> ' . urldecode($_POST['temp']) . "\n", FILE_APPEND | LOCK_EX);
}


