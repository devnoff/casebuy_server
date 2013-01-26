<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_model extends CI_Model {

    var $id = null;

	var $countTotal = 0;
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Order State List
   	function orderStates(){
 		$query = $this->db->get('order_states');
 		
 		return $query->result();
   	}
   	
   	function order($orders_id=null){
	   	if (!$orders_id) return false;
	   	
	   	$query = $this->db->get_where('orders',array('id'=>$orders_id));
	   	
	   	return $query->row();
   	}
   	
   	function orderByOrderCode($order_code){
   		if (!$order_code) return false;
   		
   		$this->db->select('orders.id id,order_code, members_id, none_member_pass, date_order, totalPrice, delivery_fee, used_point, payable_amount, orders.order_state, customer_text');
   		$this->db->from('orders');
   		$this->db->join('order_states','orders.order_state = order_states.key','left');
   		$this->db->where('order_code',$order_code);
   		
/*
   		$sql = "SELECT o.id id,order_code, members_id, none_member_pass, date_order, totalPrice, delivery_fee, used_point, payable_amount, o.order_state, customer_text
				FROM orders o
				LEFT JOIN order_states os ON o.order_state = os.key 
				WHERE  order_code='".$order_code."'";
*/
		
		$query = $this->db->get();
		
		return $query->row();
   	}
    
    // Select Order List
    function orders($limit=10,$offset=0,$date=null,$month=null,$keyword=null,$state=null){
        
        $where = ''; 
        
        $sql = 
"select *, orders.id id,(select username from members m where m.id=orders.members_id) username, ifnull(payment_method,'결제대기') payment_method,
(select count(*) from member_points where ref_orders_id = orders.id and reason in ('EARN_REFUND','SPEND_FOR_CANCEL')) has_refunded, 
(select count(*) from member_points where ref_orders_id = orders.id and reason in ('EARN_TO_BUY','SPEND_FOR_PAYMENT')) has_point   
from 
(select o.id as id, order_code, order_title, members_id, none_member_pass, date_order, format(totalPrice,0) totalPrice, format(delivery_fee,0) delivery_fee,used_point, format(payable_amount,0) payable_amount, order_state, date_modified, last_admin, c_name, d_name, invoice_no, delivery_agent_id  
from 
orders o 
left join (select name as c_name, orders_id from order_customer_info) c on (o.id = c.orders_id) 
left join (select name as d_name, orders_id, invoice_no, delivery_agent_id from order_delivery_info) d on (o.id = d.orders_id)) 
orders left join (select payment_method, rOrdNo from payments group by rOrdNo,payment_method) p on orders.id = p.rOrdNo  ";
        
        
        $q = array();
        if ($date!=null){
            $q[] = "date_format(date_order, '%Y-%m-%d') = '$date' ";
        }
        
        if ($month!=null){
            $q[] = "date_format(date_order, '%m/%Y') = '$month' ";
        }
        
        if ($keyword!=null){
            $q[] = "(c_name like '%$keyword%' or d_name like '%$keyword%' or order_code like '%$keyword%') ";
        }
        
        if ($state!=null){
            $str = join("','",$state);
            $q[] = "order_state in ('$str') ";
        }
        
        if ($date!=null || $month!=null || $keyword!=null || $state!=null){
            $sql .= "where ";
            $q = join('and ',$q);
            $sql .= $q;
        }
        
       	$query = $this->db->query($sql);
       	
       	$this->countTotal = count($query->result());
        
        $sql .= "order by date_order desc limit $offset,$limit ";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    
    // 회원 주문 (최근 2주일)
    function ordersByMemberSimple($members_id=null, $uuid=null){
        if (!$members_id && !$uuid) return false;

        $this->db->select("id, order_code, members_id, uuid, date_format(date_order, '%Y.%m.%d') date_order, format(payable_amount,0) payable_amount, order_title", false);

        if ($members_id && $members_id != ''){
            $this->db->where('members_id',$members_id);
        }

        else if ($uuid){
            $this->db->where('uuid', $uuid);
            $this->db->where('members_id is null');
        }
        
        $this->db->where('DATE_SUB(NOW(), INTERVAL 14 DAY) < DATE(date_modified)');

        $this->db->where("order_state not in ('B4PAYMENT','CANCEL_DONE')");

        $this->db->not_like("hidden","Y");

        $this->db->order_by('id', 'desc');

        $query = $this->db->get('orders');
        
        return $query->result();    
    }


    // 회원 주문 (최근 2주일)
    function ordersByMemberId($members_id=null){
    	if (!$members_id) return false;
		
		$sql = "SELECT o.id id, order_code, members_id, none_member_pass, date_format(date_order, '%Y-%m-%d') date_order, totalPrice, delivery_fee, used_point, format(payable_amount,0) payable_amount, o.order_state, customer_text
				FROM orders o
				LEFT JOIN order_states os ON o.order_state = os.key 
				WHERE  members_id=".$members_id." and DATE_SUB(NOW(), INTERVAL 14 DAY) < DATE(date_modified) AND order_state > 1 order by id desc";
		
		$query = $this->db->query($sql);
		
		return $query->result();	
    }
    
    // 회원 주문 (전체)
    function ordersOldByMemberId($members_id=null){
    	if (!$members_id) return false;
		
		$sql = "SELECT o.id id, order_code, members_id, none_member_pass, date_format(date_order, '%Y-%m-%d') date_order, totalPrice, delivery_fee, used_point, format(payable_amount,0) payable_amount, o.order_state, customer_text
				FROM orders o
				LEFT JOIN order_states os ON o.order_state = os.key 
				WHERE  members_id=".$members_id . " and order_state > 1 order by id desc";
		
		$query = $this->db->query($sql);
		
		return $query->result();	
    }
    
    
    // 주문 아이템
    function orderItems($orders_id=null){
    	if (!$orders_id) return false;
    	
    	$this->db->select('*');
    	$this->db->from('order_items');
    	$this->db->join('(select id, web_list_img from products) products', 'products.id = order_items.products_id');
    	$this->db->where('order_items.orders_id',$orders_id);
    	
    	$query = $this->db->get(); //$this->db->get_where('order_items', array('orders_id'=>$orders_id));
    	
    	return $query->result();
    }

    // 주문 아이템
    function orderItemsSimple($orders_id=null){
        if (!$orders_id) return false;
        
        $this->db->select('id, orders_id, products_id, product_name, qty, format(item_total_amount,0) item_total_amount', false);
        $this->db->from('order_items');
        $this->db->where('order_items.orders_id',$orders_id);
        
        $query = $this->db->get(); //$this->db->get_where('order_items', array('orders_id'=>$orders_id));
        return $query->result();
    }
    
    // 배송지 정보 1:m
    function orderDelivery($orders_id=null){
    	if (!$orders_id) return false;
    	
    	$query = $this->db->get_where('order_delivery_info', array('orders_id'=>$orders_id));
    	
		return $query->row();	
    }
    
    // 주문자 정보 1:1
    function orderCustomer($orders_id=null){
    	if (!$orders_id) return false;
    	
    	$query = $this->db->get_where('order_customer_info', array('orders_id'=>$orders_id));
    	
		return $query->row();
    }
    
    // 최근 주문번호 회원
    function orderIdByMember($members_id){
	    if (!$members_id) return false;
	    
	    $this->db->order_by('date_order','desc');
	    $query = $this->db->get_where('orders',array('members_id'=>$members_id));
	    
	    $order = $query->row();
	    if ($order)
	    	return $order->id;
	    	
	    return false; 
    }
    
    
    // Update Order
    function update($data){
     
        if (!$data){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->where('id', $data['id']);
        unset($data['id']);
		$this->db->update('orders', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;

    }
    
    // Update Delivery Info
    function updateDelivery($data){
        if (!$data){
            return false;
        }
        
        $this->db->trans_begin();
        $this->db->where('orders_id', $data['id']);
        unset($data['id']);
		$this->db->update('order_delivery_info', $data); 

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    
    // 주문 입력
    function insertOrder($data=null){
    	
    	if (!$data) return false;

    	
    	$orderCode = $this->generatedOrderCode(); // 주문 코드 생성
    	$this->db->set('order_code',$orderCode);
    	$this->db->set('date_order','NOW()',false);
    	
    	$this->db->trans_begin();
        $this->db->insert('orders', $data);
        $inserted = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $inserted;

    }
    
    // 주문 상세 입력
    function insertOrderItem($data=null){
	    if (!$data) return false;
    	
    	$this->db->trans_begin();
        $this->db->insert('order_items', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // 주문 코드 생성
    function generatedOrderCode(){
	    $now	= date("ymdHis"); //오늘의 날짜 년월일시분초 
		$rand	= strtoupper(substr(md5(uniqid(time())),0,6)) ; //임의의난수발생 앞6자리 
		$orderNum = $now . $rand ; 		
		return $orderNum;
    }
    
    // 주문 삭제
    function deleteOrder($id){
        $this->db->trans_begin();
        $this->db->delete('orders', array('id'=>$id));
		$this->db->delete('order_items', array('orders_id'=>$id));
		$this->db->delete('order_delivery_info', array('orders_id'=>$id));
		$this->db->delete('order_customer_info', array('orders_id'=>$id));
		$this->db->delete('member_points', array('ref_orders_id'=>$id));
		
    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // 주문자 정보 입력
    function insertOrderCustomer($data = null){
    	if (!$data) return false;
    	
    	
    	$this->db->trans_begin();
        $this->db->insert('order_customer_info', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    
    // 배송지 정보 입력
    function insertOrderDelivery($data = null){
    	if (!$data) return false;

        if (!empty($data['id'])){
            unset($data['id']);
        }
    	
    	$this->db->trans_begin();
        $this->db->insert('order_delivery_info', $data);

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