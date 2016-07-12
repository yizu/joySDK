<?php
/**
 * Project:     手机搜房
 * File:        RSA.php
 *
 * <pre>
 * 描述:RSA加解密，用于用户密码解密
 * </pre>
 *
 * @category  PHP
 * @package   Include
 * @author    yueyanlei <yueyanlei@soufun.com>
 * @copyright 2014 Soufun, Inc.
 * @license   BSD Licence
 * @link      http://example.com
 */

/**
 * RSA加解密
 *
 * Long description for file (if any)...
 *
 * @category  PHP
 * @package   Include
 * @author    yueyanlei <yueyanlei@soufun.com>
 * @copyright 2014 Soufun, Inc.
 * @license   BSD Licence
 * @link      http://example.com
 */
class RSA
{
    /**
     * 私钥
     * @var string
     */
    private static $_PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDGpWXbBPHcZKtMCuqChvmCFctrZ+/7ZVywMs4FvKS+bc3H2qqn
M6AuOT0hk7ebmv0tR3Exgs6JsHjonxPLKUZ4qf2sJ2ysfhpi3roNNAbRPLwsjQ2f
/Ug3i0eI+ariCb1gsivrJpJvLaLWcN7mRMqXgdhwHcMDdQBqd2MOhnHI3QIDAQAB
AoGAASbcXFS/AkQjKiG2EmOt9q8hqtHDdnWz/+GLiET7v47rbok6DBYki6ARVqyA
mApiBW0wntTfVbUMPm0NtFPc8LuNEw/X8F4yZly+qU9o+qnx2MYHu0mmm1wDv2ou
MULgsKPEG045NVBDR/wEH9tihtPW1N/0EcKiMSarnOF3XxECQQDufcbzc/AZTw57
OfgqKSmc7OsmeWB5dP/tTP29fnZ4tVg7kdrnGw4Z2KwzEwvsRZYaa8oD8/4SwMIN
vUBeYINjAkEA1TrEP2x3liEvPRWaYT/2fyD2B+xJXNKJY0vFOGViwuq3UF9nmr3B
GMdNvxIAZxUH5PWZchlRtdFRmaNpuWnWvwJBAL6KLtGC52jRCLja77J/gIenoZfz
kWh4WaC1ymQDDZQTDpNJTKMnsnRj7/A+X2A9mFczlwrhfTRuXJutgCfm5BECQH93
HuOvKpnDgqKobF4gR3FdudWoqX5kmR6Tp/T7nptYhnb0YVG+h1URp2dGEpmMl+iF
7NGpUxA2bepDJqthGq8CQHhWqa3eb8gVgH8hhr+2F0dC7p5z1rBmfr/4Q/J9IiE5
PhNscaMsaZ9+0z/ZBIa4Q1YTiUjmR2QCpt9wd7sm9Tc=
-----END RSA PRIVATE KEY-----';

    /**
     * 公钥
     * @var string
     */
    private static $_PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDGpWXbBPHcZKtMCuqChvmCFctr
Z+/7ZVywMs4FvKS+bc3H2qqnM6AuOT0hk7ebmv0tR3Exgs6JsHjonxPLKUZ4qf2s
J2ysfhpi3roNNAbRPLwsjQ2f/Ug3i0eI+ariCb1gsivrJpJvLaLWcN7mRMqXgdhw
HcMDdQBqd2MOhnHI3QIDAQAB
-----END PUBLIC KEY-----';

    /**
     * 返回对应的私钥
     * @return resource 处理后的私钥
     */
    private static function _getPrivateKey()
    {
        $privKey = self::$_PRIVATE_KEY;
        return openssl_pkey_get_private($privKey);
    }

    /**
     * 返回对应的公钥
     * @return resource 处理后的私钥
     */
    private static function _getPublicKey()
    {
        $pubkey = self::$_PUBLIC_KEY;
        return openssl_pkey_get_public($pubkey);      
    }

    /**
     * 私钥加密
     * @param string $data 数据
     * @return string/null
     */
    public static function privEncrypt($data)
    {
        if (!is_string($data)) {
            return null;
        }           
        return openssl_private_encrypt($data, $encrypted, self::_getPrivateKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * 私钥解密
     * @param string  $encrypted 密文（二进制格式且base64编码）
     * @param boolean $fromjs    密文是否来源于JS的RSA加密
     * @return string
     */
    public static function privDecrypt($encrypted, $fromjs = false)
    {
        if (!is_string($encrypted)) {
            return null;
        }
        $padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;  
        if (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::_getPrivateKey(), $padding)) {  
            return $fromjs ? trim(strrev($decrypted)) : $decrypted;  
        }  
        return null; 
    }

    /**
     * 公匙加密
     * @param string $data 加密字符串
     * @return string/null
     */
    public static function encrypt($data)
    {
        if (openssl_public_encrypt($data, $encrypted, self::_getPublicKey())) 
            $data = base64_encode($encrypted);  
        else  
            return '';
  
        return $data;  
    }
}

/* End of file RSA.php */
