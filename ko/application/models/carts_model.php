<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carts_model extends CI_Model {

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
    function carts(){
        $query = $this->db->get('carts');
        
        return $query->result();
    }
    
    // Select One Item
    function cartsById($id){
        if ($id == null)
            return false;
        
        $query = $this->db->get_where('carts',array('id'=>$id));
        return $query->row();
    }
    
    // Insert
    function insert(){
        
        $data = array ('title'=>$this->title);
        
        $this->db->trans_begin();
        $this->db->insert('origins', $data);

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
		$this->db->update('carts', $data); 

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
    
    	if (!$this->id)
    		return false;
    
        $this->db->trans_begin();
        $this->db->delete('carts', array('id'=>$this->id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    function deleteProduct($products_id){
	    if (!$products_id)
    		return false;
    
        $this->db->trans_begin();
        $this->db->delete('carts', array('products_id'=>$products_id));

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