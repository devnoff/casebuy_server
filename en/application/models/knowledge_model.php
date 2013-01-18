<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Knowledge_model extends CI_Model {

    var $id = null;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    
    function itemList($offset=0,$limit=20){
	    
	    $this->db->limit($limit,$offset);
	    $this->db->order_by('id','desc');
	    $query = $this->db->get_where('knowledge');
	    
	    return $query->result();
    }
    
    

    
    function insert($data=null){
	    if (gettype($data) != 'array' || count($data) < 1){
		    return false;
	    }
	    
	    if (empty($data['title']))
	    	return false;
	    
	    if (empty($data['desc']))
	    	return false;
	    	
	    if (empty($data['link']))
	    	return false;
	    
	    $this->db->trans_begin();

        $this->db->insert('knowledge', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
	    
    }

    function update($data=null){
	    if (gettype($data) != 'array' || count($data) < 1){
		    return false;
	    }
	    
	    $this->db->trans_begin();

	    $this->db->where('id',$data['id']);
        $this->db->update('knowledge', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
	    
    }

    function delete($id=null){
	    if (!$id){
		    return false;
	    }
	    
	    $this->db->trans_begin();

        $this->db->delete('knowledge', array('id'=>$id));

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