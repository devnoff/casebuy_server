		<ul class="subMenu">
			<li><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
			<li><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
			<li><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
			<li><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
			<li class="selected"><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
		</ul>
		
		<!-- Q&A 리스트 -->
				<div id="qna_view">
					<ul class="listQna">
						<!-- 질문 리스트 항목 -->
					</ul>
					
					<div class="pagination">
					</div>

				</div>
				
				<!-- Q&A 작성 -->
				<div class="detailContainer">
					<div id="qna_write_view" style="display:none">
					<ul class="inputQna boardQna">
					<li class="name">
						<div class="title"><span>닉네임</span></div>
						<div class="content"><input name="nickname" type="text" placeholder="닉네임을 입력해주세요" /></div>
					</li>
					<li class="subject">
						<div class="title"><span>제목</span></div>
						<div class="content"><input name="title" type="text" placeholder="제목을 입력해주세요" /></div>
					</li>
					<li class="content">
						<div class="title"><span>내용</span></div>
						<div class="content"><textarea name="content" placeholder="내용을 입력해주세요"></textarea></div>
					</li>
					<li class="secret">
						<div class="content"><input name="is_private" type="checkbox" checked="checked" name="chk_info" value="open"><span>문의·답변 나만보기</span></div>
					</li>
					<li class="button">
						<a onclick="sendQuestion();">문의등록</a>
						<a onclick="showQnaWriteForm();" class="cancel">취소</a>
						<p>욕설과 비방은 삼가주세요 :)</p>
					</li>
				</ul>

				</div>
				</div>
				
		
		
		<script>
			/* 페이지네이션 */
			var applyPagination = function(parent,dispFn) {
			      var members_id = '<?=$member->id;?>';
			    
			      $(parent).children('a').click(function(){
			         	var url = $(this).attr("href");
			         	$.ajax({
				         	type: "GET",
				         	data: {members_id:members_id},
				         	url: url,
				         	success: function(json) {
					         		if (json.success && dispFn)
					         			dispFn(json);
					         			}
					         	});

			        return false;
			      });
			    }

			
			
			/****** QnA 관련 함수 ******/
			
			/* QnA 작성 폼 */
			var showingForm = false;
			var showQnaWriteForm = function(){
				console.log('hi');
			
				$qna_write = $('#qna_write_view');
				if (showingForm){
					$qna_write.hide();
					$('#qna_view').show();
					showingForm = false;
				} else {
					$('#qna_view').hide();
					$qna_write.show();
					showingForm = true;
				}
			}
			
			/* QnA 불러오기 */
			var loadQuestions = function(){
				var members_id = '<?=$member->id;?>';
			
				// Ajax 요청				
				$.ajax({
		    		type: 'GET',
		    		url: '<?=site_url('actions/shop/questionsProduct');?>',
		    		data: {members_id:members_id},
		    		success: function(text){
		    			var json = eval(text);		
		                if (json.success){
			                dispQuestionList(json);
		                } else {
			                
		                }
		    		}
		    	});
			};
			
			/* QnA 목록 뿌리기 */
			var dispQuestionList = function(data){
				var pagenation = data.pagination;
				var answers = data.answers;
				data = data.questions;
				
			
				function markupTitle(reply,title,nickname,date,is_private, products_id, product_name){
					
					var state = reply?'답변완료':'답변대기';
					
					var tpl = '<div class="left"><strong '+ (reply?'class="reply"':'') +'>'+state+'</strong><span>';
					
					if (products_id != null && products_id != 0) {
						url = "<?=site_url('shop/product');?>/?id="+products_id;
						tpl += '<a class="product" href="'+url+'">['+product_name+']</a> ';
					}
					
					tpl += title;
					
					if (is_private == 'Y'){
						tpl += '<i class="secret"></i>';
					}
					
					tpl += '</span></div><div class="right"><strong>'+nickname+'</strong><span>'+date+'</span>';
					
					tpl += '</div>';
					
					return tpl;
				}
				
				function markupBody(content){
					var tpl = '<div class="content" style="display:none;">'+content+'</div>';
					return tpl;
				}
				
				function markupReply(reply){
					var tpl = '<div class="reply" style="display:none;"><span>'+reply+'</span></div>';
					return tpl;
				}
				
				
				/* 기존 항목 삭제 */
				var $list = $('#qna_view > ul').empty();
				var $page = $('#qna_view > .pagination').empty();
				
				/* 새 항목 삽입 */
				for (var i in data){
					var f = data[i].family;
					var answer = answers[f];
					var reply = answer?true:false;
					
					var $li = $('<li>').append(markupTitle(reply,data[i].title,data[i].nickname,data[i].date_write,data[i].is_private, data[i].products_id, data[i].product_name));
					
					if (data[i].content)
						$li.append(markupBody(data[i].content));
					
					if (reply){
						$li.append(markupReply(answers[f].content));
					}
					
					$li.click(function(){
						var disp = $(this).children('.content').css('display');
						if (disp == 'none'){
							$(this).children('.content,.reply').css('display','block');
						} else {
							$(this).children('.content,.reply').css('display','none');
						}
						
					});
					
					
					
					$list.append($li);
				}
				
				if (!data || data.length < 1){
					$list.append('<li>문의 내역이 없네요 :)</li>');
				}
						
				/* 페이지네이션 */
				$page.append(pagenation);
				applyPagination($page,dispQuestionList);
				
			};
			
			
			var clearQuestionForm = function(){
				$('#qna_write_view').find('input[name="nickname"]').val('');
				$('#qna_write_view').find('textarea[name="content"]').val('');
				$('#qna_write_view').find('input[name="title"]').val('');
				$('#qna_write_view').find('input[name="is_private"]').attr('checked',true);
			}
			
			/* 질문 전송 */
			var sendQuestion = function(){
				var nickname = $('#qna_write_view').find('input[name="nickname"]').val();
				var content = $('#qna_write_view').find('textarea[name="content"]').val();
				var title = $('#qna_write_view').find('input[name="title"]').val();
				var is_private = $('#qna_write_view').find('input[name="is_private"]').is(':checked');
				var product_id = $('input[name="product_id"]').val();

				
				// 유효성 검사
				var alert_title = null;
				if (!nickname || nickname.length < 1){
					alert_title = '닉네임';
					$('#qna_write_view').find('input[name="nickname"]').effect('shake',{times:2},200,null);
				}
					
				else if (!title || title.length < 1){
					alert_title = '제목';
					$('#qna_write_view').find('input[name="title"]').effect('shake',{times:2},200,null);
				}	
					
				else if (!content || content.length < 1){
					alert_title = '내용';
					$('#qna_write_view').find('textarea[name="content"]').effect('shake',{times:2},200,null);
				}
				
				
					
				if (alert_title){
/* 					alert(alert_title + '을 입력해주세요'); */
					return;	
				} 
				
				// Ajax 전송				
				$.ajax({
		    		type: 'POST',
		    		url: '<?=site_url('actions/shop/addQuestion');?>',
		    		data: {
		    			products_id:product_id, 
		    			nickname:nickname, 
		    			title:title, 
		    			content:content, 
		    			is_private: is_private?'Y':'N'
		    			},
		    		success: function(text){
		    			var json = eval(text);
		
		                if (json.success){
			                selectSubMenu('qna');
			                clearQuestionForm();
		                } 
		    		}
		    	});
			};
			
			
			/* 질문 불러오기 */
			loadQuestions();
		</script>