<?php

	
?>

			<div class="orderForm">
				<div class="left">
					<div class="container">
						<h5>주문자 정보</h5>
						<form id="orderer_form">
						<input delivery_fee type="hidden" id="orderer_delivery_fee" value="<?=$orderer_delivery_fee;?>"/>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>성명</span></th>
								<td><input name="orderer_name" type="text" alt="주문자성명" value="<?=$orderer_name;?>"/></td>
							</tr>
							<tr class="adress1">
								<th><span>주소</span></th>
								<td>
									<input class="postcode_first" name="orderer_postcode[0]" type="text" alt="우편번호" disabled value="<?=$orderer_postcode[0];?>" /><span>-</span>
									<input class="postcode_last" name="orderer_postcode[1]" type="text" alt="우편번호" disabled value="<?=$orderer_postcode[1];?>" />
									<input type="button" value="우편번호검색" class="search" onclick="showZipcodeForm(true,'#orderer_form');" />
								</td>
							</tr>
							<tr class="adress2">
								<th><span>기본주소</span></th>
								<td><input class="base_addr" name="orderer_address[0]" type="text" alt="기본 주소" disabled value="<?=$orderer_address[0];?>" /></td>
							</tr>
							<tr class="adress3">
								<th><span>나머지주소</span></th>
								<td><input class="rest_addr" name="orderer_address[1]" type="text" alt="나머지 주소" value="<?=$orderer_address[1];?>" /></td>
							</tr>
							<tr class="phone">
								<th><span>유선전화</span></th>
								<td>
									<select name="orderer_telephone[0]">
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="042">042</option>
										<option value="043">043</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="064">070</option>
									</select>
									<script>
										$('select[name="orderer_telephone[0]"]').val('<?=$orderer_telephone[0];?>');
									</script>
									<span>-</span>
									<input name="orderer_telephone[1]" type="text" alt="유선전화" value="<?=$orderer_telephone[1];?>" />
									<span>-</span>
									<input name="orderer_telephone[2]" type="text" alt="유선전화" value="<?=$orderer_telephone[2];?>" />
								</td>
							</tr>
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td>
									<select name="orderer_mobile[0]">
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
									</select>
									<script>
										$('select[name="orderer_mobile[0]"]').val('<?=$orderer_mobile[0];?>');
									</script>
									<span>-</span>
									<input name="orderer_mobile[1]" type="text" alt="휴대전화" maxlength="4" value="<?=$orderer_mobile[1];?>" />
									<span>-</span>
									<input name="orderer_mobile[2]" type="text" alt="휴대전화" maxlength="4" value="<?=$orderer_mobile[2];?>" />
								</td>
							</tr>
							<tr class="email">
								<th><span>이메일</span></th>
								<td><input name="orderer_email" type="text" alt="이메일" value="<?=$orderer_email;?>" /></td>
							</tr>
						</table>
						</form>
					</div>
				</div>
				<div class="right">
					<div class="container">
						<h5>배송지 정보</h5>
						<div class="checkbox"><input id="same_with_orderer_btn" type="checkbox"><span>주문자 정보와 동일</span></div>
						<form id="recipient_form">
						<input delivery_fee type="hidden" id="recipient_delivery_fee" value="<?=$recipient_delivery_fee;?>"/>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>수령인</span></th>
								<td><input name="recipient_name" type="text"  alt="배송지 수령인" value="<?=$orderer_name;?>"/></td>
							</tr>
							<tr class="adress1">
								<th><span>주소</span></th>
								<td>
									<input class="postcode_first" name="recipient_postcode[0]" type="text" alt="배송지 우편번호" disabled value="<?=$orderer_postcode[0];?>" /><span>-</span>
									<input class="postcode_last" name="recipient_postcode[1]" type="text" alt="배송지 우편번호" disabled value="<?=$orderer_postcode[1];?>" />
									<input type="button" value="우편번호검색" class="search" onclick="showZipcodeForm(true,'#recipient_form',true);" />
								</td>
							</tr>
							<tr class="adress2">
								<th><span>기본주소</span></th>
								<td><input class="base_addr" name="recipient_address[0]" type="text" alt="배송지 주소" value="<?=$orderer_address[0];?>" disabled /></td>
							</tr>
							<tr class="adress3">
								<th><span>나머지주소</span></th>
								<td><input class="rest_addr" name="recipient_address[1]" type="text" alt="배송지 주소" value="<?=$orderer_address[1];?>" /></td>
							</tr>
							<tr class="phone">
								<th><span>유선전화</span></th>
								<td>
									<select name="recipient_telephone[0]">
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="042">042</option>
										<option value="043">043</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="064">070</option>
									</select>
									<script>
										$('select[name="recipient_telephone[0]"]').val('<?=$recipient_telephone[0];?>');
									</script>
									<span>-</span>
									<input name="recipient_telephone[1]" type="text" alt="배송지 유선전화" value="<?=$recipient_telephone[1];?>" maxlength="4" />
									<span>-</span>
									<input name="recipient_telephone[2]" type="text" alt="배송지 유선전화" value="<?=$recipient_telephone[2];?>" maxlength="4" />
								</td>
							</tr>
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td>
									<select name="recipient_mobile[0]">
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
									</select>
									<script>
										$('select[name="recipient_mobile[0]"]').val('<?=$recipient_mobile[0];?>');
									</script>
									<span>-</span>
									<input name="recipient_mobile[1]" type="text" alt="배송지 휴대전화" maxlength="4" value="<?=$recipient_mobile[1];?>" />
									<span>-</span>
									<input name="recipient_mobile[2]" type="text"  alt="배송지 휴대전화" maxlength="4" value="<?=$recipient_mobile[2];?>" />
								</td>
							</tr>
							<tr class="email">
								<th><span>배송메시지</span></th>
								<td><input name="recipient_msg" type="text" value=" " value="<?=$recipient_msg;?>"/></td>
							</tr>
						</table>
						</form>
					</div>
				</div>
			</div>
			
			
			
			<!-- 우편번호 검색 -->
			<div id="zipcode_form" style="display:none; z-index:1">
				<h4>우편번호 검색</h4>
				<form>
				<input name="keyword" type="text" placeholder="읍/면/동" />  <button type="submit">검색</button><br/>
				</form>
				<select size="10" name="result_list" disabled>
                    <option>검색 결과</option>
                <select>
                <br/>
                <button type="button" class="apply" onclick="applyZipcode();">적용</button>
                <button type="button" class="cancel" onclick="showZipcodeForm(false,'');">취소</button>

			</div>
			
			
			<script>
			
			
			
			/** 우편번호 검색 **/
			var zipcode_form_showing = false;
			var $zip_form = $('#zipcode_form');
			var $keyword_input = $('#zipcode_form').find('input[name="keyword"]');;
			var $zipcode_select = $('#zipcode_form > select');
			var zip_target = '';
			var zipcode_result = null;
			var apply_delivery = false;
			
			//
			$zip_form.find('form').submit(function(e){
				e.preventDefault();
				
				searchZipcode();
			});
			
			/* 검색폼 */
			var showZipcodeForm = function(show, target, delivery_apply){
				
				$zip_form.center();

				apply_delivery = delivery_apply;
				
				if (!show){
					unlockScroll();
					hideLoadingOverlay();
					$zip_form.hide();
					zipcode_form_showing = false;
					$zipcode_select.attr('disabled',true);
				} else {
					lockScroll();
					showLoadingOverlay();
					$zip_form.show();
					$keyword_input.focus();
					zipcode_form_showing = true;
				}
				
				zip_target = target;
			}
			
			/* 검색 결과 뿌리기 */
			var dispZipcodeResult = function(){
				$zipcode_select.attr('disabled',false);
				$zipcode_select.empty();
				
				if (zipcode_result){
					for (var i in zipcode_result){
						$('<option>').attr('delivery_fee',zipcode_result[i].delivery_fee).val(i).html('('+zipcode_result[i].zipcode+') '+ zipcode_result[i].addr_full).appendTo($zipcode_select);
					}
				}
			}
			
			
			/* 검색 */
			var searchZipcode = function(){
				
				var keyword = $keyword_input.val();
				$.ajax({
		    		type: 'GET',
		    		url: '<?=site_url('actions/shop/zipcode');?>',
		    		data: { keyword: keyword },
		    		success: function(text){
		    			var json = eval(text);
		                zipcode_result = json;
		                dispZipcodeResult();
		                
		    		}
		    	});
			}
			
			/* 도서지방 추가배송료 적용 */
			var applyExtraDeliveryFee = function(fee){
				
				if (fee > 3000){
						alert('도서 산간 지방은 추가 배송료가 적용됩니다.');
					}
				$('#delivery_fee').attr('delivery_fee',fee).html(addCommas(fee)+'<i>원</i>');
				updatePriceUnit();
			}
			
			
			/* 적용 */
			var applyZipcode = function(){
				
				var delivery_fee = $zipcode_select.find('option:selected').attr('delivery_fee'); 
				if (apply_delivery){
					applyExtraDeliveryFee(delivery_fee);	
				}
				
				
				var idx = $zipcode_select.val();
				var zipcode = zipcode_result[idx].zipcode;
				zipcode = zipcode.split('-');
				
				$(zip_target).find('.postcode_first').val(zipcode[0]);
				$(zip_target).find('.postcode_last').val(zipcode[1]);
				$(zip_target).find('.base_addr').val(zipcode_result[idx].addr_base);
				$(zip_target).find('input[delivery_fee]').val(delivery_fee);
				
				showZipcodeForm(false,'');
				clearZipcodeForm();
			}
			
			/* 초기화 */
			var clearZipcodeForm = function(){
				$keyword_input.val(null);
				$zipcode_select.empty();
				zipcode_result = null;
			}
			
			/** 주문자와 동일 체크박스 **/
			$('#same_with_orderer_btn').click(function(){
				var checked = $(this).is(':checked');
				
				$recipient_name = $('input[name="recipient_name"]');
				$recipient_postcode_0 = $('input[name="recipient_postcode[0]"]');
				$recipient_postcode_1 = $('input[name="recipient_postcode[1]"]');
				$recipient_address_0 = $('input[name="recipient_address[0]"]');
				$recipient_address_1 = $('input[name="recipient_address[1]"]');
				
				$recipient_telephone_0 = $('select[name="recipient_telephone[0]"]');
				$recipient_telephone_1 = $('input[name="recipient_telephone[1]"]');
				$recipient_telephone_2 = $('input[name="recipient_telephone[2]"]');
				
				$recipient_mobile_0 = $('select[name="recipient_mobile[0]"]');
				$recipient_mobile_1 = $('input[name="recipient_mobile[1]"]');
				$recipient_mobile_2 = $('input[name="recipient_mobile[2]"]');
				
				$recipient_delivery_fee = $('#recipient_delivery_fee');
				
				if (checked){
					$recipient_name.val($('input[name="orderer_name"]').val());
					$recipient_postcode_0.val($('input[name="orderer_postcode[0]"]').val());
					$recipient_postcode_1.val($('input[name="orderer_postcode[1]"]').val());
					$recipient_address_0.val($('input[name="orderer_address[0]"]').val());
					$recipient_address_1.val($('input[name="orderer_address[1]"]').val());
					
					$recipient_telephone_0.val($('select[name="orderer_telephone[0]"]').val());
					$recipient_telephone_1.val($('input[name="orderer_telephone[1]"]').val());
					$recipient_telephone_2.val($('input[name="orderer_telephone[2]"]').val());
					
					$recipient_mobile_0.val($('select[name="orderer_mobile[0]"]').val());
					$recipient_mobile_1.val($('input[name="orderer_mobile[1]"]').val());
					$recipient_mobile_2.val($('input[name="orderer_mobile[2]"]').val());

					$recipient_delivery_fee.val($('#orderer_delivery_fee').val());

				} else {
					$recipient_name.val(null);
					$recipient_postcode_0.val(null);
					$recipient_postcode_1.val(null);
					$recipient_address_0.val(null);
					$recipient_address_1.val(null);
					
					$recipient_telephone_0.val(null);
					$recipient_telephone_1.val(null);
					$recipient_telephone_2.val(null);
					
					$recipient_mobile_0.val(null);
					$recipient_mobile_1.val(null);
					$recipient_mobile_2.val(null);
					$recipient_delivery_fee.val(2500);
				}

				applyExtraDeliveryFee($recipient_delivery_fee.val());
			});
			
			
			var restore = '<?=$restore;?>';
			if (restore == '1'){
				var orderer = eval('<?=$orderer;?>');
				var recipient = eval('<?=$recipient;?>');
				
				for (var i in orderer){
					$('input[name="'+orderer[i].name+'"]').val(orderer[i].value);
				}
				
				for (var i in recipient){
					$('input[name="'+recipient[i].name+'"]').val(recipient[i].value);
				}
			}


			///
			applyExtraDeliveryFee(<?=$recipient_delivery_fee;?>);

			
			</script>
			
			