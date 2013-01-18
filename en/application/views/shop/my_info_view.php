		<ul class="subMenu">
			<li><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
			<li><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
			<li class="selected"><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
			<li><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
			<li><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
		</ul>
		
		<?php
			$mobile = $member->mobile;
			$mob = explode('-',$mobile);
				
		?>
		<form action="<?=site_url('actions/member/update');?>" method="POST">
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="id">
				<th>아이디</th>
				<td><?=$member->username;?></td>
			</tr>
			<tr class="name">
				<th>닉네임</th>
				<td><input name="nickname" type="text" value="<?=$member->nickname;?>"></td>
			</tr>
			<tr class="email">
				<th>이메일</th>
				<td><input name="email" type="text" value="<?=$member->email;?>"><span></span></td>
			</tr>
			<tr class="phone">
				<th>휴대폰</th>
				<td>
					<input name="mobile[0]" type="text" value="<?=$mob[0];?>"><span>-</span>
					<input name="mobile[1]" type="text" value="<?=$mob[1];?>"><span>-</span>
					<input name="mobile[2]" type="text" value="<?=$mob[2];?>"><span></span>
				</td>
			</tr>
		</table>
		</form>
		<div class="twoButton">
			<a onclick="update()" class="submit">변경하기</a>
			<!-- <a href="" class="cancel">취소하기</a> -->
		</div>
		<br/>
		<br/>
		<br/>
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="password">
				<th>비밀번호 변경</th>
				<td><input name="password" type="password" placeholder="새 비밀번호 입력"><span style=" padding:10px;">재확인</span><input name="password1" type="password" placeholder="재확인"> &nbsp;<button type="button" onclick="updatePassword()">변경</button> <span></span></td>
			</tr>
		</table>
		
		<br/>
		<br/>
		<p><h4>회원 탈퇴</h4></p>
		<p style="margin-top:10px">회원 탈퇴 시 구매내역 및 적립금이 소멸됩니다. <a onclick="quit()" style="text-decoration:underline">회원 탈퇴</a></p>
		
		<div id="dialog-confirm" title="탈퇴 확인" style="display:none">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>회원탈퇴 시 적립금이 소멸됩니다. 정말로 탈퇴를 하시겠습니까?</p>
		</div>
		
		<script type="text/javascript" src="/loveholic/js/jquery.watermark.min.js"></script>
		<script>
		
			////
			
			// Placeholder
			$('input[placeholder]').each(function(){
/*
				var msg = $(this).attr('placeholder');
				$(this).watermark(msg);
*/
			});
		
			
			////
			
			var usernameValid = false;
			var passwordValid = false;
			var emailValid = false;
			var mobileValid = false;
			var mobileStr = '';
		
			
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
				
				if (password.length >= 4 && password == password1){
					updatePasswordState('ok');
				} 
				else if (password.length < 4){
					updatePasswordState('not_avail');
				} else {
					updatePasswordState("not_match");
				}
			};
			
			var updatePasswordState = function(state){
				passwordValid = state;
				
				$stateEl = $('input[name="password1"]').next().next();
				if (state == 'ok'){
					$stateEl.html('사용가능한 비밀번호 입니다.').css('color','green');
				} else if (state == 'not_match') {
					$stateEl.html('비밀번호가 일치하지 않습니다.').css('color','red');
				} else {
					$stateEl.html('4자 이상으로 입력하세요').css('color','red');
				}	
			};
			
			$('input[name="password"], input[name="password1"]').keyup(function(){
				validPassword();
			});
			
			
			/* 이메일 체크 */
			var validEmail =  function(value){
				
				var value = $('input[name="email"]').val();
				if (value.length < 1){
					updateEmailState('clear');
					return;	
				} 
				
				if (CheckMail(value)){
					updateEmailState('ok');
				} else {
					updateEmailState('not_avail');
				}
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
				
				validEmail();
			});
			
			
			
			
			/* 휴대전화 번호 체크 */
			var validMobile = function(){
			
				var mob = $('input[name^="mobile"]').toArray();
				var a = [];
				for (var i in mob){
					a.push($(mob[i]).val());
				}
				
				var mobile = a.join('-');
			
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
				validMobile();
			});
			
			
			
			/* 회원 정보 업데이트 */
			var update = function(){
				var valid = [];
/* 				valid.push(usernameValid); */
/* 				valid.push(passwordValid); */
				validEmail();
				validMobile();
				valid.push(emailValid);
				valid.push(mobileValid);
				
				for (var i in valid){
					if (!(valid[i] ==  true || valid[i] == 'ok')){
						alert('모든 필수항목을 정확히 입력해주세요.');
						return;
					}
				}
				
				
				$('form').submit();
			}
			
			var updatePassword = function(){
				if (passwordValid != 'ok'){
					alert('비밀 번호를 정확이 입력해주세요.');
					return;
				}
				
				var password = $('input[name="password"]').val();
				var password1 = $('input[name="password1"]').val();
				
				$.ajax({
			   		type: 'POST',
			   		url: "<?=site_url('actions/member/update');?>",
			   		data: {password:password,password1:password1},
			   		success: function(text){
			   			var json = eval('('+text+')');
			               
			            if (json.success){
			            	alert('업데이트 완료!');
			              	location.href='';  
			            } else {
				            alert(json.reason);
			            }
			   		}
			   	});
				
			}
			
			$('form').submit(function(e){
			   e.preventDefault();
			   var url = $(this).attr('action');
			   
			   $('input[name="password"]').val(null);
			   
			   var data = $(this).serialize();
			   

			   $.ajax({
			   		type: 'POST',
			   		url: "<?=site_url('actions/member/update');?>",
			   		data: data,
			   		success: function(text){
			   			var json = eval('('+text+')');
			               
			            if (json.success){
			            	alert('업데이트 완료!');
			              	location.href='';  
			            } 
			   		}
			   	});
			   
			});
			
			
			/*
			 * 회원 탈퇴
			 */
			 
			var quit = function(){
			
				$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					"탈퇴하겠어요!": function() {
					
						$.ajax({
					   		type: 'POST',
					   		url: "<?=site_url('actions/member/quit');?>",
					   		success: function(text){
						   		$( this ).dialog( "close" );
					   			var json = eval('('+text+')');				               
					            if (json.success){
					            	alert('탈퇴가 완료되었습니다.');
					              	location.href='<?=site_url('member/logout');?>';  
					            } else {
						            alert('처리 도중 문제가 발생하여 탈퇴를 완료하지 못했습니다. 관리자에게 문의하세요');
					            }

					   		}
					   	});
					
						
					},
					'아닙니다': function() {
						$( this ).dialog( "close" );
					}
				}
			});
				
			}
			
		</script>