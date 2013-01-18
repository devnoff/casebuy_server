<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');

class Members_model extends CI_Model {

    var $id = '';
    var $member_grades_id = '';
    var $username = '';
    var $nickname = '';
    var $password = '';
    var $telephone = '';
    var $mobile = '';
    var $email = '';
    var $dob = '';
    var $calendar = '';
    var $date_join = '';
    var $point = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select List
    function members(){
    	$limit = $this->input->get('limit');
    	$lastSeq = $this->input->get('last_id');
    	
        $query = $this->db->get('members');
        
        return $query->result();
    }

    function memberList($offset=0,$limit=10){

        $query = $this->db->get('members',$limit,$offset);

        return $query->result();   
    }
    
    // Select One Item by member_id
    function memberById($id){
        if ($id == null)
            return false;
        
        $query = $this->db->get_where('members',array('id'=>$id));
        return $query->row();
    }
    
    // Select One Item by username
    function memberByUsername($username){
        if ($username == null)
            return false;
        
        $query = $this->db->get_where('members',array('username'=>$username));
        return $query->row();
    }
    
    // Insert
    function insert(){
        
        $data = array (
            'member_grades_id'=>$this->member_grades_id,
            'username'=>$this->username,
            'nickname'=>$this->nickname,
            'password'=>$this->password,
            'telephone'=>$this->telephone,
            'mobile'=>$this->mobile,
            'email'=>$this->email,
            'dob'=>$this->dob,
            'calendar'=>$this->calendar,
            'point'=>$this->point
            );
        
        $this->db->set('date_join', 'NOW()', FALSE);

        $this->db->trans_begin();
        $this->db->insert('members', $data);
        $inserted = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $inserted;
    }
    
    // Update
    function update($data){
        if (!$data){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->where('id', $this->id);
		$this->db->update('members', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;

    }
        
    // Delete
    function delete(){
        $this->db->trans_begin();
        $this->db->delete('members', array('id'=>$this->id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // AdminValid 
    public function adminValid(){
        
        $secure = new Secure();
        
        $none_member_session = get_cookie('caseshop_none_member');
	    
	    $username = get_cookie('casebuy_username');
	    $sessionKey = get_cookie('casebuy_sessionKey');
	    
	    // 세션이 유효할 경우
	    if ($none_member_session != null || ($username && $sessionKey && $secure->sessionValid($username,$sessionKey))){
            $member = $this->memberByUsername($username);
            if ($member && $member->member_grades_id == 1){
                return true;
            }
	    }
	    
	    return false;
    }
    
    
    /* 포인트 관련 함수 */
    function addPoint($members_id, $point){
    	
	    $data = array(
		    'members_id' => $members_id,
		    'point' => $point
		);
		
		$this->db->set('date_added', 'NOW()', FALSE);
		
		
		$this->db->trans_begin();
		
		$this->db->insert('member_points', $data);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return true;
		
    }
    
}

?>