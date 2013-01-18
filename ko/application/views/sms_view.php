<?php
/******************** 인증정보 ********************/
    $sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
    $sms['user_id'] = base64_encode("noffxp"); //SMS 아이디.
    $sms['secure'] = base64_encode("327efd810f25ddf6948b2d890113218f") ;//인증키
    $sms['msg'] = base64_encode(stripslashes($msg));

    $sms['rphone'] = base64_encode($mobile); // ex: 010-000-0000
    $sms['sphone1'] = base64_encode('010');
    $sms['sphone2'] = base64_encode('1234');
    $sms['sphone3'] = base64_encode('5678');
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
            echo '{"success":true}';
        } else {
        	echo '{"success":false, "reason":"sms '.$Result.'", "mobile":"'.$mobile.'", "msg":"'.$msg.'"}';
        }
        
    }
    else {
        echo '{"success":false, "reason":"fsockopen not work"}';
    }
?>