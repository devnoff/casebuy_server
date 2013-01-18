<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Shop extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		
        $this->load->model('members_model');
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->database();
	}
	
	function index()
	{
        echo 'hi';
	}
	
	function memberObj(){
	    $username = get_cookie('loveholic_username');
	    
	    if ($username){
		    return $this->members_model->memberByUsername($username);
	    }
	    
	    return null;
    }
	
	
	/** 상품 후기 **/
	
	/* 후기 입력 */
	function addReview(){
		$this->load->model('products_model');
		$this->load->model('member_points_model');
	
		$products_id = $this->input->post('products_id');
		$nickname = strip_tags($this->input->post('nickname'));
		$rating = $this->input->post('rating');
		$content = strip_tags($this->input->post('content'));
		
		
		$result['success'] = false;
		$user = $this->memberObj();
		
		
		// 구매 이력 검사
		$hasPurchase = $this->products_model->hasPurchase($user->id, $products_id);
		if (!$hasPurchase){
			$this->output
				 ->set_content_type('application/json')
			 	 ->set_output(json_encode($result));
			return;
		}
		
		// 리뷰 존재 여부 검사
		$this->db->where(array('members_id'=>$user->id, 'products_id'=>$products_id));
		$written = $this->db->count_all_results('reviews');
		if ($written > 0){
			$this->output
				 ->set_content_type('application/json')
			 	 ->set_output(json_encode($result));
			return;
		}
		
		
		// 유효성 검사
		if ($nickname && $rating && $content){
			
			$data = array(
				'members_id'=>$user->id,
				'products_id'=>$products_id,
				'nickname'=>$nickname,
				'rating'=>$rating,
				'content'=>$content
			);
			
	        $this->db->trans_begin();
	        $this->db->insert('reviews', $data);
	        
	        if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	        } else {
	        
	        	$this->db->trans_commit();
	        	$result['success'] = true;
	        	
	        	// 포인트 지급
	        	$this->member_points_model->members_id = $user->id;
	        	$this->member_points_model->point = 100;
	        	$this->member_points_model->reason = 'EARN_TO_WRITE_REVIEW';
	        	$this->member_points_model->insert();
	        }
				
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	/* 후기 업데이트 */
	function updateReview(){
	
		$reviews_id = $this->input->post('reviews_id');
		$products_id = $this->input->post('products_id');
		$nickname = strip_tags($this->input->post('nickname'));
		$rating = $this->input->post('rating');
		$content = strip_tags($this->input->post('content'));
		
		$data = array(
				'products_id'=>$products_id,
				'nickname'=>$nickname,
				'rating'=>$rating,
				'content'=>$content
			);

		$result['success'] = false;		
		
		$this->db->where('id',$reviews_id);
		
		$this->db->trans_begin();
	    $this->db->update('reviews',$data);
	        
	    if ($this->db->trans_status() === FALSE)
	    {
	        $this->db->trans_rollback();
	    } else {
	     	$this->db->trans_commit();
	    	$result['success'] = true;
	    }
	    
	    
	    $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	/* 상품 후기 목록 */
	function reviewsProduct($offset=0, $limit=5){
		$products_id = $this->input->get('products_id');
		
		$result['success'] = false;
		
		// 데이터
		$sql = "select id, members_id, products_id, nickname, content, photo_path, rating, date_format(date_write,'%Y-%m-%e') date_write from reviews where products_id=".$products_id." order by id desc ";
		
		$sql .= "limit $offset,$limit ";
		
		$query = $this->db->query($sql);
		$reviews = $query->result();
		
		// 페이지네이션
		$this->load->library('pagination');

		$config['base_url'] = site_url().'/actions/shop/reviewsProduct';
		$config['uri_segment'] = 4;
		$this->db->where(array('products_id'=>$products_id));
		$config['total_rows'] = $this->db->count_all_results('reviews');
		$config['per_page'] = $limit;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';
		$this->pagination->initialize($config);
		$result['pagination'] = $this->pagination->create_links();
		
		
		if ($reviews){
			$result['success'] = true;
			$result['reviews'] = $reviews;
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
	}
		
	
	
	/** QnA **/
	
	/* QnA 목록 */
	function questionsProduct($offset=0, $limit=10){
		$products_id = $this->input->get('products_id', true);
		$members_id = $this->input->get('members_id', true);
		
		$user = $this->memberObj();
		if (!$user){
			$user = new stdClass();
			$user->id = -1;
		}
		
		$result['success'] = false;
		
		// 데이터
		$sql = "select id, members_id, products_id, (select title as product_name from products p where p.id=q.products_id) product_name, nickname, title, content, date_format(date_write,'%Y-%m-%d') date_write, is_private, family, orderby, step from questions q";
		
		
		if ($products_id)
			$sql .= " where products_id=".$products_id." and";
		else if ($members_id)	
			$sql .= " where members_id=".$members_id." and";
		else
			$sql .= " where";
		
		$sql .= " step = 0 order by family desc ";
		
		$sql .= "limit $offset,$limit ";
		
		$query = $this->db->query($sql);
		$questions = $query->result();
		
		$answers = array();
		foreach ($questions as $i){
			
			$i->locked = false;
			if ($i->members_id != $user->id && $i->is_private == 'Y'){
				$i->locked = true;
				$i->content = null;
			}
			
		
		
			$this->db->where(array('family'=>$i->family, 'step'=>1));
			$q = $this->db->get('questions');
			$row = $q->row();
			if ($row){
				
				unset($row->members_id);
				unset($row->nickname);
				unset($row->products_id);
				unset($row->title);
				
				$row->date_write = substr($row->date_write, 0, 10);
				$answers[$i->family] = $row;
				
				
				if ($i->locked)
					$row->content = null;
				
			}
		}
		
		// 페이지네이션
		$this->load->library('pagination');

		$config['base_url'] = site_url().'/actions/shop/questionsProduct';
		$config['uri_segment'] = 4;
		if ($products_id){
			$this->db->where(array('products_id'=>$products_id,'step'=>0));
		} else if ($members_id){
			$this->db->where(array('members_id'=>$members_id,'step'=>0));
		} else {
			$this->db->where(array('step'=>0));
		}
		
		$config['total_rows'] = $this->db->count_all_results('questions');
		$config['per_page'] = $limit;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';
		$this->pagination->initialize($config);
		$result['pagination'] = $this->pagination->create_links();
		
		
		if ($questions){
			$result['success'] = true;
			$result['questions'] = $questions;
			$result['answers'] = $answers;
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	/* QnA 입력 */
	function addQuestion(){
		$this->load->model('products_model');
	
		$products_id = $this->input->post('products_id');
		$nickname = strip_tags($this->input->post('nickname'));
		$title = strip_tags($this->input->post('title'));
		$content = strip_tags($this->input->post('content'));
		$is_private = $this->input->post('is_private');
		
		$result['success'] = false;
		
		$user = $this->memberObj();
		$members_id = $user?$user->id:null;
		
		// 유효성 검사
		if ($nickname && $title && $content){
			
			$data = array(
				'members_id'=>$members_id,
				'products_id'=>$products_id,
				'nickname'=>$nickname,
				'title'=>$title,
				'content'=>$content,
				'is_private'=>$is_private,
				'orderby'=>0,
				'step'=>0
			);
			
	        $this->db->trans_begin();
			
			// 질문 그룹 번호 생성	        
	        $this->db->select_max('family','max_family');
	        $m = $this->db->get('questions');
	        $max = $m->row()->max_family + 1;
			
			$this->db->set('family', $max);
			$this->db->set('date_write', 'NOW()', FALSE);
	        $this->db->insert('questions', $data);
	        
	        if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	        } else {
	        	$this->db->trans_commit();
	        	$result['success'] = true;
	        }
				
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}		



	/** 장바구니 **/
	
	/* 상품 장바구니에 담기 */
	function addToCart(){
	
		$member = $this->memberObj();
		
		$products_id = $this->input->get('products_id');
		$qty = $this->input->get('qty');
		$addToExistItem = $this->input->get('addExist', true);
		
		
		
		if (!$member){
			// 비회원 일경우 쿠키에서 정보를 가져옴
			
			$cart = get_cookie('loveholic_cart');
			
			$cart = json_decode($cart);
			if (!$cart){
				$cart = new stdClass();
			}
			
			$existItem = !empty($cart->$products_id);
			
			$cart_item;
			if (!$existItem){
				$cart_item['products_id'] = $products_id;
				$cart_item['qty'] = $qty;
			} else {
				if ($addToExistItem){
					$cart_item = $cart->$products_id;
					$q = $cart_item->qty;
					$cart_item->qty = $qty + $q;
				} else {
					echo 'exist';
					return;
				}
				
			}
			
			
			$cart->$products_id = $cart_item;
			
			$cookie = json_encode($cart);
			
			delete_cookie('loveholic_cart');
			set_cookie('loveholic_cart',$cookie, 66000);
			
			echo 'success';	
		} 
		else {
			// 회원일 경우 DB에서 정보를 가져옴
			
			$query = $this->db->get_where('carts', array('members_id'=>$member->id, 'products_id'=>$products_id));
			$cart_item = $query->row();
			
			if ($cart_item){
				if ($addToExistItem){
					
   					$this->db->trans_begin();
					$new_qty = $qty + $cart_item->qty;
					$this->db->where(array('members_id'=>$member->id,'products_id'=>$products_id));
					$this->db->update('carts',array('qty'=>$new_qty));
			
			    	if ($this->db->trans_status() === FALSE)
			        {
			            $this->db->trans_rollback();
			            echo 'failed';
			            return;
			        }
			
			        $this->db->trans_commit();
			        echo 'success';
			        return;

				} else {
					// 아이템 존재
					echo 'exist';
					return;
					
				}

			} else {
				$data = array(
					'members_id'=>$member->id,
					'products_id'=>$products_id,
					'qty'=>$qty
				);
				
				$this->db->trans_begin();
				$this->db->insert('carts',$data);
		
		    	if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            echo 'failed';
		            return;
		        }
		
		        $this->db->trans_commit();
				echo 'success';
				return;
			}
		}
		
		
		
	}
	
	/* 장바구니 상품 수량 업데이트 */
	function updateCartItem(){
	
		$member = $this->memberObj();
		
		$products_id = $this->input->get('products_id');
		$qty = $this->input->get('qty');
		
		if (!$member){
			/*
			 * 비회원 일경우 쿠키이용
			 */			
			$cart = get_cookie('loveholic_cart');
			delete_cookie('loveholic_cart');
			
			$cart = json_decode($cart);
			if (!$cart){
				echo '{"success":false}';
				return;
			}
			
			$cart_item;
			if (!empty($cart->$products_id)){
				$cart_item = $cart->$products_id;
				$cart_item->qty = $qty;
			} 
			
			$cart->$products_id = $cart_item;
			
			$cookie = json_encode($cart);
			
			set_cookie('loveholic_cart',$cookie, 66000);
			
			echo $cookie;
		}
		else {
			/*
			 * 회원 일경우 DB 이용
			 */
			$this->db->trans_begin();
			$this->db->where(array('members_id'=>$member->id,'products_id'=>$products_id));
			$this->db->update('carts',array('qty'=>$qty));
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            echo 'failed';
	            return;
	        }
	
	        $this->db->trans_commit();
	        echo 'success';
	        return;
		}
	
		
	}
	
	
	/* 장바구니 비우기 */
	function emptyCart(){
		
		$member = $this->memberObj();
		
		if (!$member){
			delete_cookie('loveholic_cart');
		} else {
			$this->db->trans_begin();
			$this->db->delete('carts',array('members_id'=>$member->id));
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            echo 'failed';
	            return;
	        }
	
	        $this->db->trans_commit();
	        echo 'success';
	        return;

		}
	}
	
	function cartEmpty(){
		
		$member = $this->memberObj();
		
		if (!$member){
			delete_cookie('loveholic_cart');
		} else {
			$this->db->trans_begin();
			$this->db->delete('carts',array('members_id'=>$member->id));
	
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            return false;
	        }
	
	        $this->db->trans_commit();
	        return true;

		}
	}
	
	
	
	
	/* 장바구니 아이템 지우기 */
	function removeCartItem(){
	
		$member = $this->memberObj();
		
		$products_id = $this->input->get('products_id');
		
		if (!$member){
			$cart = get_cookie('loveholic_cart');
			delete_cookie('loveholic_cart');
			$cart = json_decode($cart);
			
			if (!$cart){
				$cart = new stdClass();
			}
			
			if (!empty($cart->$products_id)){
				unset($cart->$products_id);
			}
			
			$cookie = json_encode($cart);
			
			set_cookie('loveholic_cart',$cookie, 66000);
			
			echo '{"success":true}';
		}
		else {
			$this->db->trans_begin();
			$this->db->delete('carts',array('members_id'=>$member->id, 'products_id'=>$products_id));

	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            echo '{"success":false}';
	            return;
	        }
	
	        $this->db->trans_commit();
	        echo '{"success":true}';
	        return;
		}
	
		
		
	}
	
	function testj(){
		echo get_cookie('loveholic_cart');
	}
	
	
	/** 찜 **/
	
	/* 찜 상품 목록 */
	function wishlist(){
		$user = $this->memberObj();
		$members_id = $user->id;
		$this->load->model('wishlist_model');
		
		$result['wishlist'] = $this->wishlist_model->wishlistByMember($members_id);
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
		
	}
	
	/* 상품 찜하기 */
	function addToWishList(){
		$user = $this->memberObj();
		$members_id = null;
		if ($user) $members_id = $user->id;
		$products_id = $this->input->post('products_id');
		
		if (!$user){
			echo '{"success":false, "reason":"로그인 후 이용이 가능합니다."}';
			return;
		}
		
		else if (!$products_id){
			echo '{"success":false, "reason":"잘 못 된 접근 입니다."}';
			return;
		}
		
		$this->db->where(array('products_id'=>$products_id, 'members_id'=>$members_id));
		$exist = $this->db->count_all_results('wishlist');
		if ($exist > 0){
			echo '{"success":false, "reason":"이미 추가된 상품입니다."}';
			return;
		}
		
		
		$this->load->model('wishlist_model');
		$this->wishlist_model->members_id = $members_id;
		$this->wishlist_model->products_id = $products_id;
		
		if ($this->wishlist_model->insert()){
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	
	/* 찜 상품 삭제 하기 */
	function removeWishListItem(){
		$user = $this->memberObj();
		$members_id = null;
		if ($user) $members_id = $user->id;
		$products_id = $this->input->post('products_id');
		
		if (!$user){
			echo '{"success":false, "reason":"로그인 후 이용이 가능합니다."}';
			return;
		}
		
		else if (!$products_id){
			echo '{"success":false, "reason":"잘 못 된 접근 입니다."}';
			return;
		}
		
		
		$this->load->model('wishlist_model');
		$this->wishlist_model->members_id = $members_id;
		$this->wishlist_model->products_id = $products_id;
		
		if ($this->wishlist_model->delete()){
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	
	
	/** 우편번호 **/
	function zipcode(){
		$keyword = $this->input->get('keyword');
		
		$this->load->model('zipcode_model');
		
		$result = $this->zipcode_model->zipcodesByKeyword($keyword);
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	
	
	/** 구매하기 **/
	function inputOrder(){
		// 필요 라이브러리
		$this->load->model('member_points_model');
		$this->load->model('orders_model');
	
		//
		$result['success'] = false;
	
	
	    /* 데이터 가져 오기 */
	    
		// 사용 포인트
		$usingPoint = $this->input->post('using_point');
		
		// 상품 목록
		$products = $this->input->post('products_id');
		
		// 수량 목록
		$quantities = $this->input->post('quantity');
		
		
		/* 배송비 정보 가져오기 */
		$base_delivery_fee = 2500;
		$ship_zipcode = implode('-',$this->input->post('recipient_postcode'));
		$zip = $this->db->get_where('zipcode',array('zipcode'=>$ship_zipcode))->row();
		if ($zip){
			$base_delivery_fee = $zip->delivery_fee;
		}
		
		
		
		/* 회원 정보 가져오기 */
		
		// 주문자 아이디 : 세션의 접속자 정보와 비교
		$member = $this->memberObj();
		$members_id = $this->input->post('members_id');
		if ($member && $member->id != $members_id){
			$result['reason'] = "잘못 된 접근 입니다.";
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		} else if (!$member && $members_id) {
			$result['reason'] = "세션이 만료 되었거나, 잘못 된 접근 입니다.";
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		
		/* 주문 정보 입력 */

		// 앱 비회원 식별
		$uuid = $this->input->post('uuid');
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
			$result['reason'] = "주문 입력 실패";
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		
		// 주문 상세 입력 
		for ($i = 0; $i < count($products); $i++){
			for ($i = 0; $i < count($products); $i++){
			
				$this->db->select('id, title, sales_price, point_rate, fixed_point');
				$query = $this->db->get_where('products', array('id'=>$products[$i]));
				$product = $query->row();
				
				$item['orders_id'] = $order_id;
				$item['products_id'] = $product->id;
				$item['product_name'] = $product->title;
				$item['item_price'] = $product->sales_price;
				$item['qty'] = $quantities[$i];
				$item['item_total_amount'] = $item['qty'] * $item['item_price'];
				
				if (!$this->orders_model->insertOrderItem($item)){
					$rollback = true; //  실패시 롤백
				}
			}
		}
		
		
		// 포인트 내역 입력 (회원)
		if ($members_id) {
			// 결제 시 포인트 차
			// $member_point = $this->member_points_model->pointsByMember($members_id);
			
			// $member_point = intVal($member_point);
			
			// if ($member_point < $usingPoint){
			// 	$result['reason'] = "잘못 된 접근 입니다.(포인트 조작)";
			// 	$this->output
			// 		 ->set_content_type('application/json')
			// 		 ->set_output(json_encode($result));
			// 	return;
			// }
			
			// $this->member_points_model->members_id = $members_id;
			// $this->member_points_model->ref_orders_id = $order_id;// 주문번호
			
			// // 사용 포인트 입력
			// if ($usingPoint > 0){
			// 	$this->member_points_model->point = -$usingPoint;
			// 	$this->member_points_model->reason = 'SPEND_FOR_PAYMENT';
			// 	if (!$this->member_points_model->insert()) $rollback = true;	
			// }			
			
/* 결제 후 입력으로 변경 
			// 적립 포인트 입력
			if ($pricePoint['sum_point'] > 0){
				$this->member_points_model->point = $pricePoint['sum_point'];
				$this->member_points_model->reason = 'EARN_TO_BUY';
				if (!$this->member_points_model->insert()) $rollback = true;	
			}
*/
			
		}
		

		// 주문자 정보
		$orderer['orders_id'] = $order_id;
		$orderer['name'] = $this->input->post('orderer_name');
		$orderer['zipcode'] = implode('-',$this->input->post('orderer_postcode'));
		$orderer['address'] = implode(' ',$this->input->post('orderer_address'));
		$orderer['telephone'] = implode('-',$this->input->post('orderer_telephone'));
		$orderer['mobile'] = implode('-',$this->input->post('orderer_mobile'));
		$orderer['email'] = $this->input->post('orderer_email');
		
		if (!$this->orders_model->insertOrderCustomer($orderer)) $rollback = true;
		
		// 배송지 정보
		$recipient['orders_id'] = $order_id;
		$recipient['name'] = $this->input->post('recipient_name');
		$recipient['zipcode'] = implode('-',$this->input->post('recipient_postcode'));
		$recipient['address'] = implode(' ',$this->input->post('recipient_address'));
		$recipient['telephone'] = implode('-',$this->input->post('recipient_telephone'));
		$recipient['mobile'] = implode('-',$this->input->post('recipient_mobile'));
		$recipient['msg'] = $this->input->post('recipient_msg');
	
		if (!$this->orders_model->insertOrderDelivery($recipient)) $rollback = true;
		
	
		
		$this->db->select('order_code');
		$query = $this->db->get_where('orders',array('id'=>$order_id));
		$order_code = $query->row();
		
		// 실패 체크
		if ($rollback){
			$this->orders_model->deleteOrder($order_id);
			$result['reason'] = $reason;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		} else {
			
			$this->cartEmpty();
			$result['success'] = true;
			$result['orders_id'] = $order_id;
			$result['order_code'] = $order_code->order_code;
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
			return;
		}
		
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
	 * 주문 삭제
	 */
	 
	function removeOrder($orders_id=null){
		
		if (!$orders_id)
			$orders_id = $this->input->get_post('orders_id');
		
		$result['success'] = false;
		if (!$orders_id){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		
		$this->load->model('orders_model');
		
		if ($this->orders_model->deleteOrder($orders_id)){
			$result['success'] = true;
		} 
		
		$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		
	}
	
	
	

	// function rrrr($id=1){
	// 	$this->db->where_in('order_state',array('B4PAYMENT','CANCEL_DONE'));
	// 	$orders = $this->db->get('orders')->result();
		
	// 	if ($orders){
	// 		foreach ($orders as $order){
	// 			$o_id = $order->id;
	// 			echo $o_id;
	// 			$this->removeOrder($o_id);
	// 		}
	// 	}
		
	// }

	
	/*
	*
	*
	*
	*
	*
	* 주문 상태 쿠키 임시 저장 
	* active x  설치시 주문 상태 저장
	*
	*/
	function saveOrderFormState(){
		$data = $this->input->post();
		
		set_cookie('loveholic_orderer',$this->input->post('orderer'),0);
		set_cookie('loveholic_recipient',$this->input->post('recipient'),0);
		set_cookie('loveholic_shouldRestoreOrderForm','true',0);
				
				
		var_dump($data);
	}
	
	
	
	
	
	/*
	*
	*
	*
	*/
	function productDetail(){
		$products_id = $this->input->get('products_id');
		
		$this->db->select('description');
		$query = $this->db->get_where('products', array('id'=>$products_id));
		$content = $query->row()->description;
		
		$data['content'] = $content;
		
		$this->load->view('shop/mobile/product_detail_layout',$data);
	}
	


	
	
}
?>