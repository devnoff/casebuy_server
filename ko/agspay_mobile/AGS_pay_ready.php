<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
<form name="payment_ready_form" method="POST" action="/ko/agspay_mobile/AGS_pay.php">
<input type="text" name="orders_id"  id="orders_id" value="<?=$_GET['orders_id'];?>"/>
<input type="text" name="payment_method" id="payment_method" value="<?=$_GET['payment_method'];?>"/>
<input type="submit" value="submit"/>
</form>
<script>
function paymentReady(orders_id,payment_method){
	document.getElementById('orders_id').value = orders_id;
	document.getElementById('payment_method').value = payment_method;
	payment_ready_form.submit();
}
</script>
</body>
</html>