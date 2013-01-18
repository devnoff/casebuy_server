
		

		<div class="wallpaper_list_wrapper">
			<div class="wallpapers">
				<h2>배경화면</h2>
					<? 

					$cnt = count($data);
					$col = $cnt > 4 ? 4 : $cnt;
					$row = ceil($cnt/4.0);

					$index = 0;
					?>

				<ol id="wallpaper_tile">
				<? foreach($data as $i) { ?>
					<li class="ui-state-default" item_id="<?=$i->id;?>">
						<button onclick="removeWallpaper(<?=$i->id;?>);">삭제</button>
						<img src="<?=$i->thumb_path;?>" item_id="<?=$i->id;?>"/>
					</li>
				<? } ?>
				</ol>
				<div class="clear"><?=$pagination;?></div>
			</div>
			<div class="related_product">
				<h2>관련상품</h2>
				<ol id="related_product_list">
				<? foreach($products as $p) { ?>
					<li class="ui-state-default" item_id="<?=$p->id;?>"><img src="<?=$p->web_list_img;?>" /> <?=$p->title?></option>
				<? } ?>
				</select>
			</div>

		</div>


		<script>
		var selected_wallpaper = null;
		$(function() {
	        $("#wallpaper_tile").selectable({
	        	tolerance:'fit',
	        	selected: function(event,ui){
	        		$(selected_wallpaper).removeClass('ui-selected');
	        		selected_wallpaper = ui.selected;

	        		var item_id = $(ui.selected).attr('item_id');
	        		console.log($(ui.selected).attr('item_id'));

	        		loadRelatedProducts(item_id);
	        	}
	        });
	    });

	    $(function() {
	        $("#related_product_list").selectable({
	        	tolerance:'fit',
	        	selected: function(event,ui){
	        		var selected_product = ui.selected;

	        		var item_id = $(ui.selected).attr('item_id');
	        		console.log($(ui.selected).attr('item_id'));

	        		addRelatedProduct(item_id);
	        	},
	        	unselected: function(event,ui){
	        		var unselected_product = ui.unselected;

	        		var item_id = $(ui.unselected).attr('item_id');
	        		removeRelatedProduct(item_id);
	        	}
	        });
	    });

	    $('#clear_btn').click(function(){
	    	$( "#related_product_list > li" ).removeClass('ui-selected');
	    });


	    // 관련 상품 불러오기
	    var loadRelatedProducts = function(wallpapers_id){
	    	$( "#related_product_list > li" ).removeClass('ui-selected');

	    	$.ajax({
				type: 'GET',
				url: '<?=site_url();?>/admins/wallpaper/products',
				data: {wallpapers_id:wallpapers_id},
				success: function(text){
					var json = eval(text);

		            if (json){
		                var products = json.related_products;
		                $.each(products, function(index, value){
		                	console.log(value);

		                	$('#related_product_list li[item_id="'+value.products_id+'"]').addClass('ui-selected');
		                });

		            } 
				}
			});
	    };


	    // 관련 상품 선택
	    var addRelatedProduct = function(products_id){
	    	if (!selected_wallpaper) {
	    		alert('배경화면을 먼저 선택하세요');
	    	}
	    	var wallpapers_id = $(selected_wallpaper).attr('item_id');

	    	$.ajax({
				type: 'POST',
				url: '<?=site_url();?>/admins/wallpaper/addProduct',
				data: {wallpapers_id:wallpapers_id,products_id:products_id},
				success: function(text){
					var json = eval(text);

		            if (json){

		            } 
				}
			});
	    };

	    // 관련 상품 선택 해제
	    var removeRelatedProduct = function(products_id){
	    	if (!selected_wallpaper) {
	    		alert('배경화면을 먼저 선택하세요');
	    	}
	    	var wallpapers_id = $(selected_wallpaper).attr('item_id');

	    	$.ajax({
				type: 'POST',
				url: '<?=site_url();?>/admins/wallpaper/removeProduct',
				data: {wallpapers_id:wallpapers_id,products_id:products_id},
				success: function(text){
					var json = eval(text);

		            if (json){

		            } 
				}
			});


	    };


	    // 배경화면 삭제
	    var removeWallpaper = function(wallpapers_id){

	    	var c = confirm('정말 삭제하시겠습니까?');

	    	if (!c){
	    		return;
	    	}

	    	$.ajax({
				type: 'POST',
				url: '<?=site_url();?>/admins/wallpaper/removeWallpaper',
				data: {wallpapers_id:wallpapers_id},
				success: function(json){
		            if (json){
		            	location.reload();
		            } 
				}
			});
	    }

		</script>