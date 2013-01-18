<?php
	foreach($categories as $c){	
?>
		<div class="menuContainer<?=$curr_category==$c->id?' menuContainerSelected':'';?>">
			<h3 class="condom">
			<span class="icon"><img src="<?=$c->img_path;?>"></span>
			<span class="name">
				<a href="<?=site_url('shop/product_list');?>?c_id=<?=$c->id;?>">
				<?=$c->category_name;?>
				</a>
			</span>
			</h3>
			<ul>
				<?php
	    			$sub = $sub_categories[$c->family];
	    			foreach($sub as $s){
		    			
		    			if ($s->hidden != 'YES'){
	    			
		    	?>
				<li class="<?=$curr_sub_category==$s->id?'selected':'';?>">
					<a href="<?=site_url('shop/product_list');?>?c_id=<?=$c->id;?>&sc_id=<?=$s->id?>"><?=$s->category_name;?></a>
				</li>
				<?php
						}
					}
				?>
			</ul>
		</div>
<?php
	}
?>

