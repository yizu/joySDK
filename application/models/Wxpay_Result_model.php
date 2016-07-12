<?php

/**
 * Project:     乐恒互动支付系统
 * File:        Wxpay_Result_model.php
 *
 * <pre>
 * 描述：微信订单记录表（成功失败都会记录）
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you PaymentSDk, Inc.
 */
class Wxpay_Result_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'wxpay_result';
        parent::__construct();
    }

    /**
     * 支付宝订单记录
     * @param int $param 基本信息数组
     * @return array
     */
    function insertOrderInfo($param) {
        $data = array(
            'appid' => $param['appid'],
            'attach' => $param['attach'],
            'bank_type' => $param['bank_type'],
            'cash_fee' => $param['cash_fee'] / 100,
            'fee_type' => $param['fee_type'],
            'is_subscribe' => $param['is_subscribe'],
            'mch_id' => $param['mch_id'],     
            'openid' => $param['openid'],
            'out_trade_no' => $param['out_trade_no'],
            'time_end' => $param['time_end'],
            'total_fee' => $param['total_fee'] / 100,
            'transaction_id' => $param['transaction_id'],
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
       
    }
    
}
