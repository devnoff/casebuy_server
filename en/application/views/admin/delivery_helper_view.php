
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Cache-Control" content="No-Cache">
<meta http-equiv="Pragma" content="No-Cache"> 
	<title>CASEBUY - <?=!empty($title)?$title:'';?></title>
	
	<link rel="stylesheet" href="/loveholic/css/shop_style.css" />
	<link type="text/css" href="/loveholic/css/ui-lightness/jquery-ui-1.8.22.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="/loveholic/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/loveholic/js/jquery-ui-1.8.22.custom.min.js"></script>
</head>

<body>

<form method="get" action="<?=site_url('admin/deliverySearch');?>">
<input type="text" placeholder="읍/면/동/리" name="keyword" value="<?=$this->input->get('keyword');?>" /> 
<input type="submit" value="검색"/>
</form>


<table border="1" width="500">
<tr>
	<td width="200">지역</td>
	<td>배송비</td>
	<td>액션</td>
</tr>
<?php
if ($result){
	foreach($result as $i){
		$addr = $i->sido.' '.$i->gugun.' '.$i->dong;
		$delivery_fee = $i->delivery_fee;
	
?>
<tr>
	<td><?=$addr;?></td>
	<td><input type="text" seq="<?=$i->seq;?>" value="<?=$delivery_fee;?>"/></td>
	<td><button type="button" seq="<?=$i->seq;?>">변경</button></td>
</tr>

<?php
	}
}
?>

</table>

<form method="post" action="<?=site_url('admin/deliveryChangeAll');?>">
검색결과 전체 적용<br/>
<input type="hidden" name="keyword" value="<?=$this->input->get('keyword');?>"/>
<input type="text" placeholder="배송비" name="fee" /> 
<input type="submit" value="적용"/>
</form>

<script>

var changeFee = function(seq, fee){
	$.ajax({
		type: 'POST',
		url: '<?=site_url('admin/changeDelivery');?>',
		data: { seq: seq, fee: fee },
		success: function(text){
			var json = eval(text);
			
			$('button[seq="'+seq+'"]').html('변경성공');
            
		}
	});
}

$('button').click(function(){
	var seq = $(this).attr('seq');
	var fee = $('input[seq="'+seq+'"]').val();
	
	$('button[seq="'+seq+'"]').html('변경중');
	
	//alert(seq + ' ' + fee);
	changeFee(seq,fee);
	
	
});
</script>

</body>

</html>