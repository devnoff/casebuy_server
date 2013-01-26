		<style>
		/** 
		 * Slideshow style rules.
		 */
		#slideshow {
			margin:0 auto;
			width:700px;
			height:400px;
			background:transparent url(img/bg_slideshow.jpg) no-repeat 0 0;
			position:relative;
		}
		#slideshow #slidesContainer {
		  margin:0 auto;
		  width:700px;
		  height:400px;
		  overflow:auto; /* allow scrollbar */
		  position:relative;
		}
		#slideshow #slidesContainer .slide {
		  margin:0 auto;
		  width:700px; /* reduce by 20 pixels of #slidesContainer to avoid horizontal scroll */
		  height:400px;
		}
		
		/** 
		 * Slideshow controls style rules.
		 */
		.control {
		  display:block;
		  width:39px;
		  height:400px;
		  text-indent:-10000px;
		  position:absolute;
		  cursor: pointer;
		}
		#leftControl {
		  top:0;
		  left:0;
		}
		#rightControl {
		  top:0;
		  right:0;
		}
		

		.slide h2 {
		  font:italic 24px Georgia, "Times New Roman", Times, serif;
		  color:#ccc;
		  letter-spacing:-1px;
		}
		.slide img {
		  float:right;
		  margin:0 15px;
		}
		
		a.left {
			position: absolute;
			clear: both;
			overflow: hidden;
			z-index: 30;
			width: 50px;
			height: 700px;
			background: url(/loveholic/img/arrow_left.png) 0 0 no-repeat;
			top: 0;
			left: 0;
			-webkit-transition: all .2s ease;
		}
		
		a.right {
			position: absolute;
			clear: both;
			overflow: hidden;
			z-index: 30;
			width: 50px;
			height: 700px;
			background: url(/loveholic/img/arrow_right.png) 0 0 no-repeat;
			top: 0;
			right: 0;
			-webkit-transition: all .2s ease;
		}
		
		a.left:hover {
			background: url(/loveholic/img/arrow_left_on.png) 0 0 no-repeat;
		}
		
		a.right:hover {
			background: url(/loveholic/img/arrow_right_on.png) 0 0 no-repeat;
		}
		
		</style>
		
		<h4 class="main">MUST GET IT<span>어머, 이건 꼭 사야해요!</span></h4>
		<!--
		<div class="mainProduct">
			<ul class="mainProduct">
				<a href="#" class="left control" id="leftControl"></a>
				<a href="#" class="right control" id="rightControl"></a>
				<li>
					<div class="overlay">
						<p class="a">CASEBUY 추천상품</p>
						<a href="<?=site_url('shop/product?id='.$recomm[0]->id.'&c_id='.$recomm[0]->categories_id.'&sc_id='.$recomm[0]->sub_category_id);?>">
							<p class="b"><?=$recomm[0]->sub_title;?></p>
							<p class="c"><?=$recomm[0]->title;?></p>
							<p class="d"><?=$recomm[0]->sales_price;?></p>
						</a>
						
					</div>
				</li>
			</ul>
		</div>
		-->
		
		<!-- Slideshow HTML -->
		<div id="slideshow" class="mainProduct" >
			<a class="left control" id="leftControl"></a>
			<a class="right control" id="rightControl"></a>
			<div id="slidesContainer" class="mainProduct">
			<?php 
			if ($recomm && count($recomm)>0){
				foreach($recomm as $i){
			?>
				<div class="slide" style="background: #F2F3F6 url('<?=$i->web_detail_img;?>') no-repeat 150px 0;">
					<div class="overlay">
						<p class="a">CASEBUY 추천상품</p>
						<a href="<?=site_url('shop/product?id='.$i->id.'&c_id='.$i->categories_id.'&sc_id='.$i->sub_category_id);?>">
							<p class="b"><?=$i->sub_title;?></p>
							<p class="c"><?=$i->title;?></p>
							<p class="d"><?=$i->sales_price;?></p>
						</a>
					</div>
				</div>
			<?php 
				}
			}
			?>
			</div>
		</div>
		<!-- Slideshow HTML -->

		<script type="text/javascript">
		$(document).ready(function(){
		  var currentPosition = 0;
		  var slideWidth = 700;
		  var slides = $('.slide');
		  var numberOfSlides = slides.length;
		
		
		  // Remove scrollbar in JS
		  $('#slidesContainer').css('overflow', 'hidden');
		  
		  
		
		  // Wrap all .slides with #slideInner div
		  slides
		    .wrapAll('<div id="slideInner"></div>')
		    // Float left to display horizontally, readjust .slides width
			.css({
		      'float' : 'left',
		      'width' : slideWidth
		    });
		
		  // Set #slideInner width equal to total width of all slides
		  $('#slideInner').css('width', slideWidth * numberOfSlides);
		

		
		  // Create event listeners for .controls clicks
		  $('.control')
		    .bind('click', function(){
		    // Determine new position
		    
		    position = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
		    
		    slidePosition(position % numberOfSlides);
		    
		  }).hide();
		
		  var slidePosition = function(position){
			var old_position = currentPosition;
			currentPosition = position;
			if (currentPosition < numberOfSlides  && currentPosition >= 0){
				$('#slideInner').animate({
				  'marginLeft' : slideWidth*(-currentPosition)
				});    
			} else {
				currentPosition = old_position;
			}	
		  };

		  var intId = setInterval(function(){animateSlide()},3000);

		  var animateSlide = function(){
			  var idx = (currentPosition +1) % numberOfSlides;
			  slidePosition(idx);
		  };
		  
		  $('#slideshow').mouseenter(function(){
			  $('.control').show();
			  clearInterval(intId);
		  }).mouseleave(function(){
			  $('.control').hide();
			  intId = setInterval(function(){animateSlide()},3000);
		  });
		  
		  
		});
		</script>

		
		
		
		<script>
			$('.mainProduct > li').css('background-image','url(<?=$recomm[0]->web_detail_img;?>)');
		</script>

		<h4 class="main">HOT ITEMS<span>얘들이 제일 잘나가요!</span></h4>

		<ul class="list">
			<?php
				foreach($popular as $i){
				
				$item_url = site_url('shop/product?id='.$i->id);
				
			?>
			<li>
				<div class="photo"><a href="<?=$item_url;?>"><img src="<?=$i->web_list_img;?>"></a></div>
				<div class="text">
					<p class="comment"><?=$i->sub_title;?></p>
					<p class="subject"><a href="<?=$item_url;?>"><?=$i->title;?></a></p>
					<p class="price">
						<strong>$<?=$i->sales_price;?></strong> 
						<?php if ($i->extra_info_value1) { ?>
						<span>(<?=$i->extra_info_value1;?>)</span>
						<?php }?>
					</p>
				</div>
			</li>
			<?php
				}
			?>
		</ul>