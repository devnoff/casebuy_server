<?php

header("Access-Control-Allow-Origin: *");

include_once('./ci_connect.php');


 /***************************************************************************************************************
 * 올더게이트로부터 가상계좌 입/출금 데이타를 받아서 상점에서 처리 한 후 
 * 올더게이트로 다시 응답값을 리턴하는 페이지입니다.
 * 상점 DB처리 부분을 업체에 맞게 수정하여 작업하시기 바랍니다.
***************************************************************************************************************/

/*********************************** 올더게이트로 부터 넘겨 받는 값들 시작 *************************************/
$trcode     = trim( $_POST["trcode"] );					    //거래코드
$service_id = trim( $_POST["service_id"] );					//상점아이디
$orderdt    = trim( $_POST["orderdt"] );				    //승인일자
$virno      = trim( $_POST["virno"] );				        //가상계좌번호
$deal_won   = trim( $_POST["deal_won"] );					//입금액
$ordno		= trim( $_POST["ordno"] );                      //주문번호
$inputnm	= trim( $_POST["inputnm"] );					//입금자명
/*********************************** 올더게이트로 부터 넘겨 받는 값들 끝 *************************************/

/***************************************************************************************************************
 * 상점에서 해당 거래에 대한 처리 db 처리 등....
 *
 * trcode = "1" ☞ 일반가상계좌 입금통보전문
 * trcode = "2" ☞ 일반가상계좌 취소통보전문
 *
***************************************************************************************************************/


$resultData = array();
$resultData['payment_method'] = 'virtual';
$resultData['state'] = $trcode == '1' ? 'DONE' : 'CANCELED';
$resultData['rStoreId'] = $service_id;
$resultData['rApprTm'] = $orderdt;
$resultData['rVirNo'] = $virno;
$resultData['rAmt'] = $deal_won;
$resultData['rOrdNo'] = $ordno;
$resultData['inputnm'] = iconv("EUC-KR", "UTF-8", $inputnm);

$rSuccYn  = "y";// 정상 : y 실패 : n

if (!savePaymentResult($resultData)){
	$rSuccYn = "n";
} else {
	insertPoint($ordno);
	// insertPoint($ordno,'SPEND_FOR_PAYMENT');
	updateOrderState($ordno, $resultData['state']);
}
	



/******************************************처리 결과 리턴******************************************************/
$rResMsg  = "";


//정상처리 경우 거래코드|상점아이디|주문일시|가상계좌번호|처리결과|
$rResMsg .= $trcode."|";
$rResMsg .= $service_id."|";
$rResMsg .= $orderdt."|";
$rResMsg .= $virno."|";
$rResMsg .= $rSuccYn."|";

echo $rResMsg;
/******************************************처리 결과 리턴******************************************************/
?> 