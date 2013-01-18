<?php
// Define Content Type
	header('Content-Type: text/html; charset=utf-8');
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Cache-Control" content="No-Cache">
	<meta http-equiv="Pragma" content="No-Cache"> 
	<meta name="Classification" content="홈" />
	<meta name="Subject" content="어른 쇼핑몰" />
	<meta name="Title" content="색콤달콤" />
	<meta name="Description" content="어른들을 위한, 어른에 의한, 어른 쇼핑몰" />
	<meta name="Keywords" content="성인, 어른, 쇼핑몰, 성인 쇼핑몰, 콘돔, 마사지젤, 기구, 자위" />
	<meta name="Author" content="YU LAB" />
	<meta name="robots" content="index,follow" />
	<title>색콤달콤</title>
	<link rel="stylesheet" href="/en/css/shop_style.css" />
	<link href='http://api.mobilis.co.kr/webfonts/css/?fontface=NanumGothicWeb' rel='stylesheet' type='text/css' />
	<link href='http://api.mobilis.co.kr/webfonts/css/?fontface=NanumGothicBoldWeb' rel='stylesheet' type='text/css' />
	<link href="http://fonts.googleapis.com/css?family=Lato:400,900" rel="stylesheet" type="text/css">
	
<script src="/en/js/jquery-1.7.2.min.js"></script>	
<script src="/en/js/helpers.js"></script>
<script>

$(document).ready(function(){

});


function showMain(){
    location.href = "<?=site_url('main');?>";
}

function validAdult(){
    var juminHead = $('input[name="juminHead"]').val();
    var juminFoot = $('input[name="juminFoot"]').val();
    var name = $('input[name="user_name"]').val();
    
    if (juminHead.length < 6 || juminFoot.length < 7 || name.length < 1){
        alert('값을 모두 채워주세요.');
        return;
    }
    
    console.log(juminHead + ' ' + juminFoot + ' ' + name);
    
    $.ajax({
		type: "POST",
	  	url: '/en/index.php/actions/member/checkJumin/',
	  	data: { jumin: juminHead+''+juminFoot },
	  	dataType: "text",
		success: function(data){
		    var json = eval('('+data+')');
			if (json.success){
				
				/*
				 * 인증 후 메인 
				 */
				showMain();
				 
			}
			else {
				alert(json.reason);
			}
		}
	});
}

function login(){
    var username = $('input[name="username"]').val();
    var password = $('input[name="password"]').val();
    
    $.ajax({
		type: "POST",
	  	url: '/en/index.php/actions/member/login/',
	  	data: { username: username, password: password },
	  	dataType: "text",
		success: function(data){
		    var json = eval('('+data+')');
			if (json.success){
				
				/*
				 * 인증 후 메인 
				 */
				showMain();
				 
			}
			else {
				alert('아이디와 비밀번호를 정확히 입력하신 후 다시 시도해 주십시오');
			}
		}
	});

}

$(document).ready(function(){
	$(document.login_form).submit(function(e){
		e.preventDefault();
		login();
	});
	
	$(document.check_adult_form).submit(function(e){
		e.preventDefault();
		validAdult()
	});
});

</script>
</head>
<body>

<div class="authWrapper">
	<div class="authContainer">

		<div class="auth">
			<div class="left">
				<img src="/en/img/19l.png" class="a">
			</div>


			<div class="right">
				<h2><img src="/en/img/19c.png" align="left"><p>이 정보내용은 청소년 유해 매체물로서 정보통신망 이용촉진법 및 정보보호등에 관한 법률 및 청소년 보호법의 규정에 의하여 19세 미만의 청소년은 이용할 수 없습니다. <a href="http://jr.naver.com">19세 미만 나가기</a></p></h2>
				<ul style="margin-right: 30px;">
					<form name="check_adult_form" method="POST" action="">
					<h3>주민번호인증</h3>
					<li class="jm"><label>주민번호</label><input type="text" name="juminHead" maxlength="6" /> - <input type="password" name="juminFoot" maxlength="7" /></li>
					<li><label>성명</label><input type="text" name="user_name" /></li>
					<li class="button" ><input type="submit" value="성인인증" /><span>입력해주신 주민등록정보는<br/>다른 용도로 저장하거나 사용하지 않습니다</li>
					</form>
				</ul>
				<ul>
					<form name="login_form" method="POST" action="">
					<h3>회원로그인</h3>
					<li><label>아이디</label><input type="text" name="username"/></li>
					<li><label>비밀번호</label><input type="password" name="password" /></li>
					<li class="button"><input type="submit" value="로그인" /></li>
					</form>
				</ul>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35124373-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>