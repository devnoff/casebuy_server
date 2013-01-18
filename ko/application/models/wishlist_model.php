<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wishlist_model extends CI_Model {

    var $id = '';
    var $members_id = '';
    var $uuid = null;
    var $products_id = '';
    var $date_added = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select List
    function wishlistByMember($id=null){
    	if (!$id)
    		return false;
    	
    	$sql = "select w.products_id products_id ,categories_id, sub_category_id, title, sub_title, web_list_img, app_list_img, format(sales_price,0) sales_price, ifnull(extra_info_value1,'') extra_info_value1 from wishlist w left join products p on w.products_id=p.id where w.members_id=".$id." order by date_added desc";
    	
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    // Insert
    function insert(){
        
        $data = array (
        	'members_id'=>$this->members_id,
        	'products_id'=>$this->products_id
        	);
        
        $this->db->trans_begin();
        $this->db->insert('wishlist', $data);

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
    	/*
        $data = null;
        if ($this->title){
            $data['title'] =  $this->title;
        }
        
        if (!$data){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->where('id', $this->id);
		$this->db->update('brands', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
		*/
    }
    
    // Delete
    function delete(){
    
    	 $data = array (
        	'members_id'=>$this->members_id,
        	'products_id'=>$this->products_id
        	);
    	
        $this->db->trans_begin();
        $this->db->delete('wishlist', $data);

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }



    /* ------------------------ for App ------------------------- */

    // Select List
    function wishlistByUuid($uuid=null){
        if (!$uuid)
            return false;
        
        $sql = "select w.products_id products_id , title, sub_title, concat('http://casebuy.me', ifnull(app_list_img, '/ko/img/empty.png')) app_list_img, format(sales_price,0) sales_price,format(regular_price,0) regular_price, ifnull(extra_info_value1,'') extra_info_value1, sales_state from wishlist w left join products p on w.products_id=p.id where w.uuid='".$uuid."' order by date_added desc";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }


    function wishlistByMemberApp($id=null){
        if (!$id)
            return false;
        
        $sql = "select w.products_id products_id , title, sub_title, concat('http://casebuy.me', ifnull(app_list_img, '/ko/img/empty.png')) app_list_img, format(sales_price,0) sales_price,format(regular_price,0) regular_price, ifnull(extra_info_value1,'') extra_info_value1, sales_state from wishlist w left join products p on w.products_id=p.id where w.members_id=".$id." order by date_added desc";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    // Insert for app
    function insertForApp(){
        $data = array (
            'uuid'=>$this->uuid,
            'products_id'=>$this->products_id
            );
        
        $this->db->trans_begin();
        $this->db->insert('wishlist', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    // Delete
    function deleteForApp(){
    
         $data = array (
            'uuid'=>$this->uuid,
            'products_id'=>$this->products_id
            );
        
        $this->db->trans_begin();
        $this->db->delete('wishlist', $data);

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