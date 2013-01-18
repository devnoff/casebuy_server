<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class Order extends Action {


	function __construct()
	{
		parent::__construct();
		
        $this->load->model('orders_model');
       
        
        
	}
	
	
	/*
	 * 주문상태 목록
	 * 
	 */
	function orderStates(){
		$result = $this->orders_model->orderStates();
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
 	
 	/*
 	 * 주문 업데이트
 	 *
 	 */
    function updateOrder($ajax=false){
        $data = $this->input->post();
        $redirect = null;
        if (!empty($data['redirect'])){
            $redirect = $data['redirect'];
        }
        unset($data['redirect']);
        
        if ($ajax){
            $result = '{"success":false}';
            if ($this->orders_model->update($data)){
                $result = '{"success":true}';
            } 
            echo $result;
        } else {
            $result = "<script>alert('실패');</script>";
            if ($this->orders_model->update($data)){
                $result = "<script>alert('성공');</script>";
            } 

            echo $result."<script>location.href='".$redirect."'</script>";    
        }
    }
    
    /*
     * 운송장 번호 업데이트
     *
     */
    function updateInvoiceNo(){
        $data = $this->input->post();

        $result = '{"success":false}';
        if ($this->orders_model->updateDelivery($data)){
            $result = '{"success":true}';
        } 
        echo $result;
    }
    
    /*
	 * 찌꺼기 주문 삭제
	 * 주문일자가 하루 이상 된 주문 건 중에 아직 결제단계가 이루어지지 않은 주문 모든 기록 삭제
	 */
	function removeTrash(){
		
		$result['success'] = true;
		
		$this->db->select('id');
		$trash = $this->db->get_where('orders',array('order_state'=>'B4PAYMENT'))->result();//,'date_order < '=>"DATE_SUB(NOW(), INTERVAL 1 DAY)"
		

		if($trash){
			$this->load->model('orders_model');
			foreach ($trash as $t){
				$id = $t->id;
				if (!$this->orders_model->deleteOrder($id)){
					$result['success'] = false;
					$result['failed_items'][] = $id;
					$result['reason'] = '일부항목 삭제 실패';
				}
			}
		}
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
	}
}


?>