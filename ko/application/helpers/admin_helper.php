<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'helpers/secure.php');

// AdminValid 
public function adminValid(){
    
    $secure = new Secure();
    $this->load->model('members_model');
    $this->load->helper('cookie');
    
    $none_member_session = get_cookie('casebuy_none_member');
    
    $username = get_cookie('casebuy_username');
    $sessionKey = get_cookie('casebuy_sessionKey');
    
    // 세션이 유효할 경우
    if ($none_member_session != null || ($username && $sessionKey && $secure->sessionValid($username,$sessionKey))){
        $member = $this->members_model->memeberByUsername($username);
        if ($member && $member->member_grades_id == 1){
            return true;
        }
    }
    
    return false;
}


?>

