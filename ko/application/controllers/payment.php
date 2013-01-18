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
	
	/* 보안 객체 */
	/*
    function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }	
	
	private function checkSession(){
	    $none_member_session = get_cookie('casebuy_none_member');
	    
	    $username = get_cookie('casebuy_username');
	    $sessionKey = get_cookie('casebuy_sessionKey');

	    
	    // 세션이 유효할 경우
	    if ($none_member_session != null || ($username && $sessionKey && $this->secure()->sessionValid($username,$sessionKey))){
	        return true;
	    }
	    
	    // 유효하지 않을 경우
	    return false;
    }
    
    function memberObj(){
	    $username = get_cookie('casebuy_username');
	    
	    if ($username){
		    return $this->members_model->memberByUsername($username);
	    }
	    
	    return null;
    }
    
    function sessionValid(){
	    if (!$this->checkSession()){
		    //redirect('member/logout/');
		    $this->emptySession();
	    }
    }
    
    function AGS_pay(){
    	$data['postData'] = $this->input->post();
	    $this->load->view('agspay/AGS_pay',$data);
    }
    
    function AGS_pay_ing(){
	    $data['postData'] = $this->input->post();
	    
	    $this->load->view('agspay/AGS_pay_ing',$data);
    }
    
    function AGS_pay_result(){
	    $data['postData'] = $this->input->post();
	    $this->load->view('agspay/AGS_pay_result',$data);
    }
    
    function AGS_progress(){
	    $this->load->view('agspay/AGS_progress');
    }
	
	function test(){
		echo str_replace('system/','',BASEPATH);
	}
	*/
}


?>