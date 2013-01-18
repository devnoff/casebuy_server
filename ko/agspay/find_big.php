<?php

/* 올더 게이트 설치 디렉토리 절대 경로 */
	global $PHP_SELF; 
	$thisfilename=basename(__FILE__);
	$temp_filename=realpath(__FILE__);

	if(!$temp_filename) $temp_filename=__FILE__;

	$osdir=eregi_replace($thisfilename,"",$temp_filename);

	unset($temp_filename);

	$virdir = eregi_replace($thisfilename,"",$PHP_SELF);
	
	$osdir = $_SERVER[‘HTTP_HOST’].'ko/agspay';
	
	echo $osdir.' '.$virdir;


?>