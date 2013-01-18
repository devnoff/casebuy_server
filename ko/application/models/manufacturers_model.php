<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manufacturers_model extends CI_Model {

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
    function manufacturers(){
        $query = $this->db->get('manufacturers');
        
        return $query->result();
    }
    
    // Select One Item
    function manufacturerById($id){
        if ($id == null)
            return false;
        
        $query = $this->db->get_where('manufacturers',array('id'=>$id));
        return $query->row();
    }
    
    // Insert
    function insert(){
        
        $data = array ('title'=>$this->title);
        
        $this->db->trans_begin();
        $this->db->insert('manufacturers', $data);

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
		$this->db->update('manufacturers', $data); 

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
        $this->db->delete('manufacturers', array('id'=>$this->id));

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