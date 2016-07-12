<?php

/**
 * Project:     乐恒互动用户系统
 * File:        UserRegisterOther_model.php
 *
 * <pre>
 * 描述：用户注册记录表
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you UserSDk, Inc.
 */
class EmailProfile_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'email_profile';
        parent::__construct();
    }

    /**
     * 注册，插入用户基本设备信息
     * @param int $param 设备信息数组
     * @return array
     */
    function insertEmailInfo($email, $token) {
        $data = array(
            'email' => $email,
            'sendtime'=>time(),
            'token'=> $token,
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
    /**
     * 查询token是否存在，如果存在返回种token的时间
     * @param int $token token
     * @return array
     */
    function queryToken($token) {
        $sql = "select sendtime, token from " . $this->db_name . " where token='$token'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['sendtime'];
        } else {
            return FALSE;
        }
    }
    
    
    /**
     * 第二次点击链接 token失效 （重置token）
     * @param int $token 用户名
     * @param int $newtoken 用户名
     * @return array
     */
    function updToken($token, $newtoken) {
        $updata['token'] = $newtoken;
        $this->db->where('token', $token);
        $tag = $this->db->update($this->db_name, $updata);
        if ($tag) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
   
}
