<?php

/**
 * Project:     乐恒互动支付系统
 * File:        PayLog_model.php
 *
 * <pre>
 * 描述：订单记录表（所有下单记录都会记录在此表中）
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you PaymentSDk, Inc.
 */
class PayLog_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'paylog';
        parent::__construct();
    }

    /**
     * 下单，记录生成单号
     * @param int $param 基本信息数组
     * @return array
     */
    function insertPayLog($param) {
        if ($param['paytype'] == 1) {
            $param['payname'] = "支付宝";
        } else if($param['paytype'] == 2) {
            $param['payname'] = "微信";
        } else if($param['paytype'] == 3) {
            $param['payname'] = "银联";
        }
        $data = array(
            'uid' => $param['userid'],
            'appid' => $param['appid'],
            'ckid' => $param['ckid'],
            'channelid' => $param['channelid'],
            'paytype' => $param['paytype'],
            'payname' => $param['payname'],
            'out_trade_no' => $param['out_trade_no'],     
            'body' => $param['body'],
            'goods_type' => $param['goods_type'],
            'detail' => $param['detail'],
            'fee_type' => $param['fee_type'],
            'total_fee' => $param['total_fee'],
            'attach' => $param['attach'],
            'status' => 0,    //默认是0   未支付状态的订单为0，支付成功后变成1
            'create_time' => time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
     * 查询商户订单信息
     * @param String $out_trade_no 商户订单号
     * @return array
     */
    function queryOrderInfo($out_trade_no) {

        $this->db->where('out_trade_no', $out_trade_no);
        $query = $this->db->get($this->db_name);
        $row = $query->row();
        if ($row) {
            return $row;
        } else {
            return FALSE;
        }
        
    }
    /**
     * 更改订单信息（插入流水号并且更改订单状态）
     * @param String $out_trade_no 商户订单号
     * @param String $transaction_id 交易流水号
     * @param String $transaction_status 第三方交易状态
     * @return array
     */
    function updateOrderInfo($out_trade_no, $transaction_id, $transaction_status) {
        $updata['status'] = 1;
        $updata['transaction_id'] = $transaction_id;
        $updata['transaction_status'] = $transaction_status;
        $updata['end_time'] = time();
        $this->db->where('out_trade_no', $out_trade_no);
        $flag = $this->db->update($this->db_name, $updata);
        if ($flag) {
            return $flag;
        } else {
            return false;
        }
    }
    
    /**
     * 查询商户订单状态
     * @param String $out_trade_no 商户订单号
     * @return array
     */
    function queryOrderStatus($out_trade_no) {

        $this->db->where('out_trade_no', $out_trade_no);
        $query = $this->db->get($this->db_name);
        $row = $query->row();
        if ($row) {
            return $row->status;
        } else {
            return FALSE;
        }
        
    }

    
    
}
