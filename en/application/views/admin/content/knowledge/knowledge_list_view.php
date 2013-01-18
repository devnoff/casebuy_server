

<div>
	<h3>작성</h3>
	<form id="write_form" action="<?=site_url('admins/action/addKnowledge');?>" method="POST">
		<input name="title" type="text" value="" placeholder="제목"/>
		<input name="desc" type="text" value="" placeholder="설명"/>
		<input name="link" type="text" value="" placeholder="링크주소"/>
		<button type="submit">입력</button>
	</form>
</div>

<br/>

<h3>목록</h3>
<table border="1">
	<tr>
		<td>번호</td>
		<td>제목</td>
		<td>설명</td>
		<td>링크주소</td>
		<td>액션</td>
	</tr>
<?php foreach ($data as $i){ ?>
	<form item_id="<?=$i->id;?>">
	<tr>
		<td><?=$i->id;?><input name="id" type="hidden" value="<?=$i->id;?>" /></td>
		<td><input name="title" type="text" size="35" value="<?=htmlentities($i->title,ENT_QUOTES, "UTF-8");?>"/></td>
		<td><input name="desc" type="text" size="35" value="<?=$i->desc;?>"/></td>
		<td><input name="link" type="text" size="35" value="<?=$i->link;?>"/></td>
		<td><a href="<?=$i->link;?>" target="blank_">링크</a> | <a style="cursor:pointer" onclick="updateKnowledge(<?=$i->id;?>)">수정</a> | <a style="cursor:pointer" onclick="deleteKnowledge(<?=$i->id;?>)">삭제</a></td>
	</tr>
	</form>
<?php } ?>
	<tr>
		<td colspan="5" align="center"><?=$pagination;?></td>
	</tr>
</table>



<script>


//작성
$('#write_form').submit(function(e){
	e.preventDefault();

	var data = $(this).serialize();
	var url = $(this).attr('action');
			   
    $.ajax({
   		type: 'POST',
   		url: url,
   		data: data,
   		success: function(text){
   			var json = text;
               
            if (json.success){
            	alert('입력 완료!');
              	location.href='';  
            } 
   		}
   	});

});


var updateKnowledge = function(item_id){

	var data = $('form[item_id="'+item_id+'"]').serialize();

	$.ajax({
   		type: 'POST',
   		url: "<?=site_url('admins/action/updateKnowledge');?>",
   		data: data,
   		success: function(text){
   			var json = text;
               
            if (json.success){
            	alert('수정 완료!');
              	location.href='';  
            } 
   		}
   	});
}

var deleteKnowledge = function(item_id){


	$.ajax({
   		type: 'POST',
   		url: "<?=site_url('admins/action/removeKnowledge');?>",
   		data: {id:item_id},
   		success: function(text){
   			var json = text;
               
            if (json.success){
            	alert('삭제 완료!');
              	location.href='';  
            } 
   		}
   	});
}


</script>