<?php

/**
 * Project:     乐恒互动支付系统
 * File:        Alipay_Result_model.php
 *
 * <pre>
 * 描述：支付宝订单记录表（成功失败都会记录）
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you PaymentSDk, Inc.
 */
class Alipay_Result_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'alipay_result';
        parent::__construct();
    }

    /**
     * 支付宝订单记录
     * @param int $param 基本信息数组
     * @return array
     */
    function insertOrderInfo($param) {
        $data = array(
            'notify_time' => $param['notify_time'],
            'notify_type' => $param['notify_type'],
            'notify_id' => $param['notify_id'],
            'sign' => $param['sign'],
            'out_trade_no' => $param['out_trade_no'],
            'subject' => $param['subject'],
            'payment_type' => $param['payment_type'],     
            'trade_no' => $param['trade_no'],
            'trade_status' => $param['trade_status'],
            'buyer_id' => $param['buyer_id'],
            'buyer_email' => $param['buyer_email'],
            'total_fee' => $param['total_fee'],
            'quantity' => $param['quantity'],
            'price' => $param['price'],
            'body' => $param['body'],
            'gmt_create' => $param['gmt_create'],
            'gmt_payment' => $param['gmt_payment'],
            'refund_status' => $param['refund_status'],
            'gmt_refund' => $param['gmt_refund'],
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
       
    }
    
}
