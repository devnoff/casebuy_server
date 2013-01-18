
<p>전체 회원수 : <?=$total;?></p>
<p>오늘 가입자수 : <?=$total_today;?></p>

<h3>회원목록</h3>


<table border="1">
	<tr>
		<td>번호</td>
		<td>아이디</td>
		<td>닉네임</td>
		<td>전화번호</td>
		<td>휴대폰번호</td>
		<td>이메일</td>
		<td>가입일</td>
		<td>보유포인트</td>
		<td>사용포인트</td>
	</tr>

<? foreach ($data as $i) { ?>

	<tr>
		<td><?=$i->id;?></td>
		<td><?=$i->username;?></td>
		<td><?=$i->nickname;?></td>
		<td><?=$i->telephone;?></td>
		<td><?=$i->mobile;?></td>
		<td><?=$i->email;?></td>
		<td><?=$i->date_join;?></td>
		<td></td>
		<td></td>
	</tr>	

<? } ?>

	<tr>
		<td colspan="9" align="center"><?=$pagination;?></td>

	</tr>

</table>