<div>
	<div style="float:left">
	상태
<?php
    foreach ($states as $c=>$v){
        $sdata[$c] = $v;
    }
     echo form_dropdown('states',$sdata,$state);
?>
	</div>
	<div style="float:left;padding-left:10px">
	카테고리
<?php
if ($categories){
    foreach ($categories as $c){
        $cdata[$c->id] = $c->category_name;
    }
     echo form_dropdown('categories',$cdata,$categories_id);
}

if ($sub_categories){
    foreach ($sub_categories as $c){
        $scdata[$c->id] = $c->category_name;
    }
     echo form_dropdown('sub_categories',$scdata,$sub_category_id);
}

?>
	</div>
	<div style="float:left;padding-left:10px">
		<form method="GET" action="">
			<input type="text" name="keyword" placeholder="상품명,상품코드"/> 
			<input type="submit" value="검색"/>
		</form>
	</div>
    <div style='float:right'>
        <button onclick="deleteEndProducts();">판매 종료 상품 일괄 삭제</button>
    </div>
	<div class="clear"></div>
</div>
<table class="product_list_table">
    <thead>
        <tr>
            <td>번호</td>
            <td width="70">카테고리</td>
            <td>상품</td>
            <td>매입가</td>
            <td>정가</td>
            <td>할인율</td>
            <td>고정 할인액</td>
            <td>판매가</td>
            <td width="80">옵션</td>
            <td>상태</td>
            <td>액션</td>
        </tr>
    </thead>
    <tbody>
<?php

    // var_dump($product_data);
    
    foreach ($product_data as $data){


?>
        <tr>
            <td class="row_num_cell"><?=$data->id;?></td>
            <td class="category_cell"><?=$data->category_name;?> <br/> <?=$data->tags;?></td>
            <td class="product_name_cell">
                <div>
                    <img src="<?=$data->web_list_img;?>"/>
                </div>
                <div>
                    <span class="product_code"><?=$data->product_code;?></span><br/>
                    <span class="title"><a target="_blank" href="<?=site_url('shop/product?id='.$data->id)?>"><?=$data->title;?></a></span><br/>
                    <span class="extra_info">
                    <?
                        $extras = array();
                        if ($data->extra_info_title1)
                            $extras[] = $data->extra_info_title1.' '.$data->extra_info_value1;
                        if ($data->extra_info_title2)
                            $extras[] = $data->extra_info_title2.' '.$data->extra_info_value2;
                        if ($data->extra_info_title3)
                            $extras[] = $data->extra_info_title3.' '.$data->extra_info_value3;
                        
                        echo join(', ',$extras);
                    ?>
                    </span><br/>
                    <span>
                    <?
                        $extras = array();
                        if ($data->pop=='Y')
                            $extras[] = "인기";
                        if ($data->hit=="Y")
                            $extras[] = "히트";
                        if ($data->new=="Y")
                            $extras[] = "신상";
                        if ($data->dc_sale=="Y")
                            $extras[] = "할인";
                        if ($data->recomm=="Y")
                            $extras[] = "추천";
                        echo join(', ',$extras);
                    ?>
                    </span>
                </div>
            </td>
            <td class="purchase_price_cell"><?=$data->purchase_price;?></td>
            <td class="regular_price_cell"><?=$data->regular_price;?></td>
            <td class="dc_rate_cell"><?=$data->dc_rate;?>%</td>
            <td class="fixed_dc_amount_cell"><?=$data->fixed_dc_amount;?></td>
            <td class="sales_price_cell"><?=$data->sales_price; // $data->regular_price - ($data->dc_rate * 0.01 * $data->regular_price);?></td>
            <td class="option_cell">
                <a href="javascript: showOptionWrite(<?=$data->id;?>,this)">추가</a> <a href="javascript: deleteOption(<?=$data->id;?>)">삭제</a><br/>
                <? if ($data->options){ ?>
                <select name="options" products_id="<?=$data->id;?>">
                    <? foreach($data->options as $option){ ?>
                    <option value="<?=$option->id;?>"><?=$option->option_name;?></option>
                    <? } ?>
                </select>
                <? } ?>
            </td>
            <td class="state_cell">
                <select class="sales_state_cell" id="state_select_<?=$data->id;?>" product_id="<?=$data->id;?>">
                    <option value="WAIT" <? if($data->sales_state=='WAIT') echo 'selected' ?> >판매대기</option>
                    <option value="SALE" <? if($data->sales_state=='SALE') echo 'selected' ?> >판매중</option>
                    <option value="TEMP_OUT" <? if($data->sales_state=='TEMP_OUT') echo 'selected' ?> >일시품절</option>
                    <option value="OUT" <? if($data->sales_state=='OUT') echo 'selected' ?> >품절</option>
                    <option value="END" <? if($data->sales_state=='END') echo 'selected' ?> >판매종료</option>
                </select>
            </td>
            <td class="action_cell">
                <button type="button" onclick="location.href='<?=site_url("admin/product/edit/".$data->id);?>/<?=$return_to?>';">수정</button><br/>
                <button onclick="raiseOrder(<?=$data->id;?>);">▲</button><button onclick="reduceOrder(<?=$data->id;?>);">▼</button><br/>
                <input products_id="<?=$data->id;?>" class="product_flag_checkbox" type="checkbox" name="pop" <? if($data->pop=='Y') echo 'checked';?>/><label>P</label> 
                <input products_id="<?=$data->id;?>" class="product_flag_checkbox" type="checkbox" name="new" <? if($data->new=='Y') echo 'checked';?>/><label>N</label> 
                <input products_id="<?=$data->id;?>" class="product_flag_checkbox" type="checkbox" name="hit" <? if($data->hit=='Y') echo 'checked';?>/><label>H</label>
            </td>
        </tr>
<?php
    }
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="18"><?php echo $pagination; ?></td>
        </tr>
        <tr>
            <td colspan="18"><?php echo $total; ?> results</td>
        </tr>
    </tfoot>
</table>



<div id="option_write" style="display:none;">
    <label>상품번호</label><input name="products_id" type="text"/><br/>
    <label>옵션명</label><input name="option_name" type="text"/><br/>
    <button type="button" onclick="addOption();">입력</button>
</div>

<script>

/*
* 판매상태
*
*/

var updateProductState = function(id, state){
    $.ajax({
       		type: 'POST',
       		url: '<?=site_url();?>/admins/product/updateProduct/true',
       		data: {id:id, sales_state:state},
       		success: function(text){
       			var json = eval("("+text+")");

                if (json.success){
                    alert('성공');
                    location.href = '';

                } else {
                    alert('실패');
                }
       		}
       	});
}

$('.sales_state_cell').change(function(){
    var id = $(this).attr('product_id');
    var value = $(this).val();
    
    updateProductState(id,value);
});


/*
*
* 카테고리
*
*/

$('select[name="categories"]').change(function(){
    var c_id = $(this).val();
    
    var sc_id = $('select[name="sub_categories"]').val();
    var state = $('select[name="states"]').val();

    var uri = '';
	
	if (sc_id && sc_id != '')
		uri += sc_id + '/';
		
    
    
    var url = '<?=$category_base_url;?>/'+c_id+'/'+uri+'?condition='+state;
    location.href = url;
    
});

$('select[name="sub_categories"]').change(function(){
    var c_id = $('select[name="categories"]').val();
    var sc_id = $(this).val();
    
    var state = $('select[name="states"]').val();
    
    var url = '<?=$category_base_url;?>/'+c_id+'/'+sc_id+'?condition='+state;
    location.href = url;
    
});

$('select[name="states"]').change(function(){
    var c_id = $('select[name="categories"]').val();
    var sc_id = $('select[name="sub_categories"]').val();
    var state = $(this).val();

    var uri = '';
	if (c_id && c_id != '')
		uri += c_id + '/';
	
	if (sc_id && sc_id != '')
		uri += sc_id + '/';
	
	

    var url = '<?=$category_base_url;?>/'+uri+'?condition='+state;
    location.href = url;
    
});


// 판매 종료 상품 일괄 삭제
var deleteEndProducts = function(){

    var c = confirm('정말로 삭제하시겠습니까?');
    if (c){
        $.ajax({
            type: 'POST',
            url: '<?=site_url();?>/admins/product/deleteEndProducts',
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
}

// 상품 정렬 위로

var raiseOrder = function(products_id){
    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/reduceOrder',
        data: {products_id:products_id},
        success: function(json){

            if (json.success){
                location.reload(true);

            } else {
                
            }
        }
    });
}

// 상품 정렬 아래로
var reduceOrder = function(products_id){
    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/raiseOrder',
        data: {products_id:products_id},
        success: function(json){

            if (json.success){
                location.reload(true);

            } else {

            }
        }
    });
}


// 옵션 추가
var addOption = function(){

    var products_id = $('#option_write > input[name="products_id"]').val();
    var option_name = $('#option_write > input[name="option_name"]').val();

    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/addProductOption',
        data: {products_id:products_id,option_name:option_name},
        success: function(json){

            if (json.success){
                location.reload(true);

            } else {

            }
        }
    });
}

// 옵션 삭제
var deleteOption = function(products_id){

    var $selected_option = $('select[name="options"][products_id="'+products_id+'"] > option:selected');
    var selected_option_id = $selected_option.val();

    if ($selected_option.val() == undefined){
        alert('옵션이 없습니다.');
        return;
    }

    var c = confirm('선택한 옵션을 지우시겠습니까? '+$selected_option.html());
    if (!c) return;

    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/removeProductOption',
        data: {id:selected_option_id},
        success: function(json){

            if (json.success){
                location.reload(true);

            } else {

            }
        }
    });
}



// 옵션 입력창
var showOptionWrite = function(products_id,el){

    $('#option_write > input[name="products_id"]').val(products_id);

    $('#option_write').dialog({
        minWidth:250,
        height:250,
        modal: false,
        resizable: false,
        position: { my: "left top", at: "center top", of: $(el) }
    });    
}


/* 상품 상태 변경 (인기, 신상, 베스트) */
var updateProductFlag = function(products_id, flag_name, state){
    var data = {};
    data['id'] = products_id;
    data[flag_name] = state;

    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/updateProduct/ajax',
        data: data,
        success: function(json){
            json = eval(json);
            if (json.success){

            } else {
                location.reload(true);
            }
        }
    });
}

$('input[type="checkbox"][class="product_flag_checkbox"]').change(function(){
    var flag_name = $(this).attr('name');
    var checked = $(this).is(':checked')?'Y':'N';
    var products_id = $(this).attr('products_id');


    updateProductFlag(products_id, flag_name, checked);

});

</script>