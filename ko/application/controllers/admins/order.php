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
        $order_id = $this->input->post('id');

        $result = '{"success":false}';
        if ($this->orders_model->updateDelivery($data)){
            $result = '{"success":true}';

            $this->sendDeliveryNoticeSms($order_id, $data['invoice_no'], $data['delivery_agent_id']);

        } 
        echo $result;
    }

    private function sendDeliveryNoticeSms($orders_id=null,$invoice_no, $delivery_agent_id){
    	if (!$orders_id){
    		return false;
    	}


    	$this->load->helper('sms');

    	$agency = $this->db->get_where('delivery_agency',array('id'=>$delivery_agent_id))->row();
		$agency_name = $agency?$agency->agency_name:'';
		$order = $this->db->get_where('order_customer_info',array('orders_id'=>$orders_id))->row();
		$to = $order?$order->mobile:'';
		$msg = 
		"[CASEBUY] 주문하신 상품이 발송 되었습니다. ".$agency_name." ".$invoice_no;
		sendSms($to,$msg);


    }


    
    /*
	 * 찌꺼기 주문 삭제
	 * 주문일자가 하루 이상 된 주문 건 중에 아직 결제단계가 이루어지지 않은 주문 모든 기록 삭제
	 */
	function removeTrash(){
		
		$result['success'] = true;
		
		$this->db->select('id');
		$this->db->where("(date_order < DATE_SUB(NOW(), INTERVAL 1 DAY) and order_state in ('B4PAYMENT','CANCEL_DONE')) || (date_order < DATE_SUB(NOW(), INTERVAL 5 DAY) and order_state like 'WAIT_PAYMENT')");
		$trash = $this->db
					  ->get_where('orders',array('date_order < '=>"DATE_SUB(NOW(), INTERVAL 1 DAY)"))
					  ->result();//,'date_order < '=>"DATE_SUB(NOW(), INTERVAL 1 DAY)"
		

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


	/*
	 * 주문 삭제
	 */
	 
	function removeOrder(){
		
		$orders_id = $this->input->post('orders_id');
		
		$result['success'] = false;
		if (!$orders_id){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		
		$this->load->model('orders_model');

		$order = $this->orders_model->order($orders_id);
		if ($order && !in_array($order->order_state, array('CANCEL_REQUESTED','CANCEL_DONE','B4PAYMENT'))){
			$result['reason'] = '진행 중인 거래는 삭제할 수 없습니다';
		}
		
		else if ($this->orders_model->deleteOrder($orders_id)){
			$result['success'] = true;
		} 
		
		$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		
	}


	/*
	 * 관리자 결제 완료 통보
	 */
	function smsNotifyOrder($orders_id=null,$type=null){
		if (!$orders_id)
			return;

		$typeStr = $type=='done'?'결제완료':'입금대기';

		$result['success'] = false;

		$this->load->helper('sms');
		
		$order = $this->db->get_where('order_customer_info',array('orders_id'=>$orders_id))->row();
		
		if ($order){
			$msg = "[케이스바이] ".$typeStr." 내역이 있습니다. ".$order->name."님";
			$to = '01033668016'; //대표님

			if (sendSms($to,$msg)){
				$result['success'] = true;
			}
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
		
	}





}


?>