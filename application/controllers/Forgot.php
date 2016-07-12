<?php

/**
 * Project:     乐恒互动用户系统SDK  找回密码
 * File:        ForgotController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李杨 <768216362@qq.com>
 * @copyright 2015 SDk, Inc.
 */
class Forgot extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserRegister_model');
        $this->load->model('EmailProfile_model');
        $in_conf['key'] = "562asd32";
        $array = array($in_conf['key'], $in_conf['key']);
        $this->load->library('DES', $array);
    }

    //更改密码页面
    public function updpwdpage() {
        $param = $this->input->post_get('param', TRUE);
        if (isset($param)) {
            $temp = explode("_", $param);
        }
        $username = $temp[0];
        $check = $temp[1];
        $flag =  $this->EmailProfile_model->queryToken($check);
        if ($flag) {
            $sendtime = $flag;
        } else {
            echo "重置密码链接已经被使用或者链接无效，请重新点击找回密码，发送邮件";
            exit;
        }
        $time = time();
        $c = $time - $sendtime;
        if ($c/(60*60) > 0.5) {
            echo '该链接已经失效，请重新点击找回密码，发送邮件';
            exit;
        } else {
            $data['username'] = $username;
            $data['token'] = $check;
            $this->load->view('updpwdpage' , $data);
        }
    }
    
    //修改密码
    public function updpwd() {
        $username = $this->input->post_get('username', TRUE);
        $password = $this->input->post_get('password', TRUE);
        $check = $this->input->post_get('check', TRUE);
        if (preg_match("/^(13[0-9]|14[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/", $username)) {
            $tag = $this->UserRegister_model->updpwdByPhone($username, $password);
            if ($tag) {
                //第二次点击链接，token失效
                $newcheck = $this->genToken();
                $a = $this->EmailProfile_model->updToken($check, $newcheck);
                if ($a) {
                    header('Location: http://www.joy4you.com');
                } else {
                    echo '修改密码失败';
                }
            } else {
                echo '修改密码失败';
            }
        } else {
            $tag = $this->UserRegister_model->updpwd($username, $password);
            if ($tag) {
                //第二次点击链接，token失效
                $newcheck = $this->genToken();
                $a = $this->EmailProfile_model->updToken($check, $newcheck);
                if ($a) {
                    header('Location: http://www.joy4you.com');
                } else {
                    echo '修改密码失败';
                }
            } else {
                echo '修改密码失败';
            }
        }
    }
    
    //生成token
    public function genToken($len = 32, $md5 = true) {
        mt_srand((double) microtime() * 1000000);
        $chars = array(
            'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
            'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
            '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
            'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
            '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
            '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
            'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
        );
        $numChars = count($chars) - 1;
        $token = '';
        for ($i = 0; $i < $len; $i++)
            $token .= $chars[mt_rand(0, $numChars)];
        if ($md5) {
            $chunks = ceil(strlen($token) / 32);
            $md5token = '';
            for ($i = 1; $i <= $chunks; $i++)
                $md5token .= md5(substr($token, $i * 32 - 32, 32));
            $token = substr($md5token, 0, $len);
        }
        return $token;
    }
    
    
}
