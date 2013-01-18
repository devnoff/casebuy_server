<?php

/*
  * sms 보내기
  */

function sendSms($to=null,$message=null)
{

	$from1 = "070";
	$from2 = "8650";
	$from3 = "2086";

	$to = explode('-', $to);
	$to = implode('', $to);
	$to = phoneNumberDash($to);

	if (!$to){
		return false;
	}

	/******************** 인증정보 ********************/
    $sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
    $sms['user_id'] = base64_encode("noffxp"); //SMS 아이디.
    $sms['secure'] = base64_encode("327efd810f25ddf6948b2d890113218f") ;//인증키
    $sms['msg'] = base64_encode(stripslashes($message));

    $sms['rphone'] = base64_encode($to);
    $sms['sphone1'] = base64_encode($from1);
    $sms['sphone2'] = base64_encode($from2);
    $sms['sphone3'] = base64_encode($from3);
    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.


    $host_info = explode("/", $sms_url);
    $host = $host_info[2];
    $path = $host_info[3]."/";

    srand((double)microtime()*1000000);
    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
    //print_r($sms);

    // 헤더 생성
    $header = "POST /".$path ." HTTP/1.0\r\n";
    $header .= "Host: ".$host."\r\n";
    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	$data = '';
    // 본문 생성
    foreach($sms AS $index => $value){
        $data .="--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
        $data .= "\r\n".$value."\r\n";
        $data .="--$boundary\r\n";
    }
    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);

    if ($fp) {
        fputs($fp, $header.$data);
        $rsp = '';
        while(!feof($fp)) {
            $rsp .= fgets($fp,8192);
        }
        fclose($fp);
        $msg = explode("\r\n\r\n",trim($rsp));
        $rMsg = explode(",", $msg[1]);
        $Result= $rMsg[0]; //발송결과

        //발송결과 알림
        if($Result=="success") {
        	return true;
        }

        echo $Result;
        
    }
  	
  	return false;
}	

function phoneNumberDash($number=null){
	if (!$number || gettype($number)!='string' || strlen($number) < 10){
		return false;
	}

	$number = explode('-', $number);
	$number = implode('', $number);

	$length = strlen($number);

	$part1 = substr($number, 0,3);
	if ($length < 11){
		$part2 = substr($number, 3,3);
		$part3 = substr($number, 6,4);
	} else {
		$part2 = substr($number, 3,4);
		$part3 = substr($number, 7,4);
	}

	return $part1.'-'.$part2.'-'.$part3;
}




?>
