<?php
function fetch_page($url,$param,$cookies=NULL,$referer_url=NULL){ 
    if(strlen(trim($referer_url)) == 0) $referer_url= $url; 
    $curlsession = curl_init (); 
    curl_setopt ($curlsession, CURLOPT_URL, $url); 
    curl_setopt ($curlsession, CURLOPT_POST, 1); 
    curl_setopt ($curlsession, CURLOPT_POSTFIELDS, $param); 
    //curl_setopt ($curlsession, CURLOPT_POSTFIELDSIZE, 0); 
    curl_setopt ($curlsession, CURLOPT_TIMEOUT, 60); 
    if($cookies && $cookies!=""){ 
        curl_setopt ($curlsession, CURLOPT_COOKIE, $cookies); 
    } 
    curl_setopt ($curlsession, CURLOPT_HEADER, 1); //헤더값을 가져오기위해 사용합니다. 쿠키를 가져오려고요. 
    curl_setopt ($curlsession, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.01; Windows NT 6.0)"); 
    curl_setopt ($curlsession, CURLOPT_REFERER, "$referer_url"); 

    ob_start(); 
    $res = curl_exec ($curlsession); 
    $buffer = ob_get_contents(); 
    ob_end_clean(); 
    $returnVal = array();
    if (!$buffer) {
    	$returnVal['error'] = true; 
        $returnVal['content'] = "Curl Fetch Error : ".curl_error($curlsession); 
    }else{ 
    	$returnVal['error'] = false;
        $returnVal['content'] = $buffer; 
    } 
    curl_close($curlsession); 
    return $returnVal; 
}

function remove_none_xml_word($content){
	$content_array = explode("\n", $content);
	foreach ($content_array as $key => $value) {
		if(substr(trim($value),0,1)!='<'){
			$content_array[$key]='';
		}
	}
	unset($content_array[0]);
	$content = implode("\n", $content_array);
	return trim($content);
}

function add_dash_and_tag_to_postcd($postcd){
	$postcd1=substr($postcd,0,3);
	$postcd2=substr($postcd,3,3);
	return "<span class='postcd1'>$postcd1</span>-<span class='postcd2'>$postcd2</span>";
}

function get_post_code_xml_by_api($query){
	$query = iconv('utf-8','euc-kr',$query);
	
	$post_data = array(
	    'target' => 'post',
	    'regkey' => '1412b5288cf217e141285851999098',
	    'query' => $query
	);

	$url = 'http://biz.epost.go.kr/KpostPortal/openapi';
	$param = http_build_query($post_data);
	$result = fetch_page($url,$param);
	$result['content'] = remove_none_xml_word($result['content']);
	
	return $result;
}

function print_postcode_table($xml){
	?>
	<table class="postcode"><tbody>
	<?php 
	    foreach ($xml->itemlist->item as $value) {
	    	$postcd = add_dash_and_tag_to_postcd($value->postcd);
	    	echo '<tr>';
	    	echo "<td><a class='address' href='#{$value->postcd}'>{$value->address}</a></td>";
	    	echo "<td><a class='postcd' href='#{$value->postcd}'>{$postcd}</a></td>";
	    	echo '</tr>';
	    }
	?>
	</tbody></table>
	<?php
}

function divide_postcd($postcd){
	$array = array();
	$array[]=substr($postcd,0,3);
	$array[]=substr($postcd,3,3);
	return $array;
}

function print_postcode_json($xml){


	$count = 1;
	echo '[';
	foreach ($xml->itemlist->item as $value) {
		$postcd = divide_postcd($value->postcd);
		$addr = (string)$value->address;
		$base = remove_useless_addr($addr);
		echo '{';
		echo "\"addr_base\": \"{$base}\",";
		echo "\"addr_full\": \"{$value->address}\",";
		echo "\"zipcode\": \"{$postcd[0]}-{$postcd[1]}\"";
		echo '}';
		if(count($xml->itemlist->item) > $count){
			echo ',';
		}
		$count++;
	}
	echo ']';
}

function string_postcode_json($xml){
	
	$count = 1;
	$str = '[';
	foreach ($xml->itemlist->item as $value) {
		$postcd = divide_postcd($value->postcd);
		$addr = (string)$value->address;
		$base = remove_useless_addr($addr);
		$str .= '{';
		$str .= "\"addr_base\": \"{$base}\",";
		$str .= "\"addr_full\": \"{$value->address}\",";
		$str .= "\"zipcode\": \"{$postcd[0]}-{$postcd[1]}\"";
		$str .= '}';
		if(count($xml->itemlist->item) > $count){
			$str .= ',';
		}
		$count++;
	}
	$str .= ']';

	return $str;
}

function print_postcode_json2($xml){
	$count = 1;
	echo '[';
	foreach ($xml->itemlist->item as $value) {
		$postcd = divide_postcd($value->postcd);
		echo "\"{$value->address}:{$postcd[0]}-{$postcd[1]}\"";
		if(count($xml->itemlist->item) > $count){
			echo ',';
		}
		$count++;
	}
	echo ']';
}


function remove_useless_addr($str){
	
	if(stripos($str, '~')){
		$arr = explode(' ', $str);
		if (count($arr)>0){
			unset($arr[count($arr)-1]);
			return implode(' ', $arr);
		}
	}
	return $str;
}

?>