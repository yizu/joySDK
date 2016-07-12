<?php

/**
 * Project:     乐恒互动用户系统SDK  Api类
 * File:        findPwdApiController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李杨 <768216362@qq.com>
 * @copyright 2015 SDK, Inc.
 */
class FindPwdApi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserRegister_model');
        $this->load->model('EmailProfile_model');
        $in_conf['key'] = "562asd32";
        $array = array($in_conf['key'], $in_conf['key']);
        $this->load->library('DES', $array);
    }

    //找回密码接口发送邮件功能
    public function findPwd() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);

        //后端对不为空的数据进行判断
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['username'] == "") {
            $result = array(
                'status' => 102,
                'msg' => '用户名不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if (!preg_match('/^[a-z\d_]{5,20}$/i', $param['username'])) {
            $result = array(
                'status' => 103,
                'msg' => '用户名不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['email'] == "") {
            $result = array(
                'status' => 104,
                'msg' => '邮箱不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (!preg_match($pattern, $param['email'])) {
            $result = array(
                'status' => 105,
                'msg' => '您输入的电子邮件地址不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $flag = $this->UserRegister_model->queryEmailbyUserName($param['username'], $param['email']);
        if ($flag == 1) {
            $result = array(
                'status' => 106,
                'msg' => '输入的邮箱与绑定的邮箱不一致',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else if ($flag == 2) {
            $result = array(
                'status' => 107,
                'msg' => '该用户没有绑定过邮箱',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $result = array(
                'status' => 200,
                'msg' => '发送成功',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            $date = date("Y-m-d", time());
            $param['token'] = $this->genToken();

            require "MySendMail.php";
            $mail = new MySendMail();
            $mail->setServer("smtp.exmail.qq.com", "game-cs@joy4you.com", "135qweQWE");
            $mail->setFrom("game-cs@joy4you.com");
            $mail->setReceiver($param['email']);
            $paramstr = $param['username'] . '_' . $param['token'];
            $url = "http://account.joy4you.com/joysdk/index.php/forgot/updpwdpage/?param=" .$paramstr;
            $mail->setMailInfo("密码找回", "亲爱的" . $param['username'] . "</br>
                                    &nbsp;&nbsp;您正在进行密码找回，请点击链接进入下一步（该链接在30分钟内有效）</br>
                                    <a href='$url'>$url</a></br>
                                    如果您没有进行找回密码操作，请忽略此邮件。</br>
                                    <li style='float:right'>乐恒互动</li><br>
                                    <li style='float:right'>" . $date . "</li><br>
                                    <li style='float:right'>乐恒互动（北京）文化有限公司</li>
                                    ");
            $flag = $mail->sendMail();
            if ($flag) {
                $this->EmailProfile_model->insertEmailInfo($param['email'], $param['token']);
            }
            exit;
        }
    }
    
    
    
    //找回密码接口发送邮件功能
    public function findPwdForPhone() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);

        //后端对不为空的数据进行判断
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['phone'] == "") {
            $result = array(
                'status' => 102,
                'msg' => '手机号不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if (!preg_match("/^(13[0-9]|14[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/", $param['phone'])) {
            $result = array(
                'status' => 103,
                'msg' => '手机号不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['email'] == "") {
            $result = array(
                'status' => 104,
                'msg' => '邮箱不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (!preg_match($pattern, $param['email'])) {
            $result = array(
                'status' => 105,
                'msg' => '您输入的电子邮件地址不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $flag = $this->UserRegister_model->queryEmailbyPhone($param['phone'], $param['email']);
        if ($flag == 1) {
            $result = array(
                'status' => 106,
                'msg' => '输入的邮箱与绑定的邮箱不一致',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else if ($flag == 2) {
            $result = array(
                'status' => 107,
                'msg' => '该手机号没有绑定过邮箱',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $result = array(
                'status' => 200,
                'msg' => '发送成功',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            $date = date("Y-m-d", time());
            $param['token'] = $this->genToken();

            require "MySendMail.php";
            $mail = new MySendMail();
            $mail->setServer("smtp.exmail.qq.com", "game-cs@joy4you.com", "135qweQWE");
            $mail->setFrom("game-cs@joy4you.com");
            $mail->setReceiver($param['email']);
            $paramstr = $param['phone'] . '_' . $param['token'];
            $url = "http://account.joy4you.com/joysdk/index.php/forgot/updpwdpage/?param=" .$paramstr;
            $mail->setMailInfo("密码找回", "亲爱的" . $param['username'] . "</br>
                                    &nbsp;&nbsp;您正在进行密码找回，请点击链接进入下一步（该链接在30分钟内有效）</br>
                                    <a href='$url'>$url</a></br>
                                    如果您没有进行找回密码操作，请忽略此邮件。</br>
                                    <li style='float:right'>乐恒互动</li><br>
                                    <li style='float:right'>" . $date . "</li><br>
                                    <li style='float:right'>乐恒互动（北京）文化有限公司</li>
                                    ");
            $flag = $mail->sendMail();
            if ($flag) {
                $this->EmailProfile_model->insertEmailInfo($param['email'], $param['token']);
            }
            exit;
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
