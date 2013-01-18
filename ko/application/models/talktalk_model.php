<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Talktalk_model extends CI_Model {

    var $id = '';
    var $title = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select List
    function talktalk($lastId=999999, $limit=10){
        $this->db->select("id,members_id,nickname,title,date_format(date_write,'%Y.%m.%d') date_write, (select count(*) from talktalk t where parent = talktalk.id) reply", false);
        $this->db->order_by('id', 'desc');
        $this->db->where('parent is null');
        $this->db->where(array('id <'=>$lastId));
        $this->db->limit($limit);
        $query = $this->db->get('talktalk');
        
        return $query->result();
    }

    function restTalk($lastId){

        $this->db->where(array('id <'=>$lastId));
        $cnt = $this->db->count_all_results('talktalk');

        return $cnt > 0 ? true : false;
    }
    
    // Select One Item
    function talkById($id){
        if ($id == null)
            return false;
        
        $this->db->select("id,members_id,nickname,title,content,date_format(date_write,'%Y.%m.%d') date_write", false);
        $query = $this->db->get_where('talktalk',array('id'=>$id));
        return $query->row();
    }

    // Replies
    function repliesById($id){
        if ($id == null)
            return false;
        
        $this->db->select("id,members_id,nickname,title,content,date_format(date_write,'%Y.%m.%d') date_write", false);
        $this->db->order_by('orderby','asc');
        $query = $this->db->get_where('talktalk',array('parent'=>$id));
        return $query->result();   
    }
    
    // Insert
    function insert($data){
        
        $this->db->trans_begin();

        $this->db->set('date_write', 'NOW()', FALSE);

        $this->db->insert('talktalk', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // Update
    function update(){
        $data = null;
        if ($this->title){
            $data['title'] =  $this->title;
        }
        
        if (!$data){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->where('id', $this->id);
		$this->db->update('talktalk', $data); 

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
        $this->db->delete('talktalk', array('id'=>$this->id));

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