<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function checkAdult($jumin){

    // 주민번호 유효성 검사
    $juminUnits = str_split($jumin);
    $addValues = array(2,3,4,5,6,7,8,9,2,3,4,5);
    
    if (count($juminUnits) != count($addValues)+1){
        return false;
    }
    
    // $lastValue = $juminUnits[count($juminUnits)-1];
    // $sum = 0;
    // for ($i = 0; $i < count($juminUnits) -1 ;$i++){
    //     $sum += $juminUnits[$i] * $addValues[$i];
    // }
    
    // $result = 11 - ($sum % 11);
    
    // if ($lastValue - $result != 0){
    //     return false;
    // }    
    
    /*
// 생년 월일 검사
    $j = substr($jumin, 0, 2); 
    $age = date('Y')-($j+($j<39?2000:1900))+1; 

    if ($age < 19){
        return false;
    }
*/
    
    return true;
    
}

function check_jumin($juminstr) {
    if(strlen($juminstr) != 13 ) {
        return false; 
    } 

    $jumin1 = substr($juminstr, 0,6);
    $jumin2 = substr($juminstr, 6);

    $jumin = $jumin1 . $jumin2; 
    for ($i=$sum=0;$i<12;$i++) {
        $sum += intval($jumin[$i]) * (($i % 8) + 2); 
    } 
    // 
    // Checksum 
    // 

    if ((11 - ($sum % 11)) % 10 != intval($jumin[12])) {
        return false; 
    } 

    // 
    // 총13자리의 주민등록번호 중 7번째의 숫자는 1부터 4까지의 값을 갖는다. 
    // 이 값에 따라 성별 및 Y2K가 확인된다. 
    // 

    $a = intval($jumin[6]); if ($a < 1 || 4 < $a) {
        return false; 
    } 

    $sex = ($a % 2) ? "M" : "F"; $year = ($a < 3) ? 1900 : 2000; 
    
    // 
    // 출생년 확인 
    // 

    $year += intval(substr($jumin1, 0, 2)); 

    // 
    // 출생월 확인 
    // 

    $month = intval(substr($jumin1, 2, 2)); if($month < 1 || 12 < $month) {
        return false; 
    } 

    // 
    // 출생일 확인(각 달에 따른 날수 차이 및 윤달 고려) 
    // 

    $day = intval(substr($jumin1, 4, 2)); 
    $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 

    // 
    // 2월생이면 윤달 확인 
    // 

    if (2 == $month && check_leapyear($year)) { 
        $days[1] = 29; 
    } 

    if(($day < 1) || ($days[$month-1] < $day)) {
        return false; 
    } 

    // 
    // 반환값 
    // 
    // 남성이면 "yyyymmddM", 여성이면 "yyyymmddF" 
    // 

    return "$year" . substr($jumin1, 2) . $sex; 

} 

function check_leapyear($year){
    if ($year%4 != 0){
        return false;
    }

    if ($year%100 == 0){
        return true;
    }

    if ($year%400 ==0){
        return true;
    }

    return false;
}

?>