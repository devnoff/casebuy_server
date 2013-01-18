		<script type="text/javascript" src="/loveholic/js/jquery.watermark.min.js"></script>
		<h4>회원정보 입력</h4>
		<form action="<?=site_url('actions/member/join');?>" method="POST">
		<h5 class="join">색콤달콤은 실명과 주소를 입력받지 않습니다</h5>
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="id">
				<th>아이디</th>
				<td><input name="username" type="text"><span></span></td>
			</tr>
			<tr class="password">
				<th>비밀번호</th>
				<td><input name="password" type="password" placeholder="비밀번호 입력"> <input name="password1" type="password" placeholder="재확인"><span></span></td>
			</tr>

		</table>

		<h5 class="join">이메일과 휴대번호는 아이디·비밀번호 찾기에만 사용됩니다</h5>
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="email">
				<th>이메일</th>
				<td><input name="email" type="text"><span></span></td>
			</tr>
			<tr class="phone">
				<th>휴대폰</th>
				<td>
					<input name="mobile[0]" type="text" onKeyPress="return numbersonly(event, false)" maxlength="4"><span>-</span>
					<input name="mobile[1]" type="text" onKeyPress="return numbersonly(event, false)" maxlength="4"><span>-</span>
					<input name="mobile[2]" type="text" onKeyPress="return numbersonly(event, false)" maxlength="4"><span></span>
				</td>
			</tr>
		</table>

		<h5 class="join">상품평 작성 시 표시될 닉네임을 입력해주세요</h5>
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="name">
				<th>닉네임</th>
				<td><input name="nickname" type="text"><span></span></td>
			</tr>
		</table>

		<div class="twoButton">
		<a onclick="join();" class="submit">확인</a>
		<a href="<?=site_url();?>" class="cancel">취소</a>
		</div>
		
		</form>
		<script>
			
			
			// Placeholder
/*
			$('input[placeholder]').each(function(){
				var msg = $(this).attr('placeholder');
				$(this).watermark(msg);
			});
*/

			
			
			// Validation Variables
			var usernameValid = false;
			var passwordValid = false;
			var emailValid = false;
			var mobileValid = false;
			var mobileStr = '';
			var nicknameValid = false;
		
			
			// str은 모두 소문자여야하고 첫글자는 영문이어야 한다. 영문과 0~9, -, _, ^는 허용한다. 
			function CheckChar(str) { 
			    strarr = new Array(str.length); 
			    var flag = true; 
			    for (i=0; i<str.length; i++) { 
			        strarr[i] = str.charAt(i) 
			        if (i==0) { 
			            if (!((strarr[i]>='a')&&(strarr[i]<='z'))) { 
			                flag = false; 
			            } 
			        } else { 
			            if (!((strarr[i]>='a')&&(strarr[i]<='z')||(strarr[i]>='0')&&(strarr[i]<='9')||(strarr[i]=='-')||(strarr[i]=='_')||(strarr[i]=='^'))) { 
			                flag = false; 
			            } 
			        } 
			    } 
			    if (flag) { 
			        return true; 
			    } else { 
			        return false; 
			    } 
			} 

		
			/* 아이디 체크 */
			var validUsername = function(){
				
				var username = $('input[name="username"]').val();
				
				if (username.length < 4){
					updateUsernameState(false);
					return;
				}
				
				if (!CheckChar(username)){
					updateUsernameState(false);
					return;
				}
					
				
				// Ajax 전송				
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/member/checkUsername');?>',
			    		data: { username:username},
			    		success: function(exist){
			    			exist = eval(exist);
			    			updateUsernameState(!exist);
			    		}
			    	});
			};
			
			var updateUsernameState = function(state){
				usernameValid = state;
				
				$stateEl = $('input[name="username"]').next();			
				if (state){
					$stateEl.html('사용가능한 아이디입니다.').css('color','green');
				} else {
					$stateEl.html('사용할 수 없는 아이디 입니다.').css('color','red');
				}
			};
			
			$('input[name="username"]').keyup(function(){
				validUsername();
			});
			
			
			
			
			/* 비번 일치 체크 */
			var validPassword = function(){
				var password = $('input[name="password"]').val();
				var password1 = $('input[name="password1"]').val();
				
				if (password.length >= 8 && password == password1){
					updatePasswordState('ok');
				} 
				else if (password.length < 8){
					updatePasswordState('not_avail');
				} else {
					updatePasswordState("not_match");
				}
			};
			
			var updatePasswordState = function(state){
				passwordValid = state;
				
				$stateEl = $('input[name="password1"]').next();
				if (state == 'ok'){
					$stateEl.html('사용가능한 비밀번호 입니다.').css('color','green');
				} else if (state == 'not_match') {
					$stateEl.html('비밀번호가 일치하지 않습니다.').css('color','red');
				} else {
					$stateEl.html('8자 이상으로 입력하세요').css('color','red');
				}	
			};
			
			$('input[name="password"], input[name="password1"]').keyup(function(){
				validPassword();
			});
			
			
			/* 이메일 체크 */
			var validEmail =  function(){
				
			};
			
			// 이메일 체크 
			var CheckMail = function (strMail) { 
			   /** 체크사항 
			     - @가 2개이상일 경우 
			     - .이 붙어서 나오는 경우 
			     -  @.나  .@이 존재하는 경우 
			     - 맨처음이.인 경우 
			     - @이전에 하나이상의 문자가 있어야 함 
			     - @가 하나있어야 함 
			     - Domain명에 .이 하나 이상 있어야 함 
			     - Domain명의 마지막 문자는 영문자 2~4개이어야 함 **/ 
			
			    var check1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/;  
			
			    var check2 = /^[a-zA-Z0-9\-\.\_]+\@[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4})$/; 
			     
			    if ( !check1.test(strMail) && check2.test(strMail) ) { 
			        return true; 
			    } else { 
			        return false; 
			    } 
			}
			
			var updateEmailState = function(state){
				emailValid = state;
				
				$stateEl = $('input[name="email"]').next();
				if (state == 'ok'){
					$stateEl.html('사용가능한 이메일 입니다.').css('color','green');
				} else if (state == 'not_avail') {
					$stateEl.html('유효하지 않은 이메일 입니다.').css('color','red');
				} else {
					$stateEl.html('');
				}
			};
			
			$('input[name="email"]').keyup(function(){
				
				if ($(this).val().length < 1){
					updateEmailState('clear');
					return;	
				} 
				
				if (CheckMail($(this).val())){
					updateEmailState('ok');
				} else {
					updateEmailState('not_avail');
				}
			});
			
			
			
			
			/* 휴대전화 번호 체크 */
			var validMobile = function(mobile){
				var exp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
				if (exp.test(mobile)){
					updateMobileState(true);
					return;
				}
				
				updateMobileState(false);
			};
			
			var updateMobileState = function(state){
				mobileValid = state;
			
				$stateEl = $('input[name="mobile[2]"]').next();
				if (state){
					$stateEl.html('사용가능한 휴대전화 번호 입니다.').css('color','green');
				} else {
					$stateEl.html('유효하지 않은 휴대전화 번호 입니다.').css('color','red');
				}
			}
			
			$('input[name^="mobile"]').keyup(function(){
				var mob = $('input[name^="mobile"]').toArray();
				var a = [];
				for (var i in mob){
					a.push($(mob[i]).val());
				}
				
				mobileStr = a.join('-');
				validMobile(mobileStr);
			});
			
			
			
			/* 회원 가입 */
			var join = function(){
				var valid = [];
				valid.push(usernameValid);
				valid.push(passwordValid);
				valid.push(nicknameValid);
				
				for (var i in valid){
					if (!(valid[i] ==  true || valid[i] == 'ok')){
						alert('모든 필수항목을 정확히 입력해주세요.');
						return;
					}
				}

				if (!emailValid && !mobileValid){
					alert('이메일 주소 또는 휴대폰 번호 중 하나를 필수로 입력하셔야합니다.');
				}
				
				$('form').submit();
			}
			
			$('form').submit(function(e){
			   e.preventDefault();
			   var url = $(this).attr('action');
			   
			   var data = $(this).serialize();
			   
			   $.ajax({
			   		type: 'POST',
			   		url: url,
			   		data: data,
			   		success: function(text){
			   			var json = eval('('+text+')');
			               
			            if (json.success){
			            	alert("환영합니다! 색콤달콤에 가입해주셔서 감사합니다. 로그인 후 정상적으로 이용이 가능합니다. 즐거운 쇼핑 되세요 :)");
			              	location.href='<?=$ref_to;?>';  
			            } else {
				            alert(json.reason);
			            }
			   		}
			   	});
			   
			});
			
			/* 닉네임 체크 */
			var validNickname = function(){
				
				var nickname = $('input[name="nickname"]').val();
				
				if (nickname.length < 2){
					updateNicknameState(false);
					return;
				}
					
				
				// Ajax 전송				
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/member/checkNickname');?>',
			    		data: { nickname:nickname},
			    		success: function(exist){
			    			exist = eval(exist);
			    			updateNicknameState(!exist);
			    		}
			    	});
			};
			
			var updateNicknameState = function(state){
				nicknameValid = state;
				
				$stateEl = $('input[name="nickname"]').next();			
				if (state){
					$stateEl.html('사용가능한 닉네임입니다.').css('color','green');
				} else {
					$stateEl.html('사용할 수 없는 닉네임 입니다.').css('color','red');
				}
			};
			
			$('input[name="nickname"]').keydown(function(){
				validNickname();
			});
			
		</script>