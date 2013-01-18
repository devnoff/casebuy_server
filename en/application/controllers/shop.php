<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');

class Shop extends CI_Controller {

    var $secureMgr;
    
    var $PAYMENT_TYPE = array('card'=>'신용카드','virtual'=>'가상계좌','iche'=>'계좌이체','hp'=>'휴대폰 결제');

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->helper('cookie');
        $this->load->helper('url');
     	$this->load->model('product_categories_model');   
     	$this->load->model('products_model');
     	$this->load->model('members_model');   
     	
	}
	
	/* 보안 객체 */
    function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }
    
    private function checkSession(){
	    $none_member_session = get_cookie('loveholic_none_member');
	    
	    $username = get_cookie('loveholic_username');
	    $sessionKey = get_cookie('loveholic_sessionKey');

	    
	    // 세션이 유효할 경우
	    if ($none_member_session != null || ($username && $sessionKey && $this->secure()->sessionValid($username,$sessionKey))){
	        return true;
	    }
	    
	    // 유효하지 않을 경우
	    return false;
    }
    
    function memberObj(){
	    $username = get_cookie('loveholic_username');
	    
	    if ($username){
		    return $this->members_model->memberByUsername($username);
	    }
	    
	    return null;
    }
    
    function sessionValid(){
    	
	    if (!$this->checkSession()){
		    //redirect('member/logout/');
		    $this->emptySession();
	    }
    }
		
	private function emptySession(){
		delete_cookie('loveholic_username');
	    delete_cookie('loveholic_sessionKey');
	    delete_cookie('none_member_session');
	    delete_cookie('loveholic_none_member');
	    
	    
	    // redirect('member/checkAdult');
	}
		
	function index()
	{
	    $this->sessionValid();  

	    redirect('/shop/main');
	    
	}
	
	/* 카테고리 뷰 */
	private function categoryView($category_id=null, $sub_category_id=null){
	
	    $data = null;
	    
	    $c = $this->product_categories_model->categories();
	    $sub = array();
	    $hide = array();
	    foreach($c as $key=>$i){
	    	if ($i->hidden != 'YES'){
		    	$s = $this->product_categories_model->categoriesByParentId($i->id);
		    	
		    	$sub[$i->family] = $s;	
	    	} else {
		    	unset($c[$key]);
	    	}
	    }
	    
	    
	    $data['curr_category'] = $category_id;
	    $data['curr_sub_category'] = $sub_category_id;
	    $data['categories'] = $c;
	    $data['sub_categories'] = $sub;
	    
	    return $this->load->view('shop/category_menu_view', $data, true);
	}
	
	/* 레이아웃 로드 뷰 */
	private function loadView($sideView, $contentView, $data, $meta=array()){
		/* 사용자 객체 */
		$data['member'] = $this->memberObj();
		
		/* 사이드 뷰 */
		$data['sideView'] = $sideView;
		
		/* 본문 */
		$data['contentView'] = $contentView;
		
		
		/* 메타 태그 */
		if (empty($meta['Subject']))
			$meta['Subject'] = array('name' => 'Subject', 'content' => '어른 쇼핑몰');

		if (empty($meta['Title']))
			$meta['Title'] = array('name' => 'Title', 'content' => '색콤달콤 - '.$data['title']);

		if (empty($meta['Description']))
			$meta['Description'] = array('name' => 'Description', 'content' => '성인용품 쇼핑몰 색콤달콤입니다. 콘돔, 마사지젤, 러브젤, 입욕제, 남성단련, 세정제, 란제리 등을 판매하고 있습니다.');

		if (empty($meta['Keywords']))
			$meta['Keywords'] = array('name' => 'Keywords', 'content' => '어른, 쇼핑몰, 콘돔, 마사지젤, 기구, 자위, 오카모토, 펀펀데이, 사가미, 커플, 부부, 남친, 여친, 섹시, 아내, 남편, 후지라텍스, 한국라텍스, 롱텍스, 입욕젤, 마사지젤, 페로몬, 향수, 세정제, 남성,여성, 단련제, 란제리, 색콤달콤, 새콤달콤');

		if (empty($meta['Author']))
			$meta['Author'] = array('name' => 'Author', 'content' => 'YU LAB');

		if (empty($meta['robots']))
			$meta['robots'] = array('name' => 'robots', 'content' => 'index,follow');


	    
	    $this->load->helper('html');
	    if (!empty($data['meta'])){
		    $meta = array_merge($data['meta'],$meta);
	    }
	    
	    $data['meta'] = meta($meta);
		
		$this->load->view('shop/layouts/shop_layout', $data);
		return;
	}
	
	/* 메인 페이지 */
	function main(){
		$this->sessionValid(); 
	
	    /* 카테고리 뷰 */
		$sideView = $this->categoryView();

		/* 인기 상품 */
		$data['popular'] = $this->products_model->productsTopSales(6,0);
		
		/* 추천 상품 */
		$data['recomm'] = $this->products_model->productsWebMain(5,0);
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/main_view', $data, true);
		
		
		/* 메타 태그 */
		$d['meta'] = array(
			array('name' => 'Classification', 'content' => '홈'),
	    );
		
		$d['title'] = '홈';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 상품 리스트 */
	function product_list(){
		$this->sessionValid(); 
	
		$category_id = $this->input->get('c_id');
		$sub_category_id = $this->input->get('sc_id');
		
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView($category_id, $sub_category_id);
		
		/* 상품 목록 */
		$data['products'] = $this->products_model->sale_products(100,0,$category_id, $sub_category_id);
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/product_list_view', $data, true);
		
		
		/* 메타 태그 */
		$category_name = $category_id?$this->db->get_where('product_categories',array('id'=>$category_id))->row()->category_name:'';
		$sub_category_name = $sub_category_id?$this->db->get_where('product_categories',array('id'=>$sub_category_id))->row()->category_name:'';
		
		$d['meta']['Classification'] = array('name' => 'Classification', 'content' => $category_name.($sub_category_id?'/'.$sub_category_name:''));
		$d['meta']['Description'] = array('name' => 'Description', 'content' => $category_name.($sub_category_id?'/'.$sub_category_name:'').' 카테고리 내 상품 목록 입니다.');
	    
	    /* 타이틀 */
	    $d['title'] = $category_name.' '.$sub_category_name;
	
		$this->loadView($sideView, $contentView, $d, $d['meta']);
		
	}
	
	/* 상품 검색 */
	function product_search(){
		$this->sessionValid(); 
	
		$keyword = $this->input->get('keyword');
		
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView(null, null);
		
		/* 상품 목록 */
		$data['products'] = $this->products_model->saleProductsSearch($keyword);
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/product_list_view', $data, true);
		
		/* 타이틀 */
		$d['title'] = '제품검색:'.$keyword;
		
		$this->loadView($sideView, $contentView, $d);
		
	}
	
	/* 상품 상세 */
	function product(){
		$this->sessionValid(); 
	    
		$product_id = $this->input->get('id');
		$category_id = $this->input->get('c_id');
		$sub_category_id = $this->input->get('sc_id');
		
		/* 상품 정보 */
		$data['product'] = $this->products_model->productById($product_id);
		$sub_category_id = $data['product']->sub_category_id;
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView($data['product']->categories_id, $sub_category_id);
		
		/* 회원 정보 */
		$data['member'] = $this->memberObj();
		
		/* 구매 이력 */
		$data['has_purchase'] = false;
		$members_id = empty($data['member'])?null:$data['member']->id;
		
		$data['is_wishItem'] = false;
		if ($members_id){
			// 구입 여부
			$data['has_purchase'] = $this->products_model->hasPurchase($members_id, $product_id);
			
			// 찜 여부
			$q = $this->db->get_where('wishlist', array('members_id'=>$members_id, 'products_id'=>$product_id));
			$data['is_wishItem'] = count($q->result())>0?true:false;
		}
		
		/* 기존 리뷰 */
		$data['review'] = null;
		if ($members_id){
			$query = $this->db->get_where('reviews', array('members_id'=>$members_id, 'products_id'=>$product_id));
			$data['review'] = $query->row();
		}
		
		if ($data['review']==null) {
			$data['review'] = new stdClass();
			$data['review']->id = '';
			$data['review']->rating = '5';
			$data['review']->nickname = '';
			$data['review']->content = '';
		}
		
		
		/* 리뷰 카운트 */
		$this->db->where('products_id',$product_id);
		$data['review_cnt'] = $this->db->count_all_results('reviews');
		
		/* QnA 카운트 */
		$this->db->where(array('products_id'=>$product_id,'step'=>0));
		$data['qna_cnt'] = $this->db->count_all_results('questions');
		
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/product_detail_view', $data, true);
		
		

		/* 메타 태그 */
		$category_name = $category_id?$this->db->get_where('product_categories',array('id'=>$category_id))->row()->category_name:'';
		$sub_category_name = $sub_category_id?$this->db->get_where('product_categories',array('id'=>$sub_category_id))->row()->category_name:'';

		/* 타이틀 */
	    $product_name = $data['product']?$data['product']->title : '';
	    $d['title'] = $category_name.' '.$sub_category_name.' '.$product_name;
		
		$d['meta']['Classification'] = array('name' => 'Classification', 'content' => $category_name.($sub_category_id?'/'.$sub_category_name:''));
		$d['meta']['Description'] = array('name' => 'Description', 'content' => $product_name.' 입니다 :) '.$data['product']->app_description);
	    
	    
		
		$this->loadView($sideView, $contentView, $d, $d['meta']);
		
	}
	
	
	private function cartItems(){
		$member = $this->memberObj();
	
		if (!$member){
			/*
			 * 비회원일 경우 쿠키 이용
			 */	
			$cart = get_cookie('loveholic_cart');
			if ($cart){
				$cart = json_decode($cart);
				foreach($cart as $i){
					$id = $i->products_id;
					$product = $this->products_model->productSimple($id);
					$i->product = $product;
				}
			}
			
			return $cart;	
		} else {
			/*
			 * 회원일 경우 DB 이용
			 */	
			 
			$query = $this->db->get_where('carts',array('members_id'=>$member->id));
			$cart = $query->result();
			
			foreach($cart as $i){
				$id = $i->products_id;
				$product = $this->products_model->productSimple($id);
				$i->product = $product;
			}
			
			return $cart;
		}
		
	}
	
	private function buyNowItem(){
		$item = get_cookie('loveholic_buy_now');
		if ($item){
			$items = json_decode($item);
			
			foreach ($items as $i){
				$i->product = $this->products_model->productSimple($i->products_id);
			}
		}
		return $items;
	}
	
	private function setBuyNowItem($items){
		delete_cookie('loveholic_buy_now'); 
		
		set_cookie('loveholic_buy_now',json_encode($items),0);
	}
	

	/* 장바구니 */
	function cart(){
		$this->sessionValid(); 
		
		
		/* 회원 정보 */
		$member = $this->memberObj();
		$members_id = !$member?'':$member->id;
		$data['member'] = $member;
		$data['members_id'] = $members_id;
		
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		/* 장바구니 목록 */
		$data['cart_items'] = $this->cartItems();
		
		$data['type'] = 'cart';

		
		// 상품이 없을 경우 
		if (!$data['cart_items'] || count((array)$data['cart_items']) < 1){
			$contentView = $this->load->view('shop/cart_empty_view', null, true);
		} else {
			/* 본문 자식 블록 */
		    $data['page_title'] = '장바구니';
		    $data['orderer_info_view'] = ''; // 주문자, 배송지 정보
		    $data['user_point_view'] = ''; // 사용자 적립금 도구
		    $data['none_member_policy_view'] = ''; // 비회원 약관 뷰
		    $data['none_member_login_view'] = '';
		    $data['action_btn_view'] = $this->load->view('shop/template/cart_purchase_btn_tpl_block', $data, true); // 구매하기 버튼
		    
		    /* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/template/cart_list_tpl_block', $data, true);
		}
	    
	    
	   /* 타이틀 */
		$d['title'] = '장바구니';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	/* 주문 */
	function order(){
		$this->sessionValid(); 
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		/* 회원 정보 */
		$member = $this->memberObj();
		$members_id = !$member?'':$member->id;
		$data['member'] = $member;
		$data['members_id'] = $members_id;
		
		
		/* 주문 종류 */
		$type = $this->input->get('type',true);
		
		/* 로그인 확인 */
		$asked = $this->input->get('asked',true);
		
		
		$data['type'] = $type;
		// 바로 구메 일 경우
		if ($type == 'buy_now'){

			/* 주문 상품 목록 */				
			$products_id = $this->input->get('products_id', true);
			$qty = $this->input->get('qty',true);
			
			$items = null;
			if ($products_id && $qty){
				$products_id = $this->input->get('products_id');
				$qty = $this->input->get('qty');
				
				
				$item = new stdClass();
				$item->products_id = $products_id;
				$item->qty = $qty;
		
				$items = new stdClass();
				$items->$products_id = $item;
				
				$this->setBuyNowItem($items);
				
				$item->product = $this->products_model->productSimple($products_id);
			} else {
				$items = $this->buyNowItem();
			}
			
			$data['cart_items'] = $items;
			
			if (!$items){	
				echo '잘못 된 접근입니다';
				return;	
			}
			
		} 
		
		// 카트 구매 일 경우
		else if ($type == 'cart'){
			// 주문 상품 목록 장바구니에서 가져오기
			$data['cart_items'] = $this->cartItems();
		}
		
		// 상품이 없을 경우
		if ($data['cart_items'] == null || count($data['cart_items']) < 1){
			redirect('shop');
		}
		
		
		if (!$members_id && !$asked){
			redirect('shop/orderNoMember/'.$type);
			return;
		}
		
		
		
		
		$deliveryInfo['member'] = $member;
		$deliveryInfo['restore'] = false;
		$deliveryInfo['orderer'] = null;
		$deliveryInfo['recipient'] = null;
		
		$shouldRestore = get_cookie('loveholic_shouldRestoreOrderForm');
		if ($shouldRestore || $shouldRestore == 'true' || $shouldRestore == 1){
			$deliveryInfo['restore'] = true;
			$deliveryInfo['orderer'] = get_cookie('loveholic_orderer');
			$deliveryInfo['recipient'] = get_cookie('loveholic_recipient');
			
			delete_cookie('loveholic_shouldRestoreOrderForm');
			delete_cookie('loveholic_orderer');
			delete_cookie('loveholic_recipient');
			
		}
		
		$deliveryInfo['orderer_name'] = '';
		$deliveryInfo['orderer_postcode'] = array('','');
		$deliveryInfo['orderer_address'] = array('','');
		$deliveryInfo['orderer_telephone'] = array('','','');
		$deliveryInfo['orderer_mobile'] = array('','','');
		$deliveryInfo['orderer_email'] = '';
		
		$deliveryInfo['recipient_name'] = '';
		$deliveryInfo['recipient_postcode'] = array('','');
		$deliveryInfo['recipient_address'] = array('','');
		$deliveryInfo['recipient_telephone'] = array('','','');
		$deliveryInfo['recipient_mobile'] = array('','','');
		$deliveryInfo['recipient_msg'] = '';
		$deliveryInfo['orderer_delivery_fee'] = 2500;
		$deliveryInfo['recipient_delivery_fee'] = 2500;
		
		if ($member){
			$this->load->model('orders_model');
			$orders_id = $this->orders_model->orderIdByMember($member->id);
			
			if ($orders_id){
				$orderer = $this->orders_model->orderCustomer($orders_id);
				$recipient = $this->orders_model->orderDelivery($orders_id);
				
				$deliveryInfo['orderer_name'] = $orderer->name;
				$deliveryInfo['orderer_postcode'] = explode('-',$orderer->zipcode);
				$addr = explode(' ',$orderer->address);
				$orderer_address[0] =  $addr[0].' '.$addr[1].' '.$addr[2];
				unset($addr[0]);unset($addr[1]);unset($addr[2]);
				$orderer_address[1] = implode(' ',$addr);
				
				$deliveryInfo['orderer_address'] = $orderer_address;
				
				$deliveryInfo['orderer_telephone'] = explode('-',$orderer->telephone);
				$deliveryInfo['orderer_mobile'] = explode('-',$orderer->mobile);
				$deliveryInfo['orderer_email'] = $orderer->email;
				
				
				
				$deliveryInfo['recipient_name'] = $recipient->name;
				$deliveryInfo['recipient_postcode'] = explode('-',$recipient->zipcode);
				$addr = explode(' ',$recipient->address);
				$recipient_address[0] =  $addr[0].' '.$addr[1].' '.$addr[2];
				unset($addr[0]);unset($addr[1]);unset($addr[2]);
				$recipient_address[1] = implode(' ',$addr);
				
				$deliveryInfo['recipient_address'] = $recipient_address;
				
				$deliveryInfo['recipient_telephone'] = explode('-',$recipient->telephone);
				$deliveryInfo['recipient_mobile'] = explode('-',$recipient->mobile);
				$deliveryInfo['recipient_msg'] = $recipient->msg;

				$this->load->model('zipcode_model');
		 		$zip = $orderer->zipcode;
		 		$addr = $this->zipcode_model->addressByZipcode($zip);
		 		$deliveryInfo['orderer_delivery_fee'] = $addr->delivery_fee;

		 		$zip = $recipient->zipcode;
		 		$addr = $this->zipcode_model->addressByZipcode($zip);
		 		$deliveryInfo['recipient_delivery_fee'] = $addr->delivery_fee;
			}
			
		}
		
		$data['orderer_info_view'] = $this->load->view('shop/template/cart_order_delivery_tpl_block', $deliveryInfo, true); //  주문자, 배송지 정보 뷰
		$data['user_point_view'] = ''; // 사용자 적립금 도구
		$data['none_member_policy_view'] = ''; // 비회원 약관
		$data['none_member_login_view'] = '';
		
		$userData['member'] = $member;
		$userData['cart_items'] = $data['cart_items'];
		if ($members_id){
			$this->load->model('member_points_model');
			$userData['user_point'] = number_format($this->member_points_model->pointsByMember($members_id),2);
		
			// 회원 일경우 	
			$data['user_point_view'] = $this->load->view('shop/template/cart_user_point_tpl_block', $userData, true);
		} else {
			// 비회원 일 경우
			$data['none_member_policy_view'] = $this->load->view('shop/template/cart_none_member_policy_tpl_block', null, true);
		}
		
		// 결제 버튼
		$data['action_btn_view'] = $this->load->view('shop/template/cart_payment_btn', $userData, true); // 구매하기 버튼
	    
	    /* 본문 블록 */
	    $data['page_title'] = '상품구매';
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/template/cart_list_tpl_block', $data, true);
		
		
		/* 타이틀 */
		$d['title'] = '주문';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 비히원 로그인 화면 */
	function orderNoMember($type=null){
		
		$data['type'] = $type;
		$data['members_id'] = '';
		
		if (!$type) return;
		
		else if ($type == 'cart'){
		
			$data['cart_items'] = $this->cartItems();
			$btnData['order_url'] = site_url('shop/order?type=cart&asked=true');
			
		} else if ($type == 'buy_now'){
		
			$data['cart_items'] = $this->buyNowItem();
			$btnData['order_url'] = site_url('shop/order?type=buy_now&asked=true');
			
		}
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		/* 본문 블록 */
		$data['orderer_info_view'] = '';
		$data['user_point_view'] = ''; // 사용자 적립금 도구
		$data['none_member_policy_view'] = '';
		$data['page_title'] = '상품구매';

	    $data['action_btn_view'] = ''; // 구매하기 버튼
	    
	    $btnData['type'] = $type;
	    $data['none_member_login_view'] = $this->load->view('shop/template/cart_none_member_login_tpl_block', $btnData, true);
	    
	    /* 본문 블록 */
	    $data['page_title'] = '상품구매';
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/template/cart_list_tpl_block', $data, true);
		
		/* 타이틀 */
		$d['title'] = '주문';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	
	/* 비회원 주문 조회 화면 */
	function orderQuery($uri=null){
		if ($uri=='result'){
			$this->orderQueryResult();
			return;
		}
		
		if ($this->memberObj()){
			redirect('shop');
		}
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    $contentData['redirect_url'] = site_url();
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/order_query_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '주문조회';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 비회원 주문 조회 결과 화면 */
	private function orderQueryResult(){
		$this->load->model('orders_model'); 
	
	    // Variables

	    $contentData;
	    $order_items = array();
	    
	    $order_code = $this->input->get('order_code');
	    
	    /* 주문 목록 */
	    $orders = $this->orders_model->orderByOrderCode($order_code);
	    
	    if ($orders){
		    redirect('shop/orderDetail?order_code='.$order_code);
	    } else {
			
			/* 타이틀 */
			$d['title'] = '주문 조회';
			
			/* 카테고리 뷰 */
			$sideView = $this->categoryView();
			
			/* 본문 컨텐츠 */
			$contentView = "<center>해당 주문번호의 주문내역이 존재하지 않습니다. 주문번호를 확인하신 후 다시 조회해주세요.</center>";
			
			$this->loadView($sideView, $contentView, $d);
	    }

	}
	
	
	/* 주문 결과 화면 */
	function orderResult(){
		$this->load->model('orders_model');
	
		$result = $this->input->post('result');
		$orders_id = $this->input->post('orders_id');
		
		
		$data = array();
		$data['order'] = $this->orders_model->order($orders_id);
		$data['order_items'] = $this->orders_model->orderItems($orders_id);
		$data['order_delivery'] = $this->orders_model->orderDelivery($orders_id);
		$data['order_customer'] = $this->orders_model->orderCustomer($orders_id);
		$data['member'] = $this->memberObj();
		$data['orders_id'] = $orders_id;
		
		$this->db->order_by('date_added','desc');
		$query = $this->db->get_where('payments',array('rOrdNo'=>$orders_id));
		$data['payment'] = $query->row();
		
		
		/* 타이틀 */
		$d['title'] = '결제 성공';
		
		// 주문 결제 성공
		if ($result == 'success'){
			/* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/order_complete_view', $data, true);
		}
		
		// 가상 계좌 결제 - 입금확인 필요
		else if ($result == 'virtual'){
			/* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/order_virtual_view', $data, true);
			
			$d['title'] = '가상 계좌';
		}
		
		// 결제 실패
		else {
			
			
			$this->db->select('rResMsg');
			$this->db->order_by('id','desc');
			$query = $this->db->get_where('payments', array('rOrdNo'=>$orders_id));
			$log = $query->row();
			
			$data['result_msg'] = '';
			if ($log){
				$msg = $log->rResMsg;
				$data['result_msg'] = $msg;
			}
			
			
			/* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/order_failed_view', $data, true);


			/* 타이틀 */
			$d['title'] = '결제 실패';
		}
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		
		
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	
	
	function login(){
	
		$member = $this->memberObj();
		if ($member)
			redirect('shop/main');
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    $contentData['redirect_url'] = $this->input->server('HTTP_REFERER', TRUE);//site_url();
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/login_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '로그인';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	function join($step = 0){
	
		$member = $this->memberObj();
		
		if ($member){
			redirect('/shop');
		}
		
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		$contentData = null;
		
		$contentView;
		if ($step == 0){
			/* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/join_policy_view', $contentData, true);		
			$ref = $this->input->server('HTTP_REFERER', TRUE);
			set_cookie('loveholic_join_ref',$ref, 0);
			
		} else if ($step == 1){
			
			$contentData['ref_to'] = get_cookie('loveholic_join_ref');
			
			/* 본문 컨텐츠 */
			$contentView = $this->load->view('shop/join_form_view', $contentData, true);	
		}
		
		/* 타이틀 */
		$d['title'] = '회원가입';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	
	/** 모든 QnA **/
	function qna(){
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    $contentData['member'] = $this->memberObj();
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/qna_list_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = 'Q&A';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	/** 내 메뉴 **/
	
	function my($sub=null){
	
		$user = $this->memberObj();
		if (!$user){
			redirect('shop');
			return;
		}
	
		switch($sub){
			case 'order_list':
				$this->orderList();
				
			break;
			case 'wishlist':
				$this->wishList();
				
			break;
			case 'info':
				$this->myInfo();
			break;
			case 'point':
				$this->myPoint();
			break;
			case 'qna':
				$this->myQna();
			break;
			default:
				redirect('shop/my/order_list');
			break;
		}
		
	}
	
	/* 주문 내역 보기 */
	private function orderList(){
		$this->load->model('orders_model'); 
		
		
		$all = $this->input->get('all');
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	
	    // Variables
	    $user = $this->memberObj();
	    $contentData;
	    $members_id = $user !=null ? $user->id : null;
	    $order_items = array();
	    
	    /* 주문 목록 */
	    if ($all){
			$orders = $this->orders_model->ordersOldByMemberId($members_id);    
	    } else {
		    $orders = $this->orders_model->ordersByMemberId($members_id);
	    }
	    
	    
	    if ($orders){
	    	foreach($orders as $i){
		    	$orders_id = $i->id;
	   		 	$orderItems = $this->orders_model->orderItems($orders_id);
	   	 		$order_items[$orders_id] = $orderItems;
		    }
	    }
	    
	    $contentData['orders'] = $orders;
	    $contentData['order_items'] = $order_items;
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/my_order_list_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '주문내역보기';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 주문 상세 보기 */
	function orderDetail(){
		$this->load->model('orders_model'); 
		
		$order_code = $this->input->get('order_code');
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		
		$order = $this->db->get_where('orders',array('order_code'=>$order_code))->row();
		
		if ($order){
			$orders_id = $order->id;
			
			$data = array();
			$data['order'] = $order;
			$data['order_items'] = $this->orders_model->orderItems($orders_id);
			$data['order_delivery'] = $this->orders_model->orderDelivery($orders_id);
			$data['order_customer'] = $this->orders_model->orderCustomer($orders_id);
			$data['orders_id'] = $orders_id;
			$data['member'] = $this->memberObj();
			
			$this->db->select('customer_text');
			$order_state = $this->db->get_where('order_states',array('key'=>$order->order_state))->row();
			$data['order_state'] = $order_state->customer_text;
			
			$this->db->where_not_in('state',array('FAILED'));
			$this->db->order_by('date_modified','desc');
			$query = $this->db->get_where('payments',array('rOrdNo'=>$orders_id));
			$payment = $query->row();
			
			$data['paid_date'] = null;
			
			$data['payment'] = $payment;
			if (!empty($payment)){
				
				$method = $payment->payment_method;
				$data['payment_type'] =  $this->PAYMENT_TYPE[$method];
				$data['payment_detail'] = '';
				$data['paid_date'] = $payment->date_modified;
				
				$amt = number_format(floatVal($payment->rAmt),2);
				
				if ($method == 'card'){
					$cardCode = floatVal($payment->rCardCd);
					$cardAgent = $this->db->get_where('card_agents', array('code'=>$cardCode))->row();
					$detail = '카드명 : ' . $cardAgent->title . '<br/>';
					
					$inst = $payment->rInstmt;
					$inst = floatVal($inst);
					if ($inst == 0)
						$detail .= '할부여부 : 일시불 <br/>';
					else
						$detail .= '할부여부 : $inst 개월 <br/>';
						
					$detail .= '결제금액 : '.$amt.'원';
					
					$data['payment_detail'] = $detail;
					
				}
				
				else if ($method == 'virtual'){
					$this->db->select('VIRTUAL_CENTERCD');
					$this->db->where(array('rVirNo'=>$payment->rVirNo,'VIRTUAL_CENTERCD !='=>'NULL'), false);
					$p = $this->db->get_where('payments')->row();
					
					$bank = $this->getCenter_cd($p->VIRTUAL_CENTERCD);
					$detail = '입금은행 : '.$bank.'<br/>';
					
					$detail .= '가상계좌번호 : '.$payment->rVirNo.'<br/>';
					
					$detail .= '입금금액 : '.$amt.'<br/>';
		
						
					if ($payment->state == 'PROCESSING'){
						$dateLimit = new DateTime($payment->date_modified);
						date_modify($dateLimit,'+5 day');
						$detail .= '입금기한 : ' . $dateLimit->format('Y년 m월 d일 H시 i분') . ' 까지 <br/>';
					} else {
						
						$detail .= '입금자명 : ' . $payment->inputnm . '<br/>';
						
						$dateLimit = new DateTime($payment->date_modified);
						date_modify($dateLimit,'+5 day');
						$detail .= '입금확인일시 : ' . $dateLimit->format('Y년 m월 d일 H시 i분 s초');
										
					}
					
					$data['payment_detail'] = $detail;
				}
				
				else if ($method == 'iche'){
				
					$detail = '이체결과 : '.$payment->rResMsg.'<br/>';
					$data['payment_detail'] = $detail;
				}
				
				else if ($methd == 'hp'){
					$mobile = $payment->rHP_HANDPHONE;
					$agent = $payment->rHP_COMPANY;
					$date = $payment->rHP_DATE;
					
					
					$detail = '휴대폰 번호 : '.$mobile.'<br/>';
					$detail .= '통신사 : '.$agent.'<br/>';
					$detail .= '결제일시 : '.$date;
					
					$data['payment_detail'] = $detail;
				}	
								
				
				$data['paid_amount'] = floatVal($payment->rAmt);
				
			}
			
		}
		
		/* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/my_order_detail_view', $data, true);
		
		/* 타이틀 */
		$d['title'] = '주문내역보기';
		
		$this->loadView($sideView, $contentView, $d);
		
	}
	
	
	/* 찜한 상품 */
	private function wishList(){
		$this->load->model('wishlist_model');
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    // Variables
	    $user = $this->memberObj();
	    $contentData;
	    $members_id = $user !=null ? $user->id : null;
	    
	    /* 찜 목록 */
	    $wishlist = $this->wishlist_model->wishlistByMember($members_id);
	    $contentData['wishlist'] = $wishlist;
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/my_wishlist_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '찜한상품';
		
		$this->loadView($sideView, $contentView, $d);
		
	}
	
	/* 기본정보 관리 */
	private function myInfo(){
		$member = $this->memberObj();
		
		if (!$member){
			echo '잘못 된 접근입니다';
			return;
		}
	
		$view = 'shop/my_info_gate_view';
		$password = $this->input->post('password');
		if ($password){
			$securePassword = $this->secure()->encrypt($password);
			if ($member->password == $securePassword){
				$view = 'shop/my_info_view';			
			} else {
				echo "<script>alert('비밀번호가 일치하지 않습니다.');</script>";
			}
		}
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    $contentData['member'] = $member;
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view($view, $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '기본정보관리';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 적립금 내역 */
	private function myPoint(){
		$this->load->model('member_points_model');
	
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    // Variables
	    $user = $this->memberObj();
	    $contentData;
	    $members_id = $user !=null ? $user->id : null;
	    
	    
	    /* 적립금 합계 */
	    $sum_point = $this->member_points_model->pointsByMember($members_id);
		$contentData['sum_point'] = number_format($sum_point,2);
		
		/* 적립금 내역 */
		$point_list = $this->member_points_model->pointListByMember($members_id);
		$contentData['point_list'] = $point_list;
		
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/my_point_list_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '적립금내역';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	/* 내 문의 내역 */
	private function myQna(){
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    $contentData['member'] = $this->memberObj();
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/my_qna_list_view', $contentData, true);
		
		/* 타이틀 */
		$d['title'] = '문의 내역';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	
	
	/** 공지사항 **/
	function notice(){
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    
	    $data = null;
	    
	    $this->db->select("id, nickname, title, content, date_format(date_write,'%Y-%m-%d') date_write", false);
	    $this->db->order_by('id','desc');
	    $query = $this->db->get('notice');
	    
	    $data['data'] = $query->result();
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/notice_list_view', $data, true);
		
		/* 타이틀 */
		$d['title'] = '공지사항';
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	function test (){
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
	    
	    
	    /* 본문 컨텐츠 */
		$contentView = $this->load->view('shop/order_hidden_frame', null, true);
		
		$this->loadView($sideView, $contentView, null);
	}
	
	
	function etc($uri='company'){
	
		$view = '';
		$title = '';
	
		switch($uri){
			case 'company':
			$view = 'shop/etc_company_view';
			$title = '회사소개';
			break;
			case 'terms':
			$view = 'shop/etc_terms_view';
			$title = '이용약관';
			break;
			case 'policy':
			$view = 'shop/etc_policy_view';
			$title = '개인정보보호정책';
			break;
			
			default:
			return;
			break;
		}
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		 /* 본문 컨텐츠 */
		$contentView = $this->load->view($view, null, true);
		
		/* 타이틀 */
		$d['title'] = $title;
		
		$this->loadView($sideView, $contentView, $d);
	}
	
	function find(){
	
		$member = $this->memberObj();
		if ($member)
			redirect('shop/main');
		
		$view = 'shop/find_id_pass_view';
		$title = '아이디 비밀번호 찾기';
	
		
		/* 카테고리 뷰 */
		$sideView = $this->categoryView();
		
		 /* 본문 컨텐츠 */
		$contentView = $this->load->view($view, null, true);
		
		/* 타이틀 */
		$d['title'] = $title;
		
		$this->loadView($sideView, $contentView, $d);
		
	}
	
	
	private function getCenter_cd($VIRTUAL_CENTERCD){
			if($VIRTUAL_CENTERCD == "39"){
				return "경남은행";
			}else if($VIRTUAL_CENTERCD == "34"){
				return "광주은행";
			}else if($VIRTUAL_CENTERCD == "04"){
				return "국민은행";
			}else if($VIRTUAL_CENTERCD == "11"){
				return "농협중앙회";
			}else if($VIRTUAL_CENTERCD == "31"){
				return "대구은행";
			}else if($VIRTUAL_CENTERCD == "32"){
				return "부산은행";
			}else if($VIRTUAL_CENTERCD == "02"){
				return "산업은행";
			}else if($VIRTUAL_CENTERCD == "45"){
				return "새마을금고";
			}else if($VIRTUAL_CENTERCD == "07"){
				return "수협중앙회";
			}else if($VIRTUAL_CENTERCD == "48"){
				return "신용협동조합";
			}else if($VIRTUAL_CENTERCD == "26"){
				return "(구)신한은행";
			}else if($VIRTUAL_CENTERCD == "05"){
				return "외환은행";
			}else if($VIRTUAL_CENTERCD == "20"){
				return "우리은행";
			}else if($VIRTUAL_CENTERCD == "71"){
				return "우체국";
			}else if($VIRTUAL_CENTERCD == "37"){
				return "전북은행";
			}else if($VIRTUAL_CENTERCD == "23"){
				return "제일은행";
			}else if($VIRTUAL_CENTERCD == "35"){
				return "제주은행";
			}else if($VIRTUAL_CENTERCD == "21"){
				return "(구)조흥은행";
			}else if($VIRTUAL_CENTERCD == "03"){
				return "중소기업은행";
			}else if($VIRTUAL_CENTERCD == "81"){
				return "하나은행";
			}else if($VIRTUAL_CENTERCD == "88"){
				return "신한은행";
			}else if($VIRTUAL_CENTERCD == "27"){
				return "한미은행";
			}
			
			return '';
		}
	
	
}


?>