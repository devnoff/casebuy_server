


<!-- 스마트에디터 로드 -->
<script type="text/javascript" src="<?=base_url();?>smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>

<div class="regi_product_wrapper">

    <form id="product_register_form" method="POST"enctype="multipart/form-data" action="<?=site_url()?>/admins/product/addProduct" accept-charset="UTF-8">

        <!-- 상품 기본 정보 -->
        <div class="basic_info">
            <h1><?=$page_title;?></h1>
            <ul>
                <li>
                    <label for="">품명</label>
                    <input name="title" type="text" />
                </li>
                <li>
                    <label for="">한줄설명</label>
                    <input name="sub_title" type="text" />
                </li>
                <li>
                    <label for="">1차 카테고리</label>
                    <select name="categories_id" id="category_select">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                </li>
                <li>
                    <label for="">2차 카테고리</label>
                    <select name="sub_category_id" id="sub_category_select">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                </li>
                <li>
                    <label for="">태그(콤마로 구분)</label>
                    <input name="tags" type="text" />
                </li>
                <li>
                    <label for="">공급업체(업체배송비)</label>
                    <select name="partners_id" id="partners_select">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                </li>
                <li>
                    <label for="">제품코드</label>
                    <input name="product_code" type="text" />
                </li>
                <li>
                    <label for="">매입가($)</label>
                    <input class="money_input price_input" name="purchase_price" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)"/>
                </li>
                <li>
                    <label for="">정가($)</label>
                    <input class="money_input price_input" name="regular_price" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)" />
                </li>
                <li>
                    <label for="">할인율(%)</label>
                    <input class="price_input" name="dc_rate" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)" optional="true"/>
                </li>
                <li>
                    <label for="">할인가격($)</label>
                    <input class="money_input price_input" name="fixed_dc_amount" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)" optional="true" />
                </li>
                <li>
                    <label for="">판매가격(자동계산)</label>
                    <input class="money_input price_input" name="sales_price" type="text"  style="text-align:right;" disabled/>
                </li>
                <li>
                    <label for="">적립포인트 비율(%)</label>
                    <input class="price_input" name="point_rate" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)" optional="true" />
                </li>
                <li>
                    <label for="">적립포인트(포인트)</label>
                    <input class="price_input" name="fixed_point" type="text" style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)" optional="true"/>
                </li>
                <li>
                    <label for="">적립포인트(자동계산)</label>
                    <input class="price_input" name="point_amount" type="text"  style="text-align:right;" disabled/>
                </li>
                <li>
                    <label for="">배송비($)</label>
                    <input class="money_input price_input" name="delivery_fee" value="" type="text"style="text-align:right;ime-mode:disabled;" onKeyPress="return numbersonly(event, true)"/>
                </li>
                <li>
                    <label for="">출시일</label>
                    <input name="date_release" type="text" />
                </li>
            </ul>
        </div>
    
        <!-- 상품 수익 정보 -->
    
        <div class="profit_info">
            <h2>수익 정보</h2>
            <ul>
                <li>
                    <label for="">수익($):<br/><span>판매가 - 매입가</span></label><input type="text" name="profit" class="money_input" disabled />
                </li>
                <li>
                    <label for="">배송비 고려 수익($):<br/><span>수익 - (업체배송비 - 배송비)</span></label><input type="text" name="profit_after_delivery" class="money_input" disabled />
                </li>
                <li>
                    <label for="">적립금 고려한 수익($):<br/><span>배송비 고려 수익 - 적립포인트</span></label><input type="text" name="profit_final" class="money_input" disabled />
                </li>
            </ul>
        </div>
    
        <!-- 상품 상태 정보 -->
    
        <div class="state_info">
            <h2>상태 정보</h2>
            <ul>
                <li>
                    <label for="">판매 상태</label>
                    <select name="sales_state">
                        <option value="WAIT">판매대기</option>
                        <option value="SALE">판매중</option>
                        <option value="TEMP_OUT">일시품절</option>
                        <option value="OUT">품절</option>
                        <option value="END">판매종료</option>
                    </select>
                </li>
                <li class="product_state">
                    <input type="checkbox" name="pop" value="Y"  for=""/>
                    <label for="pop">인기</label>
                    <input type="checkbox" name="hit" value="Y"  for=""/>
                    <label for="hit">히트</label>
                    <input type="checkbox" name="new" value="Y"  for=""/>
                    <label for="new">신상</label>
                    <input type="checkbox" name="dc_sale" value="Y"  for=""/>
                    <label for="dc_sale">세일</label>
                    <input type="checkbox" name="recomm" value="Y"  for=""/>
                    <label for="recomm">추천</label>
                </li>
            </ul>
        </div>
    
        <!-- 상품 추가 정보 -->
    
        <div class="extra_info">
            <h2>추가 정보</h2>
            <ul>
                <li>
                    <label for="">추가1</label>
                    <input name="extra_info_value1" type="text" placeholder="ex) 300 단위x" optional="true"/>
                    <input name="extra_info_title1" type="text" placeholder="제목: ex) 용량" optional="true"  value="무게" disabled/>
                </li>
                <li>
                    <label for="">추가2</label>
                    <input name="extra_info_value2" type="text" placeholder="값: ex) 300ml" optional="true"/>
                    <input name="extra_info_title2" type="text" placeholder="제목: ex) 용량" optional="true"/>
                </li>
                <li>
                    <label for="">추가3</label>
                    <input name="extra_info_value3" type="text" placeholder="값: ex) 300ml" optional="true"/>
                    <input name="extra_info_title3" type="text" placeholder="제목: ex) 용량" optional="true"/>
                </li>
            </ul>
        </div>
    
    



        <!-- 생산자 정보 -->
    
        <div class="producer_info">
            <h2>생산자 정보</h2>
            <ul>
                <li>
                    <label for="">제조사</label>
                    <select name="manufacturers_id">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                    <input type="button" name="manufacturers" value="추가"/>
                </li>
                <li>
                    <label for="">브랜드</label>
                    <select name="brands_id">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                    <input type="button" name="brands" value="추가"/>
                </li>
                <li>
                    <label for="">원산지</label>
                    <select name="origins_id">
                        <option value="a">a</option>
                        <option value="b">b</option>
                        <option value="c">c</option>
                    </select>
                    <input type="button" name="origins" value="추가"/>
                </li>
            </ul>
        </div>
    

    
        <div class="descriptions" style="display:none"> <!-- 숨김 -->
            <h2>상품 설명</h2>
            <textarea name="smarteditor_textarea" id="smarteditor_textarea" rows="10" cols="100"></textarea><br/>
        

            <div class="file_manager">
                <select size="10" name="file_explorer">
                </select>
                <img src="http://media-mcw.cursecdn.com/ko//thumb/1/1d/No_image.svg/50px-No_image.svg.png" />
                <div>
                    <strong>File Manager</strong><br/><br/>
                    가로 사이즈: <span class="img_width">0</span>px<br/>
                    세로 사이즈: <span class="img_height">0</span>px<br/><br/>
                    <button type="button" disabled>본문 삽입</button>
                </div>
            </div>
        </div>
        
    
    
        <!-- 상품 이미지 -->
        <div class="product_images">
            <h2>상품 이미지</h2>
            <div class="left">
                <h4>웹 리스트용</h4>
                <div class="left"><img src="" class="web_list_img"/></div>
                <div class="left"><input type="file" name="web_list_img" optional="true"/></div>
                <div class="clear"></div>
            </div>
            <div class="left">
                <h4>웹 상세보기 용</h4>
                <div class="left"><img src="" class="web_detail_img" /></div>
                <div class="left"><input type="file" name="web_detail_img" optional="true"/></div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="left">
                <h4>앱 리스트용</h4>
                <div class="left"><img src="" class="app_list_img" /></div>
                <div class="left"><input type="file" name="app_list_img" optional="true"/></div>
                <div class="clear"></div>
            </div>
            <div class="left">
                <h4>앱 갤러리 용</h4>
                <div class="left"><img src="" class="app_detail_img" /></div>
                <div class="left"><input type="file" name="app_detail_img" optional="true"/></div>
                <div class="clear"></div>
            </div>
            <div class="left" style="display:none">
                <h4>앱 메인 용</h4>
                <div class="left"><img src="" class="app_main_img" /></div>
                <div class="left"><input type="file" name="app_main_img" optional="true"/></div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

        <!-- 상품 추가 이미지 -->
        <!--
            product_images 아래에 상품 번호별로 폴더를 생성
        -->
        <div class="product_images_extra" style="display:none">

            <script src="<?=base_url();?>js/vendor/jquery.ui.widget.js"></script>
            <script src="<?=base_url();?>js/jquery.iframe-transport.js"></script>
            <script src="<?=base_url();?>js/jquery.fileupload.js"></script>
            <h2>상품 추가 이미지 </h2>
            <!-- 상품 추가 이미지 매니저 -->
            <!-- 1. db.product_images 테이블에서 상품번호로 데이터를 불러와서 출력 -->
            <div class="product_images_manager">
                <select size="10" name="product_images[]" multiple>
                <select>
                <img src="http://media-mcw.cursecdn.com/ko//thumb/1/1d/No_image.svg/50px-No_image.svg.png" width="150" height="150"/>
                <div>
                    <strong>Product Images Manager</strong><br/><br/>
                    가로 사이즈: <span class="img_width">0</span>px<br/>
                    세로 사이즈: <span class="img_height">0</span>px<br/><br/>
                    <input id="fileupload" type="file" name="product_photo" data-url="<?=site_url('admins/product/uploadProductImages/');?>" optional="true"/><br/>
                    <button type="button" onclick="photoMoveTop();">맨위로</button> <button onclick="deleteExtraPhoto();" type="button">삭제</button>
                </div>
            </div>
        </div>


        <!-- 상품 설명 웹용 -->
    
        <div class="app_description">
            <h2>상품 설명 앱용 ('/' 구분)</h2>
            <textarea name="app_description" style="width:300px"></textarea>
        </div>
    
        <!-- 전송 버튼 -->
        <div class="buttons">
            <input type="button" value="미리 보기">
            <input type="submit" value="저장 하기">
            <input type="button" value="상품 목록" onclick="location.href='<?=site_url();?>/admin/product/all';">
        </div>
    
    </form>
</div>








<!-- Extras -->
<div style="display:none">
    <form id="producer_add_form" method="POST" style="display:none">
        <label>타이틀</label><input name="title" type="text" /><br/>
        <input name="submit" type="submit" value="추가" disabled/>
    </form>
</div>


<div style="display:none"><div class="log" ></div></div>







<!-- 스크립트 -->

<script>


/*
*
* ajax 로딩상태
*
*/

var ajaxCount = 0;
var ajaxfinish = 0;
$('.log').ajaxSend(function() {
    ajaxCount++;
  console.log('Triggered ajaxSend handler.');
});

$('.log').ajaxComplete(function(){
    console.log('Triggered ajaxComplete handler.');
    
    ajaxfinish++;
    
    if (ajaxCount == ajaxfinish){
        loadingFinishCallback();
    }
});

//
var validators = new Array();


/*
* 카테고리 로드
*
*/

var category_data = null;

// 카테고리 옵션 그리기
var dispCategoryOption = function(select, data, selected_id){
    
    var $category = $(select);
    $category.empty();
    
    $('<option>').attr('value','').html('없음').appendTo($category);
    for (var i in data){
        var $option = $('<option>').attr('value',data[i].id).html(data[i].category_name);
        if (data[i].id==selected_id) $option.attr('selected', true);
        $option.appendTo($category);
    }
};


// 2차 카테고리 옵션 불러오기 함수
var loadSubCategoryData = function(parent_id, selected_id){
    
    $.ajax({
    		type: 'GET',
    		url: '<?=site_url();?>/admins/product/categoryLv2',
    		data: {parent_id:parent_id},
    		success: function(text){
    			var json = eval(text);

                if (json){
                    dispCategoryOption('#sub_category_select', json,selected_id);

                } 
    		}
    	});
};

// 1차 카테고리 옵션 불러오기 함수
var loadCategoryData = function (){
    $.ajax({
    		type: 'GET',
    		url: '<?=site_url();?>/admins/product/categoryLv1',
    		success: function(text){
    			var json = eval(text);

                if (json){
                    dispCategoryOption('#category_select',json,0);
                    
                    loadSubCategoryData(json[0].id, 0);
                } 

    		}
    	});
    
};


// 1차 카테고리 이벤트 트리거
$('#category_select').change(function(){
    
    var parent_id = $(this).val();
    
    loadSubCategoryData(parent_id, 0);
});


// 1차 카테고리 옵션 불러오기
loadCategoryData();




/*
* 발주업체 로드
*
*/

// 파트너 옵션 그리리
var dispPartnersOption = function(data){
    var $partners = $('#partners_select');
    $partners.empty();
    
    var $option = $('<option value="0">');
        $option.appendTo($partners);

    for (var i in data){
        var title = data[i].title;
        var delivery_fee = data[i].delivery_fee;
        var $option = $('<option>').attr('value',data[i].id).html(title + ' : ' + delivery_fee + '원');
        $option.attr('delivery_fee',delivery_fee);
        $option.appendTo($partners);
    }
};

// 파트너 옵션 불러오기 함수
var loadPartersData = function(){
    
    $.ajax({
    		type: 'GET',
    		url: '<?=site_url();?>/admins/product/partners',
    		success: function(text){
    			var json = eval(text);

                if (json){
                    dispPartnersOption(json);

                } 
    		}
    	});
};

// 파트너 옵션 불러오기 
loadPartersData();


/*
*
* 가격 입력 및 계산
*
*/

var calculatePrice = function(){
    // 결과 인풋
    var $sales_price_input = $('input[name="sales_price"]');
    var $point_amount = $('input[name="point_amount"]');
    
    var sales_price = 0;
    var point_amount = 0;
    
    // 매입가
    var purchase_price = $('input[name="purchase_price"]').val();
    purchase_price = purchase_price.split(',').join('');
    
    // 정가
    var regular_price = $('input[name="regular_price"]').val();
    regular_price = regular_price.split(',').join('');
    
    // 할인율
    var dc_rate = $('input[name="dc_rate"]').val();
    $('input[name="dc_rate"]').val(dc_rate * 1);
    
    // 고정 할인액
    var fixed_dc_amount = $('input[name="fixed_dc_amount"]').val();
    fixed_dc_amount = fixed_dc_amount.split(',').join('');
    
    // 포인트 적립률
    var point_rate = $('input[name="point_rate"]').val();
    $('input[name="point_rate"]').val(point_rate * 1);
    
    // 고정 적립 포인트
    var fixed_point = $('input[name="fixed_point"]').val();
    $('input[name="fixed_point"]').val(fixed_point * 1);
    
    // 배송비
    var delivery_fee = $('input[name="delivery_fee"]').val();
    delivery_fee = delivery_fee.split(',').join('');
    
    // 업체배송비
    var partner_delivery_fee = $('select[name="partners_id"] option:selected').attr('delivery_fee');
    if (partner_delivery_fee == undefined)
        partner_delivery_fee = 0;
    
    /*
    * 고정할인액이 있을 경우 할인율을 무시함
    * 고정 적립 포인트가 있을 경우 포인트 적립률을 무시함
    */
    
    if (fixed_dc_amount.length > 0 && fixed_dc_amount > 0){
        sales_price = regular_price - fixed_dc_amount;
    } else {
        sales_price = regular_price - (regular_price * (dc_rate/100.0))
    }
    sales_price = parseFloat(sales_price);
    $sales_price_input.val(sales_price); // addCommas
    
    
    if (fixed_point.length > 0 && fixed_point > 0){
        point_amount = fixed_point;
    } else {
        point_amount = sales_price * (point_rate/100.0);
    }
    point_amount = parseFloat(point_amount * 1);
    $point_amount.val(point_amount);
    
    
    
    
    var profit = sales_price - purchase_price;
    var profit_after_delivery = parseFloat(sales_price) - parseFloat(purchase_price) + parseFloat(delivery_fee) - parseFloat(partner_delivery_fee);
    var profit_final = profit_after_delivery - point_amount;
    profit_after_delivery = parseFloat(profit_after_delivery);
    profit_final = parseFloat(profit_final);
    
    console.log(sales_price + '-' + purchase_price + '+' + delivery_fee + '-' + partner_delivery_fee + ' = ' + profit_after_delivery);
    
    $('input[name="profit"]').val(profit.toFixed(2)); //addCommas(profit)
    
    if (profit_after_delivery)
        $('input[name="profit_after_delivery"]').val(profit_after_delivery.toFixed(2)); // addCommas
        
    if (profit_final)
        $('input[name="profit_final"]').val(profit_final.toFixed(2));// addCommas
        
    $('.money_input').each(function(){
        var v = $(this).val();
        // v = v.split(',').join('');
        // $(this).val(addCommas(v));
        // $(this).val(parseFloat(v).toFixed(2));
    });
    
}

// $('.money_input').keyup(function(){
//     var str = $(this).val();
//     str = str.split(',').join('');
//     $(this).val(str); // addCommas
// });

$('.price_input').keyup(function(){
    calculatePrice();
});

$('#partners_select').change(function(){
    calculatePrice();
});


/*
*
* 출시일 DatePicker
*
*/
$(function() {
	$("input[name='date_release']").datepicker({ dateFormat: "yy-mm-dd" });
});





/*
*
* 생산자 정보
*
*/

var p_target = {
    'manufacturers':'select[name="manufacturers_id"]',
    'brands':'select[name="brands_id"]',
    'origins':'select[name="origins_id"]'
}

var dispOptions = function(select, data){
     var $m = $(select);
     $m.empty();
     
     $('<option>').val('').html('-').appendTo($m);
     for (var i in data){
         $('<option>').val(data[i].id).html(data[i].title).appendTo($m);
     }
}


var loadProducerOptions = function(target){
    $.ajax({
    		type: 'GET',
    		url: '<?=site_url();?>/admins/product/producerOptions',
    		success: function(text){
    			var json = eval(text);
                
                if (target){
                    dispOptions(p_target[target], json[target]);
                    $(p_target[target]).find('option:last').attr('selected',true);
                } else {
                    for (var t in p_target){
                        dispOptions(p_target[t], json[t]);
                    }
                } 
    		}
    	});
};

var showAddOption = function(type){
    var title = '';
    switch(type){
        case 'manufacturers':
            title = '제조사 추가';
        break;
        case 'brands':
            title = '브랜드 추가';
        break;
        case 'origins':
            title = '원산지 추가';
        break;
    }
    
    $('#producer_add_form > input[name="title"]').val(null);
    $('#producer_add_form').attr('action','<?=site_url();?>/admins/product/addProducer/'+type);
    $('#producer_add_form').dialog({
        title: title
    })
}

$('.producer_info').find('input[type="button"]').each(function(){
    $(this).click(function(){
        showAddOption($(this).attr('name'));
    });
});

$('#producer_add_form > input[name="title"]').keyup(function(){
    var text = $(this).val();
    if (text.length > 0){
        $('#producer_add_form > input[type="submit"]').attr('disabled', false);
    } else {
        $('#producer_add_form > input[type="submit"]').attr('disabled', true);
    }
});


$('#producer_add_form').submit(function(e){
   e.preventDefault();
   var url = $(this).attr('action');
   var title = $('#producer_add_form > input[name="title"]').val();
   
   $.ajax({
   		type: 'POST',
   		url: url,
   		data: {title:title},
   		success: function(text){
   			var json = eval('('+text+')');
               
            if (json.success){
                alert('추가 완료');
                loadProducerOptions(json.type);
                $("#producer_add_form").dialog('close');
                
            } else {
                alert('추가 실패');
            }
   		}
   	});
   
});


loadProducerOptions();


/*
*
* 상품설명
*
*/

 function submit(elClickedObj){
        // 에디터의 내용이 textarea에 적용된다.
       oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);

       // 에디터에 입력된 내용의 검증은 이곳에서
       var value = document.getElementById("smarteditor_textarea").value;

       alert(value);
        
    }

/* 스마트 에디터 로드 */
var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: "smarteditor_textarea",
    sSkinURI: "<?=base_url();?>smarteditor/SmartEditor2Skin.html",
    fCreator: "createSEditor2"
});

// 에디터 콜백 세팅 

// 에디터 이미지 삽입 함수
function pasteIMG(src,width,height) {
	var sHTML = "<img src='"+src+"' style='max-width:800px'/>"; //width='"+width+"' height='"+height+"' 
	oEditors.getById["smarteditor_textarea"].exec("PASTE_HTML", [sHTML]);
}



/* 파일 메니저 */
var file_manager_data = null;

// 파일 선택시 : 썸네일 출력, 본문 삽입 버튼 활성화
$('select[name="file_explorer"]').change(function(){
    var idx = $(this).val();
    
    // 섬네일 출력
    var imgPath = $(this).find('option:selected').attr('imgPath');
    $('.file_manager > img').attr('src',imgPath);
    
    // 사이즈 정보 변경
    var img_width = file_manager_data[idx].width;
    var img_height = file_manager_data[idx].height;
    
    console.log(img_width + ' ' + img_height);
    
    $('.file_manager').find('.img_width').html(img_width);
    $('.file_manager').find('.img_height').html(img_height);
    
    // 본문삽입 버튼 활성화
    $('.file_manager > div > button').attr('disabled',false);
});

// 본문 삽입 버튼 클릭시
$('.file_manager > div > button').click(function(){
    var $op = $('select[name="file_explorer"] option:selected');
    var idx = $op.val();
    var file = file_manager_data[idx];
    var imgPath = $op.attr('imgPath');
    var img_width = file_manager_data[idx].width;
    var img_height = file_manager_data[idx].height;
    
    pasteIMG(imgPath,img_width,img_height);
});


// 파일 목록 출력
var dispFileList = function(data){
    $fe = $('select[name^="product_images"]');
    $fe.empty();
    
    for (var i in data){
        var file = data[i];
        $('<option>').html(file.filename).attr('index',i).attr('imgPath','<?=base_url();?>'+file.filepath).val(i).appendTo($fe);
    }
    
}

var appendImageFile = function(fileInfo){
    $fe = $('select[name^="product_images"]');
    
    var file = fileInfo;
    $('<option>').html(file.file_name).attr({
            'imgPath':'<?=base_url();?>'+file.file_path,
            'width':file.width,
            'height':file.height,
            'selected':true
        }).val('{"file_name":"' + file.file_name+'","file_path":"'+file.file_path+'"}').appendTo($fe);

}




/*
*
* 상품 이미지
*
*/

// 썸네일 프리뷰
function changeimage(str,cls){
    
    if(typeof str === "object") {
        str = str.target.result; // file reader
    }
    
    $('.'+cls).css(
        {
            "background-size": "100px 100px", 
            "background-image": "url(" + str + ")"
        });
    
}


// 파일 선택 시 썸네일
$('.product_images').find('input').each(function(){
    $(this).change(function(){
        
        var filepath = $(this).val();
        var pathComp = filepath.split('.');
        var ext = pathComp[pathComp.length-1];
        
        var className = $(this).attr('name');
        console.log(filepath);
        
        if (ext == 'jpeg' || ext == 'jpg' || ext == 'png' || ext == 'gif'){
            
            var fileObj = this,
                file;

            if (fileObj.files) {
                file = fileObj.files[0];
                var fr = new FileReader;
                fr.onloadend = function(str){

                    if(typeof str === "object") {
                        str = str.target.result; // file reader
                    }

                    $('.'+ className).attr('src',str); //css({"background-size": "100px 100px", "background-image": "url(" + str + ")"});
                };
                fr.readAsDataURL(file)
            } else {
                file = fileObj.value;
                changeimage(file, $(this).attr('name'));
            }
            
        } else {
            alert('이미지만 선택이 가능합니다');
            $(this).val(null);
        }
        
    });
});


// 추가 이미지 썸네일

$('.product_images_manager').find('input').each(function(){
    $(this).change(function(){
        
        var filepath = $(this).val();
        var pathComp = filepath.split('.');
        var ext = pathComp[pathComp.length-1];
        
        var className = $(this).attr('name');
        console.log(filepath);
        
        if (ext == 'jpeg' || ext == 'jpg' || ext == 'png' || ext == 'gif'){
            
            var fileObj = this,
                file;

            if (fileObj.files) {
                file = fileObj.files[0];
                var fr = new FileReader;
                fr.onloadend = function(str){

                    if(typeof str === "object") {
                        str = str.target.result; // file reader
                    }

                    $('.product_images_manager > img').attr('src',str);
                };
                fr.readAsDataURL(file)
            } else {
                file = fileObj.value;
                changeimage(file, $(this).attr('name'));
            }
            
        } else {
            alert('이미지만 선택이 가능합니다');
            $(this).val(null);
        }
        
    });
});

// 파일 선택시 : 썸네일 출력, 본문 삽입 버튼 활성화
$('select[name^="product_images"]').change(function(){

    $option = $(this).find('option:selected');
    // 섬네일 출력
    var imgPath = $(this).find('option:selected').attr('imgPath');
    $('.product_images_manager > img').attr('src',imgPath);
    
    // 사이즈 정보 변경
    var img_width = $option.attr('width');
    var img_height = $option.attr('height');
    
    console.log(img_width + ' ' + img_height);
    
    $('.product_images_manager').find('.img_width').html(img_width);
    $('.product_images_manager').find('.img_height').html(img_height);
    
    
});


/*
 * 선택 사진 삭제
 */
var deleteExtra = function(path,e){
    $.ajax({
        type: 'POST',
        url: '<?=site_url();?>/admins/product/deleteExtraImage',
        data: {filepath:path},
        success: function(text){
            var json = eval(text);
            if (json.success){
                $(e).remove();
            } 
        }
    });
}

var deleteExtraPhoto = function(){
    $parent = $('select[name^="product_images"]');
    $option = $parent.find('option:selected');

    $option.each(function(i){
        var path = $(this).attr('imgpath');
        deleteExtra(path,$(this));
    });
}


/*
 * 사진 순서 변경
 */

var photoMoveTop = function(){
    $parent = $('select[name^="product_images"]');
    $option = $parent.find('option:selected');
    $current = $parent.find('option').first();

    $option.each(function(i){
        $current.before($(this));
    });
}



/*
*
* 전송
*
*/

var validate = function(){
    
    var result = true;
/*
    $('#product_register_form').find('input:enabled').each(function(){
        
        if ($(this).attr('optional')) return true;
        
        var value = $(this).val();
        if (value == null || value == undefined || value == ''){
            alert('다 채우세요' + $(this).attr('name'));
            result = false;
            return false;
        }
    });
    
    $('.product_images').find('img').each(function(){
        var src = $(this).attr('src');
        if ( src == null || src == 'undefined' || src == ''){
            result = false;
            return false;
        }
    });
*/
    
    return result;
}

var priceFormat = function(){
    $('.money_input').each(function(){
        var v = $(this).val();
        v = v.split(',').join('');
        $(this).val(v);
    });
}

$('form').submit(function(e){
    
    // 잠금해제
    $('input:disabled').attr('disabled',false);


    // 상품이미지 잠금
    var $exImages = $('select[name^="product_images"]').attr('multiple',true);

    // Select all
    $($exImages).find('option').each(function(){
        $(this).attr('selected',true);
    });
    
    // 가격 포멧 재설정
    priceFormat();
    
    // 유효성 검사
    if (!validate()){
        e.preventDefault();
        return false;
    }
    
    // 에티더 업데이트
    oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);
        
    console.log('hi');
    // alert($(this).serialize());
    return true;  



    
});


/*
*
* 옵션 데이터 로드 완료 콜백
*
*/

var loadingFinishCallback = function(){
    $('.log').parent().empty();

};

</script>
