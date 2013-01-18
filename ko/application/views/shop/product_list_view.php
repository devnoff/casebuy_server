	<ul class="list">
	<?php
		
	
		foreach ($products as $p){
	
		$item_url = site_url('shop/product?id='.$p->id.'&c_id='.$p->categories_id.'&sc_id='.$p->sub_category_id);
	?>
			<li>
				<div class="photo">
					<a href="<?=$item_url;?>">
						<img src="<?=$p->web_list_img;?>" alt="<?=$p->title;?> - <?=$p->sub_title;?>">
					</a>
				</div>
				<div class="text">
					<p class="comment"><?=$p->sub_title;?></p>
					<p class="subject"><a href="<?=$item_url;?>"><?=$p->title;?></a></p>
					<p class="price">
						<strong><?=$p->sales_price;?>원</strong> 
						<?php if ($p->extra_info_value1) { ?>
						<!-- span>(<?=$p->extra_info_value1;?>)</span -->
						<?php }?>
					</p>
				</div>
			</li>
	<?php
		}
	?>
		</ul>
		
		

		
		<?php if (!$products || count($products)<1){ ?>
		<div style="display:block;text-align:center;padding:100px">		
		검색하신 상품명의 상품이 없습니다!
			</div>	
		<?php } ?>
