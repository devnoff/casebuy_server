<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 보안 객체 로드
require(APPPATH.'helpers/secure.php');

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
    
    function memberObj(){
	    $username = get_cookie('caseshop_username');
	    
	    if ($username){
		    return $this->members_model->memberByUsername($username);
	    }
	    
	    return null;
    }
	
	/* 회원 목록 */
	function lists(){
	    
	}
	
	/* 회원 정보 */
	function info(){
	    
	}
	
	/* 회원 가입 */
	function join(){
		$this->load->model('member_points_model');
	
	    $username = $this->input->post('username');
	    $pass = $this->input->post('password');
	    $securePassword = $this->secure()->encrypt($pass);
	    
	    $mobile = $this->input->post('mobile');
	    $email = $this->input->post('email');

	    if ((!$mobile || $mobile=='') && (!$email || $email == '')){
	    	$result['reason'] = "휴대폰 번호 또는 이메일 주소를 입력해주세요.";
	    	$this->output
			 	->set_content_type('application/json')
			 	->set_output(json_encode($result));
			return;
	    }
	    
		if ($mobile){
			$mobileStr = implode('-',$mobile);
			$mobile = $mobileStr;
		}
		
	    
	    $nickname = $this->input->post('nickname');
	    
	    /* 이메일 모바일 유효성검사 */
	    $this->db->where('email',$email);
	    $cnt = $this->db->count_all_results('members');
	    if ($cnt > 0){
		    echo '{"success":false, "reason":"이미 가입된 이메일 입니다."}';
		    return;
	    }
	    
	    $this->db->where('mobile',$mobile);
	    $cnt = $this->db->count_all_results('members');
	    if ($cnt > 0){
		    echo '{"success":false, "reason":"이미 가입된 휴대폰 번호 입니다."}';
		    return;
	    }
	    
	    
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
        
            echo '{"success":true}';
            //$this->login();
            return;
        }
        
        echo '{"success":false, "reason":"회원가입을 완료하지 못했습니다."}';
        return;
	}
	
	/* 회원정보 업데이트 */
	function update(){
		$updateItem = $this->input->post();
		
		$password = $this->input->post('password');
		$password1 = $this->input->post('password1');
		if ($password){
			
			if ($password != $password1){
				echo '{"success":false,"reason":"비밀번호가 일치하지 않습니다"}';
				return;
			}
						
			$updateItem['password'] = $this->secure()->encrypt($password);
			
			unset($updateItem['password1']);
			
		}
		
		$mobile = $this->input->post('mobile');
		if ($mobile){
			$mobileStr = implode('-',$mobile);
			$updateItem['mobile'] = $mobileStr;
		}
		
		$member = $this->memberObj();
		$this->members_model->id = $member->id;
		
		if ($this->members_model->update($updateItem)){
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	
	
	/* 사용자 아이디 존재 확인 */
	function checkUsername(){
		$username = $this->input->post('username');
		
		$this->db->where('username',$username);
		$cnt = $this->db->count_all_results('members');
		
		if ($cnt > 0){
			echo 'true';
			return;
		}		
		echo 'false';
		return;
    
	}
	
	/* 사용자 닉네임 존재 확인 */
	function checkNickname(){
		$nickname = $this->input->post('nickname');
		
		$this->db->where('nickname',$nickname);
		$cnt = $this->db->count_all_results('members');
		
		if ($cnt > 0){
			echo 'true';
			return;
		}		
		echo 'false';
		return;
    
	}
	
	
	/* 로그인 */
	function login(){
	    $username = $this->input->post('username');
	    $pass = $this->input->post('password');
	    $securePassword = $this->secure()->encrypt($pass);
	    
	    $member = $this->members_model->memberByUsername($username);
	    
	    if ($member && $securePassword == $member->password){
            
            /* 
            * 세션키와 사용자 아이디 저장
            * $sessionKey : username@현재시간 md5 암호화
            * 세션만료 : 60초
            */
	        $sessionKey = $this->secure()->getSessionKey($username);
	        set_cookie('caseshop_sessionKey',$sessionKey,3600*5);
	        set_cookie('caseshop_username',$username,3600*5);
	        
	        echo '{"success": true}';
	        return;
	    }
	    
	    echo '{"success": false}';
	    return;
	    
	}
	
	/* 로그아웃 */
	function logout(){
	    delete_cookie('caseshop_username');
	    delete_cookie('caseshop_sessionKey');
	    delete_cookie('none_member_session');
	    
	    set_cookie('caseshop_none_member','1',0);
	    
	    $ref = $this->input->server('HTTP_REFERER', TRUE);
	    redirect($ref); 
	}
	
	
	function checkJumin(){
	    
	    $jumin = $this->input->post('jumin');
	    
	    $result = false;
	    $reason = '';
	    if (check_jumin($jumin)){
            
            // 생년 월일 검사
		    $j = substr($jumin, 0, 2); 
		    $age = date('Y')-($j+($j<39?2000:1900))+1; 
		
		    if ($age > 19){		    
		    	$date = new DateTime();
	            $none_member_session = $this->secure()->encrypt($date->format('Y-m-d H:i:s'));
	            set_cookie('caseshop_none_member',$none_member_session,3600*5);
	            
	            $result = true;
		    	echo '{"success": true}';
		    	return;
		    } else {
			    echo '{"success": false, "reason":"색콤달콤은 성인만 이용하실 수 있습니다. 감사합니다."}';
			    return;
		    }
	    } 
	    
        echo '{"success": false,"reason":"성인인증에 실패했습니다. 이름과 주민등록번호를 정확하게 입력하여 주십시 오"}';
	    return;
	    
	}
	
	function sessionValid($id){
        echo $this->secure()->getSessionKey($id);
    }
    
    
    
    /*
     * 포인트 환급
     */
     
    function refundPoint(){
    	$this->load->model('member_points_model');
    	
    	$rollback = false;
    	
    	$order_id = $this->input->post('orders_id');
    	$members_id = $this->input->post('members_id');
    	$used_point = $this->input->post('used_point');
    	$saved_point = 0;
    	
    	if (!$members_id && !$order_id){
	    	echo '{"success": false}';
	    	return;
    	}
    	
    	
    	$this->db->select('point');
    	$member_point = $this->db->get_where('member_points',array('ref_orders_id'=>$order_id,'reason'=>'EARN_TO_BUY'))->row();
    	if ($member_point){
	    	$saved_point = $member_point->point;	
    	}
    	
    	$this->member_points_model->members_id = $members_id;
		$this->member_points_model->ref_orders_id = $order_id;// 주문번호
		
		// 적립되었던 포인트 회수
		if ($saved_point > 0){
			$this->member_points_model->point = -$saved_point;
			$this->member_points_model->reason = 'SPEND_FOR_CANCEL';
			if (!$this->member_points_model->insert()) $rollback = true;	
		}			
		
		// 사용한 포인트 재적립
		if ($used_point > 0){
			$this->member_points_model->point = $used_point;
			$this->member_points_model->reason = 'EARN_REFUND';
			if (!$this->member_points_model->insert()) $rollback = true;	
		}
    	
    	
    	if (!$rollback){
	    	echo '{"success": true}';
	        return;
    	}
	    
	    echo '{"success": false}';
	    return;
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
		}
		
		$member = $this->db->get_where('members',array('email'=>$email))->row();
		
		$result['inputs'] = $this->input->post();
		if ($member){
			$username = $member->username;
			$username = substr_replace($username, '*',ceil(strlen($username)/2.0),1);
			$username = substr_replace($username, '*',ceil(strlen($username)/3.0),1);
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
		}
		
		$this->db->where("replace(mobile,'-','')",$mobile);
		$member = $this->db->get('members')->row();
		
		
		if ($member){
			$username = $member->username;
			$username = substr_replace($username, '*',ceil(strlen($username)/2.0),1);
			$username = substr_replace($username, '*',ceil(strlen($username)/3.0),1);
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
			$config['smtp_user'] = 'support@scomdcom.com';
			$config['smtp_pass'] = 'cult1905';
			$config['charset']   = 'utf-8';
			$config['mailtype']  = 'html';
			$config['newline']   = "\r\n"; 
			
			$this->load->library('email',$config);
			
			$this->email->from('support@scomdcom.com', '색콤달콤');
			$this->email->to($email); 
			
			$this->email->subject('색콤달콤 임시 비밀번호 입니다.');
			$this->email->message('안녕하세요. <br/>색콤달콤입니다. <br/><br/>회원님께서 임시 비밀번호 생성을 요청하셨습니다. <br/>임시 비밀번호 '.$new_password.' <br/><br/>사이트에 로그인하신 후 MY > 내 정보에서 비밀번호를 변경해주세요. <br/><br/>본인이 아닐 경우 고객센터로 연락바랍니다.<br/>고객센터 : 070-8650-2086<br/><br/> 감사합니다.<br/>좋은 하루 되세요 <br/><br/>[색콤달콤] http://www.scomdcom.com <br/>[TEL] 070-8650-2086');	
			
			
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
		
/* 		$this->db->where("replace(mobile,'-','')",$mobile);  */
		$member = $this->db->get_where('members',array('mobile'=>$dashedMobile, 'username'=>$username))->row();
		
		
		if ($member){
			$new_password = $this->createPassword();
			$msg = '[색콤달콤] 회원님의 임시 비밀번호는 '.$new_password.' 입니다. 감사합니다.';
			$mobile = $dashedMobile;
			
			$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
			// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
		    $sms['user_id'] = base64_encode("noffxp"); //SMS 아이디.
		    $sms['secure'] = base64_encode("327efd810f25ddf6948b2d890113218f") ;//인증키
		    $sms['msg'] = base64_encode(stripslashes($msg));
		
		    $sms['rphone'] = base64_encode($mobile); // ex: 010-000-0000
		    $sms['sphone1'] = base64_encode('010');
		    $sms['sphone2'] = base64_encode('1234');
		    $sms['sphone3'] = base64_encode('5678');
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
	
	
	function createPassword($length=8){
		
		$words = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$cnt = count($words);
		$result = array();
		for($i = 0; $i < $length; $i++){
			$idx = mt_rand(0, $cnt-1);
			array_push($result,$words[$idx]);
		}
		
		return implode('',$result);
	}
	
	
	function quit(){
		$member = $this->memberObj();
		
		if ($member){
			$date = new DateTime();
			$data['date_quit'] = $date->format('Y-m-d H:i:s');
			$data['username'] = '------'.$member->username;
			$data['mobile'] = '------'.$member->mobile;
			$data['email'] = '------'.$member->email;
			 
	        $this->db->trans_begin();
	        $this->db->where('id', $member->id);
			$this->db->update('members', $data); 
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            echo '{"success":false}';
	            return;
	        }
	
	        $this->db->trans_commit();	
	        
	        echo '{"success":true}';
	        return;
		}
		
        echo '{"success":false}';
        return;		
		
	}
	
}


?>