<?php

header("Access-Control-Allow-Origin: *");

include_once('./ci_connect.php');


 /***************************************************************************************************************
 * �ô�����Ʈ�κ��� ������� ��/��� ����Ÿ�� �޾Ƽ� �������� ó�� �� �� 
 * �ô�����Ʈ�� �ٽ� ���䰪�� �����ϴ� �������Դϴ�.
 * ���� DBó�� �κ��� ��ü�� �°� �����Ͽ� �۾��Ͻñ� �ٶ��ϴ�.
***************************************************************************************************************/

/*********************************** �ô�����Ʈ�� ���� �Ѱ� �޴� ���� ���� *************************************/
$trcode     = trim( $_POST["trcode"] );					    //�ŷ��ڵ�
$service_id = trim( $_POST["service_id"] );					//�������̵�
$orderdt    = trim( $_POST["orderdt"] );				    //��������
$virno      = trim( $_POST["virno"] );				        //������¹�ȣ
$deal_won   = trim( $_POST["deal_won"] );					//�Աݾ�
$ordno		= trim( $_POST["ordno"] );                      //�ֹ���ȣ
$inputnm	= trim( $_POST["inputnm"] );					//�Ա��ڸ�
/*********************************** �ô�����Ʈ�� ���� �Ѱ� �޴� ���� �� *************************************/

/***************************************************************************************************************
 * �������� �ش� �ŷ��� ���� ó�� db ó�� ��....
 *
 * trcode = "1" �� �Ϲݰ������ �Ա��뺸����
 * trcode = "2" �� �Ϲݰ������ ����뺸����
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

$rSuccYn  = "y";// ���� : y ���� : n

if (!savePaymentResult($resultData)){
	$rSuccYn = "n";
} else {
	insertPoint($ordno);
	// insertPoint($ordno,'SPEND_FOR_PAYMENT');
	updateOrderState($ordno, $resultData['state']);
}
	



/******************************************ó�� ��� ����******************************************************/
$rResMsg  = "";


//����ó�� ��� �ŷ��ڵ�|�������̵�|�ֹ��Ͻ�|������¹�ȣ|ó�����|
$rResMsg .= $trcode."|";
$rResMsg .= $service_id."|";
$rResMsg .= $orderdt."|";
$rResMsg .= $virno."|";
$rResMsg .= $rSuccYn."|";

echo $rResMsg;
/******************************************ó�� ��� ����******************************************************/
?> 