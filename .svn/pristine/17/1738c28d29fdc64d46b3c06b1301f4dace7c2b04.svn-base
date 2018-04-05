<?php
//×Ô¶¨Òåº¯Êý
function skip($url) 
{  
    header('Location:'.$url);
    exit;
}
function sub_math($num,$scale=0)
{
    $num = sprintf("%.".$scale."f",substr(sprintf("%.".($scale+1)."f", $num), 0, -1)); 
    $num = number_format($num,$scale,'.','');
    return $num;
}
function unicode2utf8($str)//Unicode×ªutf8
{
    if(!$str) return $str;
    $decode = json_decode($str);
    if($decode) return $decode;
    $str = '["' . $str . '"]';
    $decode = json_decode($str);
    if(count($decode) == 1)
    {
        return $decode[0];
    }
    return $str;
}
function get_lastday($date)//»ñÈ¡ÔÂ·ÝµÄ×îºóÒ»Ìì
{
    return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
}
//×Ö·û´®Ìæ»»
function replace_str($str,$start,$length=-1,$relpace="*")
{
    if(mb_strlen($str,"UTF-8") <= $start || mb_strlen($str,"UTF-8") <= $length)
    {
        return $str;
    }
    $tmp = "";
    for($i = 0;$i < $length;$i++)
    {
        $tmp .= $relpace; 
    }
    $str = substr_replace($str,$tmp,$start,$length);
    return $str;
}
//ÅÐ¶ÏÊÖ»úºÅÂëÊÇ·ñÕýÈ·
function is_mobile_num($mobile_num)
{
    if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$mobile_num))
    {
        return true;
    }
    return false;
}
//²éÑ¯×Ö¶ÎÎªnullµÄ·µ»Ø¿Õ×Ö·û´®
function if_null($data)
{
    if(is_string($data))
    {
        if(empty($data)) $data = "";
        return $data;
    }
    if(is_array($data))
    {
        foreach($data as $key => $val)
        {
            if(is_array($val))
            {
                $data[$key] = if_null($val);
            }
            else
            {
                if(empty($val)) $data[$key] = "";
            }
        }
        return $data;
    }
    return $data;
}
//È¡µ±Ç°ºÁÃë
function get_ms()
{
    list($t1, $t2) = explode(' ', microtime());
    $ms = (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    return $ms;
}
//×Ö·û´®½ØÈ¡
function str_intercept($str,$len=15)
{
    if(mb_strlen($str,"UTF-8") > $len)
    {
        $str = mb_substr($str,0,$len)."...";
        return $str;
    }
    else
    {
        return $str;
    }
}
//Éú³ÉËæ»úÊý
function create_sequence($length,$en=false)
{
    if($en)
    {
        $str = '345678abcdefhjkmnprstuvwxy';
        $rand = 25;
    } 
    else
    {
        $str = '1234567890';
        $rand = 9;
    }
    $key_len = $length;
    $key = "";
    for ($i=0; $i<$key_len; $i++)
    {
        $key .= $str{rand(0,$rand)};
    }
    return $key;
}
//¶ÔÏó×ª»»³ÉÊý×é
function obj2arr($obj)
{
    if(is_object($obj))
    {
        $obj = (array)$obj;
    }
    if(is_array($obj))
    {
        foreach($obj as $key => $val)
        {
            $key = obj2arr($key);
            $obj[$key] = obj2arr($val);
        }
    }
    return $obj;
}
//»ñÈ¡ÎÄ¼þÉÏ´«ÀàÐÍ
function get_file_ext($filename, $type)
{
    switch ($type)
    {  
        case "application/zip" : $extType = "docx";break;  
        case "application/vnd.ms-office" : $extType = "doc";break;
        case "application/vnd.ms-excel" : $extType = "xls";break;
        case "application/msword" : $extType = "doc";break;  
        case "application/pdf" : $extType = "pdf";break;  
        default :$extType = "type_error";break;  
    }  
    return $extType;  
}
function create_uuid($prefix = "")
{   
    //¿ÉÒÔÖ¸¶¨Ç°×º
    $str = md5(uniqid(mt_rand(), true));   
    $uuid  = substr($str,0,8);   
    $uuid .= substr($str,8,4);   
    $uuid .= substr($str,12,4);   
    $uuid .= substr($str,16,4);   
    $uuid .= substr($str,20,12);   
    return $prefix . $uuid;
}
function notice($url,$data,$method='GET')//·¢ËÍÊý¾Ý
    {
        if(is_array($data))
        {
            $fields = http_build_query($data);
        }
        if(is_string($data))
            $fields = $data;
        $obj_ch = curl_init();
        if(strtoupper($method) == 'GET')
        {
            $url .= '?'.$fields;
            curl_setopt($obj_ch, CURLOPT_URL,$url);
            curl_setopt($obj_ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($obj_ch, CURLOPT_FOLLOWLOCATION,true);
        }
        else if(strtoupper($method) == 'POST')
        {
            $data = http_build_query($data);
            curl_setopt($obj_ch, CURLOPT_URL,$url);
            curl_setopt($obj_ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($obj_ch, CURLOPT_POST, 1);
            curl_setopt($obj_ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($obj_ch, CURLOPT_FOLLOWLOCATION,true); 
        }
        $arr_return = trim(curl_exec($obj_ch));//·µ»Ø½á¹û
        // if (curl_errno($obj_ch)) {
            // echo 'Errno'.curl_error($obj_ch);
        // }
        curl_close($obj_ch);
        if($arr_return)
        {
            return $arr_return;
        }
        else
        {
            return true;
        }
    }
    
function changeStr($keyword){
    $arr = array();
    preg_match_all("/./u",$keyword,$arr);
    $kw = "%";
    for($j = 0;$j < count($arr[0]); $j++){
        $kw = $kw.$arr[0][$j]."%";
    }
    return $kw;
}

    //»ñÈ¡×îÐÂUUID
    function get_newid($table){
        $rs = DB::select("select uuid() as new_id from ".$table." limit 1",array());
        $new_id = $rs[0]->new_id;
        $new_id = str_replace ("-","",$new_id);
        $count = DB::table($table)->where("id",$new_id)->count();
        if($count)
        {
            $this->get_newid();
        }
        else
        {
            return $new_id;
        }
    }
    //»ñÈ¡×î´óµÄ°æ±¾ºÅ
    function max_version($table,$biz_id){
        $rs = DB::select("select max(version) as max_version from ".$table." where enterprise=? limit 1",array($biz_id));
        $max_version = $rs[0]->max_version;
        return $max_version;
    }
    
    //»ñÈ¡×Ö¶ÎµÚÒ»¸öºº×ÖµÄÊ××ÖÄ¸
    function getFirstCharter($str){
        if (empty($str)) {
            return '#';
        }
        if(ord(mb_substr($str,0,1))>0xa0){
            $fchar = ord($str[0]);
            if ($fchar >= ord('A') && $fchar <= ord('z')) {
                return strtoupper($str[0]);
            }
            $s1 = iconv('UTF-8', 'gb2312', $str);
            $s2 = iconv('gb2312', 'UTF-8', $s1);
            $s = $s2 == $str ? $s1 : $str;
            $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
            if ($asc >= -20319 && $asc <= -20284) {
                return 'A';
            }
            if ($asc >= -20283 && $asc <= -19776) {
                return 'B';
            }
            if ($asc >= -19775 && $asc <= -19219) {
                return 'C';
            }
            if ($asc >= -19218 && $asc <= -18711) {
                return 'D';
            }
            if ($asc >= -18710 && $asc <= -18527) {
                return 'E';
            }
            if ($asc >= -18526 && $asc <= -18240) {
                return 'F';
            }
            if ($asc >= -18239 && $asc <= -17923) {
                return 'G';
            }
            if ($asc >= -17922 && $asc <= -17418) {
                return 'H';
            }
            if ($asc >= -17417 && $asc <= -16475) {
                return 'J';
            }
            if ($asc >= -16474 && $asc <= -16213) {
                return 'K';
            }
            if ($asc >= -16212 && $asc <= -15641) {
                return 'L';
            }
            if ($asc >= -15640 && $asc <= -15166) {
                return 'M';
            }
            if ($asc >= -15165 && $asc <= -14923) {
                return 'N';
            }
            if ($asc >= -14922 && $asc <= -14915) {
                return 'O';
            }
            if ($asc >= -14914 && $asc <= -14631) {
                return 'P';
            }
            if ($asc >= -14630 && $asc <= -14150) {
                return 'Q';
            }
            if ($asc >= -14149 && $asc <= -14091) {
                return 'R';
            }
            if ($asc >= -14090 && $asc <= -13319) {
                return 'S';
            }
            if ($asc >= -13318 && $asc <= -12839) {
                return 'T';
            }
            if ($asc >= -12838 && $asc <= -12557) {
                return 'W';
            }
            if ($asc >= -12556 && $asc <= -11848) {
                return 'X';
            }
            if ($asc >= -11847 && $asc <= -11056) {
                return 'Y';
            }
            if ($asc >= -11055 && $asc <= -10247) {
                return 'Z';
            }
            return "#";
        }else{
            return "#";
        }
    }
    
    //¸ß¾«¶ÈÏà¼õ
    function douSub($num1,$num2){
        $rt = doubleval(bcsub ($num1,$num2,3));
        return $rt;
    }
?>