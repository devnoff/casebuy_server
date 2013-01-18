<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['Sandbox'] = FALSE;
$config['APIVersion'] = '85.0';
$config['APIUsername'] = $config['Sandbox'] ? 'z8_1352695614_biz_api1.cultstory.com' : 'z8_api1.cultstory.com';
$config['APIPassword'] = $config['Sandbox'] ? '1352695634' : '2NDSKQ5692E3SLW9';
$config['APISignature'] = $config['Sandbox'] ? 'AJFQuvhToIJoOd8UwqEK5XO8254OA6MHTn0LpXMONx0fHD9zie3rU6Ep' : 'AFcWxV21C7fd0v3bYYYRCpSSRl31AkA70IPRmfGKgNg60MS.FdZijk4h';
$config['DeviceID'] = $config['Sandbox'] ? '' : '';
$config['ApplicationID'] = $config['Sandbox'] ? 'APP-80W284485P519543T' : 'APP-2MT06421DM772643J';
$config['DeveloperEmailAccount'] = $config['Sandbox'] ? 'noff@cultstory.com' : 'noff@cultstory.com';
// $config['PayFlowUsername'] = $config['Sandbox'] ? 'noff' : 'PRODUCTION_USERNAME_GOGES_HERE';
// $config['PayFlowPassword'] = $config['Sandbox'] ? 'cult1905' : 'PRODUCTION_PASSWORD_GOES_HERE';
// $config['PayFlowVendor'] = $config['Sandbox'] ? 'noff' : 'PRODUCTION_VENDOR_GOES_HERE';
// $config['PayFlowPartner'] = $config['Sandbox'] ? 'PayPal' : 'PRODUCTION_PARTNER_GOES_HERE';

/* End of file paypal.php */
/* Location: ./system/application/config/paypal.php */