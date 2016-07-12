<?php
/**
 * Project: 乐恒互动支付SDK
 * File: Output.php
 *
 * <pre> 
 * 描述：乐恒互动SDK Output基类
 * </pre>
 *
 * @category  PHP
 * @package   Include
 * @author    liyang <yang.li@soufun.com>
 * @copyright 2015 Soufun, Inc.
 * @license   BSD Licence
 * @link      http://example.com
 */

class MyOutPut
{
    /**
     * 构造函数
     */
    public function __construct()
    {
    }

    /**
     * 输出数据，根据输出格式，输出相应格式的数据
     * @param array  $data   数据数组
     * @param string $format 数据格式 json/xml
     * @param string $root   xml根节点名称
     * @return null
     */
    public static function outputData($data, $format = 'json', $root = 'data')
    {
        if ($format == 'json') {
            $data = self::convertData($data, "UTF-8", "UTF-8");
            header('Content-type: application/json');
            $output = json_encode($data);
        } else {
            $xml = self::arrayToXml($data, $root);
            header("Content-type: text/xml; charset=GBK");
            $output = "<?xml version=\"1.0\" encoding=\"GBK\"?>\r\n".$xml;
        }
        echo $output;
    }

    /**
     * 将数组转换成XML
     * @param mixed   $data          数据
     * @param string  $root          node值
     * @param boolean $ignore_numkey 是否忽略数值键做节点名
     * @return string
     */
    public static function arrayToXml($data, $root = 'data', $ignore_numkey=true)
    {
        $root = trim($root);
        if (strlen($root) == 0) {
            return '';
        }
        if (is_object($data)||is_resource($data)) {
            return '';
        }
        $xml = '';
        if (true !== $ignore_numkey || !is_numeric($root))
            $xml = "<".$root.">";
        if (!is_array($data)) {
            $xml .= Htmlspecialchars_For_php54($data);
        } else {
            $xml .= "\r\n";
            foreach ($data as $k=>$v) {
                $xml .= self::arrayToXml($v, $k, $ignore_numkey);
            }
        }
        if (true !== $ignore_numkey || !is_numeric($root))
            $xml .= "</".$root.">\r\n";
        return $xml;
    }

    /**
     * 将数据从$from编码转换成$to编码
     * @param mixed  $data 要转码的数据
     * @param string $from 源编码
     * @param string $to   目标编码
     * @return mixed
     */
    protected static function convertData($data, $from, $to)
    {
        $return = $data;
        if (is_array($data)) {
            foreach ($data as $key=>$val)
                $return[$key] = self::convertData($val, $from, $to);
        } else {
            $return = iconv($from, $to.'//IGNORE', $data);
        }
        return $return;
    }
}

/* End of file Output.php */
