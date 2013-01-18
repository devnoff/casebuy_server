
<!-- 파일 업로드 라이브러리 -->
<script src="<?=base_url();?>js/vendor/jquery.ui.widget.js"></script>
<script src="<?=base_url();?>js/jquery.iframe-transport.js"></script>
<script src="<?=base_url();?>js/jquery.fileupload.js"></script>

<div class="category left">
    <h1>category</h1>
    <div id="result_msg"></div>
    <div>
        <form id="categoryLv1Form" method="POST">
            <h2>1차 카테고리 추가</h2>
            <label>카테고리명</label><input name="category_name" type="text" />
            <input name="submit" type="submit" value="추가" disabled/>
        </form>
    </div>
    <div id="all_cateogry_list">
        
    </div>
    <div>
        <form id="categoryLv2Form" method="POST" style="display:none">
        	<input name="parent_id" type="hidden"/>
        	<input name="family" type="hidden"/>
            <label>부모카테고리</label><input name="parent_name" type="text" disabled /><br/>
            <label>카테고리명</label><input name="category_name" type="text" /><br/>
            <input name="submit" type="submit" value="추가" disabled/>
            <input name="cancel" type="button" value="취소"/>
        </form>
    </div>
    <div class="categories">
    		<div>
    		<ul class="sortable_category">
    		<?php
	    		foreach($categories as $c){	
	    	?>
    			<li class="ui-state-default">
	    			<div>
	    				<img src="<?=$c->img_path;?>" style="background-color: #ccc"/><br/>
		    			<input item_id="<?=$c->id;?>" family="<?=$c->family;?>" type="text" value="<?=$c->category_name;?>" name="category_name"/>
		    			<button item_id="<?=$c->id;?>" type="button" name="modify">M</button>
		    			<button item_id="<?=$c->id;?>" type="button" name="add_sub">A</button>
		    			<button item_id="<?=$c->id;?>" type="button" name="delete">D</button>
		    			<input item_id="<?=$c->id;?>" type="checkbox" name="hidden" <?=$c->hidden=='YES'?'checked':'';?> />
		    			<p class="handle"> </p>
		    			<ul class="sortable_sub_category">
		    			<?php
			    			$sub = $sub_categories[$c->family];
			    			foreach($sub as $s){
				    	?>
				    		<li>
				    			<div>
					    			<input parent_id="<?=$c->id;?>" item_id="<?=$s->id;?>" categories_id="<?=$s->id;?>" type="text" value="<?=$s->category_name;?>"/>
					    			<button item_id="<?=$s->id;?>" type="button" name="modify">M</button>
					    			<button item_id="<?=$s->id;?>" type="button" name="delete">D</button>
					    			<input item_id="<?=$s->id;?>" type="checkbox" name="hidden" <?=$s->hidden=='YES'?'checked':'';?> />
					    			<p class="handle"> </p>
                                    <span>
                                        <img item_id="<?=$s->id;?>" src="<?=base_url().$s->thumb;?>" alt="<?=$s->thumb;?>" width="40" height="40">
                                        <input item_id="<?=$s->id;?>" class="fileupload" type="file" name="thumb" data-url="<?=site_url('admins/product/uploadCategoryImage/');?>" />
                                        <button type="button" item_id="<?=$s->id;?>" class="delete_photo" >썸네일삭제</button>
                                    </span>
				    			</div>
                                <span class="sort_index" item_id="<?=$s->id;?>" type="child"></span>
				    		</li>
				    	<?php
					    	}
					    ?>
		    			</ul>
	    			</div>
                    <span class="sort_index" item_id="<?=$c->id;?>" type="parent"></span>
    			</li>
   	    	<?php
   	 			}
   	 		?>
    		</ul>
    		</div>	
	</div>
</div>
<div class="right" style="margin: 20px;">
    <img src="" width="150" height="150" id="photozoom" alt="확대 이미지" />
</div>


<script>


$(function() {
		//
		$( ".sortable_category" ).sortable({
            update: function(event, ui){
                refreshSortables();
            }
        });
		$( ".sortable_category" ).disableSelection();
		$( ".sortable_sub_category" ).sortable({
            update: function(event, ui){
                refreshSortables();
            }
        });
		$( ".sortable_sub_category" ).disableSelection();
		
		
		// 하위 카테고리 추가
		$('button[name="add_sub"]').click(function(){
			var item_id = $(this).attr('item_id');
			var family = $(this).parent().find('input').attr('family');
			var title = $(this).parent().find('input').val();
			
			$('#categoryLv2Form > input[name="parent_name"]').val(title);
			$('#categoryLv2Form > input[name="parent_id"]').val(item_id);
			$('#categoryLv2Form > input[name="family"]').val(family);

			$('#categoryLv2Form').dialog({
    			                title: '2차 카테고리 추가'
    			            });			
		});
		
		// 수정
		$('button[name="modify"]').click(function(){
			var item_id = $(this).attr('item_id');
			var title = $(this).parent().find('input').val();
			updateCategory(item_id,title);
		});
		
		// 삭제
		
		// 숨김
		$('input[name="hidden"]').click(function(){
				var id = $(this).attr('item_id');
				var checked = $(this).is(':checked');
				hideCategory(checked, id);
			});
		
	});


var selectedCategory = null;
var category_data = null;



function deleteCategory(id) {
	$.ajax({
    		type: 'POST',
    		url: '<?=site_url();?>/admins/product/deleteCategory',
    		data: {id:id},
    		success: function(text){
    			var json = eval(text);

                if (json.success){
                    $('#result_msg').html('카테고리 수정 완료');
                    
                    // 전체 카테고리 새로고침
                    loadAllCategory();
                } else {
                    alert('카테고리 수정 실패');
                }

    		}
    	});
}

function updateCategory(id,category_name,thumb){
    
    // alert(id + ' ' + category_name + ' ' + orderby);

    var data = {};
    data.id = id;

    if (category_name)
        data.category_name = category_name;
    
    if (thumb)
        data.thumb = thumb;

    $.ajax({
    		type: 'POST',
    		url: '<?=site_url();?>/admins/product/updateCategory',
    		data: data,
    		success: function(text){
    			var json = eval(text);

                if (json.success){
                    $('#result_msg').html('카테고리 수정 완료');
                    // alert('카테고리 수정 성공');
                    
                    // 전체 카테고리 새로고침
/*                     loadAllCategory(); */
                } else {
                    alert('카테고리 수정 실패');
                }

    		}
    	});
}

function hideCategory(hide, id){
	var h = 'NO';
	if (hide) h = 'YES';
	$.ajax({
    		type: 'POST',
    		url: '<?=site_url();?>/admins/product/updateCategory',
    		data: {id:id, hidden:h},
    		success: function(text){
    			var json = eval(text);

                if (json.success){
                    $('#result_msg').html('카테고리 수정 완료');
                    // alert('카테고리 수정 성공');
                    
                    // 전체 카테고리 새로고침
/*                     loadAllCategory(); */
                } else {
                    alert('카테고리 수정 실패');
                }

    		}
    	});
}


function loadAllCategory(){
	location.href="";
  
}


	
function addCategory(category_name){
    
    $.ajax({
    		type: 'POST',
    		url: '<?=site_url();?>/admins/product/addCategory',
    		data: {category_name:category_name},
    		success: function(text){
    			var json = eval(text);

                if (json.success){
                    // alert('카테고리 추가 성공');
                    $('#result_msg').html('카테고리 추가 완료');
                    
                    // 전체 카테고리 새로고침
                    loadAllCategory();
                } else {
                    alert('카테고리 추가 실패');
                }

    		}
    	});
}

function addSubCategory(parent_id, category_name, family){
    $.ajax({
    		type: 'POST',
    		url: '<?=site_url();?>/admins/product/addSubCategory',
    		data: {
    		    parent_id: parent_id, 
    		    category_name:category_name, 
    		    family: family
    		    },
    		success: function(text){
    			var json = eval(text);

                if (json.success){
                    // alert('서브 카테고리 추가 성공');
                    $('#result_msg').html('서브 카테고리 추가 완료');
                    
                    // 전체 카테고리 새로고침
                    loadAllCategory();
                } else {
                    alert('서브 카테고리 추가 실패');
                }

    		}
    	});
}

// 1차 카테고리
$('#categoryLv1Form').submit(function(e){
    e.preventDefault();
    
    var category_name = $('#categoryLv1Form > input[name="category_name"]').val();
    $('#categoryLv1Form > input[name="category_name"]').val('');
    $('#categoryLv1Form > :submit').attr('disabled', true);
    
    addCategory(category_name);
});

$('#categoryLv1Form > input[name="category_name"]').keyup(function(e){
    
    if ($(this).val().length > 0){
        $('#categoryLv1Form > :submit').attr('disabled', false);
    } else {
        $('#categoryLv1Form > :submit').attr('disabled', true);
    }
    
});

// 2차 카테고리
$('#categoryLv2Form').submit(function(e){
    e.preventDefault();
    
    var category_name = $('#categoryLv2Form > input[name="category_name"]').val();
    var parent_id = $('#categoryLv2Form > input[name="parent_id"]').val();
    var family = $('#categoryLv2Form > input[name="family"]').val();
    $('#categoryLv2Form > input[name="category_name"]').val('');
    $('#categoryLv2Form > input[name="parent_name"]').val('');
    $('#categoryLv2Form > input[name="parent_id"]').val('');
    $('#categoryLv2Form > input[name="family"]').val('');
    $('#categoryLv2Form > :submit').attr('disabled', true);
    $('#categoryLv2Form').dialog("close");
    
    addSubCategory(parent_id, category_name,family);
});

$('#categoryLv2Form > input[name="category_name"]').keyup(function(e){
    
    if ($(this).val().length > 0){
        $('#categoryLv2Form > :submit').attr('disabled', false);
    } else {
        $('#categoryLv2Form > :submit').attr('disabled', true);
    }
    
});


/** 썸네일 업로드 **/

$(function () {
    $('.fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            console.log(e);
            console.log(data);

            var $input = $(e.target);
            var item_id = $input.attr('item_id');

            if (data.result.success){
                var file_path = data.result.filepath;    
                updateCategory(item_id,null,file_path);

                $('img[item_id="'+item_id+'"]').attr('src','<?=base_url();?>'+file_path);
            }   
        }
    });
});


/** 이미지 확대 **/

$('img').mouseenter(function(){
    var src = $(this).attr('src');
    $('#photozoom').attr('src', src);
});



/** 이미지 삭제 **/
$('.delete_photo').click(function(){
    var item_id = $(this).attr('item_id');
    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/deleteCategoryPhoto',
        data: {id:item_id},
        success: function(text){
            var json = eval(text);

            if (json.success){
                $('img[item_id="'+item_id+'"]').attr('src',null);
            } 
        }
    });
});


var sortableIndex = function(){
    /** 순서 **/
    $('.sortable_category > li').each(function(){
        $(this).find('.sort_index').html($('.sortable_category > li').index(this));
    });

    $('.sortable_sub_category > li').each(function(){
        $(this).find('.sort_index').html($('.sortable_sub_category > li').index(this));
    });
}

var refreshSortables = function(){
    
    sortableIndex();

    /** 시리얼 라이즈 **/
    var parent_ids = [];
    var parent_orders = [];
    $('[type="parent"]').each(function(){
        parent_ids.push($(this).attr('item_id'));
        parent_orders.push($(this).html());
    });
    console.log(parent_ids + parent_orders);

    var child_ids = [];
    var child_orders = [];
    $('[type="child"]').each(function(){
        child_ids.push($(this).attr('item_id'));
        child_orders.push($(this).html());
    });
    console.log(child_ids + child_orders);


    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/updateCategoryOrder',
        data: {
            parent_ids:parent_ids, 
            parent_orders:parent_orders,
            child_ids:child_ids,
            child_orders:child_orders 
        },
        success: function(text){
            var json = eval(text);
            if (json.success){
                
            } 
        }
    });

}


sortableIndex();

    	
</script>
