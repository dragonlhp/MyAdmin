<?php
/** 
 *加密字符串 
 * 
 * 
 * @author      libin<hansen.li@silksoftware.com> 
 * @version     1.0 
 * @since       1.0 
 */  
namespace common\utils;

use Yii;


class UtilEncryption
{
   /**
     * 采用HashHmac方法获取加密的字符串
     * @param unknown $data  需要加密的字符串
     * @param string  $secret  加密秘钥
     * @return string|boolean
     */
    public static function encryptHashHmac($data, $secret) {
        $str=hash_hmac('sha256', $data,$secret, $raw = false);
        //$str=base64_encode($str);
        return $str;
    }

    /**
     * 采用md5方法获取加密的字符串
     * @param unknown $data  需要加密的字符串
     * @param string  $secret  加密秘钥
     * @return string|boolean
     */
    public static function encryptMd5($data, $secret="") {
        $str=md5($data.$secret);
        return $str;
    }
}
