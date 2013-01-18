
<?php 

?>
<!-- 월선택 Jquery UI Plugin -->
<script src='<?=base_url();?>js/jquery.ui.monthpicker.js'></script>

<div class="qna_list">
    <div class="navigation_container" style="display:block">

        <div style="text-align:right" style="display:block">
            <span>Transaction:<span><span id="transaction_result">None</span>
        </div>
    </div>
    <div class="clear"></div>
    <table class="left">
        <thead>
            <tr>
                <td>작성일시</td>
                <td>작성자</td>
                <td>상태</td>
                <td class="action_cell">액션</td>
            </tr>
        </thead>
        <tbody>
        
<?php
    if ($qna_data){
        foreach ($qna_data as $data){
?>
            <tr>
                <td><?=$data->date_write;?></td>
                <td><?=$data->nickname;?></td>
                <td class="<?=$data->reply>0?'text_color_confirmed':'text_color_cancelled';?>"><?=$data->reply>0?'답변완료':'미답변';?></td>
                <td><button name="content" type="button" inqueries_id="<?=$data->id;?>">내용</button></td>
            </tr>
            
<?php
    }
}

?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><?=$pagination;?></td>
            </tr>
        </tfoot>
    </table>
    <div class="article" inqueries_id="" answer_id="">
        <p>작성자: <span id="article_username"></span></p>
        <p>제목: <span id="article_title"></span></p>
        <textarea name="question_content" placeholder="내용없음" disabled></textarea>
        <p>답변</p>
        <textarea name="answer_content" placeholder="답변을 작성하세요."></textarea><br/>
        <button disabled type="button">작성 또는 수정</button>
    </div>
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
 * QnA 내용 보기
 *
 */
var dispQuestionContent = function(data){
    var id = data.question.username;
    id = !id ? '비회원' : id;

    $('#article_username').html(id + '(' + data.question.nickname + ')');
    $('#article_title').html(data.question.title);
    $('.article textarea[name="question_content"]').val(data.question.content);
    $('.article textarea[name="answer_content"]').val(data.answer.content);
    $('.article').attr('inqueries_id',data.question.id);
    $('.article').attr('answer_id',data.answer.id);

    
};

var loadQuestionContent = function(id){
    transaction('working');
     $.ajax({
                type: 'POST',
                url: '<?=site_url();?>/admins/inquery/qnaPair/'+id,
                success: function(text){
                    var json = eval(text);
                    transaction('ok');
                    dispQuestionContent(json);
                }
            });
}
 
 
$('button[name="content"]').click(function(){
    var id = $(this).attr('inqueries_id');
    loadQuestionContent(id);
});


// 답변 전송
$('.article textarea[name="answer_content"]').keyup(function(){
    $('.article > button').attr('disabled', false);
});

$('.article > button').click(function(){
    var inqueries_id = $('.article').attr('inqueries_id');
    var answer_id = $('.article').attr('answer_id');
    var content = $('.article textarea[name="answer_content"]').val();
    
    
    
    var uri = 'updateAnswer';
    if (answer_id=='' || answer_id == undefined || answer_id == true){
        uri = 'addAnswer';
    } 
    
    transaction('working');
     $.ajax({
                type: 'POST',
                url: '<?=site_url();?>/admins/inquery/'+uri,
                data: {inqueries_id: inqueries_id, content: content, answer_id: answer_id},
                success: function(text){
                    var json = eval("("+text+")");

                 if (json.success){
                     // alert('성공');
                     transaction('ok');
                     location.href='';

                 } else {
                     // alert('실패');
                     transaction('failed');
                 }
                }
            });
});




</script>

