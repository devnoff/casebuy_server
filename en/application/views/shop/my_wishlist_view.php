			<ul class="subMenu">
				<li><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
				<li class="selected"><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
				<li><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
				<li><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
				<li><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
			</ul>

		<ul class="list" style="margin:0;">
		
			<?php
				foreach($wishlist as $i){
					$item_url = site_url('shop/product?id='.$i->products_id.'&c_id='.$i->categories_id.'&sc_id='.$i->sub_category_id);
				
			?>
			<li>
				<div class="photo"><a href="<?=$item_url;?>"><img src="<?=$i->web_list_img;?>"></a></div>
				<div class="text">
					<p class="comment"><?=$i->sub_title;?></p>
					<p class="subject"><a href="view.html"><?=$i->title;?></a></p>
					<p class="price">
						<strong><?=$i->sales_price;?>원</strong> 
						<?php if ($i->extra_info_value1) { ?>
						<span>(<?=$i->extra_info_value1;?>)</span>
						<?php }?>
					</p>
				</div>
			</li>
			
			<?php
				}
			?>
		</ul>

