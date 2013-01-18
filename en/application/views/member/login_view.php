<?php
// Define Content Type
	header('Content-Type: text/html; charset=utf-8');
	
?>

<html>
<head>
	<title>Login View</title>
	
<script src="/en/js/jquery-1.7.2.min.js"></script>
<script src="/en/js/helpers.js"></script>	
<script>

function showMain(){
    location.href = "<?=$redirect_url?>";
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
		    console.log(data);
		    var json = eval('('+data+')');
			if (json.success){
				
				/*
				 * 인증 후 메인 
				 */
				showMain();
				 
			}
			else {
				alert('아이디 또는 비밀번호가 일치하지 않습니다.');
			}
		}
	});

}

$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        login();
    });
});




</script>
</head>
<body>

<h1>로그인</h1>


<div>
<p>로그인</p>
<ul>
    <form method="POST" action="">
    <li><label>아이디</label><input type="text" name="username" /></li>
    <li><label>비밀번호</label><input type="password" name="password" /></li>
    <li><input type="submit" value="로그인" /></li>
    </form>
</ul>
</div>

</body>
</html>