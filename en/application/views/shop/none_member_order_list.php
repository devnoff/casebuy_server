			<h4>비회원조회</h4>
			<table cellpadding="0" cellspacing="0" border="0" class="orderList">
				<tr>
					<th style="border-left:0;">주문번호·일자</th>
					<th><span>상품명 (수량)</span></th>
					<th>결제금액</th>
					<th>주문상태</th>
					<th class="final">배송정보</th>
				</tr>

			<?php
				

			
				$i = $orders;
				if ($i){
				
			?>
				<tr>
					<td class="a"><strong><?=$i->order_code;?></strong><?=$i->date_order;?></td>
					<td class="b">
						<ul>
						<?php
							$orders_id = $i->id;
							$orderItems = $order_items[$orders_id];
							foreach ($orderItems as $j){	
						?>
							<li><?=$j->product_name;?> <span>(x<strong><?=$j->qty;?></strong>)</span></li>
						<?php
							}
						?>
						</ul>
					</td>
					<td class="c"><span><?=$i->payable_amount;?><i>원</i></span></td>
					<td class="d">
						<?php 
						$state_class = '';
						switch($i->order_state){
							case 'EXCHANGE_REQUESTED';
							case 'CANCEL_REQUESTED';
							case 'REFUND_REQUESTED';
								$state_class='class="reply"';
							break;
						}
						?>
						<span <?=$state_class;?> ><?=$i->customer_text;?></span>
						<?php if ($i->order_state == 'B4PAYMENT'){ ?>
						<br/><a onclick="payAction(<?=$i->id;?>)">결제하기</a>
						<?php } ?>
					</td>
					<td class="e"><span class="end">-</span></td>
				</tr>
			<?php
				} else {
			?>
				<tr>
					<td colspan="5" style="padding: 10px; text-align:center">결과가 없습니다.</td>
				</tr>
			<? 
				} 
			?>
							
			</table>
			
			<iframe name="payment_frame" src="" width="0" height="0" style="border:0;margin:0;padding:0"></iframe>
			<form id="payment_result_form" name="payment_result" method="POST" action="<?=site_url('shop/orderResult');?>">
					<input type="hidden" name="orders_id" />
					<input type="hidden" name="result" />
				</form>
			<script>
			
			var payAction = function(orders_id){
				
				checkPayable();
				$('#payment_result_form > input[name="orders_id"]').val(orders_id);
				
			}
			
			var showPaymentModule = function(orders_id, pay_method){
					showLoadingOverlay();
				
					$iframe = $('iframe[name="payment_frame"]');
	         		$iframe.load(function(){
	         			$iframe.contents().find('input[name="orders_id"]').val(orders_id);
	         			$iframe.contents().find('input[name="payment_method"]').val(pay_method);
	         			$iframe.contents().find('#payment_ready_form').submit();
	         			
	         			$iframe.contents().find('#btn_request_payment').click();
	         			
	         		});
	         		
	         		$iframe.attr('src','<?=base_url().'agspay/AGS_pay_ready.php';?>');

				}
				
				var getPoint = function (){
					var $point = $('input[name="spending_point"]');
					if ($point && $point.is(':disabled')){
						return $point.val();
					}
					return 0;
				}
				
				var checkPayable = function(){
					$iframe = $('iframe[name="payment_frame"]');
					$iframe.attr('src','<?=base_url().'agspay/AGS_check_module.php';?>');
				}
				
				var pay_module_not_avail = function(){
					hideLoadingOverlay();
					console.log('pay_module_not_avail');
				}
				
				var pay_module_available = function(){
				
					var orders_id = $('#payment_result_form > input[name="orders_id"]').val();
					var method = 'card';
					
					// 결제
					showPaymentModule(orders_id, method);
				}
				
				var payment_virtual = function(){
					console.log('가상계좌');
					
					$('#payment_result_form > input[name="result"]').val('virtual');
					$('#payment_result_form').submit();
				}
				
				var payment_success = function(){
					console.log('결제 성공');
					
					$('#payment_result_form > input[name="result"]').val('success');
					$('#payment_result_form').submit();
				}
				
				var payment_failed = function(){
					console.log('결제 실패');
					
					$('#payment_result_form > input[name="result"]').val('failed');
					$('#payment_result_form').submit();
				}
				
				var payment_canceled = function(){
					console.log('결제 취소');
					hideLoadingOverlay();
				}
				
			
			</script>
