<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');
/* require_once(APPPATH.'helpers/secure.php'); */

class Member extends CI_Controller {

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
    function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }	
	
	/* 회원 목록 */
	function lists(){
	    
	}
	
	/* 회원 정보 */
	function info(){
	    
	}
	
	/* 성인 인증 */
	function checkAdult(){
	    $this->load->view('member/check_adult_view');
	}
	
	/* 회원 가입 */
	function join(){

	}
	
	
	
	/* 로그인 */
	function login(){
		$data['redirect_url'] = site_url('shop/main');
        $this->load->view('member/login_view',$data);
	}
	
	/* 로그아웃 */
	function logout(){
		redirect('/actions/member/logout'); // 성인 인증 화면으로 리다이렉트  
	}
	

	function checkAdult1($jumin){
	{

		if (check_jumin($jumin)){
			echo 'true';
		} else {
			echo 'false';
		}

		// $juminUnits = str_split($jumin);


		// if(empty($juminUnits) || count($juminUnits) != 13)  
		// {
		// 	echo 'false';
		// 	return false;
		// } 

		// $mm = $juminUnits[2] + $juminUnits[3];
		// $dd = $juminUnits[4] + $juminUnits[5];

		// if(intval($mm) == 0 || intval($mm) > 12)
		// {
		// 	echo 'false';
		// 	return false;
		// }

		// if(intval($dd) == 0 || intval($dd) > 31)
		// {
		// 	echo 'false';
		// 	return false;
		// }

		// if(intval($juminUnits[6]) == 0 || intval($juminUnits[6]) > 4)
		// {
		// 	echo 'false';
		// 	return false;
		// }
		
		// $arrDivide = array(2,3,4,5,6,7,8,9,2,3,4,5);  
		// $sum = 0;

		// for($i = 0; $i < count($juminUnits) - 1; $i++)
		// {   
		// $sum += intval($juminUnits[$i]) * intval($arrDivide[$i]);  
		// }

		// $mod = 11- ($sum % 11);
		// if($mod >= 10)
		// {
		// $mod = $mod % 11;
		// }

		// if($juminUnits[count($juminUnits) - 1] != $mod)
		// {
		// 	echo 'false';
		// 	return false;
		// }


		// echo 'true';
		// return true;


	}


    
}
	
}


?>