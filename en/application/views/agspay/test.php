<?

require_once (APPPATH."libraries/AGSLib.php");

$request["log"] = 'true';
$request["logLevel"] = 'INFO';
$request["AgsPayHome"] =  BASEAPPPATH.'/logs';
$request["StoreId"] = 'aegis';

$log = new PayLog($request);

if ($log->InitLog()){
	echo 'succeess';
} else {
	echo 'failed';
}
echo '<br/>';

echo  microtime();

echo '<br/>';

$sec= explode(" ", microtime(true));
var_dump($sec);
echo (float)$sec[0] + (float)$sec[1];

?>