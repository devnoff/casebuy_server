		<ul class="subMenu">
			<li><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
			<li><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
			<li class="selected"><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
			<li><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
			<li><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
		</ul>
		
		<form method="post" action="<?=site_url('shop/my/info');?>">
		<div style="padding-top: 100px">
		<table cellpadding="0" cellspacing="0" border="0" class="fullTable">
			<tr class="password">
				<th>비밀번호 확인</th>
				<td><input name="password" type="password" placeholder="비밀번호"> &nbsp;<button type="submit">확인</button> <span></span></td>
			</tr>
		</table>
		</div>
		</form>
		