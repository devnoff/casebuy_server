		

		<script src="<?=base_url();?>js/vendor/jquery.ui.widget.js"></script>
        <script src="<?=base_url();?>js/jquery.iframe-transport.js"></script>
        <script src="<?=base_url();?>js/jquery.fileupload.js"></script>

		<div class="wallpaper_add_wrapper">
			<h2>배경화면 추가</h2>
			<p>
				파일을 선택하면 바로 업로드가 됩니다. 변경시에는 '삭제'버튼을 눌러 업로드된 사진을 먼저 지웁니다.<br/> 
				취소 시에는 '취소'버튼을 눌러야 이미 업로드된 사진을 지울 수 있습니다<br/>
				모든 항목에 사진을 선택하신 후 '추가'버튼을 눌러야 배경화면 추가가 완료됩니다.
			</p>
			<form>
			<ul>
				<li>
					<h4>썸네일</h4>
						<img src="" alt="이미지"  field="thumb_path" />
						<input type="file" name="wallpaper" field="thumb_path" data-url="<?=site_url('admins/wallpaper/uploadImage/');?>"/> 
						<button type="button" class="delete_btn" field="thumb_path" >삭제</button>
					
				</li>
				<li><h4>iPhone 4 배경</h4><img src="" alt="이미지" field="original_4_path" /><input type="file" field="original_4_path" name="wallpaper" data-url="<?=site_url('admins/wallpaper/uploadImage/');?>"/> <button type="button" class="delete_btn" field="original_4_path" >삭제</button> </li>
				<li><h4>iPhone 5 배경</h4><img src="" alt="이미지" field="original_5_path" /><input type="file" field="original_5_path" name="wallpaper" data-url="<?=site_url('admins/wallpaper/uploadImage/');?>"/> <button type="button" class="delete_btn" field="original_5_path" >삭제</button> </li>
				<!-- <li><h4>iPhone 4 프리뷰</h4><img src="" field="preview_4_path" alt="이미지" /><input type="file" field="preview_4_path" name="wallpaper" data-url="<?=site_url('admins/wallpaper/uploadImage/');?>"/> <button type="button" class="delete_btn" field="preview_4_path" >삭제</button> </li>
				<li><h4>iPhone 5 프리뷰</h4><img src="" field="preview_5_path" alt="이미지" /><input type="file" field="preview_5_path" name="wallpaper" data-url="<?=site_url('admins/wallpaper/uploadImage/');?>"/> <button type="button" class="delete_btn" field="preview_5_path" >삭제</button> </li> -->
			</ul>
			<div><button type="button" id="submit_btn">추가</button> <button type="button" id="cancel_btn">취소</button></div>
			</form>
		</div>


		<script>

		$(function () {
		    $('input[type="file"]').fileupload({
		        dataType: 'json',
		        done: function (e, data) {
		        	var field = $(e.target).attr('field');
		        	var $img = $('img[field="'+field+'"]');
		            console.log(data.result);
		            if (data.result.success){
		                var file_path = data.result.file_path;
		                $img.attr('src',file_path);
		            }   
		        }
		    });
		});



		var addWallpapers = function(){

			var thumb_path = $('img[field="thumb_path"]').attr('src');
			var original_4_path = $('img[field="original_4_path"]').attr('src');
			var original_5_path = $('img[field="original_5_path"]').attr('src');
			var preview_4_path = $('img[field="preview_4_path"]').attr('src');
			var preview_5_path = $('img[field="preview_5_path"]').attr('src');

			var data = {
				thumb_path:thumb_path,
				original_4_path:original_4_path,
				original_5_path:original_5_path
				// preview_4_path:preview_4_path,
				// preview_5_path:preview_5_path
			};

			for (var i in data){
				if (data[i] == '' || data[i] == undefined || data[i] == 'blank'){
					alert('모든 항목을 채우세요');
					return;
				}
			}


			console.log(data);
			
			$.ajax({
	    		type: 'POST',
	    		url: '<?=site_url();?>/admins/wallpaper/addWallpapers',
	    		data: data,
	    		success: function(text){
	    			var json = eval(text);

	                if (json.success){
	                    alert('성공');
	                    location.href='';
	                } 
	    		}
	    	});
		}

		$('#submit_btn').click(function(){
			addWallpapers();
		});


		var deleteWallpaperItem = function(path,e){

			$.ajax({
	    		type: 'POST',
	    		url: '<?=site_url();?>/admins/wallpaper/deleteImage',
	    		data: {filepath:path},
	    		success: function(text){
	    			var json = eval(text);
	                if (json.success){
	                    $(e).attr('src','blank');
	                } 
	    		}
	    	});

		}

		$('.delete_btn').click(function(){
			var field = $(this).attr('field');
			var $img = $('img[field="'+field+'"]');
			var path = $img.attr('src');

			deleteWallpaperItem(path,$img);
		});


		$('#cancel_btn').click(function(){
			var c = confirm('정말 취소 하시겠습니까?');
			if (c){

				$('img').each(function(){
					var data = $(this).attr('src');
					if (data == '' || data == undefined || data == 'blank'){
						
					} else {
						deleteWallpaperItem(data,$(this));
					}
				});

				window.history.go(-1);
			}
		});

		</script>