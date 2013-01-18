<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function getCenter_cd($VIRTUAL_CENTERCD=null){
			if($VIRTUAL_CENTERCD == "39"){
				return "경남은행";
			}else if($VIRTUAL_CENTERCD == "34"){
				return "광주은행";
			}else if($VIRTUAL_CENTERCD == "04"){
				return "국민은행";
			}else if($VIRTUAL_CENTERCD == "11"){
				return "농협중앙회";
			}else if($VIRTUAL_CENTERCD == "31"){
				return "대구은행";
			}else if($VIRTUAL_CENTERCD == "32"){
				return "부산은행";
			}else if($VIRTUAL_CENTERCD == "02"){
				return "산업은행";
			}else if($VIRTUAL_CENTERCD == "45"){
				return "새마을금고";
			}else if($VIRTUAL_CENTERCD == "07"){
				return "수협중앙회";
			}else if($VIRTUAL_CENTERCD == "48"){
				return "신용협동조합";
			}else if($VIRTUAL_CENTERCD == "26"){
				return "(구)신한은행";
			}else if($VIRTUAL_CENTERCD == "05"){
				return "외환은행";
			}else if($VIRTUAL_CENTERCD == "20"){
				return "우리은행";
			}else if($VIRTUAL_CENTERCD == "71"){
				return "우체국";
			}else if($VIRTUAL_CENTERCD == "37"){
				return "전북은행";
			}else if($VIRTUAL_CENTERCD == "23"){
				return "제일은행";
			}else if($VIRTUAL_CENTERCD == "35"){
				return "제주은행";
			}else if($VIRTUAL_CENTERCD == "21"){
				return "(구)조흥은행";
			}else if($VIRTUAL_CENTERCD == "03"){
				return "중소기업은행";
			}else if($VIRTUAL_CENTERCD == "81"){
				return "하나은행";
			}else if($VIRTUAL_CENTERCD == "88"){
				return "신한은행";
			}else if($VIRTUAL_CENTERCD == "27"){
				return "한미은행";
			}
			
			return '';
	}

?>