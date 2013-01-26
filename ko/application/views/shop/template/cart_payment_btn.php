				<div id="payment_method_place">
					<div class="pay">
						<input type="radio" name="payment_method" value="virtual" style="margin-left: 0;display:none"><span style="display:none">가상계좌</span>
						<input type="radio" name="payment_method" value="card" checked="checked"><span>신용카드</span>
					</div>

					<?php if (false) {  // $member ?>
					<span class="m">회원 결제 시 적립금 <strong id="point_amount"></strong>원이 적립됩니다</span>
					<?php } ?>
				</div>
				<div id="payment_option_place" style="display:none;">
					<label>입금일자</label>
					<select id="option_year"><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
			            <option value="2012">2012</option>
			            <option value="2013">2013</option>
			            <option value="2014">2014</option>
			        </select> - <select id="option_month"><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
			            <option value=""></option>
			            <option value="01">01</option>
			            <option value="02">02</option>
			            <option value="03">03</option>
			            <option value="04">04</option>
			            <option value="05">05</option>
			            <option value="06">06</option>
			            <option value="07">07</option>
			            <option value="08">08</option>
			            <option value="09">09</option>
			            <option value="10">10</option>
			            <option value="11">11</option>
			            <option value="12">12</option>
			        </select> - <select id="option_day"><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
			            <option value=""></option>
			            <option value="01">01</option>
			            <option value="02">02</option>
			            <option value="03">03</option>
			            <option value="04">04</option>
			            <option value="05">05</option>
			            <option value="06">06</option>
			            <option value="07">07</option>
			            <option value="08">08</option>
			            <option value="09">09</option>
			            <option value="10">10</option>
			            <option value="11">11</option>
			            <option value="12">12</option>
			            <option value="13">13</option>
			            <option value="14">14</option>
			            <option value="15">15</option>
			            <option value="16">16</option>
			            <option value="17">17</option>
			            <option value="18">18</option>
			            <option value="19">19</option>
			            <option value="20">20</option>
			            <option value="21">21</option>
			            <option value="22">22</option>
			            <option value="23">23</option>
			            <option value="24">24</option>
			            <option value="25">25</option>
			            <option value="26">26</option>
			            <option value="27">27</option>
			            <option value="28">28</option>
			            <option value="29">29</option>
			            <option value="30">30</option>
			            <option value="31">31</option>
			        </select>
			        &nbsp;&nbsp;
			        <label>입금은행</label>
			        <select id="option_bankcode">
			            <option value=''></option>
			            <option value='04'>KB 국민은행</option>
			            <option value='11'>농협</option>
			            <option value='26'>신한은행</option>
			            <option value='20'>우리은행</option>
			            <option value='71'>우체국</option>
			            <option value='81'>하나은행</option>
			            <option value='03'>기업은행</option>
			        </select>
			        
				</div>
				<div id="payment_option_place1" style="padding:15px">
					<label>카드사 선택: </label>
					<select id="option_cardtype">
			        	<option value=""></option>
			        	<option value="310">비씨(BC)카드</option>
						<option value="310">MG새마을체크카드</option>
						<option value="310">우체국(BC)카드</option>
						<option value="410">신한(LG)카드</option>
						<option value="510">삼성카드</option>
						<option value="610">현대카드</option>
						<option value="110">국민(KB)카드</option>
						<option value="710">롯데카드</option>
						<option value="210">외환카드</option>
						<option value="912">농협(NH)카드</option>
						<option value="923">씨티카드</option>
						<option value="913">우리카드</option>
						<option value="920">전북카드</option>
						<option value="925">수협카드</option>
						<option value="610">신협(현대)카드</option>
						<option value="511">삼성올앳카드</option>
					</select>
				</div>
				<div id="payment_btn_place">
					<a id="btn_payment">결제하기</a><br/>
				</div>
				<div id="payment_module_place">
					<script language="javascript" src="https://api.paygate.net/ajax/common/OpenPayAPI.js"> </script><!--(꼭!필요합니다.)Openpayapi.js 파일을 불러와서 저희 모듈에서 결제를 시작합니다. -->
					<script language="javascript">
					 
					var paygate_replycode = 1111;

					function getPGIOresult() {                                                 //결과를 출력하기 위한 명령어들을 호출합니다. //
					    verifyReceived(getPGIOElement("tid"),'callbacksuccess','callbackfail');
					    var replycode = document.PGIOForm.elements['replycode'].value;
					    var replyMsg = document.PGIOForm.elements['replyMsg'].value;
					    displayStatus(getPGIOElement('ResultScreen'));


					    if (replycode != '0000'){
					    	alert(replycode + replyMsg);
					    	payment_canceled();

					    } else {
					    	// 성공 시
					    	var method = $('input[name="payment_method"]:checked').val();
							if (method == 'virtual')
								payment_virtual();
							else 
								payment_success();

					    }


					    
					}
					 
					</script>
					<div id="PGIOscreen"></div>  <!-- (꼭!필요합니다.)openpay API 의 결제 화면을 불러 오는 함수입니다. 절대 form안에는 넣지 말아주세요 오류의 원인이 됩니다. -->

						 
					<form name="PGIOForm"><!-- (꼭!필요합니다.)openpay API 에 입력되게될 form값입니다. 이안에서 결제 방식 구매자명 머천트계정 가격등 결제진행 방향을 정하게 됩니다.-->
					<table border=0 cellpadding=0  style="display:none;">
					    <TR>
					        <th>Style</th>
					        <td><select name="kindcss"><!-- 스타일을 정할수 있습니다. no로 선택하실 경우 상점측에서 css를 새로 구성하실수 있습니다. -->
					            <option value=""></option>
					            <option value="0">Style 0</option>
					            <option value="1">Style 1</option>
					            <option value="2">Style 2</option>
					            <option value="3">Style 3</option>
					            <option value="4">Style 4</option>
					            <option value="5">Style 5</option>
					            <option value="no">no style</option>
					        </select></td>
					        <td>&nbsp;</td>
					        <th>MID</th>
					        <td><input type=text name=mid value="cultstory"></td><!-- 발급받으신 상점 id를 넣어주세요(paygatekr로 테스트 하셔도 됩니다.) -->
					    </TR>
					 
					    <tr>
					        <th>langcode</th> <!--저희 결제 모듈의 언어를 정하게 됩니다. -->
					        <td><select name=langcode>
					            <option value=""></option>
					            <option value="KR" selected>KR</option>
					            <option value="US">US</option>
					            <option value="JP">JP</option>
					            <option value="CN">CN</option>
					        </select></td>
					        <td>&nbsp;</td>
					        <th>PayMethod</th>
					        <td><select name=paymethod><!--결제 방식을 선택하게 됩니다.  현재는 카드에 관련 값들만 들어가있지만 머천트의 선택에 따라 실시간 계좌이체 혹은 가상계좌 휴대폰 결제가 선택가능합니다.(paymethod참조)-->
					            <option value=""></option>
					            <option value="card" selected="selected" >CARD</option>
					            <option value=100>BASIC</option> <!-- 비인증 거래를 하게됩니다. 페이게이트의 허가가 필요합니다. 사전에 문의해주세요  -->
					            <option value=101>BASIC_AUTH</option> <!-- 비인증 거래에 속합니다. 페이게이트의 허가가 필요합니다. 사전에 문의해주세요 -->
					            <option value=102>ISP</option> <!-- ISP 거래로 바로 시작하게 됩니다. -->
					            <option value="103">VISA3D</option> <!-- 안심클릭 결제를 진행하게 됩니다. -->
					            <option value=104>BASIC_USD</option> <!-- 달러 정산인 업체가 진행하게 됩니다. 달러결제 달러 정산인 경우 사용합니다. -->
					            <option value=999>ESCROW</option>
					            <option value=7>BTNOTICE</option> <!-- 가상계좌 및 무통장 입금 결제입니다. -->
					            <option value=4>RTBT</option> <!-- 실시간 계좌이체로 LG U+ 결제 모듈을 사용하고 있습니다. -->
					            <option value=801>PHONE</option> <!-- 휴대폰 소액결제를 사용하게됩니다. -->
					            <option value="106">China Alipay</option> <!-- 알리페이 결제를 사용하게 됩니다. -->
					 
					        </select></td>
					    </tr>
					 
					    <tr>
					        <th>Price</th>
					        <td><input name=unitprice value="4000" size=7> <!-- 가격부분입니다. 빠져서는 안됩니다. -->
					        <select name=goodcurrency> <!--각나라 화폐단위입니다. 여러나라 화폐를 지원해 드립니다. -->
					            <option value=""></option>
					            <option value="WON" selected>WON</option>
					            <option value="USD">US Dollars</option>
					            <option value="CNY">China, Yuan Renminbi</option>
					            <option value="JPY">Japan Yen</option>
					            <option value="EUR">Euro</option>
					            <option value="AUD">Australia Dollar</option>
					            <option value="BRL">Brazil, Reais</option>
					            <option value="GBP">UK Pounds</option>
					            <option value="CAD">Canada, Dollars</option>
					            <option value="DKK">Denmark, Kroner</option>
					            <option value="HKD">Hong Kong, Dollars</option>
					            <option value="ISK">Iceland, Kronur</option>
					            <option value="INR">India, Rupees</option>
					            <option value="MYR">Malaysia, Ringgits</option>
					            <option value="MXN">Mexico, Pesos</option>
					            <option value="NZD">New Zealand, Dollars</option>
					            <option value="NOK">Norway, Krone</option>
					            <option value="PLN">Poland, Zlotych</option>
					            <option value="SGD">Singapore, Dollars</option>
					            <option value="ZAR">South Africa, Rand</option>
					            <option value="SEK">Sweden, Kronor</option>
					            <option value="CHF">Switzerland, Francs</option>
					            <option value="TWD">Taiwan, New Dollars</option>
					            <option value="THB">Thailand, Baht</option>
					        </select></td>
					        <td>&nbsp;</td>
					        <th>GoodName</th>
					        <td><input name=goodname value="상품명"></td> <!-- 상품명입니다. -->
					    </tr>
					 
					    <tr>
					        <th>CardType</th>
					        <td><select name=cardtype> <!-- 결제하실 카드타입을 미리 정해서 결제모듈을 호출 할 수 있습니다. -->
					            <option value=""></option>
					            <option value="110">KBCard</option>
					            <option value="210">KEBCard</option>
					            <option value="310">BCCard</option>
					            <option value="410">ShinHan(old LG)</option>
					            <option value="510">Samsung</option>
					            <option value="610">Hyundai</option>
					            <option value="710">Lotte</option>
					            <option value="810">Shinhan</option>
					            <option value="915">Hanmi</option>
					            <option value="912">NH card</option>
					            <option value="923">Citi</option>
					            <option value="918">KwangJu</option>
					            <option value="916">HaNa</option>
					            <option value="920">JeonBuk</option>
					            <option value="913">PyungHwa</option>
					            <option value="925">SuHyup</option>
					            <option value="2Z0">International VISA</option>
					            <option value="2Y0">International MASTER</option>
					            <option value="2J0">International JCB</option>
					            <option value="2A0">International AMEX</option>
					        </select></td>
					        <td>&nbsp;</td>
					    </tr>
					 
					    <tr>
					        <th>LoanST</th>
					        <td><select name=loanSt> <!-- 에스크로 설정을 하실수 있습니다. RTBT와 BTnotice(가상계좌)에만 해당합니다. -->
					            <option value=""></option>
					            <option value="escrow">escrow</option>
					            <option value="CASH">CASH</option>
					        </select></td>
					        <td>&nbsp;</td>
					        <th>BankCode</th> <!-- 무통장입금 또는 가상계좌를 사용하실때 사용됩니다.-->
					        <td><select name=bankcode>
					            <option value=''></option>
					            <option value='04'>KB</option>
					            <option value='11'>NongHyup</option>
					            <option value='26'>ShinHan</option>
					            <option value='20'>Woori</option>
					            <option value='71'>ePost</option>
					            <option value='81'>Hana</option>
					            <option value='03'>Kiup</option>
					            <option value='PG'>jp</option>
					        </select></td>
					    </tr>
					 
					    <tr>
					        <th>BankAccount</th><!-- 무통장입금 또는 가상계좌를 사용하실때 사용되며 가상계좌일떄는 value 값을 비워주세요.-->
					        <td><input name=bankaccount value=""></td>
					        <td>&nbsp;</td>
					        <th>TID</th>
					        <td><input name=tid size=40 value=""></td> <!-- 페이게이트 주문번호입니다. 업체에서 입력하시면 저희 admin 내역에도 주문번호가 그래도 남게됩니다. -->
					    </tr>
					 
					    <tr>
					        <th>Transfer D-Day</th><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
					        <td><select name=bankexpyear><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
					            <option value=""></option>
					            <option value="2012">2012</option>
					            <option value="2013">2013</option>
					            <option value="2014">2014</option>
					        </select> - <select name=bankexpmonth><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
					            <option value=""></option>
					            <option value="01">01</option>
					            <option value="02">02</option>
					            <option value="03">03</option>
					            <option value="04">04</option>
					            <option value="05">05</option>
					            <option value="06">06</option>
					            <option value="07">07</option>
					            <option value="08">08</option>
					            <option value="09">09</option>
					            <option value="10">10</option>
					            <option value="11">11</option>
					            <option value="12">12</option>
					        </select> - <select name=bankexpday><!-- 무통장 입금 및 가상계좌 사용시 입금날짜를 정하게 됩니다. -->
					            <option value=""></option>
					            <option value="01">01</option>
					            <option value="02">02</option>
					            <option value="03">03</option>
					            <option value="04">04</option>
					            <option value="05">05</option>
					            <option value="06">06</option>
					            <option value="07">07</option>
					            <option value="08">08</option>
					            <option value="09">09</option>
					            <option value="10">10</option>
					            <option value="11">11</option>
					            <option value="12">12</option>
					            <option value="13">13</option>
					            <option value="14">14</option>
					            <option value="15">15</option>
					            <option value="16">16</option>
					            <option value="17">17</option>
					            <option value="18">18</option>
					            <option value="19">19</option>
					            <option value="20">20</option>
					            <option value="21">21</option>
					            <option value="22">22</option>
					            <option value="23">23</option>
					            <option value="24">24</option>
					            <option value="25">25</option>
					            <option value="26">26</option>
					            <option value="27">27</option>
					            <option value="28">28</option>
					            <option value="29">29</option>
					            <option value="30">30</option>
					            <option value="31">31</option>
					        </select></td>
					        <td>&nbsp;</td>
					 
					    </tr>
					 
					    <tr>
					        <th>ReceiptToName</th>
					        <td><input name=receipttoname value="홍길동"></td><!-- 구매자 명입니다. -->
					        <td>&nbsp;</td>
					    </tr>
					 
					    <tr>
					        <th>ReceiptToTel</th> <!-- 휴대폰 소액결제시 들어가고 구매자 휴대폰 명으로도 사용합니다. -->
					        <td><select name=carrier>
					            <option value=""></option>
					            <option value="011">SKT</option>
					            <option value="016">KTF</option>
					            <option value="019">LGT</option>
					        </select> <input name=receipttotel value=""></td><!-- 휴대폰 소액결제시 들어가고 구매자 휴대폰 명으로도 사용합니다. -->
					        <td>&nbsp;</td>
					        <th>ReceiptToEmail</th>
					        <td><input name=receipttoemail value=""></td><!-- 구매내역에 대한 메일을 받으실 구매자의 메일을 등록해주세요 -->
					    </tr>
					 
					    <tr>
					        <th>AuthCode</th>
					        <td><input name=cardauthcode size=8 value=""></td><!-- 카드 승인번호입니다. 결제 완료시 폼에 자동적으로 value가 들어가게 됩니다. -->
					        <td>&nbsp;</td>
					        <th>replycode/replyMsg</th>
					        <td><input name=replycode size=6 value="">/<!-- 결과 코드를 받으셔야합니다. 결과 코드를 받으신후 결제 결과를 처리하시면됩니다.-->
					    <input   name=replyMsg size=20 value=""></td><!-- 결과 메시지입니다. 결제가 실패시 어떤 이유로 실패됬는지 알기위해서 필요합니다. -->
					    </tr>
					 
					    <tr>
					        <th>profile_nom</th>
					        <td><input name=profile_no></td><!-- 프로파일 넘버는 사전에 마케팅부서 및 기술팀에 사용 문의를 하시고 사용하셔야합니다. -->
					        <td>&nbsp;</td>
					        <th>&nbsp;</th>
					        <td>&nbsp;</td>
					    </tr>
					 
					    <tr>
					        <th>welcomeURL</th>
					        <td colspan=4><input name=welcomeURL size=60 value=""></td>
					    </tr>
					 
					    <tr>
					        <th>MoveURL</th>
					        <td colspan=4><input name=MoveURL size=60 value=""></td>
					    </tr>
					 
					 
					    <tr>
					        <th>GoodOption</th>
					        <td colspan=4>
					        <input type=text name=goodoption1> <!-- 기타 등등 고객의 주소지나 다른 입력정보를 넣으시면 됩니다. -->
					        <input type=text name=goodoption2>
					        <input type=text name=goodoption3>
					        <input type=text name=goodoption4>
					        <input type=text name=goodoption5></td>
					    </tr>
					 
					</table>
					</form>

				</div>

				
				<iframe name="payment_frame" src="" width="0" height="0" style="border:0"></iframe>
				<form id="payment_result_form" name="payment_result" method="POST" action="<?=site_url('shop/orderResult');?>">
					<input type="hidden" name="orders_id" />
					<input type="hidden" name="result" />
					<input type="hidden" name="bank_code" />
					<input type="hidden" name="acc_no" />
					<input type="hidden" name="date_limit" />
					<input type="hidden" name="card_agent" />
					<input type="hidden" name="auth_code" />
				</form>
				<script>
				
				var is_payable = false;
				var is_ie =  $.browser.msie;
				var is_member = '<?=$member?"Y":"N"?>';
				
				$('#btn_payment').click(function(){
					
					var method = $('input[name="payment_method"]:checked').val();
					console.log(method);
					switch (method){
						case 'card':
						// 카드결제시 입력값 세팅
						/* 지불수단: 카드 */
						$(PGIOForm).find('select[name="paymethod"]').val('card');

						// 카드 종류
						var cardtype = $('#option_cardtype').val();
						if (cardtype.length<1){
							alert('카드사를 선택하세요.');
							return false;
						}
						$(PGIOForm).find('select[name="cardtype"]').val(cardtype);

						break;
						case 'virtual':
						// 가상계좌 결제시 입력값 세팅
						/* 지불수단: BTNOTICE */
						$(PGIOForm).find('select[name="paymethod"]').val(7);

						/* 은행코드 */
						var code = $('#option_bankcode').val();
						if (code.length < 1){
							alert('입금 은행을 선택하세요.');
							return false;
						}
						$(PGIOForm).find('select[name="bankcode"]').val(code);
						$('input[name="bank_code"]').val(code);

						/* 입금예정일 */
						var y = $('#option_year').val();
						var m = $('#option_month').val();
						var d = $('#option_day').val();
						if (y.length < 1 || m.length < 1 || d.length < 1){
							alert('입금일자 입력 하세요.');
							return false;
						}
						$(PGIOForm).find('select[name="bankexpyear"]').val(y);
						$(PGIOForm).find('select[name="bankexpmonth"]').val(m);
						$(PGIOForm).find('select[name="bankexpday"]').val(d);
						$('input[name="date_limit"]').val(y+'-'+m+'-'+d);

						break;
					}

					// 공통 입력값 세팅
					/* 구매자명 */
					var name = $('input[name="orderer_name"]').val();
					if (name.length < 1){
						alert('주문자 성명을 입력하세요.');
						return false;
					}
					$(PGIOForm).find('input[name="receipttoname"]').val(name);

					/* 통신사 */
					var carrier = $('#option_carrier').val();
					if (carrier.length < 1){
						alert('주문자 휴대전화 통신사를 선택하세요.');
						return false;
					}
					$(PGIOForm).find('[name="carrier"]').val(carrier);

					/* 폰번호 */
					var mobile = '';
					$('[name^="orderer_mobile"]').each(function(){
						mobile += $(this).val();
					});
					if (mobile.length < 10){
						alert('주문자 휴대전화 번호를 입력하세요.');
						return false;
					}
					$(PGIOForm).find('input[name="receipttotel"]').val(mobile);


					/* 이메일 */
					var email = $('input[name="orderer_email"]').val();
					if (email.length < 1){
						alert('주문자 이메일 주소를 입력하세요.');
						return false;
					}
					$(PGIOForm).find('input[name="receipttoemail"]').val(email);



					/* 가격 */
					var price = getPayableAmount();
					$(PGIOForm).find('input[name="unitprice"]').val(price);

					/* 상품명 */
					var title = getPaymentTitle();
					$(PGIOForm).find('input[name="goodname"]').val(title);

					

					saveFormState();
					pay();
					
				});

				$('input[name="payment_method"]').change(function(){
					var method = $(this).val();
					if (method == 'virtual'){
						showVirtualOptions();
						hideCardOption();
					}
					else {
						hideVirtualOPtions();
						showCardOption();
					}
						
				});

				var showVirtualOptions = function(){
					$('#payment_option_place').show();
				}

				var hideVirtualOPtions = function(){
					$('#payment_option_place').hide();
				}

				var showCardOption = function(){
					$('#payment_option_place1').show();
				}

				var hideCardOption = function(){
					$('#payment_option_place1').hide();
				}
				
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
			         			$('input[name="goodoption1"]').val(result.orders_id);
			         			
			         			showPaymentModule();
			         			
			         		} else {
			         			alert(result.reason);
			         		}
			         		
			         		hideLoadingOverlay();
			         		$disabled.attr('disabled',true);

				        }
					}).fail(function(){
						alert('결제 실패');
						hideLoadingOverlay();
					});
				}


				var showPaymentModule = function(){

					$('#PGIOscreen').dialog({
						resizable: false,
						height:400,
						modal: true
					});

					// 결제 모듈 불러오기 
					doTransaction(document.PGIOForm);
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
				
				var callbacksuccess = function(){
					
				}

				var callbackfail = function(){
					location.reload(true);
				}
				
				var payment_virtual = function(){
					console.log('가상계좌');

					var acc_no = $('input[name="bankaccount"]').val();
					$('#payment_result_form > input[name="acc_no"]').val(acc_no);
					
					$('#payment_result_form > input[name="result"]').val('virtual');
					$('#payment_result_form').submit();
				}
				
				var payment_success = function(){
					console.log('결제 성공');
					var card_agent = document.PGIOForm.elements['cardtype'].value;
					$('#payment_result_form > input[name="card_agent"]').val(card_agent);
					// alert(card_agent);

					var cardauthcode = $('input[name="cardauthcode"]').val();
					$('#payment_result_form > input[name="auth_code"]').val(cardauthcode);
					
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
			    			location.reload(true);
			    			console.log('order id '+orders_id+' cancelled '+text.success);
			    		}
			    	});
				}
				
				</script>
