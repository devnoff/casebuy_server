<?php

require('lib/AGSLib.php');

global $PHP_SELF; 
$thisfilename=basename(__FILE__);
$temp_filename=realpath(__FILE__);

if(!$temp_filename) $temp_filename=__FILE__;

$osdir=eregi_replace($thisfilename,"",$temp_filename);

unset($temp_filename);

$virdir = eregi_replace($thisfilename,"",$PHP_SELF);




$request["log"] = 'true';
$request["logLevel"] = 'INFO';
$request["AgsPayHome"] = $osdir;
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
list($sec1, $sec2) = explode(" ", microtime(true));
echo (float)$sec1 + (float)$sec2;


echo $_GET['hello'];

?>