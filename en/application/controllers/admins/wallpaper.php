<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class Wallpaper extends Action {



	function __construct()
	{
		parent::__construct();
		
        $this->load->model('wallpapers_model');
    
        
	}
	
    
    /*
    * 배경화면 추가
    *
    */
    function addWallpapers($type=null){
        $data = $this->input->post();

        $result['success'] = false;
        if ($this->wallpapers_model->insert($data)){
            $result['success'] = true;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }
    
    
    


    /*
     * 배경화면 이미지 업로드
     *
     */
    function uploadImage(){


        $result['success'] = false;

        $fileinfo = $this->wallpapers_model->uploadPhoto('wallpaper');


        if ($fileinfo != null ){
            $result['success'] = true;
            $result['file_path'] = '/wallpapers/'.$fileinfo['file_name'];
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }
    
    

    /*
     * 카테고리 이미지 삭제
     */
    function deleteImage($path=null){
        $result['success'] = false;

        if (!$path){
            $path = $this->input->post('filepath');    
            $path = '..'.$path;
        }
        
        if (is_readable($path)){
            if (unlink($path)){
                $result['success'] = true;
            }
        }


        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 

    }

    private function deleteWallpaperImage($path=null){
        
        if (!file_exists($path)){
            return true;
        }

        if (is_readable($path)){
            if (unlink($path)){
                return true;
            }
        }

        return false;

    }



    /*
     * 관련 상품 추가
     */
    function addProduct(){
        $products_id = $this->input->post('products_id');
        $wallpapers_id = $this->input->post('wallpapers_id');


        $result['success'] = false;
        if (!$wallpapers_id || ! $products_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result)); 
        }

        $this->db->where(array('products_id'=>$products_id, 'wallpapers_id'=>$wallpapers_id));
        $exist = $this->db->count_all_results('case_wall') > 0;

        if (!$exist){
            $data['products_id'] = $products_id;
            $data['wallpapers_id'] = $wallpapers_id;
            if ($this->wallpapers_model->addRelatedProduct($data)){
                $result['success'] = true;
            }
        } else {
            $result['success'] = true;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 

    }


    /*
     * 관련 상품 삭제
     */
    function removeProduct(){
        $products_id = $this->input->post('products_id');
        $wallpapers_id = $this->input->post('wallpapers_id');


        $result['success'] = false;
        if (!$wallpapers_id || ! $products_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result)); 
        }

        $this->db->where(array('products_id'=>$products_id, 'wallpapers_id'=>$wallpapers_id));
        $exist = $this->db->count_all_results('case_wall') > 0;

        if ($exist){
            $data['products_id'] = $products_id;
            $data['wallpapers_id'] = $wallpapers_id;
            if ($this->wallpapers_model->removeRelatedProduct($data)){
                $result['success'] = true;
            }
        } else {
            $result['success'] = true;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 
    }


    /*
     * 관련 상품
     */
    function products(){
        $wallpapers_id = $this->input->get('wallpapers_id');

        $result['success'] = false;
        if (!$wallpapers_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result)); 
        }

        //$products = $this->wallpapers_model->relatedProducts($wallpapers_id);
        $query = $this->db->get_where('case_wall',array('wallpapers_id'=>$wallpapers_id));
        $products = $query->result();

        if (gettype($products)=='array'){
            $result['success'] = true;
            $result['related_products'] = $products;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 

    }


    /*
     * 배경화면 삭제
     */
    function removeWallpaper(){
        $wallpapers_id = $this->input->post('wallpapers_id');

        $result['success'] = false;
        if (!$wallpapers_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result)); 
        }

        $query = $this->db->get_where('caseshop.wallpapers',array('id'=>$wallpapers_id));
        $wallpaper = $query->row();

        if ($wallpaper){
            $this->deleteWallpaperImage("..".$wallpaper->thumb_path);
            $this->deleteWallpaperImage("..".$wallpaper->original_4_path);
            $this->deleteWallpaperImage("..".$wallpaper->original_5_path);
            $this->deleteWallpaperImage("..".$wallpaper->preview_4_path);
            $this->deleteWallpaperImage("..".$wallpaper->preview_5_path);
                                
            if ($this->wallpapers_model->delete($wallpapers_id)){
                $result['success'] = true;   
            } else {
                $result['reason'] = "Cannot delete from db";
            }

        }


        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 


    }

}











?>