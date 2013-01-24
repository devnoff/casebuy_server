<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');

class Admin extends CI_Controller {

    var $secureMgr;
    var $PAYMENT_TYPE = array('card'=>'신용카드','virtual'=>'가상계좌','iche'=>'계좌이체','hp'=>'휴대폰 결제','결제대기'=>'결제대기');

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('members_model');
        $this->load->database();
	    
	}
	
	
	private function memberObj(){
	    $username = get_cookie('casebuy_username');
	    
	    if ($username){
		    return $this->members_model->memberByUsername($username);
	    }
	    
	    return null;
    }
	
	function adminCheck(){

	    if (!$this->members_model->adminValid()){
            
            // 관리자가 아닐경우
    	    $data['redirect_url'] = site_url('admin/main/top_sales');
            $this->load->view('member/login_view',$data);
    	    return false;
	    }
	    
	    return true;
	}
	
	/* 보안 객체 */
    function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }
	
	/* 메인화면관리 */
	function index()
	{
	    redirect('/admin/main/top_sales');
	}
	
	private function loadView($viewUri, $viewData, $menuId, $subMenuKey){
	    $menuItems = $this->db->get_where('admin_menu',array('parent_id'=>NULL));
	    $sql = "SELECT  concat('admin/',p.key,'/',c.key) as uri, c.id,c.parent_id,c.menu_name, c.key "
                ."FROM  admin_menu p left join admin_menu c "
                ."on c.parent_id = p.id "
                ."WHERE c.parent_id =".$menuId
                ." order by c.family, c.orderby";
                
        $subMenuItems = $this->db->query($sql);
        
        $menuData['menuItems'] = $menuItems->result();
        $menuData['currItemId'] = $menuId;
        
        $subMenuData['menuItems'] = $subMenuItems->result();
        // $subMenuData['currItemId'] = $subMenuId;
        $subMenuData['currItemKey'] = $subMenuKey;
        
        
        $this->load->view('admin/layouts/admin_header_layout');
        $this->load->view('admin/template/menu_tpl_block',$menuData);
        $this->load->view('admin/template/sub_menu_tpl_block',$subMenuData);
        $this->load->view('admin/layouts/content_top_layout');
        $this->load->view($viewUri, $viewData);
        $this->load->view('admin/layouts/content_bottom_layout');
        $this->load->view('admin/layouts/admin_footer_layout');
	}
	
	/* 관리자 로그인 */
	function login(){
	    
	    $data['redirect_url'] = site_url('admin/main');
        $this->load->view('member/login_view',$data);
	}
	
	/* 메인화면관리 */
	function main($uri='null',$extra=0){
	    if (!$this->adminCheck()) return;
	    $this->load->model('products_model');
	    
	    switch($uri){
	        case 'top_sales':
	            $this->top_sales($extra); // extra: start
	        break;
	        case 'web_main':
	            $this->web_main($extra);
	        break;
	        case 'app_recomm':
	            $this->app_recomm($extra);
	        break;
	        default:
	            redirect(site_url('/admin/main/top_sales'));
	        break;
	    }
	    
	}
	
	/* 메인화면관리 > 판매량순 상품 조회 */
	private function top_sales($start=0){
	    $data = null;
	    
	    // 페이지네이션
		$this->load->library('pagination');
	    $perPage = 14;
		$config['base_url'] = site_url().'/admin/main/top_sales/';
		$config['uri_segment'] = 4;
		
		$this->db->where("sales_state not in ('WAIT','OUT','END')");
		$config['total_rows'] = $this->db->count_all_results('products');
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';
		$config['num_links'] = 10;

		$this->pagination->initialize($config);
		$product_data['pagination'] = $this->pagination->create_links();
	    
	    // 메뉴 리턴
	    $product_data['return_to'] = 8;	    
	    $product_data['product_data'] = $this->products_model->productsTopSales($perPage, $start);
	    
	    $data['content'] = $this->load->view('admin/template/product_tile_tpl_block',$product_data,true);
	    
        $this->loadView('admin/content/main/top_sales_block',$data,1,'top_sales');
	}
	
	/* 메인화면관리 > 웹 메일화면 */
	private function web_main($start=0){
	    $data = null;
	    
	    // 페이지네이션
		$this->load->library('pagination');
	    $perPage = 14;
		$config['base_url'] = site_url().'/admin/main/top_sales/';
		$config['uri_segment'] = 4;
		
		
		$this->db->where('web_main','Y');
		$this->db->where("sales_state not in ('WAIT','OUT','END')");
		$config['total_rows'] = $this->db->count_all_results('products');
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';
		$config['num_links'] = 5;

		$this->pagination->initialize($config);
		$product_data['pagination'] = $this->pagination->create_links();
	    
	    // 메뉴 리턴
	    $product_data['return_to'] = 9;	    
	    $product_data['product_data'] = $this->products_model->productsWebMain($perPage, $start);
	    
	    $data['content'] = $this->load->view('admin/template/product_tile_tpl_block',$product_data,true);
	    
        $this->loadView('admin/content/main/top_sales_block',$data,1,'web_main');
	}
	
	/* 메인화면관리 > 앱 추천상품 */
	private function app_recomm($start=0){
	    $data = null;
	    
	    // 페이지네이션
		$this->load->library('pagination');
	    $perPage = 14;
		$config['base_url'] = site_url().'/admin/main/top_sales/';
		$config['uri_segment'] = 4;
		
		$this->db->where('app_main','Y');
		$config['total_rows'] = $this->db->count_all_results('products');
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$product_data['pagination'] = $this->pagination->create_links();
	    
	    // 메뉴 리턴
	    $product_data['return_to'] = 10;
	    $product_data['product_data'] = $this->products_model->productsAppMainAdmin($perPage, $start);
	    
	    $data['content'] = $this->load->view('admin/template/product_tile_tpl_block',$product_data,true);
	    
        $this->loadView('admin/content/main/top_sales_block',$data,1,'app_recomm');
	}
	
	
	
	/* 상품정보관리 */
	function product($uri='null',$extra=0,$extra1=0,$extra2=0){
	    if (!$this->adminCheck()) return;
	    $this->load->model('products_model');
	    
	    $menu_id = 2;
	    
	    switch($uri){
	        case 'all':
	            $this->product_all($menu_id,$uri,$extra,$extra1,$extra2); // extra : category, extra1: sub catetory, extra2: start
	        break;
	        case 'sale':
	            $this->product_all($menu_id,$uri,$extra,$extra1,$extra2); // extra : category, extra1: sub catetory, extra2: start
	        break;
	        case 'outs':
	            $this->product_all($menu_id,$uri,$extra,$extra1,$extra2); // extra : category, extra1: sub catetory, extra2: start
	        break;
	        case 'add':
	            $this->product_add($menu_id);
	        break;
            case 'edit':
	            $this->product_edit($menu_id,$extra,$extra1); // extra: product_id, extra1: origin
	        break;
	        case 'category':
	            $this->product_category($menu_id);
	        break;
	        default:
	            redirect(site_url('/admin/product/all'));
	        break;
	    }
	}
	
	private function product_all($menu_id,$uri,$c_id, $sc_id, $start=0){
	    
	    $start = $this->input->get('per_page');
	    if (!$start)  $start = 0;
	    
	    $keyword = $this->input->get('keyword');
	    $state = $this->input->get('condition');
	    
	    $this->load->helper('form');
	    
	    $data = null;
	    
	    // 카테고리
	    $product_data['category_base_url'] = site_url().$this->uri->slash_segment(1,'both').$this->uri->slash_segment(2).$this->uri->segment(3);
	    
	    $product_data['categories_id'] = $c_id;
	    $categories = $this->db->get_where('product_categories', array('parent_id'=>null))->result();

	    


        // 전체 항목 추가
	    $all = new stdClass;
	    $all->id = 0;
	    $all->category_name = "전체";
	    array_unshift($categories, $all);
	    $product_data['categories'] = $categories;
	    
	    // 서브 카테고리
	    $product_data['sub_category_id'] = $sc_id;
	    $product_data['sub_categories'] = null;

	    $sub_category_name = null;
	    if ($c_id && $c_id != 0){
	        $product_data['sub_categories'] = $this->db->get_where('product_categories',array('parent_id'=>$c_id))->result();
	        // 전체카테고리 추가
    	    array_unshift($product_data['sub_categories'], $all);   

    	    if ($sc_id && $sc_id != 0){
    	    	foreach($product_data['sub_categories'] as $i){
    	    	if ($i->id == $sc_id){
    	    		$sub_category_name = $i->category_name;
    	    		break;
    	    	}
    	    }	
    	    }
    	    
	    }
	    
	    
	    // 판매상태
	    $states = array(
	    	''=>'전체',
	    	'WAIT'=>'판매대기',
	    	'SALE'=>'판매중',
	    	'TEMP_OUT'=>'일시품절',
	    	'OUT'=>'품절',
	    	'END'=>'판매종료'
	    );
	    
	    
	    
	    // 페이지네이션
		$this->load->library('pagination');

		$perPage = $keyword?1000:50; //
		
		$config['base_url'] = site_url().'/admin/product/'.$uri.'/'.$c_id.'/'.$sc_id.($state?'?condition='.$state:'?condition=');
		$config['uri_segment'] = 5;
		
		if ($c_id && $c_id != 0){
		    $this->db->where('categories_id',$c_id);
		}
		
		if ($sc_id && $sc_id != 0){
		    $this->db->like('tags',$sub_category_name,'both');
		}
		
		if ($keyword){
			$this->db->where("(tags like '%$keyword%' or title like '%$keyword%' or product_code like '%$keyword%')");
		}
		
		if (!$state){		
			if ($uri == 'sale') $state = array('SALE');
			else if ($uri == 'outs') $state = array('OUT','TEMP_OUT');

		} else {
			$state = array($state);
		}
		
		if ($state){
	        $this->db->where_in('sales_state',$state);
        }
		
		
		$total = $this->db->count_all_results('products');
		$config['total_rows'] = $total;
		$config['per_page'] = $perPage;
		$config['num_links'] = 7;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';
		$config['page_query_string'] = TRUE;
		
		$product_data['state'] = $state;
		$product_data['states'] = $states;
		$product_data['total'] = $total;
		$product_data['product_data'] = $this->products_model->products($perPage, $start, $c_id,null,$keyword,$state,$sub_category_name);
		$this->pagination->initialize($config);
		$product_data['pagination'] = $this->pagination->create_links();
	    

		// 상품 옵션
		foreach ($product_data['product_data'] as $product) {
			$id = $product->id;

			$query = $this->db->get_where('product_options',array('products_id'=>$id));
			$options = $query->result();

			$product->options = $options;
		}



	    // 메뉴 리턴
	    $product_data['return_to'] = 'all';
	    
	    // 컨텐트
	    $data['content'] = $this->load->view('admin/template/product_list_tpl_block',$product_data,true);
	    
        $this->loadView('admin/content/product/all_block',$data,$menu_id,$uri);
	}
	
		
	private function product_add($menu_id){
	    $data = null;
	    $data['page_title'] = "상품 등록";
        $this->loadView('admin/content/product/add_block',$data,$menu_id,'add');
	}
	
	private function product_edit($menu_id,$product_id=null,$origin='all'){
	    $data = null;
	    if (isset( $_SERVER[ 'HTTP_REFERER' ] )) 
        { 
            $data['redirect'] = $_SERVER[ 'HTTP_REFERER' ]; 
        } 
        else 
        { 
            $data['redirect'] = site_url().'/admin/product/all';
        }
	    
	    
	    $product_data['page_title'] = "상품 수정";
	    $data['content'] = $this->load->view('admin/content/product/add_block',$product_data,true);
	    $data['product_id'] = $product_id;
	    $data['product'] = json_encode($this->products_model->productById($product_id));
	    
	    $this->loadView('admin/content/product/edit_block',$data,$menu_id,$origin);
	    
	}
	
	private function product_category($menu_id){
		$this->load->model('product_categories_model');
	
	    $data = null;
	    
	    $c = $this->product_categories_model->categories();
	    $sub = array();
	    foreach($c as $i){
		    $s = $this->product_categories_model->categoriesByParentId($i->id);
		    $sub[$i->family] = $s;
	    }
	    
	    $data['categories'] = $c;
	    $data['sub_categories'] = $sub;
	    
        $this->loadView('admin/content/product/category_block',$data,$menu_id,'category');
	}
	
	
	/* 주문/배송 관리 */
	function orders($uri='null', $param1=0){ //,$param1=0,$param2=null,$param3=null,$param4=null,$param5=null
	    if (!$this->adminCheck()) return;
	    $this->load->model('orders_model');
	    
	    $menu_id = 3;
	    
	    switch($uri){
	        case 'all':
	            $this->order($menu_id,$uri,$param1); //param1: $offset ,param1: $date ,param1: $month ,param1: $keyword ,param1: $state
	        break;
	        case 'paid':
	            $this->order($menu_id,$uri,$param1, array('PAID'));
	        break;
	        case 'orders':
	            $this->order($menu_id,$uri,$param1, array('PARTNER_REQUESTED','PARTNER_SHIPPING'));
	        break;
	        case 'delivery_wait':
	            $this->order($menu_id,$uri,$param1, array('PREPARE_PRODUCT'));
	        break;
	        case 'delivery_ing':
	            $this->order($menu_id,$uri,$param1, array('OWN_SHIPPING'));
	        break;
	        case 'done':
	            $this->order($menu_id,$uri,$param1, array('DONE'));
	        break;
	        default:
                redirect(site_url('/admin/orders/all'));
	        break;
	    }
	}
	
	private function order($menu_id=3,$uri='all',$offset=0,$state=null){//,$date=null,$month=null,$keyword=null,$state=null
	    
	    $date = $this->input->get('date');
	    $month = $this->input->get('month');
	    $keyword = $this->input->get('keyword');
	    
        $perPage = 10;
	    
	    // 테이터
	    $data = null;
        $order_data['order_data'] = $this->orders_model->orders($perPage,$offset,$date,$month,$keyword,$state);
	    
	    
        // 페이지네이션
		$this->load->library('pagination');

		$config['base_url'] = site_url().'/admin/orders/'.$uri;
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $this->orders_model->countTotal;
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$order_data['pagination'] = $this->pagination->create_links();
		
		// 주문상태옵션로드
		$order_data['order_states'] = $this->orders_model->orderStates();
		
		// 배송업체 로드
		$order_data['delivery_agency'] = $this->db->get('delivery_agency')->result();
		
		$order_data['PAYMENT_TYPE'] = $this->PAYMENT_TYPE;
	    
	    // 뷰 로드
	    $data['content'] = $this->load->view('admin/template/order_list_tpl_block',$order_data,true);
        $this->loadView('admin/content/orders/all_block',$data,$menu_id,$uri);
	}
	
	
    /* 주문 상세 보기 */
	function orderDetail(){
		if (!$this->adminCheck()) return;
	
		$this->load->model('orders_model'); 
		
		$order_code = $this->input->get('order_code');
		
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
			
			$this->db->not_like('state','FAILED');
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
				
				$amt = number_format(intval($payment->rAmt));
				
				if ($method == 'card'){
					$cardCode = intval($payment->rCardCd);
					if ($cardCode){
						$cardAgent = $this->db->get_where('card_agents', array('code'=>$cardCode))->row();
						$detail = '카드명 : ' . $cardAgent->title . '<br/>';	
					}
					
					$inst = $payment->rInstmt;
					$inst = intval($inst);
					if ($inst == 0)
						$detail .= '할부여부 : 일시불 <br/>';
					else
						$detail .= '할부여부 : $inst 개월 <br/>';
						
					$detail .= '결제금액 : '.$amt;
					
					$data['payment_detail'] = $detail;
					
				}
				
				else if ($method == 'virtual'){
					$this->db->select('VIRTUAL_CENTERCD');
					$this->db->where(array('rVirNo'=>$payment->rVirNo,'rOrdNo'=>$orders_id,'VIRTUAL_CENTERCD !='=>'NULL'), false);
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
								
				
				$data['paid_amount'] = intval($payment->rAmt);
				
			}
			
		}
		
		/* 본문 컨텐츠 */
		echo '	<link rel="stylesheet" href="/ko/css/shop_style.css" />';
		$this->load->view('shop/my_order_detail_view', $data);
		
	}
    
	
	/* 교환 반품 관리 */
	function exchange($uri='null', $param1=0){
	    if (!$this->adminCheck()) return;
	    $this->load->model('orders_model');
	    
	    $menu_id = 5;
	    
	    switch($uri){
	        case 'cancel':
	            $this->order($menu_id, $uri,$param1, array('CANCEL_REQUESTED'));
	        break;
	        case 'cancel_done':
	            $this->order($menu_id, $uri,$param1, array('CANCEL_DONE'));
	        break;
	        case 'refund':
	            $this->order($menu_id, $uri,$param1, array('REFUND_REQUESTED'));
	        break;
	        case 'refund_done':
	            $this->order($menu_id, $uri,$param1, array('REFUND_DONE'));
	        break;
	        case 'exchange':
	            $this->order($menu_id, $uri,$param1, array('EXCHANGE_REQUESTED'));
	        break;
	        case 'exchange_shipping':
	            $this->order($menu_id, $uri,$param1, array('EXCHANGE_SHIPPING'));
	        break;
	        default:
	            redirect(site_url('/admin/exchange/cancel'));
	        break;
	    }
	}

	
	/* QNA 관리 */
	function qna($uri='null', $param1=0){
	    if (!$this->adminCheck()) return;
	    
	    
	    $menu_id = 6;
	    
	    switch($uri){
	        case 'all':
	            $this->qna_all($menu_id,$uri,$param1);
	        break;
	        case 'wait':
	            $this->qna_all($menu_id,$uri,$param1);
	        break;
	        case 'inquery':
	            $this->inquery_list($menu_id,$uri,$param1);
	        break;
	        case 'inquery_wait':
	            $this->inquery_list($menu_id,$uri,$param1);
	        break;
	        default:
	            redirect(site_url('/admin/qna/all'));
	        break;
	    }
	}
	
	private function qna_all($menu_id, $uri, $offset=0){
		$this->load->model('questions_model');
	    
	    $date = $this->input->get('date');
	    $month = $this->input->get('month');
	    $keyword = $this->input->get('keyword');
	    
	    $data = null;
	    $state = null;
	    if ($uri == 'wait'){
	        $state = 'not_replied';
	    }
	    
	    $perPage = 10;
	    
	    // 테이터
	    $data = null;
        $qna_data['qna_data'] = $this->questions_model->questions($perPage,$offset,$state, $date, $month, $keyword);
	    
        // 페이지네이션
		$this->load->library('pagination');

		$config['base_url'] = site_url().'/admin/qna/'.$uri;
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $this->questions_model->countQuestions($state, $date, $month, $keyword);
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$qna_data['pagination'] = $this->pagination->create_links();
	    
        
        $data['content'] = $this->load->view('admin/template/qna_list_tpl_block',$qna_data,true);
        $this->loadView('admin/content/qna/all_block',$data,$menu_id,$uri);
	}


	private function inquery_list($menu_id, $uri, $offset=0){
		$this->load->model('inqueries_model');

		$date = $this->input->get('date');
	    $month = $this->input->get('month');
	    $keyword = $this->input->get('keyword');
	    
	    $data = null;
	    $state = null;
	    if ($uri == 'inquery_wait'){
	        $state = 'not_replied';
	    }
	    
	    $perPage = 10;
	    
	    // 테이터
	    $data = null;
        $qna_data['qna_data'] = $this->inqueries_model->questions($perPage,$offset,$state);
	    
        // 페이지네이션
		$this->load->library('pagination');

		$config['base_url'] = site_url().'/admin/qna/'.$uri;
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $this->inqueries_model->countQuestions($state);
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$qna_data['pagination'] = $this->pagination->create_links();
	    
        
        $data['content'] = $this->load->view('admin/template/m2m_list_tpl_block',$qna_data,true);
        $this->loadView('admin/content/qna/all_block',$data,$menu_id,$uri);
	}


	
    /* 통계 분석 */
    function stats($uri='null'){
        $this->stats_view();
    }
	
	private function stats_view(){
	    $data = null;
        $this->loadView('admin/content/stats/stats_block',$data,7,25);
	}
	
	
	/** 공지사항 */
	function notice($uri='list'){
		if (!$this->adminCheck()) return;
		
		$menu_id = 33;

		switch($uri){
			case 'list':
			$this->notice_list($menu_id, $uri);
			break;
		}
	}
	
	private function notice_list($menu_id=33, $uri='list'){
		$data = null;
	    
	    $this->db->select("id, nickname, title, content, need_notify, date_format(date_write,'%Y-%m-%d') date_write", false);
	    $this->db->order_by('id','desc');
	    $query = $this->db->get('notice');
	    
	    $data['data'] = $query->result();
	    
	    
	    /* 본문 컨텐츠 */
        $this->loadView('admin/content/notice/notice_list_view',$data,$menu_id,$uri);
	}
	
	
	
	function adminMenu(){
	    $this->db->order_by('family asc, orderby asc');
	    $query = $this->db->get('admin_menu');

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($query->result()));
            
            
	}
	
	
	private function getCenter_cd($VIRTUAL_CENTERCD){
			if($VIRTUAL_CENTERCD == 39){
				return "경남은행";
			}else if($VIRTUAL_CENTERCD == 34){
				return "광주은행";
			}else if($VIRTUAL_CENTERCD == 4){
				return "국민은행";
			}else if($VIRTUAL_CENTERCD == 11){
				return "농협중앙회";
			}else if($VIRTUAL_CENTERCD == 31){
				return "대구은행";
			}else if($VIRTUAL_CENTERCD == 32){
				return "부산은행";
			}else if($VIRTUAL_CENTERCD == 02){
				return "산업은행";
			}else if($VIRTUAL_CENTERCD == 45){
				return "새마을금고";
			}else if($VIRTUAL_CENTERCD == 7){
				return "수협중앙회";
			}else if($VIRTUAL_CENTERCD == 48){
				return "신용협동조합";
			}else if($VIRTUAL_CENTERCD == 26){
				return "(구)신한은행";
			}else if($VIRTUAL_CENTERCD == 5){
				return "외환은행";
			}else if($VIRTUAL_CENTERCD == 20){
				return "우리은행";
			}else if($VIRTUAL_CENTERCD == 71){
				return "우체국";
			}else if($VIRTUAL_CENTERCD == 37){
				return "전북은행";
			}else if($VIRTUAL_CENTERCD == 23){
				return "제일은행";
			}else if($VIRTUAL_CENTERCD == 35){
				return "제주은행";
			}else if($VIRTUAL_CENTERCD == 21){
				return "(구)조흥은행";
			}else if($VIRTUAL_CENTERCD == 3){
				return "IBK기업은행";
			}else if($VIRTUAL_CENTERCD == 81){
				return "하나은행";
			}else if($VIRTUAL_CENTERCD == 88){
				return "신한은행";
			}else if($VIRTUAL_CENTERCD == 27){
				return "한미은행";
			}
			
			return '';
	}
	
	
	function deliveryHelper(){
		
	}
	
	function changeDelivery(){
		$seq = $this->input->post('seq');
		$fee = $this->input->post('fee');
		
		$data = array('delivery_fee'=>$fee);
		
		$this->db->where('seq', $seq);
		
		$this->db->trans_begin();
	    $this->db->update('zipcode',$data);
	        
	    if ($this->db->trans_status() === FALSE)
	    {
	        $this->db->trans_rollback();
	        $result['success'] = false;
	    }
	    
	    $this->db->trans_commit();
	    $result['success'] = true;
		
	}
	
	
	function deliverySearch(){
		$keyword = $this->input->get('keyword');
		
		$data['result'] = null;
		if ($keyword){
			$sql = "select * from zipcode where dong like '%".$keyword."%'";
			$query = $this->db->query($sql);
			$data['result'] = $query->result();
		}
		
		
		$this->load->view('admin/delivery_helper_view',$data);
	}
	
	function deliveryChangeAll(){
		$keyword = $this->input->post('keyword');
		$fee = $this->input->post('fee');
		
		$data = array('delivery_fee'=>$fee);
		
		
		$sql = "update zipcode set delivery_fee=".$fee." where dong like '%".$keyword."%'";
		
		$this->db->trans_begin();
	    $this->db->query($sql);	        
	    if ($this->db->trans_status() === FALSE)
	    {
	        $this->db->trans_rollback();
	    }
	    
	    $this->db->trans_commit();
	    
	    redirect('admin/deliverySearch?keyword='.$keyword);
	}


	function knowledge($uri='null', $param1=0){
		if (!$this->adminCheck()) return;
	    
	    $this->load->model('knowledge_model');

	    $menu_id = 37;
	    
	    switch($uri){
	        case 'list':
	            $this->knowledgeList($menu_id,$uri,$param1);
	        break;
	        default:
	            redirect(site_url('/admin/knowledge/list'));
	        break;
	    }
	}

	private function knowledgeList($menu_id, $uri, $offset=0){
		$data = null;
	    
	    // 페이지네이션
		$this->load->library('pagination');
	    $perPage = 10;
		$config['base_url'] = site_url().'/admin/knowledge/list';
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $this->db->count_all_results('knowledge');
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
	    
	    $data['data'] = $this->knowledge_model->itemList($offset,$perPage);
	    
	    
	    /* 본문 컨텐츠 */
        $this->loadView('admin/content/knowledge/knowledge_list_view',$data,$menu_id,$uri);
	}


	function member($uri='null', $param1=0){
		if (!$this->adminCheck()) return;
	    
	    $this->load->model('members_model');

	    $menu_id = 39;
	    
	    switch($uri){
	        case 'list':
	            $this->memberList($menu_id,$uri,$param1);
	        break;
	        default:
	            redirect(site_url('/admin/member/list'));
	        break;
	    }

	}

	private function memberList($menu_id, $uri, $offset=0){
		$data = null;
	    
	    // 페이지네이션
	    $total = $this->db->count_all_results('members');
		$this->load->library('pagination');
	    $perPage = 20;
		$config['base_url'] = site_url().'/admin/member/list';
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $total;
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
	    

	    $data['total'] = $total;

	    $this->db->where('date(now()) = date(date_join)');
	    $data['total_today'] = $this->db->count_all_results('members');

	    $data['data'] = $this->members_model->memberList($offset,$perPage);
	    
	    
	    /* 본문 컨텐츠 */
        $this->loadView('admin/content/member/member_list_view',$data,$menu_id,$uri);
	}


	function wallpaper($uri='null', $param1=0){
		if (!$this->adminCheck()) return;
	    
	    $this->load->model('wallpapers_model');

	    $menu_id = 43;
	    
	    switch($uri){
	        case 'list':
	            $this->wallpaperList($menu_id,$uri,$param1);
	        break;
	        case 'list_rev':
	        	$this->productWallpaper($menu_id,$uri,$param1);
	        break;
	        case 'add':
	        	$this->wallpaperAdd($menu_id,$uri);
	        break;
	        default:
	            redirect(site_url('/admin/wallpaper/list'));
	        break;
	    }

	}

	private function productWallpaper($menu_id, $uri, $offset=0){
		$data = null;

		// 페이지네이션
	    $total = $this->db->count_all_results('products');
		$this->load->library('pagination');
	    $perPage = 20;
		$config['base_url'] = site_url().'/admin/wallpaper/list_rev';
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $total;
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// 배경화면 데이터
	    $data['data'] = $this->wallpapers_model->wallpapers();
	    
	    // 전체 상품 데이터
	    $this->load->model('products_model');
	    $data['products'] = $this->products_model->products($perPage,$offset);
	    
	    /* 본문 컨텐츠 */
        $this->loadView('admin/content/wallpaper/wallpaper_rev_list_view',$data,$menu_id,$uri);
	}

	private function wallpaperList($menu_id, $uri, $offset=0){
		$data = null;
	    
	    // 페이지네이션
	    $total = $this->db->count_all_results('caseshop.wallpapers');
		$this->load->library('pagination');
	    $perPage = 20;
		$config['base_url'] = site_url().'/admin/wallpaper/list';
		$config['uri_segment'] = 4;
		
		$config['total_rows'] = $total;
		$config['per_page'] = $perPage;
		$config['first_link'] = '맨처음';
		$config['last_link'] = '끝으로';
		$config['next_link'] = '다음';
		$config['prev_link'] = '이전';

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

	    
		// 배경화면 데이터
	    $data['data'] = $this->wallpapers_model->wallpapers($offset,$perPage);
	    

	    $this->load->model('products_model');
	    // 전체 상품 데이터
	    $data['products'] = $this->products_model->products(999,0);


	    
	    /* 본문 컨텐츠 */
        $this->loadView('admin/content/wallpaper/wallpaper_list_view',$data,$menu_id,$uri);
	}

	private function wallpaperAdd($menu_id,$uri){
	    $data = null;
	    $data['page_title'] = "배경화면 등록";
        $this->loadView('admin/content/wallpaper/add_block',$data,$menu_id,'add');


	}








}





?>