<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Fail extends CI_Controller {
   
	function __construct()
	{
		parent::__construct();
		
		
	}
	
	function index()
	{
        echo '{"success":false,"reason":"권한이 없습니다"}';
	}
	
}


?>