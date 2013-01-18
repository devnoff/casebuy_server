
		

		<div class="wallpaper_list_wrapper">
			<div class="related_product">
				<h2>상품</h2>
				<ol id="related_product_list">
				<? foreach($products as $p) { ?>
					<li class="ui-state-default" item_id="<?=$p->id;?>"><img src="<?=$p->app_detail_img;?>" width="50" height="50"/> <?=$p->title?></option>
				<? } ?>
				</ol>
				<center style="padding-top:10px"><?=$pagination;?></center>
			</div>
			
			<div class="wallpapers">
				<h2>관련 배경</h2>
				<ol id="wallpaper_tile">
				<? foreach($data as $i) { ?>
					<li class="ui-state-default" item_id="<?=$i->id;?>"><img src="<?=$i->thumb_path;?>" item_id="<?=$i->id;?>"/></li>
				<? } ?>
				</ol>

			</div>
			

		</div>


		<script>
		var selected_product = null;
		// var selected_wallpaper = null;
		$(function() {
	        $("#wallpaper_tile").selectable({
	        	tolerance:'fit',
	        	selected: function(event,ui){

	        		var item_id = $(ui.selected).attr('item_id');
	        		console.log($(ui.selected).attr('item_id'));

	        		// loadRelatedProducts(item_id);
	        		addRelatedWallplaper(item_id);
	        	},
	        	unselected: function(event,ui){
	        		var unselected_wallpaper = ui.unselected;

	        		var item_id = $(ui.unselected).attr('item_id');
	        		removeRelatedWallpaper(item_id);
	        	}
	        });
	    });

	    $(function() {
	        $("#related_product_list").selectable({
	        	tolerance:'fit',
	        	selected: function(event,ui){
	        		$(selected_product).removeClass('ui-selected');
	        		selected_product = ui.selected;

	        		var item_id = $(ui.selected).attr('item_id');
	        		console.log($(ui.selected).attr('item_id'));

	        		// addRelatedProduct(item_id);
	        		loadRelatedWallpapers(item_id);


	        	}
	        });
	    });

	    $('#clear_btn').click(function(){
	    	$( "#wallpaper_tile > li" ).removeClass('ui-selected');
	    });


	    // 관련 배경 불러오기
	    var loadRelatedWallpapers = function(products_id){
	    	$( "#wallpaper_tile > li" ).removeClass('ui-selected');

	    	$.ajax({
				type: 'GET',
				url: '<?=site_url();?>/admins/product/wallpapers',
				data: {products_id:products_id},
				success: function(text){
					var json = eval(text);

		            if (json){
		                var wallpapers = json.wallpapers;
		                $.each(wallpapers, function(index, value){
		                	console.log(value);

		                	$('#wallpaper_tile li[item_id="'+value.wallpapers_id+'"]').addClass('ui-selected');
		                });

		            } 
				}
			});
	    }

	    // 관련 배경 선택
	    var addRelatedWallplaper = function(wallpapers_id){
	    	if (!selected_product) {
	    		alert('상품을 먼저 선택하세요');
	    	}
	    	var products_id = $(selected_product).attr('item_id');

	    	$.ajax({
				type: 'POST',
				url: '<?=site_url();?>/admins/product/addWallpaper',
				data: {wallpapers_id:wallpapers_id,products_id:products_id},
				success: function(text){
					var json = eval(text);

		            if (json){

		            } 
				}
			});
	    }

	    // 관련 배경 선택 해제
	    var removeRelatedWallpaper = function(wallpapers_id){
	    	if (!selected_product) {
	    		alert('상품을 먼저 선택하세요');
	    	}
	    	var products_id = $(selected_product).attr('item_id');

	    	$.ajax({
				type: 'POST',
				url: '<?=site_url();?>/admins/product/removeWallpaper',
				data: {wallpapers_id:wallpapers_id,products_id:products_id},
				success: function(text){
					var json = eval(text);

		            if (json){

		            } 
				}
			});
	    }

		</script>