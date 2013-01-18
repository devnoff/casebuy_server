<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inqueries_model extends CI_Model {

    var $id = null;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    
    function listMember($members_id=null,$offset=0,$limit=20){
    
	    if (!$members_id){
		    return false;
	    }
	    
	    $this->db->select("id, inqueries_id, members_id, uuid, title, date_format(date_write,'%Y.%m.%d') date_write,(select count(*) from inqueries child where child.inqueries_id = inqueries.id) reply, (select nickname from members where members.id=inqueries.members_id) nickname",false);
	    $this->db->limit($limit,$offset);
	    $this->db->where('inqueries_id is null');
	    $this->db->order_by('id','desc');
	    $query = $this->db->get_where('inqueries',array('members_id'=>$members_id));
	    
	    return $query->result();
	    
    }

    function questions($limit=20,$offset=0, $state=null){

    	if ($state=='not_replied'){
            $this->db->where('(select count(*) from inqueries child where child.inqueries_id = inqueries.id) <',1);
        }
    	$this->db->select("id, inqueries_id, members_id, uuid, title, date_format(date_write,'%Y.%m.%d') date_write,(select count(*) from inqueries child where child.inqueries_id = inqueries.id) reply, (select nickname from members where members.id=inqueries.members_id) nickname",false);
	    $this->db->limit($limit,$offset);
	    $this->db->where('inqueries_id is null');
	    $this->db->order_by('id','desc');
	    $query = $this->db->get('inqueries');
	    
	    return $query->result();
    }

    function countQuestions($state){
    	if ($state=='not_replied'){
            $this->db->where('(select count(*) from inqueries child where child.inqueries_id = inqueries.id) <',1);
        }
    	$this->db->select("count(*) cnt",false);
	    $this->db->where('inqueries_id is null');
	    $query = $this->db->get('inqueries');

	    return $query->row()->cnt;
    }
    
    function detail($id=null){
	    
	    if (!$id){
		    return false;
	    }
	    
	    $this->db->select("id, inqueries_id,(select nickname from members where members.id=inqueries.members_id) nickname, title, content, date_format(date_write,'%Y.%m.%d') date_write",false);
	    $query = $this->db->get_where('inqueries', array('id'=>$id));
	    
	    return $query->row();
    }
    
    function answers($inqueries_id=null){
    
	    if (!$inqueries_id){
		    return false;
	    }
	    
	    $this->db->select("id, inqueries_id,ifnull((select nickname from members where members.id=inqueries.members_id),'CASEBUY') as nickname, ifnull(title,'') title, content, date_format(date_write,'%Y.%m.%d') date_write",false);
	    $query = $this->db->get_where('inqueries', array('inqueries_id'=>$inqueries_id));
	    
	    return $query->result();
    }

    function answer($inqueries_id=null){
    
	    if (!$inqueries_id){
		    return false;
	    }
	    
	    $this->db->select("id, inqueries_id,(select nickname from members where members.id=inqueries.members_id) nickname, title, content, date_format(date_write,'%Y.%m.%d') date_write",false);
	    $query = $this->db->get_where('inqueries', array('inqueries_id'=>$inqueries_id));
	    
	    return $query->row();
    }


    function question($id=null){
	    
	    if (!$id){
		    return false;
	    }
	    
	    $this->db->select("id, inqueries_id,(select nickname from members where members.id=inqueries.members_id) nickname, title, content, date_format(date_write,'%Y.%m.%d') date_write",false);
	    $query = $this->db->get_where('inqueries', array('id'=>$id));
	    
	    return $query->row();
    }
    
    function insert($data=null){
	    if (gettype($data) != 'array' || count($data) < 1){
		    return false;
	    }
	    
	    if (empty($data['members_id']))
	    	return false;
	    
	    if (empty($data['title']))
	    	return false;
	    	
	    if (empty($data['content']))
	    	return false;
	    
	    $this->db->trans_begin();

        $this->db->insert('inqueries', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
	    
    }


    // Add Answer
    function addAnswer($target_id, $content){
        if (!$target_id || !$content)
            return false;
                
        $this->db->trans_begin();
        
          $data = array(
              'inqueries_id'=>$target_id,
              'content'=>$content
              );
          
          $this->db->set('date_write','NOW()',FALSE);
          
          $this->db->insert('inqueries',$data);
          $insert_id = $this->db->insert_id();
          
          if ($this->db->trans_status() === FALSE)
          {
              $this->db->trans_rollback();
              return false;
          }
          $this->db->trans_commit();
          return $insert_id;
    }
    
    // Update Answer
    function updateAnswer($id, $content){
        if (!$id || !$content)
            return false;
            
        $data = array('content'=>$content);
            
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('inqueries',$data);
        
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