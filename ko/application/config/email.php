<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = '\r\n';

// TODO 메일 계정 바꾸기
$config['protocol']  = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'casebuy@cultstory.com';
$config['smtp_pass'] = 'cult1905';
$config['smtp_timeout'] = 10;


/* End of file email.php */
/* Location: ./application/config/email.php */