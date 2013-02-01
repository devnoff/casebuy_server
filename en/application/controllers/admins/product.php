<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class Product extends Action {


	function __construct()
	{
		parent::__construct();
		
        $this->load->model('products_model');
        $this->load->model('product_categories_model');
        
        
	}
	
    /* 
    * 카테고리 
    */
     
    /* 전체 카테고리 목록 */
    function allCategories(){
        $result = $this->product_categories_model->all_categories();
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }
    
    /* 
    * 1차 카테고리 목록 
    */
    function categoryLv1(){
        $result = $this->product_categories_model->categories();
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
        
    }
    
    /* 
    * 2차 카테고리 목록 
    * params: parent_id
    */
    function categoryLv2(){
        $parent_id =  $this->input->get('parent_id');
        $result = $this->product_categories_model->categoriesByParentId($parent_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
        
    }
    
    /*
    * 파트너 목록
    */
    function partners(){
        $query = $this->db->get('partners');
     
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($query->result()));
    }
    
    
    
    /* 
    * 카테고리 추가 
    * params: category_name
    */
    function addCategory(){
        $c = $this->product_categories_model;
        $c->category_name = $this->input->post('category_name');
        
        $this->db->select_max('family', 'max');
        $max_family = $this->db->get('product_categories');
        
        $max_family = $max_family == null ? 0 : $max_family;
        
        $c->family = $max_family->row()->max + 1;
        $c->orderby = 0;
        $c->step = 0;
        
        $result['success'] = false;
        $result['max_family'] = $max_family->row()->max;
        if ($c->insert()){
            $result['success'] = true;
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
        
    }
    
    /* 
    * 서브 카테고리 추가 
    * params: parent_id, category_name, family
    */
    
    function addSubCategory(){
        $c = $this->product_categories_model;
        
        $c->parent_id = $this->input->post('parent_id');
        $c->category_name = $this->input->post('category_name');
        $c->family = $this->input->post('family');
        
        $this->db->select_max('orderby','max');
        $max_order = $this->db->get_where('product_categories',array('family'=>$c->family));
        
        $c->orderby = $max_order->row()->max + 1;
        $c->step = 1;
        
        $result['success'] = false;
        if ($c->insert()){
            $result['success'] = true;
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }
    
    /*
    * 카테고리 변경
    * params: id,category_name, orderby
    */
    function updateCategory(){
        $c = $this->product_categories_model;
        $c->id = $this->input->post('id');
        $c->category_name = $this->input->post('category_name');
        $c->hidden = $this->input->post('hidden');
        $c->thumb = $this->input->post('thumb');
        
        $result['success'] = false;
        if ($c->update()){
            $result['success'] = true;
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
        
    }
    
    
    /*
    * 생산자 옵션
    *
    */
    function producerOptions(){
        $m = $this->db->get('manufacturers');
        $b = $this->db->get('brands');
        $o = $this->db->get('origins');
        
        $result['manufacturers'] = $m->result();
        $result['brands'] = $b->result();
        $result['origins'] = $o->result();
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
        
        
    }
    
    /*
    * 생산자 추가
    *
    */
    function addProducer($type){
        $title = $this->input->post('title');
        
        $this->db->trans_begin();
        $this->db->insert('caseshop_ko.'.$type, array('title'=>$title));
        $this->db->insert('caseshop.'.$type, array('title'=>$title));

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo '{"success": false}';
            return;
        }

        $this->db->trans_commit();
        echo '{"success": true, "type": "'.$type.'"}';
        return;
    }
    
    
    
    
    
    
    /*
    * 상품 등록
    *
    */
    function addProduct(){
        
        $data = $this->input->post();
        $result = '';
        if ($this->products_model->insert($data)){
            $result = "<script>alert('성공');</script>";
        } else {
            $result = "<script>alert('실패');</script>";
        }
        
        echo $result."<script>location.href='".site_url('admin/product/add')."';</script>";
        
        
    }
    
    
    /*
    * 상품 업데이트 
    * parmas: all
    */
    function updateProduct($ajax=false){
        $data = $this->input->post();
        $redirect = null;
        if (!empty($data['redirect'])){
            $redirect = $data['redirect'];
        }
        
        unset($data['redirect']);
        if ($ajax){
            $result = '{"success":false}';
            if ($this->products_model->update($data)){
                $result = '{"success":true}';
            } 
            echo $result;
        } else {
            $result = "<script>alert('실패');</script>";
            if ($this->products_model->update($data)){
                $result = "<script>alert('성공');</script>";
            } 

            echo $result."<script>location.href='".$redirect."'</script>";    
        }
        
        
    }


    /*
     * 상품 이미지 업로드
     *
     */
    function uploadProductImages(){


        $result['success'] = false;

        $filenames = $this->products_model->uploadPhotos('product_photo');


        if ($filenames != null && count($filenames) > 0){
            $result['success'] = true;
            $result['file_info'] = $filenames;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }
    
    
    /*
     * 카테고리 이미지 업로드
     */
    function uploadCategoryImage(){
        $result['success'] = false;

        $filename = $this->product_categories_model->uploadPhoto('thumb');

        if ($filename != null){
            $result['success'] = true;
            $result['filepath'] = 'img/category_photo/'.$filename;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));   
    }


    /*
     * 카테고리 이미지 삭제
     */
    function deleteCategoryPhoto(){
        $result['success'] = false;

        $c_id = $this->input->post('id');
        $query = $this->db->get_where('product_categories',array('id'=>$c_id));
        $category = $query->row();

        if ($category){
            $thumbpath = $category->thumb;
            if (is_readable($thumbpath)){
                unlink($thumbpath);
            }

            $c = $this->product_categories_model;
            $c->id = $c_id;
            $c->thumb = ' ';

            if ($c->update()){
                $result['success'] = true;
            }
        }
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 

    }




    /*
     * 카테고리 출력순서
     */
    function updateCategoryOrder(){
        

        $result['success'] = true;

        $parent_ids = $this->input->post('parent_ids');
        $parent_orders = $this->input->post('parent_orders');

        
        for($i = 0; $i < count($parent_ids);$i++){
            $c = $this->product_categories_model;
            $c->id = $parent_ids[$i];
            $data['forder'] = $parent_orders[$i];
            if (!$c->update($data)){
                $result['success'] = false;
            }
        }

        $child_ids = $this->input->post('child_ids');
        $child_orders = $this->input->post('child_orders');         

        
        for($j = 0; $j < count($child_ids);$j++){
            $s = $this->product_categories_model;
            $s->forder = 0;
            $s->id = $child_ids[$j];
            $data['orderby'] = $child_orders[$j];
            $data['forder'] = 0;
            if (!$s->update($data)){
                // var_dump($c);
                $result['success'] = false;
            }
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 
    }



    /*
     * 추가 이미지 삭제
     */
    function deleteExtraImage(){
        $result['success'] = false;

        $path = $this->input->post('filepath');
        
        $path = str_replace(base_url(),BASEPATH.'../', $path);

        if (is_readable($path)){
            if (unlink($path)){
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
     * 관련 배경 화면
     */

    function wallpapers(){

        $result['success'] = false;

        $products_id = $this->input->get('products_id');

        if (!$products_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result)); 
        }

        $this->load->model('wallpapers_model');

        $wallpapers = $this->wallpapers_model->wallpapersByProduct($products_id);

        if ($wallpapers){
            $result['success'] = true;
            $result['wallpapers'] = $wallpapers;
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result)); 

    }


    /*
     * 관련 배경 추가
     */
    function addWallpaper(){
        $this->load->model('wallpapers_model');

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
     * 관련 배경 삭제
     */
    function removeWallpaper(){
        $this->load->model('wallpapers_model');

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
     * 판매 종료 상품 일괄 삭제
     */
    function deleteEndProducts(){

        $result['success'] = false;

        $this->db->trans_begin();
        $this->db->where('sales_state','END');
        $this->db->delete('products');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result));
        }

        $this->db->trans_commit();
        $result['success'] = true;
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }


    /*
     * 상품 정렬 위로
     */
    function raiseOrder(){
        $products_id = $this->input->post('products_id');

        $result['success'] = false;
        if (!$products_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result));
        }

        // 상품 정보 가져오기
        $product = $this->products_model->productById($products_id);

        if ($product){
            $order = $product->category_order;

            if ($order !== null){
                $sc_id = $product->categories_id;

                $this->db->where(array('categories_id'=>$sc_id,'category_order <'=>$order));
                $this->db->limit(1);
                $this->db->order_by('category_order','desc');
                $query = $this->db->get('products');
                $smaller = $query->row();

                if ($smaller){
                    $small_order = $smaller->category_order;
                    $small_id = $smaller->id;

                    if ($this->products_model->update(array('id'=>$small_id,'category_order'=>$order))){
                        if ($this->products_model->update(array('id'=>$products_id,'category_order'=>$small_order))){
                            $result['success'] = true;
                        } else {
                            $this->products_model->update(array('id'=>$small_id,'category_order'=>$small_order));
                        }
                    } else{
                        $result['reason'] = "Can't Update";
                    }
                } else {
                    $result['reason'] = "End Order";
                }
            } else {
                $result['reason'] = "No Category Order";
            }
        } else {
            $result['reason'] = "No Product";
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }

    /*
     * 상품 정렬 아래로
     */
    function reduceOrder(){
        $products_id = $this->input->post('products_id');

        $result['success'] = false;
        if (!$products_id){
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($result));
        }

        // 상품 정보 가져오기
        $product = $this->products_model->productById($products_id);

        if ($product){
            $order = $product->category_order;
            if ($order !== null){
                $sc_id = $product->categories_id;

                $this->db->where(array('categories_id'=>$sc_id,'category_order >'=>$order));
                $this->db->limit(1);
                $this->db->order_by('category_order','asc');
                $query = $this->db->get('products');
                $bigger = $query->row();
                if ($bigger){
                    $bigger_order = $bigger->category_order;
                    $bigger_id = $bigger->id;

                    if ($this->products_model->update(array('id'=>$bigger_id,'category_order'=>$order))){
                        if ($this->products_model->update(array('id'=>$products_id,'category_order'=>$bigger_order))){
                            $result['success'] = true;
                        } else {
                            $this->products_model->update(array('id'=>$bigger_id,'category_order'=>$bigger_order));
                        }
                    } else{
                        $result['reason'] = "Can't Update";
                    }
                } else {
                    $result['reason'] = "End Order ";
                    $result['product'] = $product;
                }
            } else {
                $result['reason'] = "No Category Order";
            }
        } else {
            $result['reason'] = "No Product";
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));



    }




    /*
     * 상품 옵션 추가
     */
    function addProductOption(){
        $result['success'] = false;

        $products_id = $this->input->post('products_id');
        $option_name = $this->input->post('option_name');
        $data = array(
            "products_id"=>$products_id,
            "option_name"=>$option_name
            );

        $this->db->trans_begin();

        $this->db->insert('product_options',$data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else {
            $result['success'] = true;
            $this->db->trans_commit();
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }

    /*
     * 상품 옵션 삭제
     */
    function removeProductOption(){
        $result['success'] = false;

        $option_id = $this->input->post('id');

        $this->db->trans_begin();

        $this->db->where('id',$option_id);
        $this->db->delete('product_options');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else {
            $result['success'] = true;
            $this->db->trans_commit();
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
    }

}











?>