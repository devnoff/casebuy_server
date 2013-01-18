			
			<h4>아이디 찾기</h4>
			<h6 style="padding-top:10px;color:gray">가입하신 아이디의 힌트를 보여드립니다.</h6>
			<div class="orderForm">
				<div class="left">
					<div class="container">
						<h5>이메일 주소로 찾기</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="email">
								<th><span>이메일</span></th>
								<td><input type="text" id="id_email" /></td>
							</tr>
						</table>
					</div>
					<div class="twoButton"><a onclick="findIdByEmail()" class="submit">확인</a></div>
				</div>
				<div class="right">
					<div class="container">
						<h5>휴대폰 번호로 찾기</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td>
									<select name="id_mobile[0]">
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
									</select>
									<span>-</span>
									<input type="text" name="id_mobile[1]" maxlength="4"/>
									<span>-</span>
									<input type="text" name="id_mobile[2]" maxlength="4"/>
									</td>
							</tr>
						</table>
					</div>
					<div class="twoButton"><a onclick="findIdByMobile()" class="submit">확인</a></div>
				</div>
			</div>
			
			<br/>
			<br/>
			<br/>
			<br/>
			
			<h4>비밀번호 찾기</h4>
			<h6 style="padding-top:10px;color:gray">가입 시 입력하신 이메일 또는 휴대폰으로 임시 비밀번호를 보내드립니다.</h6>
			<div class="orderForm">
				<div class="left">
					<div class="container">
						<h5>이메일 주소로 찾기</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>아이디</span></th>
								<td><input type="text" id="pass_id" /></td>
							</tr>
							<tr class="email">
								<th><span>이메일</span></th>
								<td><input type="text" id="pass_email"/></td>
							</tr>
						</table>
					</div>
					<div class="twoButton"><a onclick="findPassByEmail()" class="submit">확인</a></div>
				</div>
				<div class="right">
					<div class="container">
						<h5>휴대폰 번호로 찾기</h5>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr class="name">
								<th><span>아이디</span></th>
								<td><input type="text" id="pass_id1"/></td>
							</tr>
							<tr class="phone">
								<th><span>휴대전화</span></th>
								<td>
									<select name="pass_mobile[0]">
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
									</select>
									<span>-</span>
									<input type="text" name="pass_mobile[1]" maxlength="4"/>
									<span>-</span>
									<input type="text" name="pass_mobile[2]" maxlength="4" />
								</td>
							</tr>
						</table>
					</div>
					<div class="twoButton"><a onclick="findPassByMobile()" class="submit">확인</a></div>
				</div>
			</div>

			
			<script>
			
			var findIdByEmail = function(){
				var email = $('#id_email').val();
				
				if (email == ''){
					alert('이메일 주소를 입력하세요');
					return;
				}
				
				 $.ajax({
					type: "POST",
				  	url: '<?=site_url('actions/member/findUsernameByEmail');?>/',
				  	data: { email: email },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
							alert(json.username);
						}
						else {
							alert('해당 이메일주소로 가입된 계정이 없습니다. 이메일 주소를 확인하신 후 다시 시도해주세요');
						}
					}
				});

				
			}
			
			var findIdByMobile = function(){
				var mobile = '';
				$('[name^="id_mobile"]').each(function(){
					mobile += $(this).val();
				});
				
				if (mobile == ''){
					alert('휴대전화 번호를 입력하세요');
					return;
				}
				
				$.ajax({
					type: "POST",
				  	url: '<?=site_url('actions/member/findUsernameByMobile');?>/',
				  	data: { mobile: mobile },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
							alert(json.username);
						}
						else {
							alert('해당 휴대폰번호로 가입된 계정이 없습니다. 휴대폰 번호를 확인하신 후 다시 시도해주세요');
						}
					}
				});
			}
			
			var findPassByEmail = function(){
				
				showLoadingOverlay();
			
				var username = $('#pass_id').val();
				var email = $('#pass_email').val();
				
				if (username.length < 1 ){
					alert('아이디를 입력하세요');
					hideLoadingOverlay();
					return;
				}
				
				else if (email.length < 1){
					alert('이메일 주소를 입력하세요');
					hideLoadingOverlay();
					return;
				}
				
				$.ajax({
					type: "POST",
				  	url: '<?=site_url('actions/member/findPasswordByEmail');?>/',
				  	data: { username: username, email:email },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
							alert('회원님의 이메일로 임시 비밀번호를 전송하였습니다. 이메일을 확인하신 후 로그인해주세요. 감사합니다.');
						}
						else {
							// alert('일치하는 결과가 없습니다.');
							alert(json.reason);
						}
						
						hideLoadingOverlay();
					}
				}).fail(function(){
						alert('서버와의 통신이 원할하지 않습니다.');
						hideLoadingOverlay();
					});;
			}
			
			var findPassByMobile = function(){
				showLoadingOverlay();
			
				var username = $('#pass_id1').val();
				var mobile = [];
				$('[name^="pass_mobile"]').each(function(){
					mobile.push($(this).val());
				});
				mobile = mobile.join('-');

				if (username.length < 1){
					alert('아이디를 입력하세요.');
					hideLoadingOverlay();
					return;
				}

				else if (mobile.length < 6){
					alert('휴대전화 번호를 입력하세요.');
					hideLoadingOverlay();
					return;
				}
				
				$.ajax({
					type: "POST",
				  	url: '<?=site_url('actions/member/findPasswordByMobile');?>/',
				  	data: { username: username, mobile:mobile },
				  	dataType: "text",
					success: function(data){
					    console.log(data);
					    var json = eval('('+data+')');
						if (json.success){
							alert('회원님의 휴대폰으로 임시 비밀번호를 전송하였습니다. 문자메시지를 확인하신 후 로그인해주세요. 감사합니다.');
						}
						else {
							// alert('일치하는 결과가 없습니다.');
							alert(json.reason);
						}
						
						hideLoadingOverlay();
					}
				}).fail(function(){
						alert('서버와의 통신이 원할하지 않습니다.');
						hideLoadingOverlay();
					});;
			}
			
			</script>