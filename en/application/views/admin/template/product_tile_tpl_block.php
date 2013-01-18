<div class="product_tile">
<?php

foreach ($product_data as $data){
    

?>
    <div class="item_wrapper">
        <div class="img_container" style="background-image:url('<?=$data->web_list_img;?>')" onclick="window.open('<?=site_url('admin/product/edit/'.$data->id.'/all');?>')">
            <p><?=$data->title?></p>
        </div>
        <ul>
            <li>
                <div>원가</div>
                <div>$<?=$data->purchase_price;?></div>
            </li>
            <li>
                <div>판매가</div>
                <div>$<?=$data->sales_price;?></div>
            </li>
            <li>
                <div>기본마진</div>
                <div>$<?=$data->profit;?></div>
            </li>
            <li>
                <div>배송마진</div>
                <div>$<?=$data->profit_after_delivery;?></div>
            </li>
            <li>
                <div>최종마진</div>
                <div>$<?=$data->profit_final;?></div>
            </li>
            <li>
                <div>전체주문량</div>
                <div><?=$data->order_cnt;?>개</div>
            </li>
            <li>
                <p>
                    <input type="checkbox" product_id="<?=$data->id;?>" id="web_main_<?=$data->id;?>" name="web_main" <?=$data->web_main=='Y'?'checked':''?> />
                    <label for="web_main_<?=$data->id;?>">웹</label>
                    <input type="checkbox" product_id="<?=$data->id;?>" id="app_main_<?=$data->id;?>" name="app_main" <?=$data->app_main=='Y'?'checked':''?> />
                    <label for="app_main_<?=$data->id;?>">앱</label>
                </p>
            </li>
        </ul>
    </div>
<?php
    }
?>

    <div class="clear"><?php echo $pagination; ?></div>
</div>
<script>

var updateProductDisp = function(id, dest, state){
    
    var data = {};
    data['id'] = id;
    data[dest] = state;
    
    console.log(data);
    
    $.ajax({
       		type: 'POST',
       		url: '<?=site_url();?>/admins/product/updateProduct/true',
       		data: data,
       		success: function(text){
       			var json = eval("("+text+")");

                if (json.success){
                    // alert('성공');
                    location.href = '';

                } else {
                    alert('실패');
                }
       		}
       	});
}


$('input[type="checkbox"]').button().change(function(){
    var p_id = $(this).attr('product_id');
    var state = $(this).attr('checked')?'Y':'N';
    var dest = $(this).attr('name');
    
    updateProductDisp(p_id,dest,state);
    
});

</script>