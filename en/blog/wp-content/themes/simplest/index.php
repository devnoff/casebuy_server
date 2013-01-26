<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <!-- <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" /> -->
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <title><?php bloginfo( 'name' ); ?><?php wp_title( '&mdash;' ); ?></title>
    <?php if ( is_singular() && get_option( 'thread_comments') ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>

	<title>CASEBUY - 회사소개</title>
	
	<link rel="stylesheet" href="/loveholic/css/shop_style.css" />
	<link type="text/css" href="/loveholic/css/ui-lightness/jquery-ui-1.8.22.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="/loveholic/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/loveholic/js/jquery-ui-1.8.22.custom.min.js"></script>
	<script src="http://www.google.com/jsapi"></script>
	<script src="/loveholic/js/helpers.js"></script>
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
  <body <?php body_class(); ?>>


<?

$username = $_COOKIE['loveholic_username'];

?>

<div class="headerWrapper">
	<div class="headerContainer">
		<h1 onclick="location.href='http://scomdcom.com/loveholic/index.php/shop';" style="cursor:pointer">CASEBUY</h1>
		<form method="GET" action="http://scomdcom.com/loveholic/index.php/shop/product_search">
		<ul class="left">
			<li class="search"><input name="keyword" type="text" placeholder="상품 검색" /></li>
		</ul>
		</form>
		<ul class="right">
			<li><a href="/loveholic/blog">블로그</a></li>
			<li class="cart"><a href="/loveholic/index.php/shop/cart">장바구니</a></li>
			<li class="notice"><a href="/loveholic/index.php/shop/notice">공지사항</a></li>
			<li class="qna"><a href="/loveholic/index.php/shop/qna">Q&A</a></li>
			<?php if ($username){ ?>
			<li class="my"><a href="/loveholic/index.php/shop/my">MY페이지</a></li>
			<li class="logout"><a href="/loveholic/index.php/member/logout">로그아웃</a></li>
			<?php } else { ?>
			<li class="login"><a href="/loveholic/index.php/shop/login">로그인</a></li>
			<li class="join"><a href="/loveholic/index.php/shop/join">회원가입</a></li>
			<li class="orderQuery"><a href="/loveholic/index.php/shop/orderQuery">비회원조회</a></li>
			<?php } ?>
			
		</ul>
	</div>
</div>

<div class="mainWrapper">
	<div class="menuWrapper">
		<div class="menuContainer">

				<?php if ( is_active_sidebar( 'widgets' ) ) : ?>
				  <div class="widgets"><?php dynamic_sidebar( 'widgets' ); ?></div>
				<?php endif; ?>

		</div>


	</div>

	<div class="contentWrapper mainContentWrapper">

	<div id="blogContainer">
      <!-- <div id="header">
        <h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <p id="description"><?php bloginfo( 'description' ); ?></p>
        <?php if ( has_nav_menu( 'menu' ) ) : wp_nav_menu(); else : ?>
          <ul><?php wp_list_pages( 'title_li=&depth=-1' ); ?></ul>
        <?php endif; ?>
      </div><!-- header -->
      <div id="content">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <div <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_content(); ?>
            <?php if ( !is_singular() && get_the_title() == '' ) : ?>
              <a href="<?php the_permalink(); ?>">(more...)</a>
            <?php endif; ?>
			<?php comments_popup_link(__('<span class="write_comment">댓글을 입력해보세요</span>'), __('<span class="write_comment2"><strong>1</strong> 개의 댓글이 있습니다</span>'), __('<span class="write_comment2"><strong>%</strong> 개의 댓글이 있습니다</span>')); ?> 
            <?php if ( is_singular() ) : ?>
              <div class="pagination"><?php wp_link_pages(); ?></div>
            <?php endif; ?>
            <div class="clear"> </div>
          </div><!-- post_class() -->
          <?php if ( is_singular() ) : ?>
            <div class="meta">
              <p>Posted by <?php the_author_posts_link(); ?>
              on <a href="<?php the_permalink(); ?>"><?php the_date(); ?></a>
              in <?php the_category( ', ' ); ?><?php the_tags( ', ' ); ?></p>
            </div><!-- meta -->
            <?php comments_template(); ?>
          <?php endif; ?>
        <?php endwhile; else: ?>
          <div class="hentry"><h2>Sorry, the page you requested cannot be found</h2></div>
        <?php endif; ?>

        <?php if ( is_singular() || is_404() ) : ?>
          <div class="left"><a href="<?php bloginfo( 'url' ); ?>">&laquo; Home page</a></div>
        <?php else : ?>
          <div class="left"><?php next_posts_link( '&laquo; 이전 포스트 보기' ); ?></div>
          <div class="right"><?php previous_posts_link( '다음 포스트 보기 &raquo;' ); ?></div>
        <?php endif; ?>
        <div class="clear"> </div>
      </div><!-- content -->
    </div><!-- container -->
    <?php wp_footer(); ?>


	</div>
</div>

<div class="footerWrapper">
	<div class="footerContainer">

		<ul class="contact">
			<li><a href="http://scomdcom.com/loveholic/index.php/shop/etc/company">회사소개</a></li>
			<li>문의전화 <span>070-8650-2086</span></li>
			<li>문의메일 <span>support@scomdcom.com</span></li>
			<li><a href="http://scomdcom.com/loveholic/index.php/shop/etc/terms">이용약관</a></li>
			<li style="border-right: 0;"><a href="http://scomdcom.com/loveholic/index.php/shop/etc/policy">개인정보보호정책</a></li>
		</ul>
		<ul class="info">
			<li><img src="/loveholic/img/copy.png"></li>
		</ul>

	
		<p>Copyright © <strong>CASEBUY</strong> All Rights Reserved.</p>
	</div>
</div>

<div id="loading_dialog" class="ui-widget-overlay" style="display:none;position:fixed"></div>


  </body>
</html>