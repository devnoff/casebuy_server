<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_categories_model extends CI_Model {

    var $id = null;
    var $parent_id = null;
    var $category_name = null;
    var $family = null;
    var $orderby = null;
    var $step = null;
    var $hidden = null;
    var $thumb = null;
    var $forder = null;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select All Categories
    function all_categories(){
        
        $sql = "select * from product_categories order by family,orderby,step";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select Parent Categories
    function categories(){
        $this->db->order_by('forder','asc');
        $query = $this->db->get_where('product_categories', array('parent_id'=>null));
        return $query->result();
    }
    
    // Select Child Categories by Parent Id
    function categoriesByParentId($parent_id){
        $this->db->order_by('orderby','asc');
        $query = $this->db->get_where('product_categories', array('parent_id'=>$parent_id));
        return $query->result();
    }
    
    // Select Child Categories by Parent Id
    function categoriesByParentIdSimple($parent_id){
        $this->db->select('id,parent_id,category_name');
        $this->db->order_by('orderby','asc');
        $this->db->not_like('hidden','YES');
        $query = $this->db->get_where('product_categories', array('parent_id'=>$parent_id));
        return $query->result();
    }

    // Category Product Counts
    function productCounts(){
        $this->db->select('product_categories.id id, count(products.id) products_count');
        $this->db->from('(select * from product_categories where parent_id is null) product_categories');
        $this->db->join('products','product_categories.id=products.categories_id','left');
        $this->db->group_by('product_categories.id');
        $this->db->order_by('forder');
        $query = $this->db->get();
        return $query->result();
    }

    
    // Tags in Category
    function categoryTags($categories_id){
        $base_url = base_url();
        $this->db->select("id categories_id, upper(category_name) title, concat('$base_url',ifnull(thumb, 'img/empty.png')) image",false);
        $this->db->order_by('orderby','asc');
        $query = $this->db->get_where('product_categories',array('parent_id'=>$categories_id));
        return $query->result();
    }
    

    // Insert Category
    function insert(){
        $data = array();
        
        if ($this->parent_id){
            $data['parent_id'] =  $this->parent_id;
        }
        if ($this->category_name){
            $data['category_name'] =  $this->category_name;
        }
        if ($this->family){
            $data['family'] =  $this->family;
        }
        if ($this->orderby){
            $data['orderby'] =  $this->orderby;
        }
        if ($this->step){
            $data['step'] =  $this->step;
        }
        $this->db->trans_begin();
        $this->db->insert('product_categories', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // Update Category
    function update($data=null){
        if ($data == null)
            $data = array();

        if ($this->parent_id){
            $data['parent_id'] =  $this->parent_id;
        }
        if ($this->category_name){
            $data['category_name'] =  $this->category_name;
        }
        if ($this->family){
            $data['family'] =  $this->family;
        }
        if ($this->orderby){
            $data['orderby'] =  intval($this->orderby);
        }
        if ($this->forder){
            $data['forder'] =  intval($this->forder);
        }
        if ($this->step){
            $data['step'] =  $this->step;
        }
        
        if ($this->hidden){
            $data['hidden'] =  $this->hidden;
        }

        if ($this->thumb){
            $data['thumb'] =  $this->thumb;
        }

        $this->reset();

        if (!$data || count($data) < 1){
            return false;
        }
        


        $this->db->trans_begin();
        $this->db->where('id', $this->id);
		$this->db->update('product_categories', $data); 

        

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;

    }
    
    // Delete Category
    function delete(){
        $this->db->trans_begin();
        $this->db->delete('product_categories', array('id'=>$this->id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }



    function uploadPhoto($fieldName){
        $config['upload_path'] = 'img/category_photo';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = FALSE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 

        if (!$this->upload->do_upload($fieldName))
        {   

//          echo $this->upload->display_errors();
//          var_dump(is_dir($config['upload_path']));

            return false;
        }   
        else
        {
            $info = $this->upload->data();
            return $info['file_name'];
        }
    }

    function reset(){
        $id = null;
        $parent_id = null;
        $category_name = null;
        $family = null;
        $orderby = null;
        $step = null;
        $hidden = null;
        $thumb = null;
        $forder = null;
    }
}

?>