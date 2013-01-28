<?php echo $content; ?>



<script>

$('.product_images_extra').show();

var product_json = eval(<?=$product; ?>);

var loadingFinishCallback = function(){
    $('.log').parent().empty();
    $('.log').remove();

    //
    loadEditingData();
    loadSubCategoryData($('select[name="categories_id"]').val(),product_json.sub_category_id);
    calculatePrice();

};

$('form').attr('action','<?=site_url()?>/admins/product/updateProduct');



var loadEditingData = function(){
    $('input[name="id"]').remove();
    $('input[name="redirect"]').remove();
    $('<input>').attr('name','id').attr('type','hidden').val(product_json.id).appendTo($('#product_register_form'));
    $('<input>').attr('name','redirect').attr('type','hidden').val('<?=$redirect;?>').appendTo($('#product_register_form'));
    for (var i in product_json){
        var key = i;
        $field = $('input[name="'+key+'"]');
        if ($field.attr('type') == 'text'){
            $field.val(product_json[key]);
        } else if ($field.attr('type') == 'checkbox'){
            if (product_json[key] == 'Y')
                $field.attr('checked',true);
        }

        $field = $('select[name="'+key+'"]');
        if ($field.is('select')){
            $field.find('option').each(function(){
                if ($(this).val()==product_json[key])
                    $(this).attr('selected',true);
            });
        }
    }
    
    // $('#smarteditor_textarea').val(product_json['description']);

    if (product_json['description']) setTimeout("loadSmartContent('"+product_json['description']+"')",500);
    $('img[class="web_list_img"]').attr('src',product_json['web_list_img']);
    $('img[class="web_detail_img"]').attr('src',product_json['web_detail_img']);
    $('img[class="app_list_img"]').attr('src',product_json['app_list_img']);
    $('img[class="app_detail_img"]').attr('src',product_json['app_detail_img']);
    $('img[class="app_main_img"]').attr('src',product_json['app_main_img']);
}


var loadSmartContent = function(desc){
    // $('input[name="description"]').val(desc);
    // var html = '<img src="'+desc+'"/>';
    // oEditors.getById["smarteditor_textarea"].exec("PASTE_HTML", [html]);
    $('.file_manager > img').attr('src',desc);

}


$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result);
            if (data.result.success){
                var file_info = data.result.file_info;    
                appendImageFile(file_info);
            }   
        }
    });
});


// 파일 목록 출력
var files = eval(product_json.product_images);
console.log(files);
for (var i in files){
    appendImageFile(eval(files[i]));
}




</script>

