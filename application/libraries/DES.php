<?php
/**
 * Project:     用户系统SDK加密解密类
 * File:        DES.php
 *
 * <pre>
 * 描述:DES加密解密
 * </pre>
 *
 * @category  PHP
 * @package   Include
 * @author    liyang <yang.li@joy4you.com>
 * @copyright 2015 joy4you, Inc.
 * @license   BSD Licence
 * @link      http://example.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class DES
{
    /**
     * 密钥
     * @var string
     */
    private $_key;
    
    /**
     * Mcrypt 资源
     * @var string
     */
    private $_resource;

    /**
     * 加密iv
     * @var string
     */
    private $_iv;

    /**
     * 构造函数
     * 
     * @param string $key 加密key
     * @param string $iv  加密iv
     */
    public function __construct($param)
    {
        $this->_key = $param[0];
        $this->_iv = $param[1];
        $this->_resource = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
        mcrypt_generic_init($this->_resource, $this->_key, $this->_iv);
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        mcrypt_module_close($this->_resource);
    }

    /**
     * 加密字符串
     * @param string $toencrypt 需要加密的字符串
     * @return string 加密后的字符串
     */
    public function encrypt($toencrypt)
    {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this->_pkcs5Pad($toencrypt, $size);
        //$result1 = @strtoupper(bin2hex(mcrypt_cbc(MCRYPT_DES, $this->_key, $str, MCRYPT_ENCRYPT, $this->_iv)));
        $result = mcrypt_generic($this->_resource, $str);
        $result = strtoupper(bin2hex($result));
        return $result;
    }

    /**
     * 生成token
     * @param string  $text      需要加密的字符串
     * @param integer $blocksize 填充间隔
     * @return string 密匙
     */
    private function _pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    /**
     * 解密字符串
     * @param string $decrypt 需要解密的字符串
     * @return string 加密后的字符串
     */
    public function decrypt($decrypt)
    {
        $key = $this->_key;
        $iv = $this->_iv;
        $strBin = $this->_hex2bin(strtolower($decrypt));
        $decrypt = mcrypt_decrypt(MCRYPT_DES, $key, $strBin, 'cbc', $iv);
        $decrypt = $this->_pkcs5Unpad($decrypt);
        return $decrypt;
    }
    
    /**
     * 转成十进制数
     * @param string $hexData 需要解密的字符串
     * @return string 密匙
     */
    private function _hex2bin($hexData) 
    {
        $binData = "";
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }
    /**
     * 解密token
     * @param string $text 需要解密的字符串
     * @return string 密匙
     */
    private function _pkcs5Unpad($text)
    {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, - 1 * $pad);
    }
}

/* End of file DES.php */


/* End of file DES.php */
