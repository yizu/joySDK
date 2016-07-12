<?php

/**
 * Project:     乐恒互动支付系统
 * File:        YinLianPay_Result_model.php
 *
 * <pre>
 * 描述：银联订单记录表（成功失败都会记录）
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you PaymentSDk, Inc.
 */
class YinLianPay_Result_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'yinlian_result';
        parent::__construct();
    }

    /**
     * 银联订单记录
     * @param int $param 基本信息数组
     * @return array
     */
    function insertOrderInfo($param) {
        $data = array(
            'version' => $param['version'],
            'encoding' => $param['encoding'],
            'certId' => $param['certId'],
            'signature' => $param['signature'],
            'signMethod' => $param['signMethod'],
            'txnType' => $param['txnType'],
            'txnSubType' => $param['txnSubType'],     
            'bizType' => $param['bizType'],
            'accessType' => $param['accessType'],
            'merId' => $param['merId'],
            'orderId' => $param['orderId'],
            'txnTime' => $param['txnTime'],
            'txnAmt' => $param['txnAmt'] / 100,
            'queryId' => $param['queryId'],
            'respCode' => $param['respCode'],
            'respMsg' => $param['respMsg'],
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
       
    }
    
}
