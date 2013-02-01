<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');

class Main extends CI_Controller {

    var $secureMgr;

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->helper('cookie');
        $this->load->helper('url');
        
	}
	
	/* 보안 객체 */
    function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }
		
	function index()
	{
	
		// 심사 기간동안 바로 접속
		redirect('/shop/main');
		return;
		
		
	
	    $none_member_session = get_cookie('casebuy_none_member');
	    
	    $username = get_cookie('casebuy_username');
	    $sessionKey = get_cookie('casebuy_sessionKey');
	    
	    // 세션이 유효할 경우
	    if ($none_member_session != null || ($username && $sessionKey && $this->secure()->sessionValid($username,$sessionKey))){
/* 	        echo '세션 유지됨'; // 메인 페이지 로드 */
			redirect('/shop');
	        return;
	    }
	    
	    // 유효하지 않을 경우
        redirect('/member/checkAdult/'); // 성인 인증 화면으로 리다이렉트
	    return;

	}
	
	function testjumin($jumin){
	    
	    if (checkAdult($jumin)){
	        echo '성인';
	        return;
	    }
	    
	    echo '꺼져';
	    return;
	    
	}
	
	function testlogin($id){
        echo $this->secure()->getSessionKey($id);
    }
	


	function companyMobileURL(){
		redirect('http://m.cultstory.com/about');
	}
}


?>