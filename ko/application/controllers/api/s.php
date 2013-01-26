<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 보안 객체 로드
require(APPPATH.'helpers/secure.php');



class S extends CI_Controller {

    var $secureMgr;
    var $api_key = "7a12dfb4ef2c543db4cf16fc6b212e554bf7c33b";
   	var $en_db = null;

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->model('members_model');
        $this->load->helper('cookie');
        $this->load->helper('url');

        $this->load->database();
        // $this->en_db = $this->load->database('en',true);
        
        ///// 개발 중 api 체크하지 않음
        
/*
        $headers = $this->input->request_headers();
        $api = $headers['X-api-key'];
        
        if ($api != $this->api_key){
	        redirect('api/fail');
        }
*/

	}
	
	function index()
	{
        echo '';
	}
	
	/* 보안 객체 */
    private function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }	
    
    private function memberObj($members_id){
	    
	    if ($members_id){
		    return $this->members_model->memberById($members_id);
	    }
	    
	    return null;
    }

    /*
     * 1차 카테고리 상품 갯수
     */
    function categoryProductCnts(){

    	$this->load->model('product_categories_model');
    	
    	$result['code'] = API_RESULT_FAIL;

    	$categories = $this->product_categories_model->productCounts();

    	if (gettype($categories)=='array'){
    		$result['code'] = API_RESULT_OK;
    		$result['result'] = $categories;
    	}

    	$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

    }

    /*
     * 카테고리 상품 갯수
     */

    function productCountCategory(){

    	$categories_id = $this->input->get('categories_id');

		$result['code'] = API_RESULT_FAIL;
    	if ($categories_id == null){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));

			return;
		}

    	$this->db->select('count(products.id) count');
        $this->db->from('(select * from product_categories where parent_id is null) product_categories');
        $this->db->join('products','product_categories.id=products.categories_id','left');
        $this->db->group_by('product_categories.id');
        $this->db->where('product_categories.id',$categories_id);
        $query = $this->db->get();
        $row = $query->row();

        if ($row){
        	$result['code'] = API_RESULT_OK;
        	$result['result'] = intVal($row->count);
        	$result['min_count'] = 50;
        }
    	

    	$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
    }

    /*
     * 1차 카테고리 내 태그 목록
     */
    function categoryTags(){
    	$this->load->model('product_categories_model');

    	$categories_id = $this->input->get('categories_id');

    	$result['code'] = API_RESULT_FAIL;

    	$categories = $this->product_categories_model->categoryTags($categories_id);

    	$category = $this->db->get_where('product_categories',array('id'=>$categories_id))->row();

    	if (gettype($categories)=='array' && $category){
    		$result['code'] = API_RESULT_OK;
    		$result['result']['category_name'] = strtoupper($category->category_name);
    		$result['result']['tags'] = $categories;
    	}

    	$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
    }
	
	/*
	 * 카테고리 상품
	 */
	function categoryProducts(){
		$categories_id = $this->input->get('categories_id');
		$offset = $this->input->get('offset');
		$sort_by = $this->input->get('sort_order');

		$offset = $offset ? $offset : 0;
		$limit = 40; // 한번에 불러올 상품 갯수

		if ($categories_id == null){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));

			return;
		}
		
		$this->load->model('products_model');

		if ($sort_by && $sort_by == 'sales_volume')
			 $products = $this->products_model->sale_products_simple_by_sales($categories_id,$offset,$limit);
		else 
			$products = $this->products_model->sale_products_simple($categories_id,$offset,$limit);
			
		if (gettype($products)=='array'){
			$result['code'] = API_RESULT_OK;
			$result['result'] = $products;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}
	
	/*
	 * 서브 카테고리 상품
	 */
	function products(){
		$categories_id = $this->input->get('categories_id');
		$offset = $this->input->get('offset');

		$offset = $offset ? $offset : 0;
		$limit = 40; // 한번에 불러올 상품 갯수
		
		$result['code'] = API_RESULT_FAIL;
		if ($categories_id == null){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));

			return;
		}
		
		$this->load->model('products_model');
		
		$category = $this->db->get_where('product_categories',array('id'=>$categories_id))->row();

		if ($category){
			$parent_id = $category->parent_id;
			$category_name = $category->category_name;
			 if (strtoupper($category_name)=='ALL'){
			 	if ($sort_by && $sort_by == 'sales_volume')
			 		$products = $this->products_model->sale_products_simple_by_sales($parent_id,$offset,$limit);
			 	else 
			 		$products = $this->products_model->sale_products_simple($parent_id,$offset,$limit);
			 } else {
			 	$products = $this->products_model->productsByTagSimple($parent_id,$category_name,$offset,$limit);
			 }
			
			
			if (gettype($products)=='array'){
				$result['code'] = API_RESULT_OK;
				$result['result'] = $products;
			}
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}
	
	
	/*
	 * 상품 상세 정보
	 */
	function product(){
		$products_id = $this->input->get('products_id');
		$members_id = null;
		$uuid = $this->input->get('uuid');

		$finalResult['code'] = API_RESULT_FAIL;
		
		if (!$products_id){
			$finalResult['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($finalResult));
			return;
		}
		
		$this->load->model('products_model');
		$product = $this->products_model->productAppSimple($products_id);
		if ($product){
			$product->product_images = json_decode($product->product_images,true);

			$finalResult['code'] = API_RESULT_OK;
			$result['product'] = $product;
			
			// 구매 후기
			// $reviews = $this->productReviews();
			// $result['reviews'] = $reviews;

			/* 구매 이력 */
			$result['has_purchase'] = false;
			
			/* 찜 이력 */
			$result['is_wishItem'] = false;

			/* 장바구니 여 부 */
			$result['is_cartItem'] = false;


			if ($members_id){
				// 구입 여부
				$result['has_purchase'] = $this->products_model->hasPurchase($members_id, $products_id);
				
				// 찜 여부
				$q = $this->db->get_where('wishlist', array('members_id'=>$members_id, 'products_id'=>$products_id));
				$result['is_wishItem'] = count($q->result())>0?true:false;

				// 장바구니에 존재 여부
				$q = $this->db->get_where('carts', array('members_id'=>$members_id, 'products_id'=>$products_id));
				$result['is_cartItem'] = false; //count($q->result())>0?true:false;

			}

			else if ($uuid) {
				// 찜 여부
				$q = $this->db->get_where('wishlist', array('uuid'=>$uuid, 'products_id'=>$products_id));
				$result['is_wishItem'] = count($q->result())>0?true:false;

				// 장바구니에 존재 여부
				$q = $this->db->get_where('carts', array('uuid'=>$uuid, 'products_id'=>$products_id));
				$result['is_cartItem'] = false; //count($q->result())>0?true:false;
			}

			/* 관련 배경 */
			$this->db->select("id,SUBSTRING(thumb_path,13) as filename",false);
			$this->db->order_by('date_added','desc');
			$this->db->from("case_wall");
			$this->db->join("caseshop.wallpapers wallpapers", 'case_wall.wallpapers_id=wallpapers.id','right');
			$this->db->where('products_id',$products_id);
			$query = $this->db->get();
			$wallpapers = $query->result();
			if ($wallpapers){
				$result['wallpapers'] = $query->result();	
			}

			/* 상품 옵션 */
			$product_options = $this->db->get_where('product_options',array('products_id'=>$products_id))->result();
			if ($product_options){
				$result['product_options'] = $product_options;
			}

			
			
			/* 사용자 후기 */
			// $this->db->select('id,nickname,members_id,products_id,content,rating');
			// $review = $this->db->get_where('reviews', array('members_id'=>$members_id, 'products_id'=>$products_id))->row();

			// if ($review){
			// 	$result['review'] = $review;
			// }


			/* 질문 카운트 */
			// $this->db->where('products_id',$products_id);
			// $qcnt = $this->db->count_all_results('questions');
			// $result['question_cnt'] = $qcnt;
			
		}
		
		$finalResult['result'] = $result;
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($finalResult));
		
	}


	/*
	 * 메인 편집 상품
	 */
	function mainContentCategory(){
		$categories_id = $this->input->get('categories_id');

		$result['code'] = API_RESULT_FAIL;

		if (!$categories_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;		
		}

		$this->db->select("concat('".RESOURCEHOST."',ifnull(app_detail_img, '/ko/img/empty.png'))as thumb",false);
		$this->db->where('categories_id',$categories_id);
		$populars = $this->db->get_where('products',array('pop'=>'Y'),4,0)->result();;
		
		$this->db->select("concat('".RESOURCEHOST."',ifnull(app_detail_img, '/ko/img/empty.png'))as thumb",false);
		$this->db->where('categories_id',$categories_id);
		$this->db->order_by('id','desc');
		$new_arvls = $this->db->get_where('products',array('new'=>'Y'),2,0)->result();

		$this->db->select("concat('".RESOURCEHOST."',ifnull(app_detail_img, '/ko/img/empty.png'))as thumb",false);
		$this->db->where('categories_id',$categories_id);
		$bests = $this->db->get_where('products',array('hit'=>'Y'))->result();

		$result['result']['populars'] = $populars;
		$result['result']['new_arrivals'] = $new_arvls;
		$result['result']['best_sellers'] = $bests;

		$result['code'] = API_RESULT_OK;

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));


	}




	// }

	/*
	 * 배송국가
	 */

	function shipTable(){

		$result['code'] = API_RESULT_FAIL;

		$weight = $this->input->get('weight');

		if (!$weight){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;		
		}

		$original_weight = floatval($weight);

		$weight = $original_weight;

		$rest = 0;
		$times = 0;
		if ($weight > 2000){
			$rest = $weight % 2000;
			$times = intval($weight / 2000);
			$weight = $rest;
		}

		$column = 'u500';
		$extra = 'u2000';

		if ($weight > 500 && $weight < 1000){
			$column = 'u1000';
		} else if ($weight >= 1000 && $weight < 1500){
			$column = 'u1500';
		} else if ($weight >= 1500 && $weight < 2000){
			$column = 'u2000';
		} 

		$this->db->select('code,name,option,u500,u1000,u1500,u2000');
		$this->db->order_by('code','asc');
		$query = $this->db->get('shipping_table');

		$data = $query->result();

		$table = array();
		foreach($data as $row){
			$code = $row->code;
			$table[$code]['name'] = $row->name;


			if ($original_weight > 2000){
				$price = ($row->$extra) * $times;
				$price += $row->$column;
				$table[$code]['options'][$row->option] = $price;	
			} else {
				$table[$code]['options'][$row->option] = $row->$column;	
			}
			
		}

		$result['code'] = API_RESULT_OK;
		$result['result'] = $table;

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));


	}

	function shipFeeByWeight(){
		$result['code'] = API_RESULT_FAIL;

		$weight = $this->input->get('weight');
		$code = $this->input->get('code');
		$option = $this->input->get('option');

		if (!$weight || !$code || !$option){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;		
		}

		$original_weight = floatval($weight);

		$weight = $original_weight;

		$rest = 0;
		$times = 0;
		if ($weight > 2000){
			$rest = $weight % 2000;
			$times = intval($weight / 2000);
			$weight = $rest;
		}

		$column = 'u500';
		$extra = 'u2000';

		if ($weight > 500 && $weight < 1000){
			$column = 'u1000';
		} else if ($weight >= 1000 && $weight < 1500){
			$column = 'u1500';
		} else if ($weight >= 1500 && $weight < 2000){
			$column = 'u2000';
		} 

		$this->db->select('code,name,option,u2000,'.$column);
		$this->db->order_by('code','asc');
		$this->db->where(array('code'=>$code,'option'=>$option));
		$query = $this->db->get('shipping_table');

		$data = $query->row();

		if ($original_weight > 2000){
			$price = ($data->$extra) * $times;
			$price += $data->$column;
			$result['result'] = $price;	
		} else {
			$result['result'] = $data->$column;	
		}

		$result['code'] = API_RESULT_OK;
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}



	/*
	 * 상품 장바구니에 담기
	 */
	function addToCart(){
		$members_id = null;
		$uuid = $this->input->post('uuid');
		$products_id = $this->input->post('products_id');
		$option_name = $this->input->post('option_name');
		$qty = 1;
		
		$result['code'] = API_RESULT_FAIL;

		if (!$products_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		// 회원일 경우 members_id 앱회원일경우 uuid
		if ($products_id && $uuid) {

			$query = $this->db->get_where('carts', array('uuid'=>$uuid, 'products_id'=>$products_id));

		} else {

			$result['code'] = API_RESULT_LACK_PARAMS;

			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$cart_item = $query->row();
		
		if (false){ // $cart_item;
			$this->db->trans_begin();
			$new_qty = $qty + $cart_item->qty;

			if ($members_id) {
				$this->db->where(array('members_id'=>$members_id,'products_id'=>$products_id));
			} else if ($uuid) {
				$this->db->where(array('uuid'=>$uuid,'products_id'=>$products_id));
			} 
			$this->db->update('carts',array('qty'=>$new_qty));
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	        } else {
	        	$this->db->trans_commit();
	        	$result['code'] = API_RESULT_OK;
	        }

		} else {
			$data = array(
				'products_id'=>$products_id,
				'qty'=>$qty
			);

			if ($members_id) {
				$data['members_id'] = $members_id;
			} else if ($uuid) {
				$data['uuid'] = $uuid;
			} 

			if ($option_name)
				$data['option_name'] = $option_name;
			
			$this->db->trans_begin();
			$this->db->insert('carts',$data);
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	        } else {
	        	$this->db->trans_commit();
	        	$result['code'] = API_RESULT_OK;
	        }
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
	}

	/*
	 * 장바구니 카운트
	 */

	function cartCount(){
		$uuid = $this->input->get('uuid');

		$result['code'] = API_RESULT_FAIL;

		if (!$uuid){
			$result['code'] = API_RESULT_LACK_PARAMS;
			
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$this->db->where('uuid',$uuid);
		$cnt = $this->db->count_all_results('carts');

		$result['result'] =  $cnt;
		$result['code'] =  API_RESULT_OK;

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}

	/*
	 * 장바구니 리스트
	 */
	function cartList(){

		$this->load->model('carts_model');

		$members_id = null;
		$uuid = $this->input->get('uuid');

		$date_latest = doubleval($this->input->get('date_latest'));

		$result['code'] = API_RESULT_FAIL;
		

		if ($date_latest != 0){
			$this->db->select("UNIX_TIMESTAMP(MAX(date_modified)) as max_date", false);
			if ($members_id) {
				$query = $this->db->get_where('carts', array('members_id'=>$members_id));
			} else if ($uuid) {
				$this->db->where('members_id is null');
				$this->db->order_by('id','asc');
				$query = $this->db->get_where('carts', array('uuid'=>$uuid));
			} else {
				$result['code'] = API_RESULT_LACK_PARAMS;
				$this->output
					 ->set_content_type('application/json')
					 ->set_output(json_encode($result));
				return;
			}

			$max_date = $query->row()->max_date;
			if (doubleval($max_date) == $date_latest){
				$result['code'] = API_RESULT_LATEST;
				$result['date_latest'] = $date_latest;
				$this->output
					 ->set_content_type('application/json')
					 ->set_output(json_encode($result));
				return;
			}
		}

		// 회원일 경우 members_id 앱회원일경우 uuid
		$this->db->select('id,products_id,option_name,qty,UNIX_TIMESTAMP(date_modified) date_modified');
		$this->db->order_by('id','desc');
		if ($members_id) {
			$query = $this->db->get_where('carts', array('members_id'=>$members_id));
		} else if ($uuid) {
			$this->db->where('members_id is null');
			$query = $this->db->get_where('carts', array('uuid'=>$uuid));
		} else {
			$result['code'] = 'wrong_way';
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		$this->load->model('products_model');

		$total_amount = 0;
		$cart = $query->result();
		
		$new_date = 0;
		$possible = array();
		foreach($cart as $key=>$i){
			
			if ($i->date_modified > $new_date) $new_date = $i->date_modified;

			$id = $i->products_id;
			$product = $this->products_model->productAppForList($id);
			if ($product->sales_state == 'SALE'){
				$i->product = $product;
				$total_amount += ($product->sales_price * $i->qty);
				$i->sum_price = $product->sales_price * $i->qty;
				$product->sales_price = $product->sales_price;
				$possible[] = $i;
			} else {
				// 품절 상품 카트에서 지움
				$this->carts_model->deleteProduct($id);
			}
			
		}
		
		// 상품 가격 합계
		$result['result']['total_amount'] = $total_amount;
		
		$result['date_latest'] = 0;
		if (gettype($cart) == 'array' && count($cart)>0){
			$result['date_latest'] = $new_date;
		}
		
		$result['code'] = API_RESULT_OK;
		$result['result']['carts'] = $possible;

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));


	}




	/*
	 * 장바구니 업데이트
	 */
	function updateCart(){
		$cart_item_id = $this->input->post('cart_item_id');
		$qty = $this->input->post('qty');

		$result['code'] = API_RESULT_FAIL;

		if (!$cart_item_id || !$qty){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}


		$this->db->trans_begin();

		$this->db->where(array('id'=>$cart_item_id));
		$this->db->update('carts',array('qty'=>$qty));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $result['code'] = API_RESULT_FAIL;
        } else {
        	$this->db->trans_commit();
        	$result['code'] = API_RESULT_OK;
        }

        $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}

	/*
	 * 장바구니 아이템 삭제
	 */
	function removeCartItem(){
		$cart_item_id = $this->input->post('cart_item_id');

		$result['code'] = API_RESULT_FAIL;
		if (!$cart_item_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$this->db->trans_begin();

		$this->db->delete('carts',array('id'=>$cart_item_id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $result['code'] = API_RESULT_FAIL;
        } else {
        	$this->db->trans_commit();
        	$result['code'] = API_RESULT_OK;
        }

        $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	/*
	 * 장바구니 아이템 삭제
	 */
	private function cartEmpty($members_id=null, $uuid=null){
		
		if ($members_id)
			$this->db->where('members_id',$members_id);
		
		else if ($uuid)
			$this->db->where('uuid',$uuid);
		
		else
			return false;
					
		$this->db->trans_begin();
		$this->db->delete('carts');

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
	}
	
	/* 장바구니 비우기 */
	function emptyCart(){
		
		$members_id = $this->input->post('members_id');
		$uuid = $this->input->post('uuid');
		
		$result['code'] = API_RESULT_FAIL;
		
		if (!$uuid){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}



		if ($this->cartEmpty($members_id, $uuid)){
			$result['code'] = API_RESULT_OK;
		} 
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}



	/* 배경화면 목록 */
	function wallpapers(){
		$offset = $this->input->get('offset');
		$products_id = $this->input->get('products_id');

		if (!$offset) $offset = 0;


		$result['code'] = API_RESULT_FAIL;

		$this->db->select("id,SUBSTRING(thumb_path,13) as filename, '".RESOURCEHOST."/wallpapers/' as resource_dir_path",false);
		$this->db->order_by('date_added','asc');
		$this->db->from('caseshop.wallpapers');
		if ($products_id){
			$this->db->join('case_wall', 'case_wall.wallpapers_id=wallpapers.id','right');
			$this->db->where('products_id',$products_id);
		}
		$this->db->limit(24,$offset);
		$query = $this->db->get();

		$result['result'] = $query->result();
		
		$result['code'] = API_RESULT_OK;

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));


	}



	/* 배경화면 */
	function wallpaper(){
		$id = $this->input->get('id');

		$result['code'] = API_RESULT_FAIL;
		if (!$id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$this->load->model('wallpapers_model');

		$wp = $this->wallpapers_model->wallpaperById($id);

		if ($wp){
			$result['code'] = API_RESULT_OK;

			$result['result'] = $wp;

			$rp = $this->wallpapers_model->relatedProductsSimple($id);
			$result['result']->related_products = $rp;

		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}




	/*
	 * 주소 검색
	 */

	function addressSearch(){
		$keyword = $this->input->get('keyword');

		$result['code'] = API_RESULT_FAIL;
		if (!$keyword){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$this->load->model('zipcode_model');
		
		$addresses = $this->zipcode_model->zipcodesByKeyword($keyword);

		if ($addresses){
			$result['code'] = API_RESULT_OK;
			$result['result'] = $addresses;
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}




	
	/*
	 * 주문 내역 목록 :  진행중인 주문만 표시, 이전 주문내역은 지난 주문 보기 버튼 눌러서 보기
	 */
	function orderList(){
		$members_id = $this->input->get('members_id');
		$uuid = $this->input->get('uuid');

		$result['code'] = API_RESULT_FAIL;

		if (!$members_id && !$uuid){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}

		$this->load->model('orders_model');
		$orders  = $this->orders_model->ordersByMemberSimple($members_id, $uuid);

		if (gettype($orders)=='array'){
			$result['code'] = API_RESULT_OK;
			$result['result'] = $orders;
		} 

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}


	/*
	 * 주문 내역 상세 : 
	 */
	// function orderDetail(){
	// 	$orders_id = $this->input->get('orders_id');

	// 	$result['success'] = false;

	// 	if (!$orders_id){
	// 		$this->output
	// 			 ->set_content_type('application/json')
	// 			 ->set_output(json_encode($result));
	// 		return;
	// 	}

	// 	$this->load->model('orders_model');
	// 	$order = $this->orders_model->order($orders_id);
	// 	$orders  = $this->orders_model->orderItemsSimple($orders_id);

	// 	if ($order && $orders){

	// 		$ostate = $this->db->get_where('order_states', array('key'=>$order->order_state))->row();
	// 		if ($ostate)
	// 			$order->order_state_readable = $ostate->customer_text;

	// 		$order->totalPrice = number_format($order->totalPrice);
	// 		$order->delivery_fee = number_format($order->delivery_fee);
	// 		$order->used_point = $order->used_point > 0 ? -$order->used_point : $order->used_point;
	// 		$order->used_point = number_format($order->used_point);
	// 		$order->payable_amount = number_format($order->payable_amount);

	// 		$orderer = $this->db->get_where('order_customer_info',array('orders_id'=>$orders_id))->row();
	// 		$recipient = $this->db->get_where('order_delivery_info',array('orders_id'=>$orders_id))->row();

	// 		$vir = $this->db->get_where('payments', array('rOrdNo'=>$orders_id,'rAuthTy'=>'vir_n'))->row();

	// 		if ($vir){
	// 			$this->load->helper('bankagency');
	// 			$virInfo['acc_no'] = $vir->rVirNo;
	// 			$virInfo['agency'] = getCenter_cd($vir->VIRTUAL_CENTERCD);
	// 			$result['virtual'] = $virInfo;
	// 		}

	// 		$result['success'] = true;
	// 		$result['order'] = $order;
	// 		$result['order_items'] = $orders;
	// 		$result['orderer'] = $orderer;
	// 		$result['recipient'] = $recipient;
	// 	}

	// 	$this->output
	// 		 ->set_content_type('application/json')
	// 		 ->set_output(json_encode($result));


	// }


	/*
	 * 1:1 상담 목록
	 */

	/*
	 * 1:1 상담 작성
	 */



	 /*
	  * 지난 주문 정보 가져오기
	  */
	 // function prevInfo(){
		//  $uuid = $this->input->get('uuid');
		//  $members_id = $this->input->get('members_id');
		 
		//  $result['success'] = false;
		//  if (!$uuid && !$members_id){
		// 	 $this->output
		// 		 ->set_content_type('application/json')
		// 		 ->set_output(json_encode($result));
		// 	return;
		//  }
		 
		 
		 
		 
		//  $this->db->select('id');
		//  $this->db->order_by('id','desc');
		//  if ($members_id){
		// 	 $this->db->where('members_id',$members_id);
		//  }
		//  else if ($uuid){
		// 	 $this->db->where('uuid',$uuid);
		//  }
		//  $order = $this->db->get_where('orders')->row();
		 

		//  $this->load->model('zipcode_model');
		//  if ($order){
		 	
		//  	$orderer = $this->db->get_where('order_customer_info',array('orders_id'=>$order->id))->row();
		//  	$recipient = $this->db->get_where('order_delivery_info',array('orders_id'=>$order->id))->row();
		 	
		//  	if ($orderer && $recipient){
		 	
		//  		$zip = $recipient->zipcode;
		//  		$addr = $this->zipcode_model->addressByZipcode($zip);
		//  		$result['delivery_fee'] = $addr->delivery_fee;
		 		

		//  		$zip = $orderer->zipcode;
		//  		$addr = $this->zipcode_model->addressByZipcode($zip);
		//  		$result['orderer_delivery_fee'] = $addr->delivery_fee;
		 	
		// 	 	$result['orderer'] = $orderer;
		// 	 	$result['recipient'] = $recipient;
		// 	 	$result['success'] = true;	
		//  	}
		//  }
		 
		//  $this->output
		// 	  ->set_content_type('application/json')
		// 	  ->set_output(json_encode($result));
		
		 
	 // }


	/*
	 * 주문 입력
	 */
	 
	function inputOrder(){
	
/*
		var_dump($this->input->post());
		return;
*/

		$result['code'] = API_RESULT_FAIL;
	
		// 필요 라이브러리
		$this->load->model('member_points_model');
		$this->load->model('orders_model');
	
	    /* 인자 */
	    
		// 사용 포인트
		$usingPoint = $this->input->post('using_point');
		
		// 상품 목록
		$products = $this->input->post('products_id');
		
		// 수량 목록
		$quantities = $this->input->post('quantity');

		// 옵션 목록
		$options = $this->input->post('option_name');
		
		// 앱식별 번호
		$uuid = $this->input->post('uuid');
		
		// 회원 번호
		$members_id = $this->input->post('members_id');
		
		
		/* 배송비 정보 가져오기 */
		$base_delivery_fee = 2500;
		$ship_zipcode = $this->input->post('recipient_postcode');
		$zip = $this->db->get_where('zipcode',array('zipcode'=>$ship_zipcode))->row();
		if ($zip){
			$base_delivery_fee = $zip->delivery_fee;
		}
		
		
		
		/* 회원 정보 가져오기 */
		
		// 주문자 아이디 : 세션의 접속자 정보와 비교
		if (!$members_id && !$uuid){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		
		/* 주문 정보 입력 */

		// 앱 비회원 식별
		$orders['uuid'] = $uuid;
		
		
		// 주문 입력 - 회원 번호
		if ($members_id){
			$orders['members_id'] = $members_id;	
		}
		
		// 주문 입력 - 주문 요약 타이틀
		$orders['order_title'] = '';
		$this->db->select('title');
		$query = $this->db->get_where('products', array('id'=>$products[0]));
		$tmp_product = $query->row();
		if (count($products) > 1){
			$orders['order_title'] = $tmp_product->title.' 외 '.(count($products)-1).'건';
		} else {
			$orders['order_title'] = $tmp_product->title;
		}
		
		// 주문 입력 - 상품가격 합계
		$pricePoint = $this->calculatePricePoint($products, $quantities);
		$orders['totalPrice'] = $pricePoint['sum_price'];
		
		// 주문 입력 - 사용 포인트 (회원)
		$orders['used_point'] = $usingPoint;
		
		// 주문 입력 - 적립 포인트
		$orders['saving_point'] = $pricePoint['sum_point'];
		
		// 주문 입력 - 배송료
		$orders['delivery_fee'] = $base_delivery_fee; // 기본 2500원, 도서지방 5000원
		
		// 주문 입력 - 결제 예정액 : 상품가격 합계 + 배송료 - 사용포인트
		$orders['payable_amount'] = $orders['totalPrice'] + $orders['delivery_fee'] - $orders['used_point']; 
		
		// 주문 입력
		$order_id = $this->orders_model->insertOrder($orders);
		
		
		$rollback = false;
		$reason = '';
		if (!$order_id){
			echo '{"success": false, "reason": "주문 입력 실패"}';
			return;
		}
		
		// 주문 상세 입력 
		for ($i = 0; $i < count($products); $i++){
			
			$this->db->select('id, title, sales_price, point_rate, fixed_point');
			$query = $this->db->get_where('products', array('id'=>$products[$i]));
			$product = $query->row();
			
			$item['orders_id'] = $order_id;
			$item['products_id'] = $product->id;
			$item['product_name'] = $product->title;
			$item['item_price'] = $product->sales_price;
			$item['qty'] = $quantities[$i];
			$item['product_option_name'] = !empty($options[$i])?$options[$i]:" ";
			$item['item_total_amount'] = $item['qty'] * $item['item_price'];
			
			if (!$this->orders_model->insertOrderItem($item)){
				$rollback = true; //  실패시 롤백
			}
		}
		

		// 주문자 정보
		$orderer['orders_id'] = $order_id;
		$orderer['name'] = $this->input->post('orderer_name');
		$orderer['zipcode'] = $this->input->post('orderer_postcode');
		$orderer['address'] = $this->input->post('orderer_address');
/* 		$orderer['telephone'] = $this->input->post('orderer_telephone'); */
		$orderer['mobile'] = $this->input->post('orderer_mobile');
		$orderer['email'] = $this->input->post('orderer_email');
		
		if (!$this->orders_model->insertOrderCustomer($orderer)) $rollback = true;
		
		// 배송지 정보
		$recipient['orders_id'] = $order_id;
		$recipient['name'] = $this->input->post('recipient_name');
		$recipient['zipcode'] = $this->input->post('recipient_postcode');
		$recipient['address'] = $this->input->post('recipient_address');
/* 		$recipient['telephone'] = $this->input->post('recipient_telephone'); */
		$recipient['mobile'] = $this->input->post('recipient_mobile');
		$recipient['msg'] = $this->input->post('recipient_msg');
	
		if (!$this->orders_model->insertOrderDelivery($recipient)) $rollback = true;
	
		
		$this->db->select('order_code');
		$query = $this->db->get_where('orders',array('id'=>$order_id));
		$order_code = $query->row();
		
		// 실패 체크
		if ($rollback){
			// 주문 삭제
			$this->orders_model->deleteOrder($order_id);
			$result['reason'] = $reason;
		} else {
			$result['code'] = API_RESULT_OK;
			$result['orders_id'] = $order_id;
			$result['order_code'] = $order_code->order_code;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
	}
	
	/*
	 * 주문 업데이트
	 */
	// function updateOrder(){
	// 	$data = $this->input->post();
		
	// 	$this->load->model('orders_model');
		
	// 	$result['success'] = false;
		
	// 	if ($this->orders_model->update($data)){
	// 		$result['success'] = true;	
	// 	} 
		
	// 	$this->output
	// 		 ->set_content_type('application/json')
	// 		 ->set_output(json_encode($result));
	// }
	
	/*
	 * 주문 삭제
	 */
	 
	function removeOrder(){
		
		$orders_id = $this->input->post('orders_id');
		
		$result['code'] = API_RESULT_FAIL;
		if (!$orders_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		
		$this->load->model('orders_model');
		
		if ($this->orders_model->deleteOrder($orders_id)){
			$result['code'] = API_RESULT_OK;
		} 
		
		$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		
	}

	/*
	 * 주문 취소
	 */
	function cancelOrder(){
		$orders_id = $this->input->post('orders_id');

		$result['code'] = API_RESULT_FAIL;
		if (!$orders_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		$data['id'] = $orders_id;
		$data['order_state'] = 'CANCEL_REQUESTED';

		$this->load->model('orders_model');

		if ($this->orders_model->update($data)){
			$result['code'] = API_RESULT_OK;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}
	
	
	
	private function calculatePricePoint($products, $quantities){
	
		$sum_price = 0;
		$sum_point = 0;
		for ($i = 0; $i < count($products); $i++){
		
			$this->db->select('sales_price, point_rate, fixed_point');
			$query = $this->db->get_where('products', array('id'=>$products[$i]));
			$product = $query->row();
			
			$fixed_point = $product->fixed_point;
			$point_rate = $product->point_rate;
			$sales_price = $product->sales_price;
			$qty = $quantities[$i];
			
			$unit_sum = $qty * $sales_price;
			$sum_price += $unit_sum;
			if ($fixed_point > 0 ){
				$sum_point += $fixed_point * $qty;
			} else {
				$pr = $unit_sum;
				$p = $pr * ($point_rate / 100.0);
				$sum_point += $p;
			}
			
		}
		
		$result['sum_price'] = $sum_price;
		$result['sum_point'] = $sum_point;
		
		return $result;

	}
	




	 /*
	 * 주문 내역 뷰
	 */
	private function orderDetailView($orderCode=null){

		if (!$orderCode){
			echo "잘못된 접근입니다";
			return;
		}

		$this->load->model('orders_model');

		$order = $this->orders_model->orderByOrderCode($orderCode);
		$orders_id = !empty($order->id)?$order->id:null;
		$orders  = $this->orders_model->orderItemsSimple($orders_id);

		if ($order && $orders){

			$ostate = $this->db->get_where('order_states', array('key'=>$order->order_state))->row();
			if ($ostate)
				$order->order_state_readable = $ostate->customer_text;

			$order->totalPrice = number_format($order->totalPrice,0);
			$order->delivery_fee = number_format($order->delivery_fee,0);
			$order->used_point = $order->used_point > 0 ? -$order->used_point : $order->used_point;
			$order->used_point = number_format($order->used_point,0);
			$order->payable_amount = number_format($order->payable_amount,0);

			$orderer = $this->db->get_where('order_customer_info',array('orders_id'=>$orders_id))->row();

			$this->db->select('*');
			$this->db->from('order_delivery_info');
			$this->db->join('delivery_agency','order_delivery_info.delivery_agent_id=delivery_agency.id','left');
			$this->db->where(array('orders_id'=>$orders_id));
			$recipient = $this->db->get()->row();

			$vir = $this->db->get_where('payments', array('rOrdNo'=>$orders_id,'payment_method'=>'virtual'))->row();

			if ($vir!=null){
				$this->load->helper('bankagency');
				$virInfo['acc_no'] = $vir->rVirNo;
				$virInfo['agency'] = getCenter_cd($vir->VIRTUAL_CENTERCD);
				$result['virtual'] = $virInfo;
			}


			$result['order'] = $order;
			$result['order_items'] = $orders;
			$result['orderer'] = $orderer;
			$result['recipient'] = $recipient;

		
			$data['data'] = $result;

			$data['json'] = json_encode($result);
			$this->load->view('shop/mobile/order_result_view',$data);	
			return;
		} else {

			$this->load->view('shop/mobile/order_result_empty');	
			return;
		}

		

	}

	/*
	 * 주문 내역 뷰
	 */
	private function orderDetailView1($orderCode=null){

		if (!$orderCode){
			echo "잘못된 접근입니다";
			return;
		}

		$this->load->model('orders_model');

		$order = $this->orders_model->orderByOrderCode($orderCode);
		$orders_id = !empty($order->id)?$order->id:null;
		$orders  = $this->orders_model->orderItemsSimple($orders_id);

		if ($order && $orders){

			$ostate = $this->db->get_where('order_states', array('key'=>$order->order_state))->row();
			if ($ostate)
				$order->order_state_readable = $ostate->customer_text;

			$order->totalPrice = number_format($order->totalPrice,0);
			$order->delivery_fee = number_format($order->delivery_fee,0);
			$order->used_point = $order->used_point > 0 ? -$order->used_point : $order->used_point;
			$order->used_point = number_format($order->used_point,0);
			$order->payable_amount = number_format($order->payable_amount,0);

			$orderer = $this->db->get_where('order_customer_info',array('orders_id'=>$orders_id))->row();
			$recipient = $this->db->get_where('order_delivery_info',array('orders_id'=>$orders_id))->row();

			$vir = $this->db->get_where('payments', array('rOrdNo'=>$orders_id,'payment_method'=>'virtual'))->row();

			if ($vir!=null){
				$this->load->helper('bankagency');
				$virInfo['acc_no'] = $vir->rVirNo;
				$virInfo['agency'] = getCenter_cd($vir->VIRTUAL_CENTERCD);
				$result['virtual'] = $virInfo;
			}

			$result['order'] = $order;
			$result['order_items'] = $orders;
			$result['orderer'] = $orderer;
			$result['recipient'] = $recipient;

		
			$data['data'] = $result;

			$data['json'] = json_encode($result);
			$this->load->view('shop/mobile/order_result_view1',$data);	
			return;
		} else {

			$this->load->view('shop/mobile/order_result_empty');	
			return;
		}


	}


	/*
	 * 공지 사항
	 */
	function noticeList(){

		$result['code'] = API_RESULT_FAIL;

		$this->db->order_by('id','desc');
		$query = $this->db->get('notice');
		$list = $query->result();

		if ($list){
			$result['result'] = $list;
			$result['code'] = API_RESULT_OK;
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}


	function newNotice($latest=0){

		// 공지
		$this->db->order_by('id','desc');
		$query = $this->db->get_where('notice',array('id >'=>$latest,'need_notify'=>'Y'),1,0);
		$notice = $query->row();

		// 미결제
		$uuid = $this->input->get('uuid');
		$waitPayments = false;
		if ($uuid){
			$this->db->select("order_code");
			$this->db->where("DATE_ADD(date_order, INTERVAL 1 HOUR) < NOW()");
			$this->db->where('order_state','WAIT_PAYMENT');
			$this->db->where('uuid',$uuid);
			$this->db->order_by('id','desc');
			$waitPayments = $this->db->count_all_results('orders');	
		}

		$result['code'] = API_RESULT_LATEST;
		if ($notice){
			$result['code'] = API_RESULT_OK;
			$result['result'] = $notice;
			
		}

		else if ($waitPayments > 0){
			$notice['title'] = '입금 요청 안내';
			$notice['id'] = -1;

			$result['code'] = API_RESULT_OK;
			$result['result'] = $notice;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}




	private function noticeDetail($id=null){

		if (!$id){
			echo "잘못된 접근입니다";
			return;
		}

		// NOTICE_ID_FOR_WAIT_PAYMENT 입금 안내일 경우 리다이렉트
		if ($id == '-1'){
			$uuid = $this->input->get('uuid');
			if ($uuid){
				$query = $this->db->get_where('orders',array('uuid'=>$uuid,'order_state'=>'WAIT_PAYMENT'));
				$wait = $query->row();

				if ($wait){
					$this->orderDetailView($wait->order_code);
					return;
				}
			}
		}
			
		$query = $this->db->get_where('notice',array('id'=>$id));
		$notice = $query->row();

		if (!$notice) return;

		$data['notice'] = $notice;

		$this->load->view('shop/mobile/notice_detail_view',$data);
	}


	/*
	 * 모바일 페이지
	 */

	 function mobile($uri=null,$param=null){


	 	switch ($uri) {
	 		case 'orderInfoView':{

	 			$this->load->view('shop/mobile/order_info_view');
	 		}
	 		break;
	 		case 'orderPaymentView':{

	 			$this->load->view('shop/mobile/order_payment_view');
	 		}
	 		break;
	 		case 'orderDetailView':{
	 		
	 			$this->orderDetailView($param);
	 		}
	 		break;
	 		case 'orderDetailView1':{
	 		
	 			$this->orderDetailView1($param);
	 		}
	 		break;
	 		case 'noticeDetailView':{
	 		
	 			$this->noticeDetail($param);
	 		}
	 		break;
	 		case 'legalView':{
	 			$this->load->view('shop/mobile/legal_view');
	 		}
	 		break;
	 		default:
	 			echo "잘못된 접근입니다";
	 		return;
	 	}
	 }




	 /*
	  * 좋아요
	  */
	 function likeUp(){
	 	$products_id = $this->input->get_post('products_id');

	 	$result['code'] = API_RESULT_FAIL;
		if (!$products_id){
			$result['code'] = API_RESULT_LACK_PARAMS;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}



		$this->db->trans_begin();
        $this->db->query('update products set likes = likes +1 where id='.$products_id);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else {
        	$this->db->trans_commit();	
        	$result['code'] = API_RESULT_OK;

        	$this->db->select('likes');
			$product = $this->db->get_where('products',array('id'=>$products_id))->row();
			$result['result'] = $product->likes;
        }


        $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
        


	 }



}


?>