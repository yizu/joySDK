<?php

/**
 * Project:     乐恒互动用户系统
 * File:        UserRegister_model.php
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
class UserRegister_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'user_register';
        parent::__construct();
    }

    /**
     * 查看用户名是否存在
     * @param int $username 用户名
     * @param int $ckid 设备加密串
     * @return array
     */
    function queryUserName($username) {
        $sql = "select username from " . $this->db_name . " where username='$username'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['username'];
        } else {
            return FALSE;
        }
    }
    
    /**
     * 查看电话号是否已经注册过
     * @param int $phone 手机号
     * @return array
     */
    function queryPhone($phone) {
        $sql = "select phone from " . $this->db_name . " where phone='$phone'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['phone'];
        } else {
            return FALSE;
        }
    }

    /**
     * 注册，插入用户基本信息
     * @param int $param 基本信息数组
     * @return array
     */
    function insertUserInfo($param) {
        $data = array(
            'username' => $param['username'],
            'password' => md5($param['password'] . 'joy4you'),
            'phone' => $param['phone'],
            'email' => $param['email'],
            'type' => $param['type'],
            'appid' => $param['appid'],
            'cuid' => $param['cuid'],
            'ckid' => $param['ckid'],
            'token' => $param['token'],
            'tokentime' => time(),
            'tuid' => $param['tuid'],
            'thd' => $param['thd'],
            'tun' => $param['tun'],
            'tph' => $param['tph'],
            'sessionid' => $param['sessionid'],
            'sidtime' => time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            $retrunData = array(
                'username' => $param['username'],
                'password' => md5($param['password'] . 'joy4you'),
                'phone' => $param['phone'],
                'email' => $param['email'],
                'ckid' => $param['ckid'],
                'token' => $param['token'],
                'sessionid' => $param['sessionid'],
                'sidtime' => time(),
            );
            return $retrunData;
        } else {
            return FALSE;
        }
    }

    /**
     * 游客注册
     * @param int $param 基本信息数组
     * @return array
     */
    function insertTouristInfo($param) {
        if ($param['password'] != "") {
            $param['password'] = md5($param['password'] . 'joy4you');
        } else {
            $param['password'] = "";
        }
        $data = array(
            'username' => $param['username'],
            'password' => $param['password'],
            'phone' => $param['phone'],
            'email' => $param['email'],
            'type' => $param['type'],
            'appid' => $param['appid'],
            'cuid' => $param['cuid'],
            'ckid' => $param['ckid'],
            'token' => $param['token'],
            'tokentime' => time(),
            'tuid' => $param['tuid'],
            'thd' => $param['thd'],
            'tun' => $param['tun'],
            'tph' => $param['tph'],
            'sessionid' => $param['sessionid'],
            'sidtime' => time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    /**
     * 查询用户ID通过用户名密码
     * @param int $param 用户名、密码
     * @return array
     */
    function queryUserId($param) {
        $username = $param['username'];
        $password = $param['password'];
        $sql = "select id from " . $this->db_name . " where username='$username' and password='$password' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }
    
    
    /**
     * 查询用户ID通过手机号
     * @param int $phone 手机号
     * @return array
     */
    function queryUserIdByPhone($phone) {
        $sql = "select id from " . $this->db_name . " where phone='$phone' ";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 用户名登录
     * @param String $username 用户名
     * @param String $password 密码
     * @return array
     */
    function queryUserInfoByUsername($username, $password) {

        $this->db->where('username', $username);
        $this->db->where('password', md5($password . 'joy4you'));
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if (empty($user)) {
            return false;
        }
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    
    
    /**
     * 手机号登录
     * @param String $phone 手机号
     * @param String $password 密码
     * @return array
     */
    function queryUserInfoByPhone($phone, $password) {

        $this->db->where('phone', $phone);
        $this->db->where('password', md5($password . 'joy4you'));
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if (empty($user)) {
            return false;
        }
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * 游客登录
     * @param String $ckid 设备加密信息
     * @return array
     */
    function queryUserInfoByCkid($ckid) {

        $this->db->where('ckid', $ckid);
        $query = $this->db->get($this->db_name);
        foreach ($query->result() as $row) {
            if ($row->username == "" && $row->phone == "") {
                $data['userid'] = $row->id;
                $data['ckid'] = $row->ckid;
                $data['sessionid'] = $row->sessionid;
                $data['token'] = $row->token;
                break;
            }
        }
        if (isset($data)) {
            return $data;
        } else {
            return false;
        }
        
    }

    /**
     * 绑定用户名和密码
     * @param String $param 参数数组
     * @return array
     */
    function bindUserName($param) {
        $flag = "";
        $username = $param['username'];
        //先判断传过来的用户名是否在库中存在，如果存在，则直接返回不能绑定
        $sql = "select username from " . $this->db_name . " where username='$username'";
        $queryusername = $this->db->query($sql);
        if ($queryusername->num_rows() > 0) {
            $flag = 3;
            return $flag;
        } else {
            $this->db->where('id', $param['userid']);
            $this->db->where('ckid', $param['ckid']);
            $query = $this->db->get($this->db_name);
            $row = $query->row_array();
            if (isset($row)) {
                if ($row['username'] == $param['username']) {
                    $flag = 1;
                    return $flag;
                } else {
                    $updata['username'] = $param['username'];
                    $updata['password'] = md5($param['password'] . 'joy4you');
                    $this->db->where('id', $param['userid']);
                    $this->db->where('ckid', $param['ckid']);
                    $tag = $this->db->update($this->db_name, $updata);
                    if ($tag) {
                        return $row;
                    }
                }
            } else {
                $flag = 2;
                return $flag;
            }
        }
    }
    
    /**
     * 游客绑定手机号
     * @param String $param 参数数组
     * @return array
     */
    function bindPhone($param) {
        $flag = "";
        $phone = $param['phone'];
        //先判断传过来的用户名是否在库中存在，如果存在，则直接返回不能绑定
        $sql = "select phone from " . $this->db_name . " where phone='$phone'";
        $queryphone = $this->db->query($sql);
        if ($queryphone->num_rows() > 0) {
            $flag = 3;
            return $flag;
        } else {
            $this->db->where('id', $param['userid']);
            $this->db->where('ckid', $param['ckid']);
            $query = $this->db->get($this->db_name);
            $row = $query->row_array();
            if (isset($row)) {
                if ($row['phone'] == $param['phone']) {
                    $flag = 1;
                    return $flag;
                } else {
                    $updata['phone'] = $param['phone'];
                    $this->db->where('id', $param['userid']);
                    $this->db->where('ckid', $param['ckid']);
                    $tag = $this->db->update($this->db_name, $updata);
                    if ($tag) {
                        return $row;
                    }
                }
            } else {
                $flag = 2;
                return $flag;
            }
        }
    }
    
    

    /**
     * 用户名绑定邮箱
     * @param String $param 参数数组
     * @return array
     */
    function bindEmail($param) {
        $flag = "";
        $this->db->where('username', $param['username']);
        $this->db->where('password', md5($param['password'] . 'joy4you'));
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if ($user) {
            if ($user['email'] != "") {
                $flag = 1;
                return $flag;
            } else {
                $updata['email'] = $param['email'];
                $this->db->where('username', $param['username']);
                $this->db->where('password', md5($param['password'] . 'joy4you'));
                $tag = $this->db->update($this->db_name, $updata);
                if ($tag) {
                    return $user;
                }
            }
        } else {
            $flag = 2;
            return $flag;
        }
    }
    
    /**
     * 手机号绑定邮箱
     * @param String $param 参数数组
     * @return array
     */
    function phoneBindEmail($param) {
        $flag = "";
        $this->db->where('phone', $param['phone']);
        $this->db->where('password', md5($param['password'] . 'joy4you'));
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if ($user) {
            if ($user['email'] != "") {
                $flag = 1;
                return $flag;
            } else {
                $updata['email'] = $param['email'];
                $this->db->where('phone', $param['phone']);
                $this->db->where('password', md5($param['password'] . 'joy4you'));
                $tag = $this->db->update($this->db_name, $updata);
                if ($tag) {
                    return $user;
                }
            }
        } else {
            $flag = 2;
            return $flag;
        }
    }
    

    /**
     * 通过用户名查邮箱
     * @param String $username 用户名
     * @param String $email    邮箱
     * @return array
     */
    function queryEmailbyUserName($username, $email) {
        $flag = "";
        $this->db->where('username', $username);
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if ($user && $user['email']) {
            if ($user['email'] != $email) {
                $flag = 1;
            } else {
                $flag = 0;
            }
        } else {
            $flag = 2;
        }
        return $flag;
    }
    
    
    /**
     * 通过手机号查邮箱
     * @param String $phone   手机号
     * @param String $email    邮箱
     * @return array
     */
    function queryEmailbyPhone($phone, $email) {
        $flag = "";
        $this->db->where('phone', $phone);
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if ($user && $user['email']) {
            if ($user['email'] != $email) {
                $flag = 1;
            } else {
                $flag = 0;
            }
        } else {
            $flag = 2;
        }
        return $flag;
    }

    /**
     * 通过手机号改密码
     * @param String $phone 用户名
     * @param String $password 密码
     * @return array
     */
    function phoneUpdPwd($phone, $password) {
        $updata['password'] = md5($password . 'joy4you');
        $this->db->where('phone', $phone);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    /**
     * 通过用户名改密码
     * @param String $username 用户名
     * @param String $password 密码
     * @return array
     */
    function updpwd($username, $password) {
        $updata['password'] = md5(md5($password) . 'joy4you');
        $this->db->where('username', $username);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    /**
     * 通过手机号改密码
     * @param String $phone 用户名
     * @param String $password 密码
     * @return array
     */
    function updpwdByPhone($phone, $password) {
        $updata['password'] = md5(md5($password) . 'joy4you');
        $this->db->where('phone', $phone);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
      
    /**
     * 查看session 是否合法，如果合法则返回用户信息
     * @param int $sessionid  自动登陆标识
     * @return array
     */
    function querySessionid($sessionid) {
        $sql = "select * from " . $this->db_name . " where sessionid='$sessionid'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }
    }
    
    
    /**
     * 修改sessionid
     * @param String $oldsessionid  旧自动登陆标识
     * @param String $sessionid  自动登陆标识
     * @return array
     */
    function updSessionid($oldsessionid, $sessionid) {
        $updata['sessionid'] = $sessionid;
        $updata['sidtime'] = time();
        $this->db->where('sessionid', $oldsessionid);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }
    
    
    /**
     * CP验证token
     * @param int $token token
     * @return array
     */
    function checkToken($token) {
        $sql = "select id, tokentime, token from " . $this->db_name . " where token='$token'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }
    }
    
    
    /**
     * 修改token
     * @param String $oldtoken  旧自动登陆标识
     * @param String $token  自动登陆标识
     * @return array
     */
    function updToken($oldtoken, $token) {
        $updata['token'] = $token;
        $updata['tokentime'] = time();
        $this->db->where('token', $oldtoken);
        $tag = $this->db->update($this->db_name, $updata);
        return $tag;
    }

}
