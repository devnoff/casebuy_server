<?php

include_once('../agspay/ci_connect.php');

/*

*/

$product_names = array('핀돔','크리스탈','와우 2박스','듀렉스','슬림','운수대통','스킨리스','베네통','롱러브9p','운수대통 2박스','ABC','레드폭스','체어맨','체어맨 2박스','오카모토','오카모토 슬림','오카모토 울트라 슬림');

$product_name = $product_names[rand(0,(count($product_names)-1))];




//*******************************************************************************
// MD5 결제 데이터 암호화 처리
// 형태 : 상점아이디(StoreId) + 주문번호(OrdNo) + 결제금액(Amt)
//*******************************************************************************

$StoreId 	= "yulabs79";
$OrdNo 		= $_POST['orders_id'];
$payment_method = $_POST['payment_method'];


if ($payment_method == 'hp'){
	header( 'Location: ./AGS_mobile_not_ready.php ') ;
}


if (!$OrdNo || !$payment_method){
	header( 'Location: ./AGS_error.php ') ;
} else {

	$db = new mysqlMgr;
	$orders_id = mysql_real_escape_string($OrdNo);
	$sql = "select o.id id,members_id, order_title, payable_amount, cs.name orderer_name, cs.mobile orderer_mobile, cs.address orderer_address, cs.email orderer_email , d.name recipient_name, d.mobile recipient_mobile, d.address recipient_address, d.msg from orders o left join order_customer_info cs on(o.id = cs.orders_id) left join order_delivery_info d on(o.id = d.orders_id) where o.id=".$orders_id;
	$result = $db->query($sql);
	
	$product_name = $result['order_title'];
/* 	var_dump($result);	 */
}



$amt = $result['payable_amount'];

$AGS_HASHDATA = md5($StoreId . $OrdNo . $amt); 

?>


<!--

* 프로젝트 : AGSMobile V1.0
* (※ 본 프로젝트는 아이폰 및 안드로이드에서 이용하실 수 있으며 일반 웹페이지에서는 결제가 불가합니다.)

* 파일명 : AGS_pay.html
* 최종수정일자 : 2011/09/01

* 올더게이트 결제창을 호출합니다.

* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.

-->
<html>
<head>
<title>올더게이트</title>
<META content="user-scalable=no, initial-scale = 1.0, maximum-scale=1.0, minimum-scale=1.0" name=viewport>
<META content=telephone=no name=format-detection>
<style type="text/css">
body { font-family:"돋움"; font-size:9pt; color:#333333; font-weight:normal; letter-spacing:0pt; line-height:180%; }
td { font-family:"돋움"; font-size:9pt; color:#333333; font-weight:normal; letter-spacing:0pt; line-height:180%; }
.clsright { padding-right:10px; text-align:right; }
.clsleft { padding-left:10px; text-align:left; }
</style>
<script language=javascript>

var _ua = window.navigator.userAgent.toLowerCase();

var browser = {
	model: _ua.match(/(samsung-sch-m490|sonyericssonx1i|ipod|iphone)/) ? _ua.match(/(samsung-sch-m490|sonyericssonx1i|ipod|iphone)/)[0] : "",
	skt : /msie/.test( _ua ) && /nate/.test( _ua ),
	lgt : /msie/.test( _ua ) && /([010|011|016|017|018|019]{3}\d{3,4}\d{4}$)/.test( _ua ),
	opera : (/opera/.test( _ua ) && /(ppc|skt)/.test(_ua)) || /opera mobi/.test( _ua ),
	ipod : /webkit/.test( _ua ) && /\(ipod/.test( _ua ) ,
	iphone : /webkit/.test( _ua ) && /\(iphone/.test( _ua ),
	lgtwv : /wv/.test( _ua ) && /lgtelecom/.test( _ua )
};

if(browser.opera) {
	document.write("<meta name=\"viewport\" content=\"user-scalable=no, initial-scale=0.75, maximum-scale=0.75, minimum-scale=0.75\" \/>");
} else if (browser.ipod || browser.iphone) {
	setTimeout(function() { if(window.pageYOffset == 0){ window.scrollTo(0, 1);} }, 100);
}

function Pay(form){
	if(Check_Common(form) == true){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 올더게이트 플러그인 설정값을 동적으로 적용하기 JavaScript 코드를 사용하고 있습니다.
		// 상점설정에 맞게 JavaScript 코드를 수정하여 사용하십시오.
		//
		// [1] 일반/무이자 결제여부
		// [2] 일반결제시 할부개월수
		// [3] 무이자결제시 할부개월수 설정
		// [4] 인증여부
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [1] 일반/무이자 결제여부를 설정합니다.
		//
		// 할부판매의 경우 구매자가 이자수수료를 부담하는 것이 기본입니다. 그러나,
		// 상점과 올더게이트간의 별도 계약을 통해서 할부이자를 상점측에서 부담할 수 있습니다.
		// 이경우 구매자는 무이자 할부거래가 가능합니다.
		//
		// 예제)
		// 	(1) 일반결제로 사용할 경우
		// 	form.DeviId.value = "9000400001";
		//
		// 	(2) 무이자결제로 사용할 경우
		// 	form.DeviId.value = "9000400002";
		//
		// 	(3) 만약 결제 금액이 100,000원 미만일 경우 일반할부로 100,000원 이상일 경우 무이자할부로 사용할 경우
		// 	if(parseInt(form.Amt.value) < 100000)
		//		form.DeviId.value = "9000400001";
		// 	else
		//		form.DeviId.value = "9000400002";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		form.DeviId.value = "9000400001";
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [2] 일반 할부기간을 설정합니다.
		// 
		// 일반 할부기간은 2 ~ 12개월까지 가능합니다.
		// 0:일시불, 2:2개월, 3:3개월, ... , 12:12개월
		// 
		// 예제)
		// 	(1) 할부기간을 일시불만 가능하도록 사용할 경우
		// 	form.QuotaInf.value = "0";
		//
		// 	(2) 할부기간을 일시불 ~ 12개월까지 사용할 경우
		//		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		//
		// 	(3) 결제금액이 일정범위안에 있을 경우에만 할부가 가능하게 할 경우
		// 	if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
		// 		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		// 	else
		// 		form.QuotaInf.value = "0";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//결제금액이 5만원 미만건을 할부결제로 요청할경우 결제실패
		if(parseInt(form.Amt.value) < 50000)
			form.QuotaInf.value = "0";
		else
			form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [3] 무이자 할부기간을 설정합니다.
		// (일반결제인 경우에는 본 설정은 적용되지 않습니다.)
		// 
		// 무이자 할부기간은 2 ~ 12개월까지 가능하며, 
		// 올더게이트에서 제한한 할부 개월수까지만 설정해야 합니다.
		// 
		// 100:BC
		// 200:국민
		// 300:외환
		// 400:삼성
		// 500:신한
		// 800:현대
		// 900:롯데
		// 
		// 예제)
		// 	(1) 모든 할부거래를 무이자로 하고 싶을때에는 ALL로 설정
		// 	form.NointInf.value = "ALL";
		//
		// 	(2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "200-2:3:4:5:6";
		//
		// 	(3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "300-2:3:4:5:6";
		//
		// 	(4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
		//	
		//	(5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정
		//	form.NointInf.value = "NONE";
		//
		//	(6) 전카드사 특정개월수만 무이자를 하고 싶은경우(2:3:6개월)
		//	form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";
		//
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(form.DeviId.value == "9000400002")
			form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";

		form.submit();
	}
}

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("상점아이디를 입력하십시오.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("상점명을 입력하십시오.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("주문번호를 입력하십시오.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("상품명을 입력하십시오.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("금액을 입력하십시오.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("상점URL을 입력하십시오.");
		return false;
	}
	return true;
}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0"> <!--  style="display:none" -->
<!-- 인코딩 방식을 UTF-8로 하는 경우 action 경로 ☞ http://www.allthegate.com/payment/mobile_utf8/pay_start.jsp -->
<form name="frmAGS_pay" method="post" action="http://www.allthegate.com/payment/mobile_utf8/pay_start.jsp">
<table border="0" width="320" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td align="center">
		<table width=320 border=0 cellpadding=0 cellspacing=0>
			<tr><td><hr></td></tr>
			<tr><td class=clsleft><b>모바일 테스트 페이지</b></td></tr>
			<tr><td class=clsleft>☞ 표시는 필수 입력사항입니다. </td></tr>
			<tr><td><hr></td></tr>
			<tr>
				<td>
				<table width=320 border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td class=clsleft colspan=2><font color=#006C6C>+ 공통 사용 변수</font></td>
					</tr>
					<tr>
						<td width=140 class=clsleft>☞ 결제선택</td>
						<td width=180 colspan=2>
							<input name="Job" value="<?=$payment_method;?>" />
<!--
							<select name=Job style=width:150px>
								<option value="card">신용카드
								<option value="virtual">가상계좌
								<option value="hp">휴대폰
							</select>
-->
						</td>
					</tr>
					<tr>
						<td width=140 class=clsleft>☞ 상점아이디 (20)</td>
						<!--상점아이디를 실거래 전환후에는 발급받은 아이디로 바꾸시기 바랍니다. yulabs79 -->
						<td width=180 colspan=2><input type=text style=width:100px name=StoreId maxlength=20 value="yulabs79"></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 주문번호 (40)</td>
						<td><input type=text style=width:100px name=OrdNo maxlength=40 value="<?=$OrdNo?>"></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 금액 (12)</td>
						<td><input type=text style=width:100px name=Amt maxlength=12 value="<?=$amt;?>">원</td>
					</tr>
					<tr>
						<td class=clsleft>☞ 상점명 (50)</td>
						<td><input type=text style=width:180px name=StoreNm value="색콤달콤"></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 상품명 (300)</td>
						<td><input type=text style=width:180px name=ProdNm maxlength=300 value="<?=$product_name;?>"></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 상점URL (50)</td>
						<!-- 주의) 상점홈페이지주소를 반드시 입력해 주십시오. -->
						<td><input type=text style=width:180px name=MallUrl value="http://www.scomdcom.com"></td>
					</tr>
					<tr>
						<td class=clsleft>주문자이메일 (50)</td>
						<td><input type=text style=width:180px name=UserEmail maxlength=50 value="<?=$result['orderer_email'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>회원아이디 (20)</td>
						<td><input type=text style=width:180px name=UserId maxlength=20 value="<?=$result['members_id'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>주문자명 (40)</td>
						<td><input type=text style=width:100px name=OrdNm maxlength=40 value="<?=$result['orderer_name'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>주문자연락처 (21)</td>
						<td><input type=text style=width:100px name=OrdPhone maxlength=21 value="<?=$result['orderer_mobile'];?>"></td>
					</tr>
               		<tr>
						<td class=clsleft>주문자주소 (100)</td>
						<td><input type=text style=width:180px name=OrdAddr maxlength=100 value="<?=$result['orderer_address'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>수신자명 (40)</td>
						<td><input type=text style=width:100px name=RcpNm maxlength=40 value="<?=$result['recipient_name'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>수신자연락처 (21)</td>
						<td><input type=text style=width:100px name=RcpPhone maxlength=21 value="<?=$result['recipient_mobile'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>배송지주소 (100)</td>
						<td><input type=text style=width:180px name=DlvAddr maxlength=100 value="<?=$result['recipient_address'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>기타요구사항 (350)</td>
						<td><input type=text style=width:180px name=Remark maxlength=350 value="<?=$result['msg'];?>"></td>
					</tr>
					<tr>
						<td class=clsleft>카드사선택</td>
						<td colspan=2><input type=text style=width:300px name=CardSelect value=""></td>
						<!-- 결제창에 특정카드만 표기기능입니다. 
						          사용방법 예)  BC, 국민을 사용하고자 하는 경우 ☞ 100:200
											    국민 만 사용하고자 하는 경우 ☞ 200
							 모두 사용하고자 할 때에는 아무 값도 입력하지 않습니다.
							 카드사별 코드는 매뉴얼에서 확인해 주시기 바랍니다. -->
				    </tr>
					<tr>
						<td class=clsleft>☞ 성공 URL (150)</td>
						<!-- 성공 URL은 반드시 상점의 AGS_pay_ing.php의 전체 경로로 맞춰 주시기 바랍니다. -->
						<td><input type=text style=width:180px name=RtnUrl value="http://scomdcom.com/loveholic/agspay_mobile/AGS_pay_ing.php"></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 취소 URL (150)</td>
						<!-- 고객이 취소를 눌렀을 경우의 이동 URL 경로로 전체 경로로 맞춰 주시기 입니다. -->
						<td><input type=text style=width:180px name=CancelUrl value="http://scomdcom.com/loveholic/agspay_mobile/AGS_pay_cancel.php"></td>
					</tr>
					<tr>
						<td class=clsleft>추가사용필드1 (200)</td>
						<td><input type=text style=width:180px name=Column1 maxlength=200 value="상점정보입력1"></td>
					</tr>
					<tr>
						<td class=clsleft>추가사용필드2 (200)</td>
						<td><input type=text style=width:180px name=Column2 maxlength=200 value="상점정보입력2"></td>
					</tr>
					<tr>
						<td class=clsleft>추가사용필드3 (200)</td>
						<td><input type=text style=width:180px name=Column3 maxlength=200 value="상점정보입력3"></td>
					</tr>
					<tr>
						<td class=clsleft colspan=2><font color=#006C6C>+ 가상계좌 결제 사용 변수</font></td>
					</tr>
					<tr>
						<td class=clsleft>☞ 통보페이지 (100)</td>
						<!-- 가상계좌 결제에서 입/출금 통보를 위한 필수 입력 사항 입니다. -->
						<!-- 페이지주소는 도메인주소를 제외한 '/'이후 주소를 적어주시면 됩니다. -->
						<td><input type=text style=width:180px name=MallPage maxlength=100 value="/lovehlic/agspay_mobile/AGS_VirAcctResult.php"></td>
					</tr>
					<tr>
						<?php 
							$date = new DateTime();
							$date->modify('+5 day');
						?>
						<td class=clsleft>입금예정일 (8)</td>
						<td><input type=text style=width:180px name=VIRTUAL_DEPODT maxlength=8 value="<?=$date->format('Ymd');?>"></td>
					</tr>
					<tr>
						<td class=clsleft colspan=2><font color=#006C6C>+ 핸드폰 결제 사용 변수</font></td>
					</tr>
					<tr>
						<td class=clsleft>CP아이디 (10)</td>
						<!-- CP아이디를 핸드폰 결제 실거래 전환후에는 발급받으신 CPID로 변경하여 주시기 바랍니다. -->
						<td><input type=text style=width:100px name=HP_ID maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>CP비밀번호 (10)</td>
						<!-- CP비밀번호를 핸드폰 결제 실거래 전환후에는 발급받으신 비밀번호로 변경하여 주시기 바랍니다. -->
						<td><input type=text style=width:100px name=HP_PWD maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>SUB-CP아이디 (10)</td>
						<!-- SUB-CPID는 핸드폰 결제 실거래 전환후에 발급받으신 상점만 입력하여 주시기 바랍니다. -->
						<td><input type=text style=width:100px name=HP_SUBID maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>상품코드 (10)</td>
						<!-- 상품코드를 핸드폰 결제 실거래 전환후에는 발급받으신 상품코드로 변경하여 주시기 바랍니다. -->
						<td><input type=text style=width:100px name=ProdCode maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>상품종류</td>
						<td>
							<!-- 상품종류를 핸드폰 결제 실거래 전환후에는 발급받으신 상품종류로 변경하여 주시기 바랍니다. -->
							<!-- 판매하는 상품이 디지털(컨텐츠)일 경우 = 1, 실물(상품)일 경우 = 2 -->
							<select name=HP_UNITType style=width:100px>
								<option value="1">디지털:1
								<option value="2" selected="">실물:2
							</select>
						</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr><td><hr></td></tr>
			<tr>
				<td align=center>
					<input type="button" value="지불요청" onclick="javascript:Pay(frmAGS_pay);">
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
	<input type=hidden name=DeviId value="">			<!-- 단말기아이디 -->
	<input type=hidden name=QuotaInf value="0">			<!-- 할부개월설정변수 -->
	<input type=hidden name=NointInf value="NONE">		<!-- 무이자할부개월설정변수 -->
</form>

<input type="button" value="지불요청" onclick="javascript:Pay(frmAGS_pay);">
</body>
</html> 