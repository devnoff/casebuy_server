			<div class="loginForm">
				<form name="login_form" method="POST" action="/loveholic/index.php/actions/member/login/">
				<div class="member">
					<p class="title"><strong>환영합니다♥</strong><span>CASEBUY을 이용해주셔서 감사합니다!</span></p>
					<p><input name="username" type="text" placeholder="아이디" /><input name="password" type="password" placeholder="비밀번호" /></p>
					<p><a onclick="login();" class="button"><span>로그인</span></a></p>
				</div>
				<div class="member2"><a href="<?=site_url('shop/join');?>" class="join">회원가입</a><a href="<?=site_url('shop/find');?>">아이디·비밀번호 찾기</a><span>회원으로 가입하시면 구매 시 적립금을 드립니다!</span></div>

			</div>
			
			<script>
			
			$('form[name="login_form"]').find('input').each(function(){
			
				$(this).keyup(function(e){
					console.log('keyup');
					 if (e.keyCode == 13) { // enter key
					 	$(this).blur();
                    	login();
	                    return false
	                    }
				});
				
			});
			
			$('form[name="login_form"]').submit(function(e){
				console.log('submit');
				e.preventDefault();
				login();
			});
			
			
			var showMain = function(){
			    location.href = "<?=$redirect_url?>";
			}
			
			var login = function(){
			    var username = $('input[name="username"]').val();
			    var password = $('input[name="password"]').val();
			    
			    if (username.length < 1){
				    alert('아이디를 입력하세요');
				    return;
			    }
			    
			    else if (password.length < 1){
				    alert('비밀번호를 입력하세요');
				    return;
			    }
			    
			    $.ajax({
					type: "POST",
				  	url: '/loveholic/index.php/actions/member/login/',
				  	data: { username: username, password: password },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
/* 							alert('로그인 완료'); */
							
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

			
			</script>