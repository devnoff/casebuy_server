
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.wapforum.org/DTD/xhtml-mobile12.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"> 
	<head>
		<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 
		<meta http-equiv=Cache-Control content=No-Cache>
		<meta http-equiv=Pragma	content=No-Cache>
        <title>Case Buy - </title> 
        <link rel="stylesheet" href="<?=base_url()."css/";?>order_detail_style.css" />
        <script type="text/javascript" src="/en/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/en/js/json2.js"></script>
		<script type="text/javascript">
		<!--
		 window.addEventListener('load', function(){
		  setTimeout(scrollTo, 0, 0, 1);
		 }, false);
		</script>

		<style>
		center {
			font-size: 9pt;
			padding: 10pt;
			color: #999;
			background-color: #eee;
		}		
		</style>
	</head>
	<body>

		<h2><span>결제금액 안내</span></h2>
		
		<div class="inputWrapper">
			<div class="inputContainer">
				<div class="input total">
					<span>최종 결제금액</span>
					<input name="payable_amount" type="text" value="" readonly>
				</div>
			</div>
		</div>

		<h2><span>비회원 약관</span></h2>

		<div class="inputWrapper">
			<div class="inputContainer">
				<div class="input">
					<div class="policy">
						<p class="subject">비회원 구매 시 약관</p>
						<ul>
							<li>회사는 비회원도 CASEBUY 쇼핑몰에서 자유롭게 상품을 구매할 수 있도록 서비스를 제공하고 있습니다.</li>
							<li>비회원의 경우 적립금 적립 등의 혜택이 제공되지 않습니다. 단, 스마트폰 이용시 스마트폰을 통한 비회원 구입시 애플리케이션 제거 전까지 포인트를 제공하며 해당 포인트를 활용할 수 있습니다.</li>
							<li>그러나 스마트폰 애플리케이션 제거시 소멸되는 포인트를 회사가 복원해주는 책임은 없습니다. 이 경우 애플리케이션 제거 전 회원가입을 하시면 포인트를 지속적으로 유지할 수 있습니다.</li>
							<li>비회원 구매시 잘못 기재한 정보 (연락처, 주소 등) 로 인한 책임은 모두 주문고객에게 있습니다.</li>
							<li>주소 정보를 잘못 기재하여 제품이 배송된 경우 회사는 책임을 지지 않습니다.</li>
						</ul>

						<p class="subject">수집하는 개인정보 항목</p>
						<p>"CASEBUY(www.casebuy.me)"은 비회원 상품구매를 위해 아래와 같은 개인정보를 수집하고 있습니다.</p>
						<ul>
							<li>수집항목: 이름, 전화번호, 주소, 이메일</li>
							<li>개인정보 수집방법 : 홈페이지, 스마트폰 앱</li>
						</ul>

						<p class="subject">개인정보의 수집 및 이용목적</p>
						<p>"CASEBUY(www.casebuy.me)"이 수집한 개인정보를 다음의 목적을 위해 활용합니다.</p>
						<ul>
							<li>서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산 콘텐츠 제공 , 구매 및 요금 결제 , 물품배송 또는 청구지 등 발송</li>
						</ul>

						<p class="subject">개인정보의 보유 및 이용기간</p>
						<p>원칙적으로, 개인정보 수집 및 이용목적이 달성된 후에는 해당 정보를 지체 없이 파기합니다. 단, 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 아래와 같이 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다.</p>
						<ul>
							<li>보존 항목: 결제기록</li>
							<li>보존 근거: 계약 또는 청약철회 등에 관한 기록</li>
							<li>보존 기간: 3년</li>
							<li>계약 또는 청약철회 등에 관한 기록: 5년 (전자상거래등에서의 소비자보호에 관한 법률)</li>
							<li>대금결제 및 재화 등의 공급에 관한 기록: 5년 (전자상거래등에서의 소비자보호에 관한 법률)</li>
							<li>소비자의 불만 또는 분쟁처리에 관한 기록: 3년 (전자상거래등에서의 소비자보호에 관한 법률)</li>
						</ul>
						 
						<p class="subject">개인정보 제공</p>
						<p>회사는 이용자의 개인정보를 원칙적으로 외부에 제공하지 않습니다. 다만, 아래의 경우에는 예외로 합니다.
						<ul>
							<li>이용자들이 사전에 동의한 경우</li>
							<li>법령의 규정에 의거하거나, 수사 목적으로 법령에 정해진 절차와 방법에 따라 수사기관의 요구가 있는 경우</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="checkbox"><input type="checkbox" id="ab"><label for="ab">약관에 동의합니다</label></div>

		</div>



		<h2><span>결제방법 선택</span></h2>
		<div class="button"><a onclick="pay('card');" class="left">신용카드</a><a onclick="pay('virtual');" class="right" >계좌입금</a></div>
		<!-- <p style="padding:15px;font-size:10pt;color:red;font-weight:bold">삼성, 롯데 카드 등 일부 카드는 결제가 되지 않을 수 있습니다.</p> -->
		<center>CultStory 대표: 윤제필 사업자등록번호: 105-87-36667 통신판매업신고번호: 제 2012-서울중구-1342 호<br/>
			주소: 서울시 중구 충무로2가 50-6 라이온스빌딩 1003 TEL: 070-8650-2086 FAX: 02-6280-7428<br/>
			개인정보관리책임자: 박용남(casebuy@cultstory.com)</center>

	</body>

	<script>
		var pay = function(method){

			if (!$('#ab').is(':checked')){
				alert('비회원 구매 약관에 동의 하셔야 구매를 하실 수 있습니다.');
				return;
			}

			if (method=='virtual'){
				var c = confirm('계좌입금으로 주문하시겠습니까?');
				if (!c){
					return;
				}
			}

			location.href = "paywithmethod:"+method;
		}

	</script>
</html>