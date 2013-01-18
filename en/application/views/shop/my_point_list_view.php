		<ul class="subMenu">
			<li><a href="<?=site_url('shop/my/order_list');?>">주문내역보기</a></li>
			<li><a href="<?=site_url('shop/my/wishlist');?>">찜한 상품</a></li>
			<li><a href="<?=site_url('shop/my/info');?>">기본정보관리</a></li>
			<li class="selected"><a href="<?=site_url('shop/my/point');?>">적립금 내역</a></li>
			<li><a href="<?=site_url('shop/my/qna');?>">문의 내역</a></li>
		</ul>
		
		<div class="mileageHead">
			<p><?=$sum_point;?><span>원</span><strong>회원님의 보유 적립금입니다<br/>적립금은 5,000원 이상부터 구매시 사용가능합니다.</strong></p>
		</div>

		<table cellpadding="0" cellspacing="0" border="0" class="mileageHistory">
			<tr>
				<th style="border-left:0;">일자</th>
				<th style="text-align: left";><span>적립 내역</span></th>
				<th>비고</th>
				<th>관련주문</th>
			</tr>

			<?php
				foreach($point_list as $i){
			?>
			<tr>
				<td class="a" style="border-left:0;"><?=$i->date_added;?></td>
				<td class="b"><p class="<?=$i->mark=='+'?'plus':'minus';?>"><?=$i->mark;?><?=$i->point;?><span>원 <!--<?=$i->mark=='+'?'적립':'사용';?> --></span></p></td>
				<td class="c">
					<?php
					
						switch($i->reason){
							case 'EARN_TO_JOIN':
								echo '가입축하';
							break;
							case 'EARN_TO_BUY':
								echo '구매적립';
							break;
							case 'SPEND_FOR_PAYMENT':
								echo '상품결제';
							break;
							case 'EARN_ETC':
								echo '기타';
							break;
							case 'SPEND_FOR_CANCEL':
								echo '적립금 회수';
							break;
							case 'EARN_REFUND':
								echo '적립금 환급';
							break;
							case 'EARN_TO_WRITE_REVIEW':
								echo '리뷰작성적립';
							break;
						}
					?>
				</td>
				<td class="d">
				<?php if ($i->order_code){ ?>
				<a href="<?=site_url('shop/orderDetail?order_code='.$i->order_code);?>">주문내역보기</a>
				<? } else { ?>
				-
				<? } ?>
				</td>
			</tr>
			<?php
				}
			?>

			
		</table>
