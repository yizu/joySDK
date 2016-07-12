<?php

/**
 * Project:     乐恒互动支付系统下单时签名获取
 * File:        ForgotController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李杨 <768216362@qq.com>
 * @copyright 2015 SDk, Inc.
 */
class Sign extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $in_conf['key'] = "562asd32";
        $array = array($in_conf['key'], $in_conf['key']);
        $this->load->library('DES', $array);
    }

    //下单时的前面从服务端获取
    public function getSign() {
        $result = array(
            'status' => 200,
            'msg' => '获取签名key成功',
            'data' => 'Goy4You!@#123',
        );
        $input = urldecode(json_encode($result));
        $token = $this->des->encrypt($input);
        echo $token;
        exit;
    }
}
