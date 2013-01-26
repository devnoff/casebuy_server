					<!--div class="useMileage">
						<span><i>적립금 사용</i> (보유 적립금: <strong><?=$user_point;?></strong>원)</span><br/>
						<p>적립금은 5,000원 이상부터 사용하실 수 있습니다.</p>
						<input name="spending_point" type="text" class="text" placeholder="사용할 적립금 입력" onKeyPress="return numbersonly(event, false)">
						<input onclick="usePointForPayment()" type="button" value="사용" class="button"  id="usePointButton">
						<input onclick="resetSpendingPoint()" type="button" value="초기화" class="button" id="resetPointButton" style="display:none">
						<input type="hidden" type="checkbox" name="using_point" />
					</div><br/>
					
					<script>
					
					var dispPointState = function(){
						$('#spending_point').html(spending_point + '<i>원</i>');
						
						if (spending_point > 0){
							$('.spending_point').show();
						} else {
							$('.spending_point').hide();
						}
						
						updatePriceUnit();
					};
					
					<?php
					
						$products_amount = 0;
						
						
						foreach($cart_items as $i){
							$p = $i->product;
							$sum_price = $p->sales_price * $i->qty;
							$products_amount += $sum_price;
						}
					
					?>
					
					var usePointForPayment = function(){
						$('input[name="spending_point"]').attr('disabled', true);
						$('input[name="spending_point"], #usePointButton').hide();
						
						
						var s_point = $('input[name="spending_point"]').val();
						var user_point = parseInt('<?=str_replace(',','',$user_point);?>');
						var total_price = parseInt('<?=$products_amount;?>');
						
						console.log(s_point + ' ' + user_point);
						
						if (s_point < 5000){
							alert("적립금은 5000원 이상부터 사용 가능합니다.");
							resetSpendingPoint();
							return;
						}
						
						if (s_point > total_price){
							alert("상품 가격 이내에서 적립금을 이용이 가능합니다. 적립금 사용금액을 다시 확인해주세요.");
							resetSpendingPoint();
							return;
						}

						
						if (s_point > user_point){
							alert("보유하신 포인트 내에서 이용이 가능합니다.");
							resetSpendingPoint();
							return;
						}
						
						$('#resetPointButton').show();
						$('input[name="using_point"]').attr('checked',true);
						spending_point = s_point;
						
						dispPointState();
					}
					
					var resetSpendingPoint = function(){
						$('input[name="spending_point"]').attr('disabled',false);
						$('input[name="spending_point"], #usePointButton').show();
						spending_point = 0;
						$('input[name="spending_point"]').val(null);
						$('input[name="using_point"]').attr('checked',false);
						$('#resetPointButton').hide();
						dispPointState();
					};
					</script -->