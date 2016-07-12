<?php

/**
 * Project:     乐恒互动用户系统
 * File:        PhoneCode_model.php
 *
 * <pre>
 * 描述：发送手机短信记录表
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you UserSDk, Inc.
 */
class PhoneCode_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'phone_code';
        parent::__construct();
    }

    /**
     * 手机短信验证码记录表
     * @param int $param 基本信息数组
     * @return array
     */
    function insertPhoneRecord($param) {
        $data = array(
            'phone' => $param['phone'],
            'code' => $param['code'],
            'code_flag' => $param['code_flag'],
            'codetime' => $param['codetime'],
            'ckid' => $param['ckid'],
            'ip' => $param['ip'],
            'remark' => $param['remark'],
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 根据手机号查看发送时间
     * @param int $phone 手机号
     * @return array
     */
    function querySendCodeTime($phone) {
        $sql = "select * from " . $this->db_name . " where phone = '$phone' order by codetime desc LIMIT 1";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    /**
     * 修改验证码状态
     * @param String $phone 手机号
     * @return array
     */
    function updCodeFlag($phone) {
        $updata['code_flag'] = 0;
        $this->db->where('phone', $phone);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    /**
     * 修改验证码状态(通过手机号和验证码)
     * @param String $phone 手机号
     * @return array
     */
    function updCodeFlagByPhoneCode($phone, $code) {
        $updata['code_flag'] = 0;
        $this->db->where('phone', $phone);
        $this->db->where('code', $code);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    /**
     * 检查验证码是否正确
     * @param int $phone 手机号
     * @param int $code 验证码
     * @return array
     */
    function checkCode($phone, $code) {
        $sql = "select * from " . $this->db_name . " where phone = '$phone' and code = '$code' and code_flag = 1";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    
    /**
     * 检查token是否存在
     * @param int $phone 手机号
     * @param int $token 验证码
     * @return array
     */
    function checkToken($phone, $token) {
        $sql = "select * from " . $this->db_name . " where phone = '$phone' and remark = '$token'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
}
