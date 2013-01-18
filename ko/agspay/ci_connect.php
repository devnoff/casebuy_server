<?php



class mysqlMgr{
	var $hostname = "localhost";
	var $username = "cultstory";
	var $password = "cult1905";
	var $dbname = "caseshop_ko";
	var $db = null;
	
	public function connectDB(){
		$this->db = new mysqli($this->hostname, $this->username, $this->password, $this->dbname) or die("MySQL Server 연결에 실패했습니다");
		
		
		
		return !mysqli_connect_errno();
	}
	
	public function closeDB(){
		$this->db->close();
	}
	
	public function query($sql){
		if (!$this->connectDB()) {
			$this->failed(); return;
		}
		
		$this->db->query("SET NAMES utf8");
		
		$result = $this->db->query($sql);
		
		if ($this->db->sqlstate != 0000){
			$this->failed(); return false;
		}
		
		
		if ($result->num_rows > 1){
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$result->free();
				$this->closeDB();
				return $row;	
			} else if ($result->num_rows == 1){
				$row = $result->fetch_assoc();
				$result->free();
				$this->closeDB();
				return $row;
			}	
		
		
		return true;
	}
	
	public function insert($sql){
		if (!$this->connectDB()) {
			$this->failed(); return;
		}
		
		$this->db->query("SET NAMES utf8");
		
		$this->db->query($sql);
	
		if ($this->db->sqlstate != 0000){
			$this->failed(); return false;
		}
		
		$inserted = $this->db->insert_id;
		return $inserted;
	}
	
	public function update($sql){
		if (!$this->connectDB()) {
			$this->failed(); return;
		}
		
		$this->db->query("SET NAMES utf8");
		
		$this->db->query($sql);
	
		if ($this->db->sqlstate != 0000){
			$this->failed(); return false;
		}
		
		return true;
	}

	private function failed(){
		exit();
		echo("MySQL Server Connect 실패");
	}
}


function sqlInsert($table,$resultData){
	
	$fields = array();
	$values = array();
	
	foreach ($resultData as $key=>$value){
		array_push($fields, $key);
		
		if (gettype($value) == "string"){
			array_push($values, '"'.$value.'"');	
		} else {
			array_push($values, $value);	
		}
	}
	
	return 'insert into '.$table.' ('. implode(', ', $fields) .') values (' . implode(', ',$values) .')';
}

function savePaymentResult($resultData){
	
	$fields = array();
	$values = array();
	
	foreach ($resultData as $key=>$value){
		array_push($fields, $key);
		
		if (gettype($value) == "string"){
			array_push($values, '"'.$value.'"');	
		} else {
			array_push($values, $value);	
		}
	}
	
	$sql = 'insert into payments ('. implode(', ', $fields) .') values (' . implode(', ',$values) .')';
	
	
	// 결제 결과 입력
	$db = new mysqlMgr;	
	
	$result = $db->insert($sql);
	$db->db->commit();
		
	if (!$result)
		return false;
	
	return $result;
}

function updateOrderState($orders_id, $payment_state){

	$db = new mysqlMgr;	
	
	// 주문 상태 변경
	if ($orders_id != null && $orders_id != '0'){
	
		$order_state = 'B4PAYMENT';
		
		if ($payment_state == 'DONE'){
			$order_state = 'PAID';
		} 
		
		else if ($payment_state == 'PROCESSING'){
			$order_state = 'WAIT_PAYMENT';
		}
		
		$sql = "update orders set order_state='".$order_state."' where id=".$orders_id;	
		if (!$db->update($sql)){
			return false;
		}
		
		return true;
	}
	
	return false;
	
}

function insertPoint($orders_id,$reason="EARN_TO_BUY"){
	
	$db = new mysqlMgr;	

	$target_point = "saving_point";
	if ($reason=='SPEND_FOR_PAYMENT')
		$target_point = "used_point";

	if ($orders_id != null && $orders_id != 0){
		
		$result = $db->query('select members_id, '.$target_point.' from orders where id='.$orders_id);
		
		if ($result){
			$point = array();
			$point['members_id'] = intVal($result['members_id']);
			$point['point'] = intVal($result[$target_point]);
			
			if ($reason=='SPEND_FOR_PAYMENT')
				$point['point'] *= -1;

			$point['reason'] = $reason;
			$point['ref_orders_id'] = intVal($orders_id);
			$date = new DateTime();
			$point['date_added'] = $date->format('Y-m-d H:i:s');
			
			if ($point['point']==0){
				return true;
			}
			
			$sql = sqlInsert('member_points',$point);
			
			$result = $db->insert($sql);
			if ($result){
				// echo '<script>alert("success inserted point '.$result.'");</script>';
				return $result;
			} else {
				// echo '<script>alert("failed inserted point");</script>';
				return false;
			}
		}
		
	}
	
	return false;
}


?>