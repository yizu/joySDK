<?php

/**
 * Project:     乐恒互动用户系统
 * File:        UserProfile_model.php
 *
 * <pre>
 * 描述：用户登陆记录表
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you UserSDk, Inc.
 */
class UserProfile_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'user_profile';
        parent::__construct();
    }

    /**
     * 注册时插入用户的登陆时间
     * @param int $userid 用户id
     * @return array
     */
    function insertUserprofile($userid) {
        $data = array(
            'uid' => $userid,
            'lastlogintime' => time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 更改最后登陆时间
     * @param int $userid 用户id
     * @return array
     */
    function updateUserProfile($userid) {
        
        $updata['lastlogintime'] = time();
        $this->db->where('uid', $userid);
        $tag = $this->db->update($this->db_name, $updata);
        if ($tag) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
