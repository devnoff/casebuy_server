<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallpapers_model extends CI_Model {

    var $id = '';
    var $title = '';
    var $resource_host = RESOURCEHOST;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select List
    function wallpapers($offset=0,$limit=null){

        if ($limit){
            $this->db->limit($limit,$offset);
        }

        $this->db->order_by('id','asc');
        $query = $this->db->get('caseshop.wallpapers');
        
        return $query->result();
    }
    
    // Select One Item
    function wallpaperById($id){
        if ($id == null)
            return false;
        
        $this->db->select("SUBSTRING(original_4_path,13) original_4_filename,SUBSTRING(original_5_path,13) original_5_filename,SUBSTRING(preview_4_path,13) preview_4_filename,SUBSTRING(preview_5_path,13) preview_5_filename, '".RESOURCEHOST."/wallpapers/' as resource_dir_path",false);
        $query = $this->db->get_where('wallpapers',array('id'=>$id));
        return $query->row();
    }

    // 상품 배경화면
    function wallpapersByProduct($products_id=null){
        if (!$products_id)
            return false;

        $this->db->select('*');
        $this->db->from('case_wall');
        $this->db->join('caseshop.wallpapers','wallpapers.id=case_wall.wallpapers_id','left');
        $this->db->where('products_id',$products_id);
        $query = $this->db->get();

        $result = $query->result();
        if (gettype($result)=='array'){
            return $result;
        }
    }
    
    // Insert
    function insert($data){

        if (count($data) < 5){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->insert('wallpapers', $data);

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
		$this->db->update('wallpapers', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;

    }
    
    // Delete
    function delete($id){
        $this->db->trans_begin();
        $this->db->delete('wallpapers', array('id'=>$id));
        $this->db->delete('case_wall', array('wallpapers_id'=>$id));
        $this->db->delete('caseshop_ko.case_wall', array('wallpapers_id'=>$id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }


      /*
    * 업로드 함수
    */
    function uploadPhoto($fieldName){
        $config['upload_path'] = '../wallpapers';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 

        if (!$this->upload->do_upload($fieldName))
        {   


            return false;
        }   
        else
        {
            return $this->upload->data();
        }
    }

    /*
     * 관련 상품 추가
     */
    function addRelatedProduct($data){

        $this->db->trans_begin();
        $this->db->insert('case_wall', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /*
     * 관련 상품 삭제
     */
    function removeRelatedProduct($data){
        
        $this->db->trans_begin();
        $this->db->delete('case_wall', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }


    /*
     * 관련 상품
     */

    function relatedProducts($wallpapers_id){

        $this->db->select('*');
        $this->db->from('case_wall');
        $this->db->join('products','products.id=case_wall.products_id','left');
        $this->db->where('wallpapers_id',$wallpapers_id);
        $query = $this->db->get();

        $result = $query->result();
        if (gettype($result)=='array'){
            return $result;
        }

        return false;
    }

    function relatedProductsSimple($wallpapers_id){

        $this->db->select("products.id products_id, concat('$this->resource_host', ifnull(app_detail_img, '/en/img/empty.png')) thumb",false);
        $this->db->from('case_wall');
        $this->db->join('products','products.id=case_wall.products_id','right');
        $this->db->where('wallpapers_id',$wallpapers_id);
        $query = $this->db->get();

        $result = $query->result();
        if (gettype($result)=='array'){
            return $result;
        }

        return false;
    }

}

?>