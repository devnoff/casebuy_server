			<h4><?=$page_title;?></h4>
			<form method="POST" id="orderable_form">
			<input type="hidden" name="members_id" value="<?=$members_id;?>" />
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
							
							if ($p->sales_state != 'SALE'){
								continue;
							}
							
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
					<input type="hidden" name="products_id[]" value="<?=$p->id;?>" />
					<td class="photo"><a href="<?=site_url('shop/product?id='.$p->id);?>"><img src="<?=$p->web_list_img;?>"></a></td>

					<td class="subject">
						<p class="product_name"><?=$p->title;?></p>
						<? if ($type == 'cart') {?><a onclick="removeCartItem(<?=$p->id;?>)">삭제</a> <? } ?>
					</td>

					<td class="price">
						<span><?=number_format($p->sales_price);?></span><span><i>원</i></span><br/>
						<span style="font-weight: normal; font-size:8pt;">( <?php if ($point_type=='rate') echo $point_rate.'%'; else echo '개당 '.$point.'원'; ?> 적립)</span>
					</td>

					<td class="quantity">
						<select name="quantity[]" <? if ($type == 'cart') { ?>onchange="updateCart(<?=$p->id;?>,this);"<? }?>>>
							<?php $cnt = $i->qty > 10 ? $i->qty : 10; ?>
							<?php for($j=1; $j<=$cnt; $j++){ ?>
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
			
			</form>
			<?=$orderer_info_view;?>
			
			<div class="finalPrice">
				<ul>
					<?=$user_point_view;?>
					<li class="a"><span>상품금액</span><strong id="total_price"></span><i>원</i></strong></li>
					<li class="split"><span>+</span></li>
					<li class="b"><span>배송료</span><strong id="delivery_fee" delivery_fee="2500">2,500<i>원</i></strong></li>
					<!-- 포인트 사용시 출력 -->
					<!--li class="split spending_point" style="display:none"><span>-</span></li>
					<li class="m spending_point" style="display:none"><span>적립금 사용</span><strong id="spending_point">1,000<i>원</i></strong></li -->
					<!--               -->
					
					<li class="split"><span>=</span></li>
					<li class="c"><span>결제금액</span><strong id="payable_amount"><i>원</i></strong></li>
				</ul>
				<p>도서산간지역의 경우 배송료가 추가 될 수 있습니다</p><br/>
				
				<?=$none_member_policy_view;?>
				<?=$action_btn_view;?>
			</div>
			
			<?=$none_member_login_view;?>

			<script>
				var spending_point = 0;
				
				/* 장바구니 업데이트 */
				var updateCart = function(products_id,self){
					
					var qty = $(self).val();
					
					// Ajax 전송				
					$.ajax({
			    		type: 'GET',
			    		url: '<?=site_url('actions/shop/updateCartItem');?>',
			    		data: {
			    			products_id:products_id,
			    			qty:qty
			    			},
			    		success: function(text){

			    		}
			    	});

				}
				
				
				/* 수량 변경에 따른 가격 업데이트 */
				var updatePriceUnit = function(){
				
					var sum = 0;
					var point = 0;
					var delivery_fee = parseInt($('#delivery_fee').attr('delivery_fee'));
					
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
					$('#payable_amount').html(addCommas(sum - spending_point + delivery_fee) + '<i>원</i>');
					
					
					// 포인트 업데이트
					$('#point_amount').html(addCommas(point));
					
				};

				/* 결제액 구하기 */
				var getPayableAmount = function(){
					var sum = 0;
					var point = 0;
					var delivery_fee = parseInt($('#delivery_fee').attr('delivery_fee'));
					
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
					
					
					return (sum - spending_point + delivery_fee);
				}

				/* 결제 타이틀 구하기 */
				var getPaymentTitle = function(){
					var cnt = 0;
					var title = '';
					$('.product_item').each(function(){
						if (cnt == 0)
							title = $(this).find('.product_name').html();

						cnt++;
					});

					if (cnt > 1){
						title += '외 '+(cnt-1);
					}

					return title;
				}

				
				$('select[name^="quantity"]').change(function(){
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
			    		data: { products_id:products_id },
			    		success: function(text){
							location.href = '<?=current_url();?>';
			    		}
			    	});
				};
				
				
			</script>