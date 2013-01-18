		<div class="productWrapper">
			<div class="photo"><img src="<?=$product->web_detail_img;?>"></div>
			<div class="infoWrapper">
				<div class="text">
					<p class="comment"><?=$product->sub_title;?></p>
					<p class="subject"><?=$product->title;?></p>
					<p class="price">
						<i><?=number_format($product->regular_price);?></i> <strong><?=number_format($product->sales_price);?><b>원</b></strong> 						<?php if ($product->extra_info_value1) { ?>
						<!-- span>(<u><?=$product->extra_info_value1;?></u>)</span 추가정보 숨김 -->
						<?php }?>
					</p>
				</div>
				<div class="button">
					<p class="button">
						<a onclick="<?=$product->sales_state!='SALE'?'soldout()':'order()';?>"><span>바로구매</span></a>
						<a onclick="<?=$product->sales_state!='SALE'?'soldout()':'addToCart('.$product->id.',false)';?>" class="square"><span>장바구니</span></a>
						<a id="zzimBtn" zzim="<?=$is_wishItem?'true':'false';?>" onclick="zzimBtnTapped();" class="square">
							<span><?=$is_wishItem?'찜해제':'찜하기';?></span>
						</a>
					</p>
				</div>
				<div class="info">
					<p class="quantity">
						<span>수량선택</span>
						<select name="quantity">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</p>
					<p class="mileage">결제금액 <strong class="price" id="purchase_price"><?=number_format($product->sales_price);?></strong>원 (적립금 <strong id="final_point"><?=$product->point_amount;?></strong>원)</p>
				</div>
			</div>

		</div>
		<input type="hidden" name="product_id" value="<?=$product->id;?>" />
		<input type="hidden" name="qty" value="1"/>
		<input type="hidden" name="price" value="<?=$product->sales_price;?>"/>
		<input type="hidden" name="point_rate" value="<?=$product->point_rate;?>" />
		<input type="hidden" name="fixed_point" value="<?=$product->fixed_point;?>" />
		<input type="hidden" name="has_purchase" value="<?=$has_purchase;?>" />
		<input type="hidden" name="is_member" value="<?=$member?'Y':'N';?>" />
		

		<form name="buy_form" method="GET" action="<?=site_url('shop/order')?>">
			<input type="hidden" name="products_id" value="<?=$product->id;?>" />
			<input type="hidden" name="qty" value="1"/>
			<input type="hidden" name="type" value="buy_now"/>
		</form>

		<script>
		
			var calculatePriceNPoint = function(){
				var qty = $('select[name="quantity"]').val();
				var price = $('input[name="price"]').val();
				var point_rate = $('input[name="point_rate"]').val();
				var fixed_point = $('input[name="fixed_point"]').val();
				
				var purchase_price = qty * price;
				$('#purchase_price').html(addCommas(purchase_price));
				
				var final_point = 0;
				if (fixed_point && fixed_point != 0){
					final_point = qty * fixed_point;
				} else if (point_rate && point_rate !=0) {
					final_point = parseInt(purchase_price * (point_rate / 100.0));
				}
				
				$('#final_point').html(final_point);	
			}
		
			/* 수량 변경 시 결제 금액 및 포인트 계산 */
			$('select[name="quantity"]').change(function(){
				calculatePriceNPoint();
			});
			
			calculatePriceNPoint();
		</script>


		<div class="detailWrapper">
			<ul class="tab" style="display:none"> <!-- 숨김 -->
				<li id="detail_view_btn" class="selected">
					<a onclick="selectSubMenu('detail')">상세정보</a>
				</li>
				<li id="review_view_btn">
					<a onclick="selectSubMenu('review')">구매후기 <strong>(<?=$review_cnt;?>)</strong></a>
				</li>
				<li id="qna_view_btn">
					<a onclick="selectSubMenu('qna')">Q&A <strong>(<?=$qna_cnt;?>)</strong></a>
				</li>
				<li id="info_view_btn">
					<a onclick="selectSubMenu('info')">배송·교환·환불</a>
				</li>
			</ul>

			<!-- 상품 설명 -->
			<div class="detailContainer">
			
				<!-- 상세 정보 -->
				<div id="detail_view" class="description" style="display:none">
					<?=$product->description;?>
				</div>
				
				
				<!-- 구매 후기 목록 -->
				<div id="review_view" style="display:none">
					<div class="titleRate">
						<a onclick="selectSubMenu('review_write')">구매후기 남기기</a>
						<p>상품 구매 후 후기를 남겨주시면, <span>적립금 <strong>100</strong>원</span>을 드립니다</p>
					</div>
	
					<ul class="listRate">
						<!-- 후기 리스트 항목 -->
					</ul>
					<div class="pagination">
					</div>

				</div>
				
				
				<!-- 구매 후기 작성 -->
				<div id="review_write_view" style="display:none">
					<ul class="inputQna">
						<li class="name">
							<div class="title"><span>닉네임</span></div>
							<div class="content"><input name="nickname" type="text" placeholder="별명을 입력해주세요" <?=$member?'value='.$member->nickname.' disabled':'';?> /></div>
						</li>
						<li class="subject">
							<div class="title"><span>별점</span></div>
							<div class="content">
								<select name="rating">
									<?php
										for ($i = 5; $i >= 0; $i--){
									?>
									<option value="<?=$i;?>" <?=$i==$review->rating?'selected':'';?>><? for($j=$i;$j>0;$j--){echo '★'; } ?></option>	
									<?php
										}
									?>
								</select>
							</div>
						</li>
						<li class="content">
							<div class="title"><span>내용</span></div>
							<div class="content"><textarea maxlength="100" name="content" placeholder="내용을 입력해주세요"><?=$review->content;?></textarea></div>
						</li>
	
						<li class="button">
							<a onclick="sendReview();">후기등록</a>
							<a onclick="selectSubMenu('review');" class="cancel">취소</a>
							<p>상품과 관련없는 내용은 삭제될 수 있습니다</p>
						</li>
					</ul>

				</div>
				
				
				<!-- Q&A 리스트 -->
				<div id="qna_view" style="display:none">
					<div class="titleQna">
						<a onclick="selectSubMenu('qna_write')">문의하기</a>
						<p>상품에 대해 궁금한 점이 있으세요? 최대한 빨리 답변해드립니다!</p>
					</div>
	
					<ul class="listQna">
						<!-- 질문 리스트 항목 -->
					</ul>
					
					<div class="pagination">
					</div>

				</div>
				
				<!-- Q&A 작성 -->
				<div id="qna_write_view" style="display:none">
					<ul class="inputQna">
					<li class="name">
						<div class="title"><span>닉네임</span></div>
						<div class="content"><input name="nickname" type="text" placeholder="닉네임을 입력해주세요" <?=$member?'value='.$member->nickname.' disabled':'';?> /></div>
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
						<a onclick="selectSubMenu('qna');" class="cancel">취소</a>
						<p>상품과 관련없는 내용은 삭제될 수 있습니다</p>
					</li>
				</ul>

				</div>
				
				
				
				<!-- 배송/교환/환블 -->
				<div id="info_view">
					<h4>배송료 및 배송기간</h4>
					<ul class="deliverInfo">
						<li>제품 발송에는 3~5일 정도 소요됩니다.</li>
						<li>배송비 : 2500원 (제주, 도서산간지방은 5000원)</li>
					</ul>
	
	
					<h4>교환 및 반품정보</h4>
					<ul class="deliverInfo">
						<li><strong>반품 가능한 경우</strong>
							<ul>
								<li>상품을 공급 받으신 날로부터 7일이내 가능.</li>
								<li>공급받으신 상품 내용이 표시.광고 내용과  다르거나 다르게 이행된 경우.</li>
								<li>단,  포장을 개봉하였거나, 훼손되어 상품가치가 상실된 경우에는 교환/반품이 불가능합니다.</li>
							</ul>
						</li>
						<li><strong>교환 및 반품이 불가능한 경우</strong>
							<ul style="margin-bottom: 0;">
								<li>상품을 공급 받으신 날로부터 7일 이후 불가능</li>
								<li>고객님의 책임 있는 사유로 상품이 멸실 또는 훼손된 경우.</li>
								<li>포장을 개봉하였거나 포장이 훼손되어 상품가치가 상실된 경우</li>
								<li>시간의 경과에 의하여 재판매가 곤란할 정도로 상품등의 가치가 현저히 감소한 경우.</li>
							</ul>
						</li>
					</ul>
	
	
					<h4>교환/환불을 원하시는 경우 070-8650-2086 으로 연락주세요. 감사합니다.</h4>


				</div>
			</div>

		</div>
		
		
		<script>
		
			/* 서브 탭 메뉴 컨트롤 */
			
			var curr_sub_menu_key = 'detail';
			var sub_menu = {
				detail : {
					btn_id: '#detail_view_btn',
					view_id: '#detail_view',
					fn: null
				},
				review : {
					btn_id: '#review_view_btn',
					view_id: '#review_view',
					fn: function(){
						loadReviews();
					}
				},
				review_write : {
					btn_id: '#review_view_btn',
					view_id: '#review_write_view',
					fn: function(){
						if (!$('input[name="has_purchase"]').val()){
							alert('상품 구입 후 후기를 작성해주세요.');
							selectSubMenu('review');
						}
					}
				},
				qna : {
					btn_id: '#qna_view_btn',
					view_id: '#qna_view',
					fn: function(){
						loadQuestions();
					}
				},
				qna_write : {
					btn_id: '#qna_view_btn',
					view_id: '#qna_write_view',
					fn: function(){
						if ($('input[name="is_member"]').val()=='N'){
							alert('로그인 후 작성하실 수 있습니다');
							selectSubMenu('qna');
						}
					}
				},
				info : {
					btn_id: '#info_view_btn',
					view_id: '#info_view',
					fn: null
				}
			};
			
			var selectSubMenu = function(key){
				$(sub_menu[curr_sub_menu_key].btn_id).removeClass('selected');
				$(sub_menu[curr_sub_menu_key].view_id).css('display','none');
				
				curr_sub_menu_key = key;
				
				$(sub_menu[key].btn_id).addClass('selected');
				$(sub_menu[key].view_id).css('display','block');
				
				if (sub_menu[key].fn){
					sub_menu[key].fn();
				}
				
				
			}
			
			
			
			/****** 후기 관련 함수 ******/
			
			/* 후기 목록 불러오기 */
			var loadReviews = function(){
				var products_id = $('input[name="product_id"]').val();
			
				// Ajax 요청				
				$.ajax({
		    		type: 'GET',
		    		url: '<?=site_url('actions/shop/reviewsProduct');?>',
		    		data: {products_id:products_id},
		    		success: function(text){
		    			var json = eval(text);		
		                if (json.success){
			                dispReviewList(json);
		                } else {
			                
		                }
		    		}
		    	});
			};
			
			/* 후기 전송 */
			/*
			 * 회원이고 구입 이력이 있는 경우 신규 아닌 수정 업데이트
			 */
			var sendReview = function(){
				var nickname = $('#review_write_view').find('input[name="nickname"]').val();
				var content = $('#review_write_view').find('textarea[name="content"]').val();
				var rating = $('#review_write_view').find('select[name="rating"]').val();
				var product_id = $('input[name="product_id"]').val();
				var reviews_id = '<?=$review->id;?>';
				
				// 구입 이력에 따른 uri
				var uri = 'addReview';
				if (reviews_id!='')
					uri = 'updateReview';
				
				// 유효성 검사
				var alert_title = null;
				if (!nickname || nickname.length < 1){
					alert_title = '닉네임';
					$('#review_write_view').find('input[name="nickname"]').effect('shake',{times:2},200,null);
				}
					
				else if (!content || content.length < 1){
					alert_title = '내용';
					$('#review_write_view').find('textarea[name="content"]').effect('shake',{times:2},200,null);
				}
					
					
				if (alert_title){
/* 					alert(alert_title + '을 입력해주세요'); */
					return;	
				} 
				
				// Ajax 전송				
				$.ajax({
		    		type: 'POST',
		    		url: '<?=site_url('actions/shop/');?>/'+uri,
		    		data: {products_id:product_id, nickname:nickname, rating:rating, content:content, reviews_id: reviews_id},
		    		success: function(text){
		    			var json = eval(text);
		
		                if (json.success){
			                selectSubMenu('review');
		                } 
		    		}
		    	});
			};
			
			/* 후기 목록 뿌리기 */
			var dispReviewList = function(data){
				var pagenation = data.pagination;
				data = data.reviews;
			
				function markupItem(rating, content,nickname,date){
					rating = parseInt(rating);
					var rating_img = '';
					switch(rating){
						case 0: rating_img = '<?=base_url();?>/img/star_0.png';
						break;
						case 1: rating_img = '<?=base_url();?>/img/star_1.png';
						break;
						case 2: rating_img = '<?=base_url();?>/img/star_2.png';
						break;
						case 3: rating_img = '<?=base_url();?>/img/star_3.png';
						break;
						case 4: rating_img = '<?=base_url();?>/img/star_4.png';
						break;
						case 5: rating_img = '<?=base_url();?>/img/star_5.png';
						break;
					}
					
				
					var tpl = '<li><div class="left"><strong><img src="'+rating_img+'"></strong><span>'+content+'</span></div><div class="right"><strong>'+nickname+'</strong><span>'+date+'</span></div></li>';
							
					return tpl;
				}
				
				/* 기존 항목 삭제 */
				var $list = $('#review_view > ul').empty();
				var $page = $('#review_view > .pagination').empty();
				
				/* 새 항목 삽입 */
				for (var i in data){
					$list.append(markupItem(data[i].rating, data[i].content,data[i].nickname,data[i].date_write));
				}
						
				/* 페이지네이션 */
				$page.append(pagenation);
				applyPagination($page,dispReviewList);
				
			};
			
			/* 페이지네이션 */
			var applyPagination = function(parent,dispFn) {
			      var product_id = $('input[name="product_id"]').val();
			    
			      $(parent).children('a').click(function(){
			         	var url = $(this).attr("href");
			         	$.ajax({
				         	type: "GET",
				         	data: {products_id:product_id},
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
			
			/* QnA 불러오기 */
			var loadQuestions = function(){
				var products_id = $('input[name="product_id"]').val();
			
				// Ajax 요청				
				$.ajax({
		    		type: 'GET',
		    		url: '<?=site_url('actions/shop/questionsProduct');?>',
		    		data: {products_id:products_id},
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
				
			
				function markupTitle(reply,title,nickname,date,is_private){
					
					var state = reply?'답변완료':'답변대기';
					
					var tpl = '<div class="left"><strong '+ (reply?'class="reply"':'') +'>'+state+'</strong><span><a href="#"></a>'+title;
					
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
					
					var $li = $('<li>').append(markupTitle(reply,data[i].title,data[i].nickname,data[i].date_write,data[i].is_private));
					
					if (data[i].content){
						$li.append(markupBody(data[i].content));
					} else {
						$li.click(function(){
							alert('비공개 글은 작성자만 보실 수 있습니다.');
						});
					}
					
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
						
				/* 페이지네이션 */
				$page.append(pagenation);
				applyPagination($page,dispQuestionList);
				
			};
			
			
			var clearQuestionForm = function(){
				// $('#qna_write_view').find('input[name="nickname"]').val('');
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
			
			
			
			
			/** 주문하기 **/
			var order = function(){
				var products_id = '<?=$product->id;?>';
				var qty = $('select[name="quantity"]').val();
				
				$('form[name="buy_form"]').find('input[name="qty"]').val(qty);
				
/*
				$form = $('<form name="buy_form">').attr({action:'<?=site_url('shop/order')?>',method:'GET'});
				
				$inputId = $('<input>').attr({
					name:'products_id',
					value: products_id,
					type: 'hidden'
				}).appendTo($form);
				
				$inputQty = $('<input>').attr({
					name:'qty',
					value: qty,
					type: 'hidden'
				}).appendTo($form);
				
				$inputStep = $('<input>').attr({
					name:'type',
					value: 'buy_now',
					type: 'hidden'
				}).appendTo($form);
				
				$('body').append($form);
				
				$form.submit();
*/
				
				
				$('form[name="buy_form"]').submit();

			}
			
			/** 장바구니 **/
			var addToCart = function(products_id,add){
					var data = {};
					var qty = $('select[name="quantity"]').val();
					data['products_id'] = products_id;
					data['qty'] = qty;

					if (add){
						data['addExist'] = true;
					}
					
					// Ajax 전송				
					$.ajax({
			    		type: 'GET',
			    		url: '<?=site_url('actions/shop/addToCart');?>',
			    		data: data,
			    		success: function(text){
			    		
			    			if (text == 'exist'){
				    			var c = confirm('상품이 이미 장바구니에 있습니다. 더해서 추가하시겠습니까?');
				    			if (c){
				    				addToCart(products_id, true);
				    			}
			    			} else if (text == 'success') {
				    			var c = confirm('상품을 장바구니에 넣었습니다. 장바구니를 확인하시겠습니까??');
				    			if (c){
				    				location.href = "<?=site_url('shop/cart')?>";
				    			}	
			    			} 
				    		
			    		}
			    	});
				}
			
			
			
			var zzimBtnTapped = function(){
				var is_zzim = $('#zzimBtn').attr('zzim');
				var products_id = $('input[name="product_id"]').val();
				
				if (is_zzim == 'true'){
					rmvFromWishlist(products_id);
				} else {
					addToWishlist(products_id);
				}
			};
			
			/** 찜하기 **/
			var addToWishlist = function(products_id){
			
				if($('input[name="is_member"]').val()!='Y'){
					alert('로그인 후 이용 가능합니다');
				}
			
				$zzimBtn = $('#zzimBtn');
			
				// Ajax 전송				
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/shop/addToWishList');?>',
			    		data: {
			    			products_id:products_id
			    			},
			    		success: function(text){
				    		//location.href = "<?=site_url('shop/cart')?>";
				    		var json = eval('('+text+')');
				    		if (json.success){
				    			$zzimBtn.html('<span>찜해제</span>').css({
					    			'background': '#000',
					    			'text-shadow': '0 0'
				    			}).attr('zzim','true');
				    			var c = confirm('상품을 찜하였습니다. 찜리스트로 가시겠습니까?');
				    			if (c){
				    				location.href="<?=site_url('shop/my/wishlist')?>";
				    			}
				    		} else {
				    			// 추가 실패
				    			alert(json.reason);
				    		}
			    		}
			    	});
			}
			
			/** 찜해제 **/
			var rmvFromWishlist = function(products_id){
			
				if($('input[name="is_member"]').val()!='Y'){
					alert('로그인 후 이용 가능합니다');
				}
			
				// Ajax 전송				
					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url('actions/shop/removeWishListItem');?>',
			    		data: {
			    			products_id:products_id
			    			},
			    		success: function(text){
				    		//location.href = "<?=site_url('shop/cart')?>";
				    		var json = eval('('+text+')');
				    		if (json.success){
				    			$zzimBtn.html('<span>찜하기</span>').css({
					    			'background': '#a2a3a8 url(<?=base_url();?>img/button_b.png) no-repeat',
					    			'text-shadow': '0 -1px #787878'
				    			}).attr('zzim','false');
				    			var c = alert('찜목록에서 상품을 제거하였습니다.');

				    		} else {
				    			// 제거 실패
				    			alert(json.reason);
				    		}
			    		}
			    	});
			}
			
			$zzimBtn = $('#zzimBtn');
			if ($zzimBtn.attr('zzim')=='true') 
				$zzimBtn.css({
	    			'background': '#000',
	    			'text-shadow': '0 0'
    			});
    			
    		/** 품절 알림 **/
    		var soldout = function(){
	    		alert('품절 또는 판매 종료된 상품입니다');
    		}
		</script>
	