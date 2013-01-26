<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.wapforum.org/DTD/xhtml-mobile12.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"> 
	<head>
		<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 
		<meta http-equiv=Cache-Control content=No-Cache>
		<meta http-equiv=Pragma	content=No-Cache>
        <title>Case Buy - </title> 
        <link rel="stylesheet" href="<?=base_url()."css/";?>order_detail_style.css" />
        <script type="text/javascript" src="/ko/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/ko/js/json2.js"></script>
		<script type="text/javascript">
		<!--
		 window.addEventListener('load', function(){
		  setTimeout(scrollTo, 0, 0, 1);
		 }, false);

		function setAddress(target_name,value){
			$('input[name="'+target_name+'"]').val(value);
		}
		</script>
	</head>
	<body>
	<form name="myform" action="" method="get">
		<h2 style="float:left"><span>주문자 정보</span></h2>
		<div style="float:right;padding:4px;color:gray;font-size:9pt;text-decoration:underline;margin-top:5px;margin-right:5px;">
			<a onclick="clearForm();">초기화</a>
		</div>
		<div style="clear:both;"></div>
		
		<div class="inputWrapper">
			<div class="inputContainer">
				<div class="input">
					<span>이름</span>
					<input name="orderer_name" type="text" placeholder="이름을 입력" />
				</div>
				<div class="input">
					<span>연락처</span>
					<select name="orderer_carrier">
			            <option value="">통신사</option>
			            <option value="011">SKT</option>
			            <option value="016">KT</option>
			            <option value="019">LGT</option>
			        </select>
					<input name="orderer_mobile" type="text" pattern="[0-9]*" style="width:170px" placeholder="핸드폰번호를 입력"/>
				</div>
				<div class="input">
					<span>이메일</span>
					<input name="orderer_email" type="email" placeholder="이메일을 입력"/>
				</div>
				<div class="input">
					<span>주소</span>
					<input style="text-align:center;white-space: normal;padding-left:5px;padding-right:5px;" name="orderer_address" type="button" value="주소 검색" class="zipcode" onclick="javascript:findAddress('orderer_address');"/>
				</div>
			</div>
		</div>
		

		<h2><span>배송지 정보</span><a onclick="javascript:sameValues();">주문자와 동일</a></h2>


		<div class="inputWrapper">

			<div class="inputContainer">
				<div class="input">
					<span>이름</span>
					<input name="recipient_name" type="text" value="" placeholder="이름을 입력"/>
				</div>
				<div class="input">
					<span>연락처</span>
					<input name="recipient_mobile" type="text" value="" placeholder="연락처 입력"/>
				</div>
				<div class="input">
					<span>주소</span>
					<input style="text-align:center;white-space: normal;padding-left:5px;padding-right:5px;" name="recipient_address" type="button" value="주소 검색" class="zipcode" onclick="javascript:findAddress('recipient_address');"/>
				</div>
				<div class="input delivery">
					<span>배송메시지</span>
					<input name="recipient_msg" type="text" placeholder="+ 상품옵션">
				</div>
			</div>
		</div>

		<a onclick="javascript:complete();" class="checkout">입력 완료</a>
	</form>
		<p class="add" style="text-decoration:underline;color:red;">상품 옵션이 있는 경우 배송메세지에 남겨주세요!</p>



	<script>

	var findAddress = function(target_name){
		location.href = "findaddressfor:"+target_name;
	}

	var sameValues = function(){
		$('input[name="recipient_name"]').val($('input[name="orderer_name"]').val());
		$('input[name="recipient_address"]').val($('input[name="orderer_address"]').val());
		$('input[name="recipient_mobile"]').val($('input[name="orderer_mobile"]').val());
	}

	var validation = function(){
		var keys = new Array();
		keys['orderer_name'] = '주문자 성명';
		keys['orderer_carrier'] = '주문자 통신사';
		keys['orderer_mobile'] = '주문자 휴대전화번호';
		keys['orderer_email'] = '주문자 이메일';
		keys['orderer_address'] = '주문자 주소';

		keys['recipient_name'] = '수령인 성명';
		keys['recipient_mobile'] = '수령인 전화번호';
		// keys['recipient_msg'] = '배송메세지';
		keys['recipient_address'] = '수령지 주소';

		var vars = new Array();
		vars['orderer_name'] = $('[name="orderer_name"]').val();
		vars['orderer_carrier'] = $('[name="orderer_carrier"]').val();
		vars['orderer_mobile'] = $('[name="orderer_mobile"]').val();
		vars['orderer_email'] = $('[name="orderer_email"]').val();
		vars['orderer_address'] = $('[name="orderer_address"]').val();

		vars['recipient_name'] = $('[name="recipient_name"]').val();
		vars['recipient_mobile'] = $('[name="recipient_mobile"]').val();
		// vars['recipient_msg'] = $('[name="recipient_msg"]').val();
		vars['recipient_address'] = $('[name="recipient_address"]').val();

		for (var i in vars){
			if (vars[i].length < 1){
				alert('다음을 입력하세요('+keys[i]+')');
				return false;
			}
		}

		return true;
	}

	var complete = function(){

		if (!validation()){
			return;
		}

		var query = $('form').serializeArray(),
		json = {};

		for (i in query) {
			json[query[i].name] = query[i].value
		}

		location.href = "submitted:";
	}


	var clearForm = function(){
		$('[name="orderer_name"]').val('');
		$('[name="orderer_carrier"]').val('');
		$('[name="orderer_mobile"]').val('');
		$('[name="orderer_email"]').val('');
		$('[name="orderer_address"]').val('주소 검색');

		$('[name="recipient_name"]').val('');
		$('[name="recipient_mobile"]').val('');
		$('[name="recipient_msg"]').val('');
		$('[name="recipient_address"]').val('주소 검색');
	}
	</script>

	</body>
</html>