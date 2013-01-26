			<div class="nomemberCode">
				<div>
					<p class="title"><strong>주문조회</strong></p>
					<form name="form" method="GET" action="<?=site_url('shop/orderQuery/result');?>">
					<p>
						<input id="order_code" name="order_code" type="text" placeholder="주문코드를 입력해주세요" />	
					</p>
					</form>
					<p><a onclick="javascript:form.submit();" class="button"><span>조회</span></a></p>
				</div>
			</div>
			<script>
				$('#order_code').placeholder();
			</script>