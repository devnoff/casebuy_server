<?php
// Define Content Type
	header('Content-Type: text/html; charset=utf-8');
	
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>관리자 화면</title>
	<link rel="stylesheet" type="text/css" href="/en/css/admin_style.css" />
	<link type="text/css" href="/en/css/ui-lightness/jquery-ui-1.9.1.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="/en/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/en/js/jquery-ui-1.9.1.custom.min.js"></script>
    <script src="/en/js/helpers.js"></script>

</head>
<body>
<div id="loading_dialog" class="ui-widget-overlay" style="display:none">
    Loading...
</div>
<div style="position:fixed">
	<select id="store_link">
		<option value="http://casebuy.me/ko/index.php/<?=$this->uri->uri_string();?>" selected>한글 스토어</option>
		<option value="http://casebuy.me/en/index.php/<?=$this->uri->uri_string();?>">영문 스토어</option>
	</select>
</div>
<script>
	$('#store_link').change(function(){
		location.href = $('#store_link option:selected').val();
	});
</script>
<div class="header_wrapper">
    <div><span><a style="text-decoration:none; color: white" target="_blank" href="http://casebuy.me">casebuy.me</a></span> Administration System v.0.1</div>
</div>