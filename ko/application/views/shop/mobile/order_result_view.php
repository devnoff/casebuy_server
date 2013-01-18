
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.wapforum.org/DTD/xhtml-mobile12.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"> 
	<head>
		<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 
		<meta http-equiv=Cache-Control content=No-Cache>
		<meta http-equiv=Pragma	content=No-Cache>
		<meta name="format-detection" content="telephone=no" />
        <title>Case Buy - </title> 
        <link rel="stylesheet" href="/ko/css/order_detail_style.css" />
        <script type="text/javascript" src="/ko/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/ko/js/json2.js"></script>
		<script type="text/javascript">
		<!--
		 window.addEventListener('load', function(){
		  setTimeout(scrollTo, 0, 0, 1);
		 }, false);
		</script>

		<style>
		center {
			font-size: 9pt;
			padding: 10pt;
			color: #999;
			background-color: #eee;
		}		

		#wrap {
			width: 100%;
			height: 100%;
			overflow: scroll;
			text-align: center;
		}

		#deliveryIframe {
		    zoom: 0.75;
		    -moz-transform: scale(0.75);
		    -moz-transform-origin: 0 0;
		    -o-transform: scale(0.75);
		    -o-transform-origin: 0 0;
		    -webkit-transform: scale(0.75);
		    -webkit-transform-origin: 0 0;
		}		

		#closeBtn {
			width: 160px;
			height: 44px;
			background-color:black;
			color:white;
			font-weight: bold;
			font-size: 15px;
		}

		</style>
	</head>
	<body>

		<h2><span>주문 상태</span><span class="number">주문번호: <?=$data['order']->order_code;?></span></h2>
		<div class="generalWrapper">
			<div class="generalContainer">
				<span>
					<?=$data['order']->order_state_readable;?>
				<? 
				if (!empty($data['virtual'])){ 

					if ($data['order']->order_state == 'WAIT_PAYMENT'){
						echo '<i style="font-size: 10pt;margin-top:5px;margin-bottom:5px">아래의 계좌로 입금해주세요 :)</i>';
					}
				?>

				<i><?=$data['virtual']['acc_no'];?><b><?=$data['virtual']['agency'];?>・예금주: 조영운(YU LAB)</b></i>
				<? 
				} 
				?>
				</span>
			</div>
		</div>

		<h2><span>상품 및 금액</span></h2>
		<div class="tableWrapper">
			<div class="tableContainer">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr class="head">
						<td class="left"><span>상품명</span></td>
						<td class="right"><span>수량</span></td>
						<td class="right"><span>총액</span></td>
					</tr>

					<? foreach ($data['order_items'] as $item){ ?>
					<tr class="loop">
						<td class="left"><span><?=$item->product_name;?></span></td>
						<td class="right"><span><?=$item->qty;?></span></td>
						<td class="right"><span><?=$item->item_total_amount;?></span></td>
					</tr>
					<? } ?>
					<tr class="loop">
						<td class="left"><span>배송료</span></td>
						<td class="right"><span>-</span></td>
						<td class="right"><span><?=$data['order']->delivery_fee;?></span></td>
					</tr>
					<tr class="total">
						<td class="left"><span>총결제금액</span></td>
						<td colspan="2" class="right"><span><?=$data['order']->payable_amount;?></span></td>
					</tr>
				</table>
			</div>
		</div>

		<h2><span>배송 정보</span></h2>
		<div class="tableWrapper">
			<div class="tableContainer">
				<table cellpadding="0" cellspacing="0" border="0" class="delivery">
					<tr class="head">
						<td colspan="2" class="left"><span>주문자 정보</span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>이름</span></td>
						<td class="right"><span><?=$data['orderer']->name;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>연락처</span></td>
						<td class="right"><span><?=$data['orderer']->mobile;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>이메일</span></td>
						<td class="right"><span><?=$data['orderer']->email;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>주소</span></td>
						<td class="right"><span><?=$data['orderer']->address;?></span></td>
					</tr>

					<tr class="head">
						<td colspan="2" class="left"><span>배송지 정보</span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>이름</span></td>
						<td class="right"><span><?=$data['recipient']->name;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>연락처</span></td>
						<td class="right"><span><?=$data['recipient']->mobile;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>주소</span></td>
						<td class="right"><span><?=$data['recipient']->address;?></span></td>
					</tr>
					<tr class="loop">
						<td class="left"><span>배송메시지</span></td>
						<td class="right"><span><?=$data['recipient']->msg;?></span></td>
					</tr>
				</table>
			</div>
		</div>
		<h2><span>배송 추적</span></h2>
		<div class="tableWrapper">
			<div class="tableContainer">
				<table cellpadding="0" cellspacing="0" border="0" class="delivery">
					<tr class="head">
						<td class="left" style="text-align:center;">
						<? if ($data['recipient']->invoice_no){ ?>
							<a onclick="showDeliveryInquery();" style="text-decoration:underline;color:blue"><span>우체국 <?=$data['recipient']->invoice_no;?></span></a>
						<? } else { ?>
							<span>아직 배송중이 아닙니다.</span>
						<? } ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<? if ($data['order']->order_state == 'B4PAYMENT' ||$data['order']->order_state == 'WAIT_PAYMENT') { ?>
		<h2><span> </span></h2>
		<div class="tableWrapper">
			<div class="tableContainer">
				<table cellpadding="0" cellspacing="0" border="0" class="delivery">
					<tr class="head">
						<td class="left" style="text-align:center;background-color:#EB0000"  onclick="cancelOrder(<?=$data['order']->id;?>);">
						<a style="text-decoration:none;color:white;text-weight:bold"><span>주문 취소 하기</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<? } ?>

		<br/>
		<center>CultStory 대표: 윤제필 사업자등록번호: 105-87-36667 통신판매업신고번호: 제 2012-서울중구-1342 호<br/>
			주소: 서울시 중구 충무로2가 50-6 라이온스빌딩 1003 TEL: 070-8650-2086 FAX: 02-6280-7428<br/>
			개인정보관리책임자: 박용남(casebuy@cultstory.com)</center>

		<div id="wrap">
			<iframe name="deliveryIframe" id="deliveryIframe" src="http://service.epost.go.kr/iservice/trace/Trace_ok.jsp?sid1=<?=$data['recipient']->invoice_no;?>" style="display:none;position:fixed;width:100%;height:100%;overflow:scroll;border:0;left:0;top:0;" scrolling="yes" ></iframe>
			<button id="closeBtn" style="display:none;position:fixed;">닫기</button>
		</div>
		<script>
		var queryShipping = function(invoice_no){
			if (!invoice_no || invoice_no==''){
				alert('아직 배송중이 아닙니다.');
			}
		}


		// 배송 정보 보기
		var showDeliveryInquery = function(){

			$('#closeBtn').fadeIn('fast',function(){
				$('#deliveryIframe').fadeIn();
			});

			$('#closeBtn').css('top',($('#deliveryIframe').innerHeight()/2-15)+'px').css('left','80px');
		}

		$('#closeBtn').click(function(){
			$('#deliveryIframe').hide('fast',function(){
				$('#closeBtn').fadeOut();
			});	
		});


		// 주문 취소
		var cancelOrder = function(id){

			var c = confirm('정말 주문을 취소 하시겠습니까?');
			if (!c){
				return;
			}

			$.ajax({
                type: 'POST',
                url: '<?=site_url();?>/api/s/removeOrder',
                data: {orders_id:id},
                success: function(json){
                	if (json.code == 0){
                		alert('주문 취소 요청이 완료되었습니다.');
                	}
                }
            });
		};
		</script>

	</body>
</html>