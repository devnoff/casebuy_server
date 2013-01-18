<?


$bundleID = $_GET['bundleID'];
$countryCode = $_GET['countryCode'];
$languageCode = $_GET['languageCode'];
$type = $_GET['type'];


header('Content-type: application/json');

echo '{"code":0, "result":{ "title":"광고테스트","content_url":"http://casebuy.me/adContents/test.php"}}';

?>