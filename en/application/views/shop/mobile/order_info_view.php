<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.wapforum.org/DTD/xhtml-mobile12.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"> 
	<head>
		<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" /> 
		<meta http-equiv=Cache-Control content=No-Cache>
		<meta http-equiv=Pragma	content=No-Cache>
        <title>Case Buy - </title> 
        <link rel="stylesheet" href="/en/css/order_detail_style.css" />
        <script type="text/javascript" src="/en/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript">
		<!--
		 window.addEventListener('load', function(){
		  setTimeout(scrollTo, 0, 0, 1);
		 }, false);
		</script>
	</head>
	<body class="shipping">

		<h2><span>Shipping Info</span></h2>
		
		<div class="inputWrapper">
			<div class="inputContainer">
				<div class="input">
					<span>Name</span>
					<input type="text" name="shiptoname">
				</div>
				<div class="input">
					<span>Phone</span>
					<input type="text" name="shiptophonenum" pattern="[0-9]*">
				</div>
				<div class="input">
					<span>Address 1</span>
					<input type="text" name="shiptostreet">
				</div>
				<div class="input">
					<span>Address 2</span>
					<input type="text" name="shiptostreet2">
				</div>
				<div class="input">
					<span>City</span>
					<input type="text" name="shiptocity">
				</div>
				<div class="input">
					<span>State</span>
					<input type="text" name="shiptostate">
				</div>
				<div class="input">
					<span>Zipcode</span>
					<input type="text" name="shiptozip" pattern="[0-9]*">
				</div>
				<div class="input">
					<span>Country</span>
					<input type="text" id="shiptocountrycode" class="country" readonly>
					<input type="hidden" name="shiptocountrycode"/>
				</div>

			</div>
		</div>


		<a href="submitted:" class="checkout">CONTINUE</a>
		<p class="add">Please make sure your shipping address is correct!</p>

	</body>


</html>