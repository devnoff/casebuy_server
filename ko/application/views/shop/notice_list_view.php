				<h4>공지사항</h4>

				<ul class="listQna listNotice" style="border-top:1px solid #e0e0e0; margin-top: 10px;">
				<?php
				
					foreach($data as $i){
					
				?>
					<li>
						<div class="left"><span><a href="#"><?=$i->title?></a></span></div>
						<div class="right"><strong><?=$i->nickname?></strong><span><?=$i->date_write?></span></div>
						<div class="content" style="display:none"><?=$i->content?></div>
					</li>
				<?php
				
					}
				?>
				</ul>
				
				
				
				<div class="pagination">
				</div>
				
				<script>
				
				var $li = $('li');
		
				$li.click(function(){
					var disp = $(this).children('.content').css('display');
					if (disp == 'none'){
						$(this).children('.content').css('display','block');
					} else {
						$(this).children('.content').css('display','none');
					}
					
				});
						
				</script>