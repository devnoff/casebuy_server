<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');


class Payment extends CI_Controller {

    var $secureMgr;

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->model('members_model');
        $this->load->helper('cookie');
        $this->load->helper('url');
	}
	
	function index()
	{
        echo 'hi';
	}
	

}


?>