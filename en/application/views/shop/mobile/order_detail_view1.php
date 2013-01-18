<?
		$emsType = '';
		$invoiceNo = $data['recipient']->invoice_no;
		if ($invoiceNo){
			$emsType = substr($invoiceNo, 0,1);
		}
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.wapforum.org/DTD/xhtml-mobile12.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"> 
	<head>
		<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 
		<meta http-equiv=Cache-Control content=No-Cache>
		<meta http-equiv=Pragma	content=No-Cache>
        <title>Case Buy - </title> 
        <link rel="stylesheet" href="<?=base_url()."css/";?>order_detail_style.css" />
        <script type="text/javascript" src="/en/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript">
		<!--
		 window.addEventListener('load', function(){
		  setTimeout(scrollTo, 0, 0, 1);
		 }, false);
		</script>

		<style>
		#bg {
			width: 100%;
			height: 100%;
			background-color: black;
			position: fixed;
			left: 0;
			top: 0;
			padding:0;
			margin: 0;
		}
		#wrap {
			width: 100%;
			height: 100%;
			overflow: scroll;
			text-align: center;
			position: relative;
			background-color: black;
		}

		#deliveryIframe {
		    zoom: 0.45;
		    -moz-transform: scale(0.45);
		    -moz-transform-origin: 0 0;
		    -o-transform: scale(0.45);
		    -o-transform-origin: 0 0;
		    -webkit-transform: scale(0.45);
		    -webkit-transform-origin: 0 0;

		    position:fixed;
		    width:100%;
		    height:100%;
		    overflow:scroll;
		    border:0;
		    left:0;
		    top:0;
		    background-color: black;

		}		

		#closeBtn {
			right: 10px;
			bottom: 24px;
			width: 60px;
			height: 24px;
			background-color:black;
			color:white;
			font-weight: bold;
			font-size: 11px;
			position: fixed;
			display: block;
		}

		#trackingBtn {
			position: absolute;
			right: 0px;
			top: 0px;
			min-width: 45px;
			height: 21px;
			background-color: #6AC9FF;
			color: black;
			font-weight: bold;
			font-family: "HelveticaNeue-CondensedBold";
			border: 0;
			padding: 4px;
			font-size: 11px;
			margin-top: 22px;
			margin-right: 10px;
		}
		</style>
	</head>
	<body class="confirm">
		<div class="paypalConfirm">
			<div class="blackContainer">
				<h3>Thank you for purchasing!</h3>
				<h5>Order Status:</h5>
				<h4><span><?=strtoupper($data['order']->order_state_readable);?></span></h4>
			</div>
			<div style="background-color:#666;position:relative">
				<div class="addressContainer">
					<p class="name"><strong>Ship to</strong> <?=$data['recipient']->name;?></p>
					<p><?=$data['recipient']->address;?> <?=$data['recipient']->country;?> <?=$data['recipient']->zipcode;?></p>
				</div>
				<? if ($invoiceNo) { ?>
				<button id="trackingBtn" onclick="showDeliveryInquery();">TRACKING</button>
				<? } ?>
			</div>
			<ul class="confirm">
				<?
				foreach ($data['order_items'] as $item){
				?>
				<li>
					<div class="left">
						<p class="title"><?=$item->product_name;?></p>
						<p><strong>Item No</strong> <?=$item->products_id;?></p>
						<p><strong>Price</strong> $<?=$item->item_price;?></p>
						<p><strong>Quantity</strong> <?=$item->qty;?></p>
						<div class="price">$<?=$item->item_total_amount;?></div>
					</div>
				</li>
				<?
				}
				?>
			</ul>
			<div class="paidContainer">
				<p class="total">TOTAL $<?=$data['order']->payable_amount;?> USD</p>
				<p>Subtotal $<?=$data['order']->totalPrice;?> • Shipping $<?=$data['order']->delivery_fee;?></p>
			</div>
		</div>	


		<div id="bg" style="display:none;"></div>
		<div id="wrap" style="display:none">
			<iframe name="deliveryIframe" id="deliveryIframe" src="http://service.epost.go.kr/trace.RetrieveEmsTraceEngTibco.postal?ems_gubun=<?=$emsType;?>&POST_CODE=<?=$invoiceNo;?>" style="" ></iframe>
			<button id="closeBtn">CLOSE</button>
		</div>
		
	
		<script>

		var showDeliveryInquery = function(){
			$('#bg').fadeIn('fast',function(){
			});
			$('#wrap').fadeIn('fast',function(){
			});

		}

		$('#closeBtn').click(function(){
			$('#bg').fadeOut('fast',function(){
			});
			$('#wrap').fadeOut('fast',function(){
				
			});	
		});
		</script>







	</body>
</html>