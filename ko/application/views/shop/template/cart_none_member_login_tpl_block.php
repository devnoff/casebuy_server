
			<div class="loginForm">
				<form name="login_form" action="">
				<div class="member">
					<p class="title"><strong>환영합니다♥</strong><span>CASEBUY을 이용해주셔서 감사합니다!</span></p>
					<p><input name="username" type="text" placeholder="아이디" /><input name="password" type="password" placeholder="비밀번호" /></p>
					<p><a onclick="login()" class="button"><span>로그인</span></a></p>
				</div>
				</form>
				<div class="member2"><a href="<?=site_url('shop/join');?>" class="join">회원가입</a><a href="<?=site_url('shop/find');?>">아이디·비밀번호 찾기</a></div>
				<div class="nomember">
					<div>
						<a href="<?=$order_url;?>">비회원 구매</a>
						<span>CASEBUY에 가입하지 않고도 구매하실 수 있습니다. 단, 이 경우 적립금이 적립되지 않습니다.</span>
					</div>
				</div>
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
			
			
				var login = function(){
			    var username = $('input[name="username"]').val();
			    var password = $('input[name="password"]').val();
			    
			    $.ajax({
					type: "POST",
				  	url: '/ko/index.php/actions/member/login/',
				  	data: { username: username, password: password },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
							location.href='<?=$order_url;?>';
						}
						else {
							alert('아이디 또는 비밀번호가 일치하지 않습니다.');
						}
					}
				});
			
			}
			</script>