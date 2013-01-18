			
			<h4 style="margin-bottom: 30px;">주문번호 <?=$order->order_code;?> 
			<span class="odate">
			주문일 <strong><?=substr($order->date_order,0,10);?></strong>
			<?php if ($payment && $payment->state=='DONE'){ ?>
			 | 결제일 <strong><?=substr($paid_date,0,10);?> </strong>
			<?php } ?>
			| 주문상태 : <?=$order_state;?>
			
			</span>
			</h4>

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
						<p style="cursor:pointer;" onclick="javascript:window.open('<?=site_url('shop/product?id='.$i->products_id);?>','_blank');"><?=$i->product_name;?><?=$i->product_option_name!='' ? '('.$i->product_option_name.')':'';?></p>
					</td>
					<td class="price"><span><?=number_format($i->item_price);?><i>원</i></span></td>
					<td class="quantity"><?=$i->qty;?></td>
					<td class="final"><span><?=number_format($i->item_total_amount);?><i>원</i></span></td>
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
			
			<h5 style="margin-top:30px;">결제 정보</h5>
			<?php
			
				if ($payment){
			?>
			<table cellpadding="0" cellspacing="0" border="0" class="fullTable checkedOut">
				<tr class="name">
					<th>결제수단</th>
					<td><?=$payment_type;?></td>
				</tr>
				<tr class="name">
					<th>결제내역</th>
					<td><?=$payment_detail;?></td>
				</tr>
			</table>
			<?php
				} else {
			?>
			
			<table cellpadding="0" cellspacing="0" border="0" class="fullTable checkedOut">
				<tr>
					<td>결제한 내역이 없습니다.</td>
				</tr>
			</table>
			
			<?php
				}
			?>

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
				<a onclick="window.history.back();" class="orderedList">돌아가기</a><!-- href="<?=site_url('shop/my');?>" -->
			<?php } ?>
			</div>
			<br/>
			