<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 보안 객체 로드
require(APPPATH.'helpers/secure.php');

class M extends CI_Controller {

    var $secureMgr;
    var $api_key = "7a12dfb4ef2c543db4cf16fc6b212e554bf7c33b";
   
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->model('members_model');
        $this->load->helper('cookie');
        $this->load->helper('url');
        
        
        // $headers = $this->input->request_headers();
        // $api = $headers['X-api-key'];
        
        // if ($api != $this->api_key){
	       //  redirect('api/fail');
        // }
	}
	
	function index()
	{
        echo 'hi';
	}
	
	/* 보안 객체 */
    private function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }	
    
    private function memberObj($members_id){
	    
	    if ($members_id){
		    return $this->members_model->memberById($members_id);
	    }
	    
	    return null;
    }
	
	
	/* 회원 정보 */
	function info(){
	    
	}
	
	/* 회원 가입 */
	function join(){
		$this->load->model('member_points_model');
	
	    $result['success'] = false;
	    
	    $username = $this->input->post('username');
	    $member = $this->db->get_where('members',array('username'=>$username))->row();
	    if ($member){
	    	$result['reason'] = "이미 사용중인 아이디입니다";
	    	$this->output
			 	->set_content_type('application/json')
			 	->set_output(json_encode($result));
			return;
	    }
	    
	    $nickname = $this->input->post('nickname');
	    $member = $this->db->get_where('members',array('nickname'=>$nickname))->row();
	    if ($member){
	    	$result['reason'] = "이미 사용중인 닉네임입니다";
	    	$this->output
			 	->set_content_type('application/json')
			 	->set_output(json_encode($result));
			return;
	    }
	    

	    $mobile = $this->input->post('mobile');
	    $email = $this->input->post('email');
	    
	    if ((!$mobile || $mobile=='') && (!$email || $email == '')){
	    	$result['reason'] = "휴대폰 번호 또는 이메일 주소를 입력해주세요.";
	    	$this->output
			 	->set_content_type('application/json')
			 	->set_output(json_encode($result));
			return;
	    }
	    
	    
	    if ($mobile && $mobile != ''){
	    	$member = $this->db->get_where('members',array('mobile'=>$mobile))->row();
		    if ($member){
		    	$result['reason'] = "이미 사용중인 휴대폰 번호 입니다";
		    	$this->output
				 	->set_content_type('application/json')
				 	->set_output(json_encode($result));
				return;
		    }	
	    }

	    if ($email && $email != ''){
	    	$member = $this->db->get_where('members',array('email'=>$email))->row();
		    if ($member){
		    	$result['reason'] = "이미 사용중인 이메일주소 입니다";
		    	$this->output
				 	->set_content_type('application/json')
				 	->set_output(json_encode($result));
				return;
		    }
	    }
	    
	    
	    
	    
	    
	    $pass = $this->input->post('password');
	    $securePassword = $this->secure()->encrypt($pass);
	    
	    
	    
	    
        $this->members_model->username = $username;
        $this->members_model->password = $securePassword;
        $this->members_model->mobile = $mobile;
        $this->members_model->email = $email;
        $this->members_model->nickname = $nickname;
        
        
        $members_id = $this->members_model->insert();
        if ($members_id){
        	// 포인트 지급
        	$this->member_points_model->members_id = $members_id;
        	$this->member_points_model->point = 1000;
        	$this->member_points_model->insert();
        	
        	
        	$this->db->select('id,username,telephone,mobile,nickname,email,date_join');
        	$query = $this->db->get_where('members',array('id'=>$members_id));
        	$member = $query->row();

        	// 패스워드 및 탈퇴일 제거
        	unset($member->password);
	        unset($member->date_quit);

            $result['success'] = true;
	    	$result['member'] = $member;
	    }
	    
	    $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	// /* 회원정보 업데이트 */
	// function update(){
	// 	$updateItem = $this->input->post();
		
	// 	$password = $this->input->post('password');
	// 	if ($password){
	// 		$updateItem['password'] = $this->secure()->encrypt($password);
	// 	}
		
	// 	$mobile = $this->input->post('mobile');
	// 	if ($mobile){
	// 		$mobileStr = implode('-',$mobile);
	// 		$updateItem['mobile'] = $mobileStr;
	// 	}
		
	// 	$member = $this->memberObj();
	// 	$this->members_model->id = $member->id;
		
	// 	if ($this->members_model->update($updateItem)){
	// 		echo '{"success":true}';
	// 	} else {
	// 		echo '{"success":false}';
	// 	}
	// }
	
	
	// /* 사용자 아이디 존재 확인 */
	// function checkUsername(){
	// 	$username = $this->input->post('username');
		
	// 	$this->db->where('username',$username);
	// 	$cnt = $this->db->count_all_results('members');
		
	// 	if ($cnt > 0){
	// 		echo 'true';
	// 		return;
	// 	}		
	// 	echo 'false';
	// 	return;
    
	// }
	
	
	/* 로그인 */
	function login(){
	
/*
		$headers = $this->input->request_headers();
		
		$headers['content'] = $this->input->post();
	
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($headers));
		return;
*/

	
	    $username = $this->input->post('username');
	    $pass = $this->input->post('password');

	    
	    $securePassword = $this->secure()->encrypt($pass);
	    
	    $member = $this->members_model->memberByUsername($username);
	    
	    $result['success'] = false;
	    if ($member && $securePassword == $member->password){
            
            /* 
            * 세션키와 사용자 아이디 저장
            * $sessionKey : username@현재시간 md5 암호화
            * 세션만료 : 60초
            */
	        $sessionKey = $this->secure()->getSessionKey($username);
	        set_cookie('casebuy_sessionKey',$sessionKey,3600);
	        set_cookie('casebuy_username',$username,3600);
	        
	        unset($member->password);
	        unset($member->date_quit);
	        
	    	$result['success'] = true;
	    	$result['member'] = $member;
	    }
	    
	    $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
			 
		return;
	    
	}
	
	/* 로그아웃 */
	function logout(){
	    delete_cookie('casebuy_username');
	    delete_cookie('casebuy_sessionKey');
	    delete_cookie('none_member_session');
	    
	    set_cookie('casebuy_none_member','1',0);
	    
	    $ref = $this->input->server('HTTP_REFERER', TRUE);
	    redirect($ref); 
	}
	
	
	/*
	 * 성인 인
	 */
	function checkJumin(){
	    $name = $this->input->post('name');
	    $jumin = $this->input->post('jumin');
	    
	    $reason = '';
	    if (check_jumin($jumin)){
            
            // 생년 월일 검사
		    $j = substr($jumin, 0, 2); 
		    $age = date('Y')-($j+($j<39?2000:1900))+1; 
		
		    if ($age > 19){		    
		    	$date = new DateTime();
	            $none_member_session = $this->secure()->encrypt($date->format('Y-m-d H:i:s'));
	            set_cookie('casebuy_none_member',$none_member_session,3600*5);
	            
		    	echo '{"success": true}';
		    	return;
		    } else {
			    echo '{"success": false, "reason":"CASEBUY는 성인만 이용하실 수 있습니다. 감사합니다."}';
			    return;
		    }
	    } 
	    
        echo '{"success": false,"reason":"성인인증에 실패했습니다. 이름과 주민등록번호를 정확하게 입력하여 주십시오"}';
	    return;
	    
	}
	
	function sessionValid($id){
        echo $this->secure()->getSessionKey($id);
    }
	
	
	
	function test(){
		
	$headers = $this->input->request_headers();
	
	$this->output
		 ->set_content_type('application/json')
		 ->set_output(json_encode($headers));
		
	}
	
	/*
	 * 아이디 찾기 : 이메일 
	 */
	function findUsernameByEmail(){
		$result['success'] = false;
		$email = $this->input->post('email');
		
		if ($email == '' || $email == null){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;	
		}
		
		$member = $this->db->get_where('members',array('email'=>$email))->row();
		
		$result['inputs'] = $this->input->post();
		if ($member){
			$username = $member->username;
			$result['username'] = $username;
			$result['success'] = true;		
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
		
	}
	
	/*
	 * 아이디 찾기 : 휴대폰 
	 */
	function findUsernameByMobile(){
	
		$result['success'] = false;
		
		$mobile = $this->input->post('mobile');

		if ($mobile == '' || $mobile == null){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));	
			return;
		}

		$mobile = explode('-',$mobile);
		$mobile = implode('', $mobile);
		
		$this->db->where("replace(mobile,'-','')",$mobile);
		$member = $this->db->get('members')->row();
		
		
		if ($member){
			$username = $member->username;
			$result['username'] = $username;
			$result['success'] = true;		
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
		
	}
	
	
	/*
	 * 비밀번호 찾기 : 이메일 
	 */
	 
	function findPasswordByEmail(){
		$result['success'] = false;
		
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		
		if (!$username || !$email){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		
		$member = $this->db->get_where('members',array('email'=>$email, 'username'=>$username))->row();
		
		if (!$member){
			$result['code'] = 'id_not_exist';
			$result['reason'] = '입력하신 아이디 또는 이메일로 가입된 계정이 없습니다';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
				 
			return;
		}
		
		if ($member){
			$new_password = $this->createPassword();
			
			/* 이메일로 전송 */
			$config['protocol']  = 'smtp';
			$config['smtp_host'] = 'ssl://smtp.googlemail.com';
			$config['smtp_port'] = 465;
			$config['smtp_user'] = 'support@casebuy.me';
			$config['smtp_pass'] = 'cult1905';
			$config['charset']   = 'utf-8';
			$config['mailtype']  = 'html';
			$config['newline']   = "\r\n"; 
			
			$this->load->library('email',$config);
			
			$this->email->from('support@casebuy.me', 'CASEBUY');
			$this->email->to($email); 
			
			$this->email->subject('CASEBUY 임시 비밀번호 입니다.');
			$this->email->message('안녕하세요. <br/>CASEBUY입니다. <br/><br/>회원님께서 임시 비밀번호 생성을 요청하셨습니다. <br/>임시 비밀번호 '.$new_password.' <br/><br/>사이트에 로그인하신 후 MY > 내 정보에서 비밀번호를 변경해주세요. <br/><br/>본인이 아닐 경우 고객센터로 연락바랍니다.<br/>고객센터 : 070-8650-2086<br/><br/> 감사합니다.<br/>좋은 하루 되세요 <br/><br/>[CASEBUY] http://www.casebuy.me <br/>[TEL] 070-8650-2086');	
			
			
			if($this->email->send()){
				$result['success'] = true;
/* 				$result['new_password'] = $new_password; */

				// 회원 정보 업데이트
	        	if (!$this->saveNewPassword($member->id,$new_password)){
		        	$result['success'] = false;
		        	$result['reason'] = 'DB Update Failed';
	        	} 
			} else {
				$result['reason'] = $this->email->print_debugger();
			}
		} 
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
			 
	}
	
	
	/*
	 * 비밀번호 찾기 : 휴대폰 
	 */
	function findPasswordByMobile(){
		$result['success'] = false;
	
		$username = $this->input->post('username');
		$dashedMobile = $this->input->post('mobile');
		
		$mobile = explode('-',$dashedMobile);
		$mobile = implode('',$mobile);
		
		if (!$username || !$dashedMobile){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			
			return;
		}
		
 		$this->db->where("replace(mobile,'-','')",$mobile);
		$member = $this->db->get_where('members',array('username'=>$username))->row();
		
		if ($member){

			$new_password = $this->createPassword();
			$msg = '[CASEBUY] 회원님의 임시 비밀번호는 '.$new_password.' 입니다. 감사합니다.';
			$mobile = $dashedMobile;
			
			$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
			// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
		    $sms['user_id'] = base64_encode("noffxp"); //SMS 아이디.
		    $sms['secure'] = base64_encode("327efd810f25ddf6948b2d890113218f") ;//인증키
		    $sms['msg'] = base64_encode(stripslashes($msg));
		
		    $sms['rphone'] = base64_encode($mobile); // ex: 010-000-0000
		    $sms['sphone1'] = base64_encode('070');
		    $sms['sphone2'] = base64_encode('8650');
		    $sms['sphone3'] = base64_encode('2086');
		    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		
		
		    $host_info = explode("/", $sms_url);
		    $host = $host_info[2];
		    $path = $host_info[3]."/";
		
		    srand((double)microtime()*1000000);
		    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		    //print_r($sms);
		
		    // 헤더 생성
		    $header = "POST /".$path ." HTTP/1.0\r\n";
		    $header .= "Host: ".$host."\r\n";
		    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
		
			$data = '';
		    // 본문 생성
		    foreach($sms AS $index => $value){
		        $data .="--$boundary\r\n";
		        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		        $data .= "\r\n".$value."\r\n";
		        $data .="--$boundary\r\n";
		    }
		    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
		
		    $fp = fsockopen($host, 80);
		
		    if ($fp) {
		        fputs($fp, $header.$data);
		        $rsp = '';
		        while(!feof($fp)) {
		            $rsp .= fgets($fp,8192);
		        }
		        fclose($fp);
		        $msg = explode("\r\n\r\n",trim($rsp));
		        $rMsg = explode(",", $msg[1]);
		        $Result= $rMsg[0]; //발송결과
		
		        //발송결과 알림
		        if($Result=="success") {
		        
		        	// 회원 정보 업데이트
		        	if ($this->saveNewPassword($member->id, $new_password)){
			        	echo '{"success":true}';
			        	return;
		        	} else {
			        	echo '{"success":false}';
			        	return;
		        	}
		            
		        } else {
		        	echo '{"success":false, "reason":"sms '.$Result.'", "mobile":"'.$mobile.'", "msg":"'.$msg.'"}';
		        	return;
		        }
		        
		    }
		    else {
		        echo '{"success":false, "reason":"fsockopen not work"}';
		        return;
		    }
		}
		
		$result['code'] = 'id_not_exist';
		$result['reason'] = '입력하신 아이디 또는 휴대전화 번호로 가입된 계정이 없습니다';
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
		
	}
	
	private function saveNewPassword($members_id,$new_password){
	
		$updateItem['password'] = $this->secure()->encrypt($new_password);
		
		$this->members_model->id = $members_id;
		if ($this->members_model->update($updateItem)){
			return true;
		} else {
			return false;
		}
	}
	
	
	function createPassword($length=4){
		
		$words = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$cnt = count($words);
		$result = array();
		for($i = 0; $i < $length; $i++){
			$idx = mt_rand(0, $cnt-1);
			array_push($result,$words[$idx]);
		}
		
		return implode('',$result);
	}
	
	
	
	function changePassword(){
		 $members_id = $this->input->post('members_id');
		 $current_password = $this->input->post('current_password');
		 $new_password = $this->input->post('new_password');
		 
		 $result['success'] = false;
		 if (!$members_id || !$current_password || !$new_password){
		 	$result['code'] = 'lack_of_element';
			$result['reason'] = 'lack_of_element';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
			 
		 }
		 
		 $member = $this->memberObj($members_id);
		 if (!$member){
			$result['code'] = 'not_member';
			$result['reason'] = 'not_member';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return; 
		 }
		 
		 $secure_current = $this->secure()->encrypt($current_password);
		 if ($member->password != $secure_current){
			$result['code'] = 'current_password_not_match';
			$result['reason'] = '현재 비밀번호가 맞지 않습니다. 다시 입력해주세요.';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;  
		 }
		 $secure_new_pass = $this->secure()->encrypt($new_password);
		 
		 $data = array('password'=>$secure_new_pass);
		 
		 $this->load->model('members_model');
		 $this->members_model->id = $members_id;
		 
		 
		 if ($this->members_model->update($data)){
			 $result['success'] = true;
		 }
		 
		 $this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result)); 
		 
	 }
	 
	 function changeEmail(){
		 $members_id = $this->input->post('members_id');
		 $email = $this->input->post('email');
		 
		 $result['success'] = false;
		 if (!$members_id || !$email){
		 	$result['code'] = 'lack_of_element';
			$result['reason'] = 'lack_of_element';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		 }
		 
		 $member = $this->db->get_where('members',array('email'=>$email))->row();
		 if ($member){
		 	$result['code'] = 'email_already_exist';
			$result['reason'] = '이미 사용중인 이메일 입니다. 다시 입력하세요.';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		 }
		 
		 
		 $data = array('email'=>$email);
		 
		 $this->load->model('members_model');
		 $this->members_model->id = $members_id;
		 
		 if ($this->members_model->update($data)){
			 $result['success'] = true;
		 }
		 
		 $this->output
			  ->set_content_type('application/json')
			  ->set_output(json_encode($result)); 
		 
	 }
	 
	 function changeMobile(){
		 $members_id = $this->input->post('members_id');
		 $mobile = $this->input->post('mobile');
		 
		 $result['success'] = false;
		 if (!$members_id || !$mobile){
		 	$result['code'] = 'lack_of_element';
			$result['reason'] = 'lack_of_element members_id:'.$members_id.' mobile:'.$mobile;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		 }
		 
		 $member = $this->db->get_where('members',array('mobile'=>$mobile))->row();
		 if ($member){
		 	$result['code'] = 'mobile_already_exist';
			$result['reason'] = '이미 사용중인 핸드폰 번호입니다. ';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		 }
		 
		 $data = array('mobile'=>$mobile);
		 
		 $this->load->model('members_model');
		 $this->members_model->id = $members_id;
		 
		 if ($this->members_model->update($data)){
			 $result['success'] = true;
		 }
		 
		 $this->output
			  ->set_content_type('application/json')
			  ->set_output(json_encode($result)); 
	 }

	 function mobileExists(){
	 	$mobile = $this->input->get('mobile');

	 	$result['success'] = false;
	 	if (!$mobile){
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($result)); 
	 	}

		$result['success'] = true;
	 	$member = $this->db->get_where('members',array('mobile'=>$mobile))->row();
		 if ($member){
		 	$result['exist'] = true;
		 } else {
		 	$result['exist'] = false;
		 }

		 $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	 }

	 function smsValid(){


	 	$msg = $this->input->post('msg');
	 	$mobile = $this->input->post('to');

	 	if (!$msg || !$mobile){
	 		echo '{"success":false}';
	 		return;
	 	}

	 	$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
	    $sms['user_id'] = base64_encode("noffxp"); //SMS 아이디.
	    $sms['secure'] = base64_encode("327efd810f25ddf6948b2d890113218f") ;//인증키
	    $sms['msg'] = base64_encode(stripslashes($msg));

	    $sms['rphone'] = base64_encode($mobile); // ex: 010-000-0000
	    $sms['sphone1'] = base64_encode('070');
	    $sms['sphone2'] = base64_encode('8650');
	    $sms['sphone3'] = base64_encode('2086');
	    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.


	    $host_info = explode("/", $sms_url);
	    $host = $host_info[2];
	    $path = $host_info[3]."/";

	    srand((double)microtime()*1000000);
	    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	    //print_r($sms);

	    // 헤더 생성
	    $header = "POST /".$path ." HTTP/1.0\r\n";
	    $header .= "Host: ".$host."\r\n";
	    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		$data = '';
	    // 본문 생성
	    foreach($sms AS $index => $value){
	        $data .="--$boundary\r\n";
	        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
	        $data .= "\r\n".$value."\r\n";
	        $data .="--$boundary\r\n";
	    }
	    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

	    $fp = fsockopen($host, 80);

	    if ($fp) {
	        fputs($fp, $header.$data);
	        $rsp = '';
	        while(!feof($fp)) {
	            $rsp .= fgets($fp,8192);
	        }
	        fclose($fp);
	        $msg = explode("\r\n\r\n",trim($rsp));
	        $rMsg = explode(",", $msg[1]);
	        $Result= $rMsg[0]; //발송결과

	        //발송결과 알림
	        if($Result=="success") {
	            echo '{"success":true}';
	        } else {
	        	echo '{"success":false, "reason":"sms '.$Result.'", "mobile":"'.$mobile.'", "msg":"'.$msg.'"}';
	        }
	        
	    }
	    else {
	        echo '{"success":false, "reason":"fsockopen not work"}';
	    }
	 }
}


?>