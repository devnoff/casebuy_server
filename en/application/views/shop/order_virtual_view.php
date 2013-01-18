			<?
				function getCenter_cd($VIRTUAL_CENTERCD){
					if($VIRTUAL_CENTERCD == "39"){
						echo "경남은행";
					}else if($VIRTUAL_CENTERCD == "34"){
						echo "광주은행";
					}else if($VIRTUAL_CENTERCD == "04"){
						echo "국민은행";
					}else if($VIRTUAL_CENTERCD == "11"){
						echo "농협중앙회";
					}else if($VIRTUAL_CENTERCD == "31"){
						echo "대구은행";
					}else if($VIRTUAL_CENTERCD == "32"){
						echo "부산은행";
					}else if($VIRTUAL_CENTERCD == "02"){
						echo "산업은행";
					}else if($VIRTUAL_CENTERCD == "45"){
						echo "새마을금고";
					}else if($VIRTUAL_CENTERCD == "07"){
						echo "수협중앙회";
					}else if($VIRTUAL_CENTERCD == "48"){
						echo "신용협동조합";
					}else if($VIRTUAL_CENTERCD == "26"){
						echo "(구)신한은행";
					}else if($VIRTUAL_CENTERCD == "05"){
						echo "외환은행";
					}else if($VIRTUAL_CENTERCD == "20"){
						echo "우리은행";
					}else if($VIRTUAL_CENTERCD == "71"){
						echo "우체국";
					}else if($VIRTUAL_CENTERCD == "37"){
						echo "전북은행";
					}else if($VIRTUAL_CENTERCD == "23"){
						echo "제일은행";
					}else if($VIRTUAL_CENTERCD == "35"){
						echo "제주은행";
					}else if($VIRTUAL_CENTERCD == "21"){
						echo "(구)조흥은행";
					}else if($VIRTUAL_CENTERCD == "03"){
						echo "중소기업은행";
					}else if($VIRTUAL_CENTERCD == "81"){
						echo "하나은행";
					}else if($VIRTUAL_CENTERCD == "88"){
						echo "신한은행";
					}else if($VIRTUAL_CENTERCD == "27"){
						echo "한미은행";
					}
				}
				
				$bankCode = $payment->VIRTUAL_CENTERCD;
				$bankAccNo = $payment->rVirNo;
				$dateLimit = new DateTime($payment->date_modified);
				date_modify($dateLimit,'+5 day');
				

			?>
			
			<h4 class="checked">
				<p>
					<strong>주문이 완료되었습니다!</strong>
					<span>주문번호 <i><?=$order->order_code;?></i></span>
					<span class="b">주문번호로 결제하신 상품의 주문조회가 가능합니다</span>
				</p>
			</h4>

			<div class="virtualAccount">
				<p class="number"><? getCenter_cd($bankCode);?> <strong><?=$bankAccNo;?></strong></p>
				<p class="message">회원님의 결제를 위해 위와 같이 가상계좌번호가 생성되었습니다 (예금주 YULAB)</p>
				<ul>
					<li><span>입금 기한</span>&nbsp; <?=$dateLimit->format('Y-m-d H:i:s');?></li>
					<li><span>입금 금액</span>&nbsp; <strong><?=$order->payable_amount;?></strong>원</li>
				</ul>
			</div>


			<h5>주문한 상품</h5>
			<table cellpadding="0" cellspacing="0" border="0" class="cartWrapper">
				<tr>
					<th class="subject" colspan="2">상품명</td>
					<th>가격</td>
					<th>수량</td>
					<th class="final">총계</td>
				</tr>

			<?php
				
				foreach($order_items as $i){
			?>
				<tr>
					<td class="photo"><img src="<?=$i->web_list_img;?>"></td>
					<td class="subject">
						<p><?=$i->product_name;?></p>
					</td>
					<td class="price"><span><?=$i->item_price;?><i>원</i></span></td>
					<td class="quantity"><?=$i->qty;?></td>
					<td class="final"><span><?=$i->item_total_amount;?><i>원</i></span></td>
				</tr>
			<?php
				}
			?>
				
			</table>



			<div class="orderForm">
				<div class="left">
					<div class="container">
						<h5>주문자 정보</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>성명</span></th>
								<td><?=$order_customer->name;?></td>
							</tr>
							<tr>
								<th><span>주소</span></th>
								<td>[<?=$order_customer->zipcode;?>] <?=$order_customer->address;?></td>
							</tr>

							<tr class="phone">
								<th><span>유선전화</span></th>
								<td><?=$order_customer->telephone;?></td>
							</tr>
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td><?=$order_customer->mobile;?></td>
							</tr>
							<tr class="email">
								<th><span>이메일</span></th>
								<td><?=$order_customer->email;?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="right">
					<div class="container">
						<h5>배송지 정보</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>성명</span></th>
								<td><?=$order_delivery->name;?></td>
							</tr>
							<tr>
								<th><span>주소</span></th>
								<td>[<?=$order_delivery->zipcode;?>] <?=$order_delivery->address;?></td>
							</tr>

							<tr class="phone">
								<th><span>유선전화</span></th>
								<td><?=$order_delivery->telephone;?></td>
							</tr>
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td><?=$order_delivery->mobile;?></td>
							</tr>
							<tr class="email">
								<th><span>배송메시지</span></th>
								<td><?=$order_delivery->msg;?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			

			<div class="finalPrice">
				<ul>
					<li class="a"><span>상품금액</span><strong><?=$order->totalPrice;?><i>원</i></strong></li>
					<li class="split"><span>+</span></li>
					<li class="b"><span>배송료</span><strong><?=$order->delivery_fee;?><i>원</i></strong></li>
					<? if ($order->used_point > 0) { ?>
					<li class="split"><span>-</span></li>
					<li class="m"><span>사용적립금</span><strong><?=$order->used_point;?><i>원</i></strong></li>
					<? } ?>
					<li class="split"><span>=</span></li>
					<li class="c"><span>결제금액</span><strong><?=$order->payable_amount;?><i>원</i></strong></li>
				</ul>

			<?php if ($member) { ?>
				<a href="<?=site_url('shop');?>" class="orderedList">계속 쇼핑 하기</a>
			<?php } ?>
			</div>