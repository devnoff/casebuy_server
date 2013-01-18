<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products_model extends CI_Model {

    var $id = null;
    var $categories_id = null;
    var $sub_categories_id = null;
    var $brands_id = null;
    var $manufacturers_id = null;
    var $origins_id = null;
    var $title = null;
    var $sub_title = null;
    var $purchase_price = 0;
    var $regular_price = 0;
    var $dc_rate = 0;
    var $fixed_dc_amount = null;
    var $point_rate = 0;
    var $fixed_point = null;
    var $sales_price = 0;
    var $date_release = null;
    var $tags = null;


    var $resource_host = RESOURCEHOST;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Load Database
        $this->load->database();
    }
    
    
    function sale_products_simple($categories_id=null,$offset=0,$limit=null){

        $this->db->select("id,upper(title) title, sales_price,concat('$this->resource_host',ifnull(app_detail_img, '/ko/img/empty.png')) thumb,concat('$this->resource_host',ifnull(app_list_img, '/ko/img/empty.png')) image, sales_state", false);

        if ($categories_id){
            $this->db->where('categories_id',$categories_id, false);
        }
        
        $this->db->where_not_in('sales_state',array('WAIT','END','OUT'));

        if ($limit){
            $this->db->limit($limit,$offset);
        }
        
        $this->db->order_by('category_order','desc');

        $query = $this->db->get('products');
        
        return $query->result();
    }
    
    function sale_products($limit=10,$offset=0,$category_id=null,$sub_category_id=null){
        
        $where = "where sales_state not in ('WAIT','OUT','END') ";
        
        if ($category_id && $sub_category_id && $category_id != 0 && $sub_category_id !=0){
            $where .= "and categories_id=$category_id and sub_category_id=$sub_category_id ";
        } else if ($category_id && $category_id != 0){
            $where .= "and categories_id=$category_id ";
        } else if ($sub_category_id && $sub_category_id != 0){
            $where .= "and sub_category_id=$sub_category_id ";
        }
        
        
        $sql = "select 
        id, categories_id, sub_category_id, brands_id, manufacturers_id, origins_id, title, sub_title, format(purchase_price,2) purchase_price, format(regular_price,2) regular_price, dc_rate, format(fixed_dc_amount,2) fixed_dc_amount, point_rate, fixed_point, point_amount, format(sales_price,2) sales_price, format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery, format(profit_final,2) profit_final, format(delivery_fee,2) delivery_fee , sales_state , date_release, tags, partners_id, extra_info_title1, extra_info_value1, extra_info_title2, extra_info_value2, extra_info_title3, extra_info_value3, pop, hit, new, dc_sale, recomm, product_code, description, ifnull(web_list_img, '/en/img/empty.png') web_list_img, web_detail_img, app_list_img, app_detail_img, app_description, web_main, app_main, category_name, sub_category_name, sub_categories_id, category_order 
        from products products left join 
        (select 
        p.category_name category_name,
        c.category_name sub_category_name, 
        c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p 
        on c.parent_id = p.id) categories on products.sub_category_id = categories.sub_categories_id "
        .$where.
        "order by products.category_order asc limit $offset,$limit;";
        
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select Product List
    function products($limit=10,$offset=0,$category_id=null,$sub_category_id=null,$keyword=null,$state=null, $sub_category_name=null){
        
        $where = '';
        
        if ($category_id && $sub_category_id && $category_id != 0 && $sub_category_id !=0){
            $where = "where categories_id=$category_id and sub_category_id=$sub_category_id ";
        } else if ($category_id && $category_id != 0 && $sub_category_name){
            $where = "where categories_id=$category_id and tags like '%$sub_category_name%' ";
        } else if ($category_id && $category_id != 0){
            $where = "where categories_id=$category_id ";
        } else if ($sub_category_id && $sub_category_id !=0){
            $where = "where sub_category_id=$sub_category_id ";
        } 
        
        if ($keyword && $where == ''){
	        $where .= " where (tags like '%$keyword%' or title like '%$keyword%' or product_code like '%$keyword%') ";
        } else if ($keyword && $where != ''){
	        $where .= " and (tags like '%$keyword%' or title like '%$keyword%' or product_code like '%$keyword%') ";
        }
        
        if ($state && $where == ''){
        	$str = implode("','",$state);
	        $where .= " where sales_state in ('".$str."') ";
        } else if ($state && $where != '') {
	        $str = implode("','",$state);
	        $where .= " and sales_state in ('".$str."') ";
        }


        
        
        $sql = "select 
        products.id id, categories_id, sub_category_id, brands_id, manufacturers_id, origins_id, title, sub_title, format(purchase_price,2) purchase_price, format(regular_price,2) regular_price, dc_rate, format(fixed_dc_amount,2) fixed_dc_amount, point_rate, fixed_point, point_amount, format(sales_price,2) sales_price, format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery, format(profit_final,2) profit_final, format(delivery_fee,2) delivery_fee , sales_state , date_release, tags, partners_id, extra_info_title1, extra_info_value1, extra_info_title2, extra_info_value2, extra_info_title3, extra_info_value3, pop, hit, new, dc_sale, recomm, product_code, description, ifnull(web_list_img, '/en/img/empty.png') web_list_img, web_detail_img, app_list_img, app_detail_img, app_description, web_main, app_main, category_name 
        from products products left join 
        (select * from product_categories where parent_id is null) categories on products.categories_id = categories.id "
        .$where.
        "order by products.category_order desc limit $offset,$limit;";
        
        $query = $this->db->query($sql);
        
        
/*
        $this->db->select("id, categories_id, sub_category_id, brands_id, manufacturers_id, origins_id, title, sub_title, format(purchase_price,2) purchase_price, format(regular_price,2) regular_price, dc_rate, format(fixed_dc_amount,2) fixed_dc_amount, point_rate, fixed_point, point_amount, format(sales_price,2) sales_price, format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery, format(profit_final,2) profit_final, format(delivery_fee,2) delivery_fee , sales_state , date_release, tags, partners_id, extra_info_title1, extra_info_value1, extra_info_title2, extra_info_value2, extra_info_title3, extra_info_value3, pop, hit, new, dc_sale, recomm, product_code, description, ifnull(web_list_img, '/en/img/empty.png') web_list_img, web_detail_img, app_list_img, app_detail_img, app_description, web_main, app_main, category_name, sub_category_name, sub_categories_id",false);
        
        
        $this->db->join('(select 
        p.category_name category_name,
        c.category_name sub_category_name, 
        c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p 
        on c.parent_id = p.id) categories','products.sub_category_id = categories.sub_categories_id');
        
        if ($category_id && $category_id != 0){
        	$this->db->where('categories_id',$category_id);
        } 
        
        if ($sub_category_id && $sub_category_id !=0){
            $this->db->where('sub_category_id',$sub_category_id);
        }
        
        if ($state){
	        $this->db->where_in('sales_state',$state);
        }
        
        if ($keyword){
	        $this->db->like('sub_category_id',$sub_category_id);
        }
        
        $this->db->order_by('id','desc');
        
        $query = $this->db->get('products',$limit,$offset);
*/
        
        
        
        return $query->result();
    }
    
    function saleProductsSearch($keyword){
    
    	$sql = "select 
        id, categories_id, sub_category_id, brands_id, manufacturers_id, origins_id, title, sub_title, format(purchase_price,2) purchase_price, format(regular_price,2) regular_price, dc_rate, format(fixed_dc_amount,2) fixed_dc_amount, point_rate, fixed_point, point_amount, format(sales_price,2) sales_price, format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery, format(profit_final,2) profit_final, format(delivery_fee,2) delivery_fee , sales_state , date_release, tags, partners_id, extra_info_title1, extra_info_value1, extra_info_title2, extra_info_value2, extra_info_title3, extra_info_value3, pop, hit, new, dc_sale, recomm, product_code, description, ifnull(web_list_img, '/en/img/empty.png') web_list_img, web_detail_img, app_list_img, app_detail_img, app_description, web_main, app_main, category_name, sub_category_name, sub_categories_id 
        from products products left join 
        (select 
        p.category_name category_name,
        c.category_name sub_category_name, 
        c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p 
        on c.parent_id = p.id) categories on products.sub_category_id = categories.sub_categories_id 
        where sales_state not in ('WAIT','OUT','END') and (title like '%$keyword%' or sub_title like '%$keyword%' or tags like '%$keyword%') 
		order by products.id desc";
		
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    function productsSearch($keyword){
    	$sql = "select 
        id, categories_id, sub_category_id, brands_id, manufacturers_id, origins_id, title, sub_title, format(purchase_price,2) purchase_price, format(regular_price,2) regular_price, dc_rate, format(fixed_dc_amount,2) fixed_dc_amount, point_rate, fixed_point, point_amount, format(sales_price,2) sales_price, format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery, format(profit_final,2) profit_final, format(delivery_fee,2) delivery_fee , sales_state , date_release, tags, partners_id, extra_info_title1, extra_info_value1, extra_info_title2, extra_info_value2, extra_info_title3, extra_info_value3, pop, hit, new, dc_sale, recomm, product_code, description, ifnull(web_list_img, '/en/img/empty.png') web_list_img, web_detail_img, app_list_img, app_detail_img, app_description, web_main, app_main, category_name, sub_category_name, sub_categories_id 
        from products products left join 
        (select 
        p.category_name category_name,
        c.category_name sub_category_name, 
        c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p 
        on c.parent_id = p.id) categories on products.sub_category_id = categories.sub_categories_id 
        where (title like '%$keyword%' or sub_title like '%$keyword%' or tags like '%$keyword%') 
		order by products.id desc";
		
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select One Product Item
    function productById($id){
        if ($id == null)
            return false;
        
        $query = $this->db->get_where('products',array('id'=>$id));
        return $query->row();
    }
    
    // Select Product Items By tag
    function productsByTag($tag){
        $sql = "select * from products where tags like '%".$tag."%'";
        
        $this->db->order_by('id','desc'); 
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    // Select Product Items By tag
    function productsByTagSimple($categories_id,$tag,$offset=0,$limit=null){
        $base_url = base_url();

        if (strtoupper($tag)=='ALL'){
            $sql = "select id, category_order, title, sales_price,concat('$this->resource_host',app_detail_img) thumb, concat('$this->resource_host',app_list_img) image, sales_state "
            ."from products where sales_state not in ('WAIT','END','OUT') and categories_id=".$categories_id
            ." order by category_order desc";
        } else {
            $sql = "select id, category_order, title, sales_price,concat('$this->resource_host',app_detail_img) thumb, concat('$this->resource_host',app_list_img) image, sales_state "
            ."from products where tags like '%".$tag."%' and sales_state not in ('WAIT','END','OUT') and categories_id=".$categories_id
            ." order by category_order desc";
        }
        
        
        // $this->db->order_by('category_order','desc'); 

        if ($limit){
            // $this->db->limit($limit,$offset);
            $sql .= ' limit '.$offset.','.$limit;
        }

        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select Product by State
    function productsByState($state){
        if ($state == null)
            return false;
        
        $this->db->order_by('id','desc');
        $query = $this->db->get_where('products',array('sales_state'=>$state));
        return $query->result();
    }
    
    // Select Simple Product Info
    function productSimple($id){
    	$this->db->select("id, title, sales_price, point_rate, fixed_point , ifnull(web_list_img, '/en/img/empty.png') web_list_img, sales_state", false);
    	$this->db->where('id',$id);
    	$query = $this->db->get('products');
    	
    	return $query->row();
    }
    
    
    // Select Simple Product Info
    function productAppSimple($id){
    	$this->db->select("id, title,sub_title, sales_price, app_description,sales_state,replace(product_images,'\'','\"') product_images", false);
    	$this->db->where('id',$id);
    	$query = $this->db->get('products');
    	
    	return $query->row();
    }

    // Select Simple Product Info
    function productAppForList($id){
        $this->db->select("id, title, sales_price, product_code ,concat('$this->resource_host', ifnull(app_detail_img, '/en/img/empty.png')) thumb, sales_state, if(extra_info_value1='',150,extra_info_value1) weight", false);
        $this->db->where('id',$id);
        $query = $this->db->get('products');
        
        return $query->row();
    }
    
    // Select Products order by top sales
    function productsTopSales($limit=10,$offset=0){
        
        $where = '';
        
        $sql = "select id,categories_id, sub_category_id, title,sub_title, format(purchase_price,2) purchase_price ,format(regular_price,2) regular_price, format(sales_price,2) sales_price ,format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery,format(profit_final,2) profit_final,extra_info_value1,ifnull(web_list_img, '/en/img/empty.png') web_list_img,web_detail_img,ifnull(order_cnt,2) order_cnt, web_main, app_main    
        from (select * from products products left join 
        (select p.category_name category_name, c.category_name sub_category_name, c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p on c.parent_id = p.id) categories on products.sub_category_id = categories. sub_categories_id 
        order by products.id desc) a left join 
        (select count(orders_id) as order_cnt, products_id 
        from order_items 
        group by products_id) b on b.products_id = a.id 
        where sales_state not in ('WAIT','OUT','END')  
        order by order_cnt desc limit $offset,$limit;";
        
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select Products disp on web main
    function productsWebMain($limit=10,$offset=0){
        
        $where = '';
        
        $sql = "select id,categories_id, sub_category_id, title,sub_title, format(purchase_price,2) purchase_price  ,format(regular_price,2) regular_price, format(sales_price,2) sales_price ,format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery,format(profit_final,2) profit_final,extra_info_value1,ifnull(web_list_img, '/en/img/empty.png') web_list_img,web_detail_img,ifnull(order_cnt,2) order_cnt, web_main, app_main    
        from (select * from products products left join 
        (select p.category_name category_name, c.category_name sub_category_name, c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p on c.parent_id = p.id) categories on products.sub_category_id = categories. sub_categories_id 
        order by products.id desc) a left join 
        (select count(orders_id) as order_cnt, products_id 
        from order_items 
        group by products_id) b on b.products_id = a.id 
        where sales_state not in ('WAIT','OUT','END') and web_main = 'Y' 
        order by order_cnt desc limit $offset,$limit;";
        
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    // Select Products disp on app main
    function productsAppMain($limit=10,$offset=0){
        
        $where = '';
        
        $sql = "select id,categories_id, sub_category_id, title,sub_title, format(purchase_price,2) purchase_price ,format(regular_price,2) regular_price, format(sales_price,2) sales_price ,extra_info_value1,concat('$this->resource_host', ifnull(app_main_img, '/en/img/empty.png')) app_detail_img    
        from products 
        where sales_state not in ('WAIT','OUT','END') and app_main = 'Y' 
        order by id limit $offset,$limit;";
        
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    

    // Select Products disp on app main
    function productsAppMainAdmin($limit=10,$offset=0){
        
        $where = '';
        
        $sql = "select id,categories_id, sub_category_id, title,sub_title, format(purchase_price,2) purchase_price  ,format(regular_price,2) regular_price, format(sales_price,2) sales_price ,format(profit,2) profit, format(profit_after_delivery,2) profit_after_delivery,format(profit_final,2) profit_final,extra_info_value1,ifnull(web_list_img, '/en/img/empty.png') web_list_img,web_detail_img,ifnull(order_cnt,2) order_cnt, web_main, app_main    
        from (select * from products products left join 
        (select p.category_name category_name, c.category_name sub_category_name, c.id sub_categories_id
        from (select * from product_categories where parent_id is not null) c left join product_categories p on c.parent_id = p.id) categories on products.sub_category_id = categories. sub_categories_id 
        order by products.id desc) a left join 
        (select count(orders_id) as order_cnt, products_id 
        from order_items 
        group by products_id) b on b.products_id = a.id 
        where sales_state not in ('WAIT','OUT','END') and app_main = 'Y' 
        order by order_cnt desc limit $offset,$limit;";
        
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    
    /*
    * 업로드 함수
    */
    function uploadPhoto($fieldName){
		$config['upload_path'] = 'product_images';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config); 

		if (!$this->upload->do_upload($fieldName))
		{	

//			echo $this->upload->display_errors();
//			var_dump(is_dir($config['upload_path']));

			return false;
		}	
		else
		{
			return $this->upload->data();
		}
	}

    function uploadPhotos($filedName){

        $fileNames = new stdClass;
        $fileInfo = $this->uploadPhoto($filedName);
        if ($fileInfo){
            $fileNames->file_name = $fileInfo['file_name'];
            $fileNames->file_path = 'product_images/'.$fileInfo['file_name'];
            $fileNames->width = $fileInfo['image_width'];
            $fileNames->height = $fileInfo['image_height'];
            return $fileNames;
        }

        return false;
    }
	
    
    // Insert Product
    function insert($data){
        
        $web_list_img = $this->uploadPhoto('web_list_img');

        if ($web_list_img)
            $data['web_list_img'] = '/ko/product_images/'.$web_list_img['file_name'];

            
        $web_detail_img = $this->uploadPhoto('web_detail_img');

        if ($web_detail_img)
            $data['web_detail_img'] = '/ko/product_images/'.$web_detail_img['file_name'];

            
        $app_list_img = $this->uploadPhoto('app_list_img');

        if ($app_list_img)
            $data['app_list_img'] = '/ko/product_images/'.$app_list_img['file_name'];

            
        $app_detail_img = $this->uploadPhoto('app_detail_img');

        if ($app_detail_img)
            $data['app_detail_img'] = '/ko/product_images/'.$app_detail_img['file_name'];


        $app_main_img = $this->uploadPhoto('app_main_img');

        if ($app_main_img)
            $data['app_main_img'] = '/ko/product_images/'.$app_main_img['file_name'];

        $app_main_img = $this->uploadPhoto('app_main_img');
            
    

        if (!empty($data['product_images'])){
            $data['product_images'] = json_encode($data['product_images']);
        }
        
        $data['tags'] = preg_replace("/\s+/","",$data['tags']); // 태그 공백제거
        
        $data['description'] = $data['smarteditor_textarea'];
        unset($data['smarteditor_textarea']);
        unset($data['file_explorer']);
        unset($data['product_photo']);
        
//        var_dump($data);

        $this->db->select_max('category_order','max');
        $this->db->where('categories_id',$data['categories_id']);
        $pr = $this->db->get('products')->row();
        if ($pr){
            $data['category_order'] = $pr->max + 1;
        }
        
        
        $this->db->trans_begin();
        $this->db->insert('products', $data);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return true;
   
    }
    
    // Update Product
    function update($productData){
        $data = $productData;
     
        if (!$data){
            return false;
        }

        // // 기존 파일 삭제
        // if (gettype($_FILES)=='array' && count($_FILES) > 0){
        //     $this->db->select(implode(",", array_keys($_FILES)));
        //     $query = $this->db->get_where('products',array('id'=>$data['id']));
        //     $row = $query->row();
        //     if ($row){
        //         foreach ($_FILES as $col=>$filename){

        //             if ($row->$col){
        //                 $path = "..".$row->$col;

        //                 if (is_readable($path)){
        //                     if (!unlink($path)){
        //                         return false;
        //                     }
        //                 }    
        //             }
                    
        //         }
        //     }
        // }

        unset($_FILES['product_photo']);
        
        // 파일 업데이트
        foreach($_FILES as $files => $filesValue){
            if (!empty($filesValue['name'])){
                $result = $this->uploadPhoto($files);
                
                $data[$files] = '/en/product_images/'.$result['file_name'];
            }
        }
        
        if (!empty($data['tags']))
            $data['tags'] = preg_replace("/\s+/","",$data['tags']); // 태그 공백제거
        
        if (!empty($data['smarteditor_textarea'])){
            $data['description'] = $data['smarteditor_textarea'];
            unset($data['smarteditor_textarea']);
            unset($data['file_explorer']);
        }
        
        if (empty($data['pop'])) $data['pop'] = 'N';
        if (empty($data['hit'])) $data['hit'] = 'N';
        if (empty($data['new'])) $data['new'] = 'N';
        if (empty($data['dc_sale'])) $data['dc_sale'] = 'N';
        if (empty($data['recomm'])) $data['recomm'] = 'N';

        if (!empty($data['product_images'])){

            $data['product_images'] = '['.implode(',', $data['product_images']).']';
        }
        unset($data['product_photo']);
        
        
        $this->db->trans_begin();
        $this->db->where('id', $data['id']);
		$this->db->update('products', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;

    }
    
    // Delete Product
    function delete(){
        $this->db->trans_begin();
        $this->db->delete('product', array('id'=>$this->id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    
    // 상요자 구매 이력 존재 여부
    function hasPurchase($membersId, $productsId){

	   	$sql = "select count(*) as cnt from (select id, members_id from orders where members_id=".$membersId.") orders left join (select orders_id,products_id from order_items where products_id=".$productsId.") items on orders.id = items.orders_id where products_id is not null";
	    
	    $query = $this->db->query($sql);
	    
	    $cnt = $query->row()->cnt;
	    
	    if ($cnt>0){
		    return true;
	    }
	    
	    return false;
	   
    }

}

?>