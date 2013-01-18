			<ul class="subMenu">
				<li class="selected"><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
				<li><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
				<li><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
				<li><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
				<li><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
			</ul>


			<table cellpadding="0" cellspacing="0" border="0" class="orderList">
				<tr>
					<th style="border-left:0;">주문번호·일자</th>
					<th><span>상품명 (수량)</span></th>
					<th>결제금액</th>
					<th>주문상태</th>
					<th class="final">배송정보</th>
				</tr>

			<?php
				

			
				foreach($orders as $i){
				
				
			?>
				<tr>
					<td class="a"><a href="<?=site_url('shop/orderDetail?order_code='.$i->order_code);?>"><strong><?=$i->order_code;?></strong></a><?=$i->date_order;?></td>
					<td class="b">
						<ul>
						<?php
							$orders_id = $i->id;
							$orderItems = $order_items[$orders_id];
							foreach ($orderItems as $j){	
						?>
							<li><a href="<?=site_url('shop/product?id='.$j->products_id);?>"><?=$j->product_name;?></a> <span>(x<strong><?=$j->qty;?></strong>)</span></li>
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
								$state_class='class="change"';
							break;
							case 'CANCEL_REQUESTED';
							case 'REFUND_REQUESTED';
								$state_class='class="repay"';
							break;
						}
						?>
						<span <?=$state_class;?> ><?=$i->customer_text;?></span>
						<?php if ($i->order_state == 'B4PAYMENT'){ ?>
						<br/><a onclick="payAction(<?=$i->id;?>)" style="text-decoration:underline">결제하기</a>
						<?php } ?>
					</td>
					<td class="e"><span class="end">-</span></td>
				</tr>
			<?php
				}
			?>
							
			</table>
			
			<center style="display:block;padding:30px">
				<?php
					if (!$this->input->get('all')){
				?>
				<a href="?all=true" style="text-decoration:undeline">전체 주문내역 보기</a>
				<?php
					}
				?>
			</center>
			
			<iframe name="payment_frame" src="" width="0" height="0" style="border:0"></iframe>
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
