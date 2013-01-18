				<div>
					<p>제목 좌측 체크박스는 활성화 버튼입니다. 체크 된 항목은 앱실행시 보여지게 되며, 그렇지 않은 경우는 아무것도 뜨지 않습니다.</p>
					<ul class="listQna listNotice" style="border-top:1px solid #e0e0e0; margin-top: 10px; margin-right: 10px;">
					<?php
					
						foreach($data as $i){
						
					?>
						<li>
							<div class="left">
								<span><input type="checkbox" name="need_notify" notice_id="<?=$i->id?>" <?=$i->need_notify=='Y'?'checked':'';?>/><a href="#"><?=$i->title?></a> <button type="button" onclick="removeNotice(<?=$i->id?>);">삭제</button></span>
							</div>
							<div class="right"><strong><?=$i->nickname?></strong><span><?=$i->date_write?></span></div>
							<div class="content" style="display:none"><?=$i->content?></div>
						</li>
					<?php
					
						}
					?>
					</ul>
				</div>
								
				
				<div class="article" style="margin-top: 20px">
				        <p>제목: <input type="text" name="title"/></p>
				        <textarea name="content" placeholder="내용" style="width: 300px; height: 200px"></textarea><br/>
				        <button type="button" onclick="postNotice();">작성</button>
				    </div>
				<script>
				
				var $li = $('li');
		
				$li.find('a').click(function(){
					var $l = $(this).parent().parent().parent();
					var disp = $l.children('.content').css('display');
					if (disp == 'none'){
						$l.children('.content').css('display','block');
					} else {
						$l.children('.content').css('display','none');
					}
					
				});


				$('input[name="need_notify"]').change(function(){
					var notice_id = $(this).attr('notice_id');
					var checked = $(this).is(':checked');

					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url();?>/admins/action/updateNotice',
			    		data: {need_notify:checked?'Y':'N',notice_id:notice_id},
			    		success: function(json){
			                if (json.success){
			                    alert('성공');

			                   	location.reload(true);

			                } else {
			                	alert('실패');
			                }
			    		}
			    	});
				});


				var postNotice = function(){

					var title = $('input[name="title"]').val();
					var content = $('textarea[name="content"]').val();

					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url();?>/admins/action/addNotice',
			    		data: {title:title,content:content},
			    		success: function(json){
			                if (json.success){
			                    alert('성공');

			                   	location.reload(true);

			                } else {
			                	alert('실패');
			                }
			    		}
			    	});
				}

				var removeNotice = function(id){

					var c = confirm('정말 삭제하시겠습니까?');
					if (!c){
						return;
					}

					$.ajax({
			    		type: 'POST',
			    		url: '<?=site_url();?>/admins/action/removeNotice',
			    		data: {id:id},
			    		success: function(json){
			                if (json.success){
			                    location.reload(true);
			                } else {
			                	alert('실패');
			                }
			    		}
			    	});
				}
						
				</script>