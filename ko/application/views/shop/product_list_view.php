	<div class="sideWidget">
		<p style="text-align:center;color:gray">
			<span style="font-weight:bold">아이폰에서 간편하게 이용하세요!</span><br/>
		</p>
		<a href="https://itunes.apple.com/ko/app/casebuy-iphone-cases-wallpapers/id583783910?l=ko&ls=1&mt=8" target="_blank" border="0"><img width="250" src="/ko/img/casebuy_app_promote_side.png"/></a>
		<br/>
		<br/>
		<p style="text-align:center;color:gray">
			<span style="font-weight:bold">결제사 안내</span>
			<br/>
			<span>케이스바이는 모든 브라우저에서<br/> 결제가 가능합니다.</span>
			<br/><br/>
			<a href="http://www.paygate.net/" target="_blank" border="0"><img src="/ko/img/paygate_banner.png"/></a>
			<br/><br/>
			<a href="http://www.paypal.com/" target="_blank" border="0"><img src="/ko/img/paypal_banner.png"/></a>
		</p>
	</div>
	
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
