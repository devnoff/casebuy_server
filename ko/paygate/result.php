<?


include_once('ci_connect.php');


$result = $_POST['result'];
$orders_id = $_POST['orders_id'];

if (!$result || !$orders_id){
	header('location:error.php?code=lack_of_params');
}


$db = new mysqlMgr;
$orders_id = mysql_real_escape_string($orders_id);
$sql = "select * from orders where id=".$orders_id;
$order = $db->query($sql);


if (!$order){
	header('location:error.php?code=lack_of_params');
}

if ($result == 'success'){
	$updateData['id'] = $orders_id;
	$updateData['order_state'] = 'PAID';

	$cardAgent = $_POST['card_agent'];
	$authCode = $_POST['auth_code'];

	$insertData['payment_method'] = 'card';
	$insertData['state'] = 'DONE';
	$insertData['rSuccYn'] = 'y';
	$insertData['rOrdNo'] = intval($orders_id);
	$insertData['rCardCd'] = $cardAgent;
	$insertData['rApprNo'] = $authCode;
	$insertData['rAmt'] = $order['payable_amount'];

	updateOrderState($orders_id,$insertData['state']);
	savePaymentResult($insertData);

} else if ($result == 'virtual'){
	$accNo = $_POST['acc_no'];
	$bankName = $_POST['bank_name'];
	$bankCode = $_POST['bank_code'];
	$dateLimit = $_POST['date_limit'];

	$insertData['VIRTUAL_CENTERCD'] = intval($bankCode);
	$insertData['payment_method'] = 'virtual';
	$insertData['rVirNo'] = $accNo;
	$insertData['state'] = 'PROCESSING';
	$insertData['rSuccYn'] = 'y';
	$insertData['rAuthTy'] = 'vir_n';
	$insertData['rOrdNo'] = intval($orders_id);
	$insertData['rAmt'] = $order['payable_amount'];

	updateOrderState($orders_id,$insertData['state']);
	savePaymentResult($insertData);
}

// var_dump($insertData);

// Empty Cart
$uuid = $_POST['uuid'];
$sql = "delete from carts where uuid='".$uuid."'";
$db->query($sql);
$db->closeDB();

// var_dump($uuid);

?>

<script>

<? if ($result == 'success') { ?>
	document.location = "scomdcom:success:<?=$orders_id;?>:card";

<? } else { ?>

	document.location = "scomdcom:success:<?=$orders_id;?>:virtual:<?=$bankName;?>:<?=$accNo;?>";
<? } ?>
</script>