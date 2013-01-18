			<h4>장바구니</h4>


			<table cellpadding="0" cellspacing="0" border="0" class="cartWrapper">
				<tr>
					<th class="subject" colspan="2">상품명</td>
					<th>가격</td>
					<th>수량</td>
					<th class="final">총계</td>
				</tr>

				<?php
					if ($cart_items){
						foreach($cart_items as $i){
							$p = $i->product;
							
							$sum_price = $p->sales_price * $i->qty;
							
							$point_rate = $p->point_rate;
							$fixed_point = $p->fixed_point;
							$point_type;
							$point;
							
							if ($fixed_point == null || $fixed_point == 0){
								$point_type = 'rate';
								$point = $point_rate;
							} else {
								$point_type = 'fixed';
								$point = $fixed_point;
							}
							
				?>
				<tr class="product_item">
					<input type="hidden" point_type="<?=$point_type;?>" name="point" value="<?=$point;?>" />
					<td class="photo"><img src="<?=$p->web_list_img;?>"></td>

					<td class="subject">
						<p><?=$p->title;?></p>
						<a onclick="removeCartItem(<?=$p->id;?>)">삭제</a>
					</td>

					<td class="price"><span><?=number_format($p->sales_price);?></span><span><i>원</i></span></td>

					<td class="quantity">
						<select>
							<?php for($j=1; $j<=10; $j++){ ?>
							<option value="<?=$j;?>" <?=$i->qty==$j?'selected':''?>><?=$j;?></option>
							<?php } ?>
						</select>
					</td>

					<td class="final"><span><?=number_format($sum_price);?></span><span><i>원</i></span></td>
				</tr>
				<?php
						}	
					}
				?>
				
			</table>

			<div class="finalPrice">
				<ul>
					<li class="a"><span>상품금액</span><strong id="total_price">83,000</span><i>원</i></strong></li>
					<li class="split"><span>+</span></li>
					<li class="b"><span>배송료</span><strong>2,500<i>원</i></strong></li>
					<li class="split"><span>=</span></li>
					<li class="c"><span>결제금액</span><strong id="payable_amount">85,500<i>원</i></strong></li>
				</ul>
				<a href="#">구매하기</a>
				<span class="m">결제 시 적립금 <strong id="point_amount">855</strong>원이 적립됩니다</span>
			</div>

			<script>
				
				/* 수량 변경에 따른 가격 업데이트 */
				var updatePriceUnit = function(){
				
					var sum = 0;
					var point = 0;
					$('.product_item').each(function(){
						// 가격
						var price = $(this).find('.price span:first-child').html();
						price = price.replace(',','');
						
						var qty = $(this).find('.quantity > select').val();
						
						var item_sum = price * qty;
						sum += item_sum;
						
						// 포인트
						var p = $(this).find('input').val();
						var p_type = $(this).find('input').attr('point_type');
						var sp = p_type == 'rate' ? item_sum * (p / 100.0) :  p * qty;
						sp = parseInt(sp);
						point += sp;
						
					});
					
					// 가격 업데이트
					$('#total_price').html(addCommas(sum) + '<i>원</i>');
					$('#payable_amount').html(addCommas(sum + 2500) + '<i>원</i>');
					
					
					// 포인트 업데이트
					$('#point_amount').html(point);
					
				};

				/* 합계 금액 가져오기 */
				var getPayableAmount = function(){

				}
				
				$('select').change(function(){
					var qty = $(this).val();
					var $priceEl = $(this).parent().parent().find('.price span:first-child');
					var $sumEl = $(this).parent().parent().find('.final span:first-child');
					var price = $priceEl.html().replace(',','');
					
					var sum = qty * price;
					
					var sumStr = addCommas(sum);
					
					$sumEl.html(sumStr);

					updatePriceUnit();
				});
				
				updatePriceUnit();
				
				
				/* 장바구니 아이템 지우기 */
				var removeCartItem = function(products_id){
				
					var c = confirm('장바구니 상품을 삭제 하시겠습니까?');
					if (!c){
						return;
					}
				
					// Ajax 전송				
					$.ajax({
			    		type: 'GET',
			    		url: '<?=site_url('actions/shop/removeCartItem');?>',
			    		data: {
			    			products_id:products_id, 
			    			},
			    		success: function(text){
							location.href = '';
			    		}
			    	});
				}
				
				
				
				
			</script>