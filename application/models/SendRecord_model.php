<?php

/**
 * Project:     乐恒互动支付系统
 * File:        SendRecord_model.php
 *
 * <pre>
 * 描述：充值成功后发给游戏的成功记录
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you PaymentSDk, Inc.
 */
class SendRecord_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'send_record';
        parent::__construct();
    }

    /**
     * 发送给游戏的订单记录
     * @param int $param 基本信息数组
     * @return array
     */
    function insertSendRecord($param) {
        $data = array(
            'appid' => $param['appid'],
            'userid' => $param['userid'],
            'paytype' => $param['paytype'],
            'status' => $param['status'],
            'out_trade_no' => $param['out_trade_no'],
            'transaction_id' => $param['transaction_id'],
            'create_time' => $param['create_time'],
            'channelid' => $param['channelid'],
            'body' => $param['body'],
            'total_fee' => $param['total_fee'],
            'attach' => $param['attach'],
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
