
<?php 

?>
<!-- 월선택 Jquery UI Plugin -->
<script src='<?=base_url();?>js/jquery.ui.monthpicker.js'></script>

<div class="order_list">
    <div class="navigation_container">
    	<div>
    		<input id="date_picker" name="date_picker" type="text" placeholder="날짜선택"/>
    		<button for="date_picker" type="button">조회</button>
    	</div>
    	<div>
    		<input id="month_picker" name="month_picker" type="text" placeholder="월선택"/>
    		<button for="month_picker" type="button">조회</button>
    	</div>
    	<div>
    		<label for="search_keyword">키워드</label>
    		<input type="txt" name="search_keyword" id="search_keyword" placeholder="상품코드/주문자/수령인" />
    		<button for="search_keyword" type="button">검색</button>
    	</div>
    	<div>
    	    <span>Transaction:<span><span id="transaction_result">None</span>
    	</div>
    </div>
    <table class="order_list_table">
        <thead>
            <tr>
                <td>주문일시</td>
                <td>주문번호</td>
                <td>주문자</td>
                <td>수령인</td>
                <td class="product_name_cell">상품명</td>
                <td>상품금액</td>
                <td>배송비</td>
                <td>사용포인트</td>
                <td>결제금액</td>
                <td>결제수단</td>
                <td>주문상태</td>
                <td>포인트<br/>환급</td>
                <td>운송장 번호</td>
                <td class="action_cell">액션</td>
            </tr>
        </thead>
        <tbody>
        
<?php
    foreach ($order_data as $data){
        
?>
            <tr>
                <td><?=$data->date_order;?></td>
                <td><?=$data->id;?><br/>(<?=$data->order_code;?>)</td>
                <td><?=$data->c_name;?></br>(<?=$data->username;?>)</td>
                <td><?=$data->d_name;?></td>
                <td><?=$data->order_title;?></td>
                <td><?=$data->totalPrice;?></td>
                <td><?=$data->delivery_fee;?></td>
                <td><?=$data->used_point;?></td>
                <td><?=$data->payable_amount;?></td>
                <td><?=$PAYMENT_TYPE[$data->payment_method];?></td>
                <td>
                    <select name="order_state" orders_id="<?=$data->id;?>">
                    <?php 
                    	foreach($order_states as $state){
                    		
                    ?>
                        <option value="<?=$state->key;?>" <?=$data->order_state==$state->key?"selected":"";?>><?=$state->admin_text;?></option>
                    <?php 
                    	}
                    ?>
                    </select>
                </td>
                <td>
                <?php
                	/* 취소 완료 및 환불 완료 시 포인트 환급을 할 수 있음 */
	                $cancel_state = array('CANCEL_DONE','REFUND_DONE'); 
	                if(in_array($data->order_state, $cancel_state) && $data->has_point > 0){
		                
                ?>
	                <input type="checkbox" name="point_refund_checkbox" members_id="<?=$data->members_id;?>" used_point="<?=$data->used_point;?>" orders_id="<?=$data->id;?>" <?=$data->has_refunded?'checked disabled':'';?> />
	            <?php
	            
	            	}
	            ?>
                </td>
                <td>
                    <input orders_id="<?=$data->id;?>" type="text" value="<?=$data->invoice_no;?>" /><button orders_id="<?=$data->id;?>" name="invoice_btn" for="<?=$data->id;?>" type="button">입력</button>
                </td>
                <td><button order_code="<?=$data->order_code;?>" type="button" name="detail_button" class="detail_button">상세보기</button></td>
            </tr>
            
<?php

}

?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="13"><?=$pagination;?></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>

/*
 * 트랜젝션 상태
 *
 */
 
var transaction = function(state){
    $('#transaction_result').attr('class',null);
    
    switch(state){
        case 'none':
            $('#transaction_result').html('None').addClass('text_color_normal');
        break;
        case 'working':
            $('#transaction_result').html('Working').addClass('text_color_normal');
        break;
        case 'ok':
            $('#transaction_result').html('OK').addClass('text_color_confirmed');
        break;
        case 'failed':
            $('#transaction_result').html('Failed').addClass('text_color_cancelled');
        break;
    }
}


/*
 * 조건별 조회 조회
 * 날짜, 월, 검색어
 */

// Date Picker
$('#date_picker').datepicker({ 
	dateFormat: "yy-mm-dd"
});
$('button[for="date_picker"]').click(function(){
	
	var date = $('#'+$(this).attr('for')).val();
    // var regExp = /^[12][0-9]{3}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    // if (!regExp.test(date)){
    //  alert('날짜 형식이 잘 못 되었습니다');
    //  return;
    // }	
    // alert('clicked date ' + date);
    location.href="<?=current_url();?>/?date="+date;
});

// Month Picker
$('#month_picker').monthpicker();
$('button[for="month_picker"]').click(function(){
	
	var month = $('#'+$(this).attr('for')).val();
    location.href="<?=current_url();?>/?month="+month;
});

// Search Keyword
$('button[for="search_keyword"]').click(function(){
	
	var keyword = $('#'+$(this).attr('for')).val();
    location.href="<?=current_url();?>/?keyword="+keyword;
});



/*
 * 주문상태 업데이트
 *
 */
 var updateOrderState = function(id, state){
     transaction('working');
     $.ajax({
        		type: 'POST',
        		url: '<?=site_url();?>/admins/order/updateOrder/true',
        		data: {id:id, order_state:state},
        		success: function(text){
        			var json = eval("("+text+")");

                 if (json.success){
                     // alert('성공');
                     transaction('ok');
                     // location.href = '';

                 } else {
                     // alert('실패');
                     transaction('failed');
                 }
        		}
        	});
 };
 
 $('select[name="order_state"]').change(function(){
     var id = $(this).attr('orders_id');
     var value = $(this).val();
     updateOrderState(id,value);
 });


/*
 * 운송장 번호 업데이트
 *
 */
 
 var updateShipInvoice = function(id, no){
     transaction('working');
     
     $.ajax({
         		type: 'POST',
         		url: '<?=site_url();?>/admins/order/updateInvoiceNo/true',
         		data: {id:id, invoice_no:no},
         		success: function(text){
         			var json = eval("("+text+")");

                  if (json.success){
                      // alert('성공');
                      // location.href = '';
                      transaction('ok');

                  } else {
                      // alert('실패');
                      transaction('failed');
                  }
         		}
         	});
 };
 
$('button[name="invoice_btn"]').click(function(){
    var id = $(this).attr('orders_id');
    var val = $('input[orders_id="'+id+'"]').val();
    
    updateShipInvoice(id,val);
});


/*
 * 상세보기
 *
 */

$('button[name="detail_button"]').click(function(){
	var order_code = $(this).attr('order_code');
	
	$div = $('<div>').addClass(order_code).css({width:'710px',height:'690px', backgroundColor:'white',padding:'5px'});
	
	$iframe = $('<iframe>').attr({
				width:700,
				height:700,
				scrolling:'no',
				src: '<?=site_url();?>/admin/orderDetail?order_code=' + order_code,
				border:0
				}).css({width:'700px',height:'690px',border:0, backgroundColor:'white'}).load();
	
	$iframe.load(function(){
		$(this).contents().find('a').remove();
	});
	
	$iframe.appendTo($div);
	$div.dialog({minWidth:720,height:740,modal: false,resizable: false});
	
});


/*
 * 포인트 환급
 */

var refundPoint = function(members_id,orders_id,used_point){
	 transaction('working');
	 
	 var $checkbox = $('input[orders_id="'+orders_id+'"]');
	 
/*
	 alert(members_id + ' ' + orders_id + ' ' + used_point);
     
     return;
*/
     $.ajax({
         		type: 'POST',
         		url: '<?=site_url();?>/actions/member/refundPoint',
         		data: {members_id:members_id,orders_id:orders_id,used_point:used_point},
         		success: function(text){
         			var json = eval("("+text+")");

                  if (json.success){
                      // alert('성공');
                      transaction('ok');
                      $checkbox.attr('disabled',true);

                  } else {
                      // alert('실패');
                      transaction('failed');
                      $checkbox.attr('checked',!checked);
                  }
         		}
         	});
}

$('input[name="point_refund_checkbox"]').change(function(){
	var checked = $(this).is(':checked');
    var members_id = $(this).attr('members_id');
    var orders_id = $(this).attr('orders_id');
    var used_point = $(this).attr('used_point');
	
	var refund = confirm('포인트 환급을 적용하시겠습니까?');
	if (refund){
		refundPoint(members_id,orders_id,used_point);
		
	} else {
		$(this).attr('checked',!checked);
		
	}
	
});


</script>

