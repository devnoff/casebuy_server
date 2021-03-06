
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Cache-Control" content="No-Cache">
<meta http-equiv="Pragma" content="No-Cache"> 
<?=$meta;?>
	<title>CASEBUY - <?=!empty($title)?$title:'';?></title>
	
	<link rel="stylesheet" href="/ko/css/shop_style.css" />
	<link type="text/css" href="/ko/css/ui-lightness/jquery-ui-1.9.1.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="/en/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/en/js/jquery-ui-1.8.22.custom.min.js"></script>
	<script src="http://www.google.com/jsapi"></script>
	<script src="/en/js/helpers.js"></script>
	<script>
	 google.load( "webfont", "1" );
	 google.setOnLoadCallback(function() {
	  WebFont.load({ custom: {
	   families: [ "NanumGothic" ],
	   urls: [ "http://fontface.kr/NanumGothic/css" ]
	  }});
	 });
	</script>
	<link href='http://api.mobilis.co.kr/webfonts/css/?fontface=NanumGothicWeb' rel='stylesheet' type='text/css' />
	<link href='http://api.mobilis.co.kr/webfonts/css/?fontface=NanumGothicBoldWeb' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css'>
</head>

<body>

<div class="headerWrapper">
	<div class="headerContainer">
		<h1 onclick="location.href='<?=site_url('shop');?>';" style="cursor:pointer">CASEBUY</h1>
		<form method="GET" action="<?=site_url('shop/product_search');?>">
		<ul class="left">
			<li class="search"><input name="keyword" type="text" placeholder="상품 검색" /></li>
		</ul>
		</form>
		<ul class="right">
			<li class="cart"><a href="<?=site_url('shop/cart');?>">장바구니</a></li>
			<li class="notice"><a href="<?=site_url('shop/notice');?>">공지사항</a></li>
			<li class="qna"><a href="<?=site_url('shop/qna');?>">Q&A</a></li>
			<?php if ($member){ ?>
			<li class="my"><a href="<?=site_url('shop/my');?>">MY페이지</a></li>
			<li class="logout"><a href="<?=site_url('member/logout');?>">로그아웃</a></li>
			<?php } else { ?>
			<!-- li class="login"><a href="<?=site_url('shop/login');?>">로그인</a></li>
			<li class="join"><a href="<?=site_url('shop/join');?>">회원가입</a></li -->
			<li class="orderQuery"><a href="<?=site_url('shop/orderQuery');?>">주문조회</a></li>
			<?php } ?>
			
		</ul>
	</div>
</div>
<script>
var uri = '<?=$this->uri->segment(2);?>';

$('.headerContainer').find('.'+uri).find('a').addClass('selected');

</script>

<div class="mainWrapper">
	<div class="menuWrapper">
		<?=$sideView;?>
	</div>

	<div class="contentWrapper mainContentWrapper">
		<?=$contentView;?>
	</div>
</div>

<div class="footerWrapper">
	<div class="footerContainer">

		<ul class="contact">
			<li><a href="<?=site_url('shop/etc/company');?>">회사소개</a></li>
			<li>문의전화 <span>070-8280-4090</span></li>
			<li>문의메일 <span>casebuy@cultstory.com</span></li>
			<li><a href="<?=site_url('shop/etc/terms');?>">이용약관</a></li>
			<li style="border-right: 0;"><a href="<?=site_url('shop/etc/policy');?>">개인정보보호정책</a></li>
		</ul>
		<ul class="info">
			<li>
<center>(주)컬트스토리 대표: 윤제필 사업자등록번호: 105-87-36667 통신판매업신고번호: 제 2012-서울중구-1342 호</center>
<center>주소: 서울시 중구 충무로2가 50-6 라이온스빌딩 1003 TEL: 070-8280-4090 FAX: 02-6280-7428</center>
<center>개인정보관리책임자: 박용남(casebuy@cultstory.com)</center>
			</li>
		</ul>

	
		<p>Copyright © <strong>CASEBUY</strong> All Rights Reserved.</p>
	</div>
</div>

<div id="loading_dialog" class="ui-widget-overlay" style="display:none;position:fixed"></div>

</body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37995511-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</html>