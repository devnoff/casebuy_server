				<div class="pay">
					<input type="radio" name="payment_method" value="onlyvirtual" style="margin-left: 0;"><span>가상계좌</span>
					<input type="radio" name="payment_method" value="onlycard" checked="checked"><span>신용카드</span>
					<input type="radio" name="payment_method" value="onlyiche"><span>계좌이체</span>
					<input type="radio" name="payment_method" value="onlyhp" disabled><span>휴대폰결제</span>
				</div>

				<a id="btn_payment">결제하기</a>
				<?php if ($member) { ?>
				<span class="m">회원 결제 시 적립금 <strong id="point_amount"></strong>원이 적립됩니다</span>
				<?php } ?>
				
				<iframe name="payment_frame" src="" width="0" height="0" style="border:0"></iframe>
				<form id="payment_result_form" name="payment_result" method="POST" action="<?=site_url('shop/orderResult');?>">
					<input type="hidden" name="orders_id" />
					<input type="hidden" name="result" />
				</form>
				<script>
				
				var is_payable = false;
				var is_ie =  $.browser.msie;
				var is_member = '<?=$member?"Y":"N"?>';
				
				$('#btn_payment').click(function(){
					
					checkPayable();
					
				});
				
				var validation = function(){
					var success = true;
					var msg = '';
					
					$('#recipient_form').find('input').each(function(){
						var value = $(this).val();
						if (value.length < 1){
							success = false;
							msg = $(this).attr('alt');
							return false;
						}
					});
					
					$('#orderer_form').find('input').each(function(){
						var value = $(this).val();
						if (value.length < 1){
							success = false;
							msg = $(this).attr('alt');
							return false;
						}
					});
					
					
					if (!success) {
						alert('다음 항목을 채워주세요. ('+msg+')');
						return false;
					}
					
					
					if (is_member=='N'){
						var check = $('input[name="none_member_policy_agree"]').is(':checked');
						if (!check){
							alert('비회원 구매 약관에 동의 하셔야 구매하실 수 있습니다.');
							return false;
						}
					}
					
					return success;
					
				}
				

				
				var pay = function(){
					
					if (!validation()) return;
					
					console.log('pay');
					
					showLoadingOverlay();
					
					var $disabled = $('input:disabled');
					
					var data = new Array();
					
					data[0] = 'using_point='+getPoint(); // 사용 포인트
					
					$disabled.attr('disabled',false); 

					data[1] = $('#orderer_form').serialize(); // 주문자 정보
					data[2] =$('#recipient_form').serialize(); // 배송지 정보
					data[3] = $('#orderable_form').serialize(); // 주문 상품 정보
					
					console.log(data.join('&'));					
					var serializedData = data.join('&');
					
					$.ajax({
			         	type: "POST",
			         	url: '<?=site_url('actions/shop/inputOrder');?>',
			         	data: serializedData,
			         	success: function(json) {
			         		console.log(json);
			         		var result = json;
			         		
			         		if (result.success){
			         			$('#payment_result_form > input[name="orders_id"]').val(result.orders_id);
			         			
			         			if (is_payable){
				         			showPaymentModule(result.orders_id);	
			         			} else {
			         				if (is_member=='Y')
					         			location.href="<?=site_url('shop/my');?>";
					         		else 
					         			location.href="<?=site_url('shop/orderQuery/result');?>?order_code="+result.order_code;
			         			}
			         			
			         		} else {
			         			alert(result.reason);
				         		hideLoadingOverlay();
			         		}
			         		
			         		$disabled.attr('disabled',true);

				        }
					}).fail(function(){
						alert('결제 실패');
						hideLoadingOverlay();
					});
				}
				
				var checkPayable = function(){
					$iframe = $('iframe[name="payment_frame"]');
					$iframe.attr('src','<?=base_url().'agspay/AGS_check_module.php';?>');
				}

				
				var showPaymentModule = function(orders_id){
					var pay_method = $('input[name="payment_method"]:checked').val();
				
					$iframe = $('iframe[name="payment_frame"]');
					$iframe.unbind('load');
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
				
				
				var saveFormState = function(){
					var $disabled = $('input:disabled');
					$disabled.attr('disabled',false); 
					
					var orderer = $('#orderer_form').serializeArray();
					var recipient = $('#recipient_form').serializeArray();
					
					$disabled.attr('disabled',true);
					
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/shop/saveOrderFormState');?>',
			    		data: { orderer: json_stringify(orderer), recipient: json_stringify(recipient) },
			    		success: function(text){
			    			console.log(text);
			    			//var json = eval('('+text+')');
			    			
			                
			    		}
			    	});
				}
				
				var pay_module_not_avail = function(){
					
					is_payable = false;
					
					if (!is_ie){
						var c = confirm('인터넷 익스플로러와 아이폰, 안드로이드폰의 ‘CASEBUY’ 앱에서 결제하실 수 있습니다. 주문입력 후 나중에 결제 하시겠습니까?');
						if (c){
							console.log('confirm');
							pay();
						}
						
					} else {
						// 상태 저장
						saveFormState();	
					}
				}
				
				var pay_module_available = function(){
					saveFormState();
					
					// 결제
					is_payable = true;
					pay();
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
					
					hideLoadingOverlay();
					
					alert('결제를 성공하지 못했습니다. 관리자에게 문의하세요');
/*	
					$('#payment_result_form > input[name="result"]').val('failed');
					$('#payment_result_form').submit();
*/
				}
				
				var payment_canceled = function(){
					console.log('결제 취소');
					hideLoadingOverlay();
					
					var orders_id = $('#payment_result_form > input[name="orders_id"]').val();
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/shop/removeOrder');?>',
			    		data: { orders_id : orders_id },
			    		success: function(text){
			    			console.log('order id '+orders_id+' cancelled '+text.success);
			    		}
			    	});
				}
				
				</script>
