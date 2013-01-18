<?php

if(!class_exists("Secure")){

class Secure {
    
    private static $cryptKey = 'loveholic';
    
    public function sessionValid($id, $key){
        $sessionKey = base64_decode($key);
        
        // descryption
        /* 복호화 후 사용자 아이디와 날짜를 키로부터 추출하여 만료날짜와 아이디를 비교한다 */
        $sessionKey = $this->decrypt($sessionKey, self::$cryptKey);;
        
        $sessionValues = preg_split('/@/',$sessionKey);
        
        $sessionUserId = $sessionValues[0];
        $sessionDate = $sessionValues[1];
        
        $date = new DateTime($sessionDate);
        $curDate = new DateTime();
    
        date_modify($date,'+7 day');
        
        if ($date < $curDate  || $id != $sessionUserId){
            return false;
        }
        return true;
    }
    
    public function getSessionKey($userId){

	    $date = new DateTime();
        $dateStr = $date->format('Y-m-d H:i:s');

        $dateAndId = $userId.'@'.$dateStr;

        // encryption
        $dateStr = $this->encrypt($dateAndId);

        $key = base64_encode($dateStr);
        return $key;
	}

    public function checkSessionValid($id, $key){

        if (!$this->sessionValid($id, $key)){
            return false;
        }

        return true;
    }

    
    
    public function encrypt($sText)  {  
        $sCode = self::$cryptKey;
        
        $cntData  =  strlen($sText)  -  1;  
        $cntCode  =  strlen($sCode)  -  1;  

        $arrData  =  array();  
        $arrCode  =  array();  


        for($i  =  0;$cntData  >=  $i;  $i++)  
                $arrData[$i]  =  $sText[$i];  

        for($i  =  0;$cntCode  >=  $i;  $i++)  
                $arrCode[$i]  =  $sCode[$i];  

        $flag  =  0;  
        $strResult  =  "";  

        for($i  =  0;$cntData  >=  $i;  $i++)  {  

                $strResult  =  $strResult  .  (ord($arrData[$i])  ^  ord($arrCode[$flag]))  .  chr(8);  

                if($flag  ==  $cntCode)  
                        $flag  =  0;  
                else  
                        $flag++;  
        }  

        return  base64_encode($strResult);  
    }
    
    public function decrypt($sText)  {  
        $sCode = self::$cryptKey;
        
        $sText  =  base64_decode($sText);  

        $arrData  =  preg_split('/'.chr(8).'/',  $sText);  
        $arrCode  =  array();  

        $cntData  =  count($arrData)  -  2;  
        $cntCode  =  strlen($sCode)  -  1;  

        for($i  =  0;  $cntCode  >=  $i;  $i++)  
                $arrCode[$i]  =  $sCode[$i];  

        $flag  =  0;  
        $strResult  =  "";  

        for($i  =  0;$cntData  >=  $i;  $i++)  {  
                $strResult  =  $strResult  .  chr((int)($arrData[$i])  ^  ord($arrCode[$flag]));  

                if($flag  ==  $cntCode)  
                        $flag  =  0;  
                else  
                        $flag++;  
        }  

        return  $strResult;  
    }
    
    
}

}
?>
