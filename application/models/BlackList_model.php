<?php

/**
 * Project:     乐恒互动用户系统
 * File:        BlackList_model.php
 *
 * <pre>
 * 描述：黑名单记录表
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you UserSDk, Inc.
 */
class BlackList_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'black_list';
        parent::__construct();
    }

    /**
     * 查看设备是否被封停
     * @param int $ckid 设备号
     * @return array
     */
    function queryCkidFlag($ckid) {
        $sql = "select * from " . $this->db_name . " where ckid = '$ckid' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    /**
     * 修改设备封停状态
     * @param String $ckid 设备号
     * @return array
     */
    function updCkidFlag($ckid) {
        $updata['ckidflag'] = 1;
        $this->db->where('ckid', $ckid);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    /**
     * 查看用户是否被封停
     * @param int $account 账号
     * @return array
     */
    function queryAccountFlag($account) {
        $sql = "select * from " . $this->db_name . " where account = '$account' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    
    /**
     * 修改设备封停状态
     * @param String $account 账号
     * @return array
     */
    function updAccountFlag($account) {
        $updata['ckidflag'] = 1;
        $this->db->where('account', $account);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    
    /**
     * 查看用户是否被封停通过userid
     * @param int $userid 账号
     * @return array
     */
    function queryAccountFlagByUid($userid) {
        $sql = "select * from " . $this->db_name . " where uid = '$userid' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    
    /**
     * 修改用户封停状态通过用户ID
     * @param String $userid 用户ID
     * @return array
     */
    function updAccountFlagByUid($userid) {
        $updata['ckidflag'] = 1;
        $this->db->where('uid', $userid);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
}
