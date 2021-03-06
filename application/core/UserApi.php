<?php

/**
 * Project:     乐恒互动用户系统SDK  Api类
 * File:        UserApiController.php
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
class UserApi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserRegister_model');
        $this->load->model('UserRegisterOther_model');
        $this->load->model('Game_model');
        $this->load->model('UserProfile_model');
        $in_conf['key'] = "562asd32";
        $array = array($in_conf['key'], $in_conf['key']);
        $this->load->library('DES', $array);
        //初始化日志
        $in_conf['login'] = "login";
        $arrayLogin = array($in_conf['login']);
        $this->load->library('FileLog', $arrayLogin);
    }

    //检查用户名是否存在
    public function checkUserName() {
        $param = $this->input->post_get('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);
        //后端对不为空的数据进行判断
        if ($param['username'] == "") {
            $result = array(
                'status' => 101,
                'msg' => '用户名不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if (!preg_match('/^[a-z\d_]{5,20}$/i', $param['username'])) {
            $result = array(
                'status' => 102,
                'msg' => '用户名不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }

        if ($param['ckid'] == "") {
            $result = array(
                'status' => 103,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $userinfo = $this->UserRegister_model->queryUserName($param['username']);
        if ($userinfo) {
            $result = array(
                'status' => 104,
                'msg' => '用户名已存在',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $result = array(
                'status' => 200,
                'msg' => '用户名不存在',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
    }

    //如果用户名不存在时，则调用注册接口注册，如果用户名存在则不能注册
    public function register() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);
        $param['appid'] = isset($param['appid']) ? $param['appid'] : "";
        $param['username'] = isset($param['username']) ? $param['username'] : NULL;
        $param['password'] = isset($param['password']) ? $param['password'] : "";
        $param['phone'] = isset($param['phone']) ? $param['phone'] : "";
        $param['email'] = isset($param['email']) ? $param['email'] : "";
        $param['type'] = isset($param['type']) ? $param['type'] : "";
        $param['cuid'] = isset($param['cuid']) ? $param['cuid'] : "";
        $param['ckid'] = isset($param['ckid']) ? $param['ckid'] : "";
        $param['tuid'] = isset($param['tuid']) ? $param['tuid'] : "";
        $param['thd'] = isset($param['thd']) ? $param['thd'] : "";
        $param['tun'] = isset($param['tun']) ? $param['tun'] : "";
        $param['tph'] = isset($param['tph']) ? $param['tph'] : "";
        $param['sex'] = isset($param['sex']) ? $param['sex'] : "";
        $param['nickname'] = isset($param['nickname']) ? $param['nickname'] : "";
        $param['birthday'] = isset($param['birthday']) ? $param['birthday'] : "";
        $param['from'] = isset($param['from']) ? $param['from'] : "";
        $param['chid'] = isset($param['chid']) ? $param['chid'] : "";
        $param['sdkverison'] = isset($param['sdkverison']) ? $param['sdkverison'] : "";
        $param['sdkjsversion'] = isset($param['sdkjsversion']) ? $param['sdkjsversion'] : "";
        $param['systemhardware'] = isset($param['systemhardware']) ? $param['systemhardware'] : "";
        $param['telecomoper'] = isset($param['telecomoper']) ? $param['telecomoper'] : "";
        $param['network'] = isset($param['network']) ? $param['network'] : "";
        $param['screenwidth'] = isset($param['screenwidth']) ? $param['screenwidth'] : "";
        $param['screenhight'] = isset($param['screenhight']) ? $param['screenhight'] : "";
        $param['density'] = isset($param['density']) ? $param['density'] : "";
        $param['channelid'] = isset($param['channelid']) ? $param['channelid'] : "";
        $param['cpuhardware'] = isset($param['cpuhardware']) ? $param['cpuhardware'] : "";
        $param['memory'] = isset($param['memory']) ? $param['memory'] : "";
        $param['dt'] = isset($param['dt']) ? $param['dt'] : "";
        $param['dm'] = isset($param['dm']) ? $param['dm'] : "";
        $param['osv'] = isset($param['osv']) ? $param['osv'] : "";
        $param['mac'] = isset($param['mac']) ? $param['mac'] : "";
        $param['imei'] = isset($param['imei']) ? $param['imei'] : "";
        $param['srl'] = isset($param['srl']) ? $param['srl'] : "";
        $param['pkg'] = isset($param['pkg']) ? $param['pkg'] : "";
        $param['bn'] = isset($param['bn']) ? $param['bn'] : "";
        $param['idfa'] = isset($param['idfa']) ? $param['idfa'] : "";

        //后端对不为空的数据进行判断
        if ($param['appid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
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
        if ($param['password'] == "") {
            $result = array(
                'status' => 104,
                'msg' => '密码不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 105,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['channelid'] == "") {
            $result = array(
                'status' => 106,
                'msg' => '渠道id不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($param['appid']);
        if (!$checkAppid) {
            $result = array(
                'status' => 107,
                'msg' => 'appid不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $userName = $this->UserRegister_model->queryUserName($param['username']);
            if ($userName) {
                $result = array(
                    'status' => 108,
                    'msg' => '用户名已存在',
                );
                $input = urldecode(json_encode($result));
                $token = $this->des->encrypt($input);
                echo $token;
            } else {
                $param['token'] = $this->genToken();
                $param['sessionid'] = $this->getSid();
                $registerInfo = $this->UserRegister_model->insertUserInfo($param);
                $userid = $this->UserRegister_model->queryUserId($registerInfo);
                if ($userid) {
                    $param['uid'] = $userid->id;
                } else {
                    $param['uid'] = "";
                }
                $registerOtherInfo = $this->UserRegisterOther_model->insertUserOtherInfo($param);
                if ($registerInfo && $registerOtherInfo) {
                    //$userid = $this->UserRegister_model->queryUserId($registerInfo);
                    $this->UserProfile_model->insertUserprofile($userid->id);
                    $data['userid'] = $param['uid'];
                    $data['username'] = $registerInfo['username'];
                    $data['phone'] = $registerInfo['phone'];
                    $data['email'] = $registerInfo['email'];
                    $data['ckid'] = $registerInfo['ckid'];
                    $data['sessionid'] = $registerInfo['sessionid'];
                    $data['token'] = $registerInfo['token'];
                    $result = array(
                        'status' => 200,
                        'msg' => '注册成功',
                        'data' => $data,
                    );
                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                    exit;
                } else {
                    echo false;
                }
            }
        }
    }

    //登陆
    public function login() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);
        //设置存储日期时区
        date_default_timezone_set('PRC');
        $param['appid'] = isset($param['appid']) ? $param['appid'] : "";
        $param['username'] = isset($param['username']) ? $param['username'] : NULL;
        $param['password'] = isset($param['password']) ? $param['password'] : "";
        $param['phone'] = isset($param['phone']) ? $param['phone'] : "";
        $param['email'] = isset($param['email']) ? $param['email'] : "";
        $param['type'] = isset($param['type']) ? $param['type'] : "";
        $param['cuid'] = isset($param['cuid']) ? $param['cuid'] : "";
        $param['ckid'] = isset($param['ckid']) ? $param['ckid'] : "";
        $param['tuid'] = isset($param['tuid']) ? $param['tuid'] : "";
        $param['thd'] = isset($param['thd']) ? $param['thd'] : "";
        $param['tun'] = isset($param['tun']) ? $param['tun'] : "";
        $param['tph'] = isset($param['tph']) ? $param['tph'] : "";
        $param['sex'] = isset($param['sex']) ? $param['sex'] : "";
        $param['nickname'] = isset($param['nickname']) ? $param['nickname'] : "";
        $param['birthday'] = isset($param['birthday']) ? $param['birthday'] : "";
        $param['from'] = isset($param['from']) ? $param['from'] : "";
        $param['chid'] = isset($param['chid']) ? $param['chid'] : "";
        $param['sdkverison'] = isset($param['sdkverison']) ? $param['sdkverison'] : "";
        $param['sdkjsversion'] = isset($param['sdkjsversion']) ? $param['sdkjsversion'] : "";
        $param['systemhardware'] = isset($param['systemhardware']) ? $param['systemhardware'] : "";
        $param['telecomoper'] = isset($param['telecomoper']) ? $param['telecomoper'] : "";
        $param['network'] = isset($param['network']) ? $param['network'] : "";
        $param['screenwidth'] = isset($param['screenwidth']) ? $param['screenwidth'] : "";
        $param['screenhight'] = isset($param['screenhight']) ? $param['screenhight'] : "";
        $param['density'] = isset($param['density']) ? $param['density'] : "";
        $param['channelid'] = isset($param['channelid']) ? $param['channelid'] : "";
        $param['cpuhardware'] = isset($param['cpuhardware']) ? $param['cpuhardware'] : "";
        $param['memory'] = isset($param['memory']) ? $param['memory'] : "";
        $param['dt'] = isset($param['dt']) ? $param['dt'] : "";
        $param['dm'] = isset($param['dm']) ? $param['dm'] : "";
        $param['osv'] = isset($param['osv']) ? $param['osv'] : "";
        $param['mac'] = isset($param['mac']) ? $param['mac'] : "";
        $param['imei'] = isset($param['imei']) ? $param['imei'] : "";
        $param['srl'] = isset($param['srl']) ? $param['srl'] : "";
        $param['pkg'] = isset($param['pkg']) ? $param['pkg'] : "";
        $param['bn'] = isset($param['bn']) ? $param['bn'] : "";

        //后端对不为空的数据进行判断
        if ($param['appid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
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
        if ($param['password'] == "") {
            $result = array(
                'status' => 104,
                'msg' => '密码不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 105,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['channelid'] == "") {
            $result = array(
                'status' => 106,
                'msg' => '渠道id不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($param['appid']);
        if (!$checkAppid) {
            $result = array(
                'status' => 107,
                'msg' => 'appid不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $userName = $this->UserRegister_model->queryUserName($param['username']);
            if (!$userName) {
                $result = array(
                    'status' => 108,
                    'msg' => '用户名不存在',
                );
                $input = urldecode(json_encode($result));
                $token = $this->des->encrypt($input);
                echo $token;
            } else {
                $userInfo = $this->UserRegister_model->queryUserInfoByUsername($param['username'], $param['password']);
                if ($userInfo) {
                    $sessionid = $this->getSid();
                    $this->UserProfile_model->updateUserProfile($userInfo['id']);
                    $flag = $this->UserRegister_model->updSessionid($userInfo['sessionid'], $sessionid);
                    if ($flag) {
                        $data['sessionid'] = $sessionid;
                    }
                    $data['userid'] = $userInfo['id'];
                    $data['username'] = $userInfo['username'];
                    $data['phone'] = $userInfo['phone'];
                    $data['email'] = $userInfo['email'];
                    $data['ckid'] = $userInfo['ckid'];
                    $data['token'] = $userInfo['token'];
                    //日志文件格式处理（按照数据库字段顺序写文件，方便执行批量程序   记录登陆成功的返回信息
                    $str = "";
                    $temp = array();
                    $temp['userid'] = $data['userid'];
                    $temp['username'] = $data['username'];
                    $temp['phone'] = $data['phone'];
                    $temp['email'] = $data['email'];
                    $temp['ckid'] = $data['ckid'];
                    $temp['logintime'] = time();
                    foreach ($temp as $key => $value) {
                        $str = $str . ',' . $value;
                    }
                    //写日志
                    $this->filelog->fileWrite($str);

                    $result = array(
                        'status' => 200,
                        'msg' => '登陆成功',
                        'data' => $data,
                    );

                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                } else {
                    $result = array(
                        'status' => 109,
                        'msg' => '密码不正确'
                    );
                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                }
            }
        }
    }

    //游客登陆
    public function touristLogin() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);
        date_default_timezone_set('PRC');
        //后端对不为空的数据进行判断
        $param['username'] = isset($param['username']) ? $param['username'] : NULL;
        $param['password'] = isset($param['password']) ? $param['password'] : "";
        $param['phone'] = isset($param['phone']) ? $param['phone'] : "";
        $param['email'] = isset($param['email']) ? $param['email'] : "";
        $param['type'] = isset($param['type']) ? $param['type'] : "";
        $param['cuid'] = isset($param['cuid']) ? $param['cuid'] : "";
        $param['ckid'] = isset($param['ckid']) ? $param['ckid'] : "";
        $param['tuid'] = isset($param['tuid']) ? $param['tuid'] : "";
        $param['thd'] = isset($param['thd']) ? $param['thd'] : "";
        $param['tun'] = isset($param['tun']) ? $param['tun'] : "";
        $param['tph'] = isset($param['tph']) ? $param['tph'] : "";
        $param['sex'] = isset($param['sex']) ? $param['sex'] : "";
        $param['nickname'] = isset($param['nickname']) ? $param['nickname'] : "";
        $param['birthday'] = isset($param['birthday']) ? $param['birthday'] : "";
        $param['from'] = isset($param['from']) ? $param['from'] : "";
        $param['chid'] = isset($param['chid']) ? $param['chid'] : "";
        $param['sdkverison'] = isset($param['sdkverison']) ? $param['sdkverison'] : "";
        $param['sdkjsversion'] = isset($param['sdkjsversion']) ? $param['sdkjsversion'] : "";
        $param['systemhardware'] = isset($param['systemhardware']) ? $param['systemhardware'] : "";
        $param['telecomoper'] = isset($param['telecomoper']) ? $param['telecomoper'] : "";
        $param['network'] = isset($param['network']) ? $param['network'] : "";
        $param['screenwidth'] = isset($param['screenwidth']) ? $param['screenwidth'] : "";
        $param['screenhight'] = isset($param['screenhight']) ? $param['screenhight'] : "";
        $param['density'] = isset($param['density']) ? $param['density'] : "";
        $param['channelid'] = isset($param['channelid']) ? $param['channelid'] : "";
        $param['cpuhardware'] = isset($param['cpuhardware']) ? $param['cpuhardware'] : "";
        $param['memory'] = isset($param['memory']) ? $param['memory'] : "";
        $param['dt'] = isset($param['dt']) ? $param['dt'] : "";
        $param['dm'] = isset($param['dm']) ? $param['dm'] : "";
        $param['osv'] = isset($param['osv']) ? $param['osv'] : "";
        $param['mac'] = isset($param['mac']) ? $param['mac'] : "";
        $param['imei'] = isset($param['imei']) ? $param['imei'] : "";
        $param['srl'] = isset($param['srl']) ? $param['srl'] : "";
        $param['pkg'] = isset($param['pkg']) ? $param['pkg'] : "";
        $param['bn'] = isset($param['bn']) ? $param['bn'] : "";
        $param['idfa'] = isset($param['idfa']) ? $param['idfa'] : "";

        if ($param['appid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 102,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['channelid'] == "") {
            $result = array(
                'status' => 103,
                'msg' => '渠道id不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($param['appid']);
        if (!$checkAppid) {
            $result = array(
                'status' => 104,
                'msg' => 'appid不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $userInfo = $this->UserRegister_model->queryUserInfoByCkid($param['ckid']);
            if ($userInfo) {
                $newstoken = $this->genToken();
                $this->UserRegister_model->updToken($userInfo['token'], $newstoken);
                $data['userid'] = $userInfo['userid'];
                $data['username'] = "";
                $data['phone'] = "";
                $data['email'] = "";
                $data['ckid'] = $userInfo['ckid'];
                $data['sessionid'] = $userInfo['sessionid'];
                $data['token'] = $newstoken;
                //日志文件格式处理（按照数据库字段顺序写文件，方便执行批量程序   记录登陆成功的返回信息
                $str = "";
                $temp = array();
                $temp['userid'] = $data['userid'];
                $temp['username'] = $data['username'];
                $temp['phone'] = $data['phone'];
                $temp['email'] = $data['email'];
                $temp['ckid'] = $data['ckid'];
                $temp['logintime'] = time();
                foreach ($temp as $key => $value) {
                    $str = $str . ',' . $value;
                }
                //写日志
                $this->filelog->fileWrite($str);
                $result = array(
                    'status' => 200,
                    'msg' => '登陆成功',
                    'data' => $data,
                );
                $input = urldecode(json_encode($result));
                $token = $this->des->encrypt($input);
                echo $token;
                exit;
            } else {
                $param['token'] = $this->genToken();
                $param['sessionid'] = $this->getSid();
                $uid = $this->UserRegister_model->insertTouristInfo($param);
                if ($uid) {
                    $param['uid'] = $uid;
                    $this->UserRegisterOther_model->insertTouristOtherInfo($param);
                    $this->UserProfile_model->insertUserprofile($uid);
                    $data['userid'] = strval($uid);
                    $data['username'] = "";
                    $data['phone'] = "";
                    $data['email'] = "";
                    $data['ckid'] = $param['ckid'];
                    $data['sessionid'] = $param['sessionid'];
                    $data['token'] = $param['token'];
                    $result = array(
                        'status' => 200,
                        'msg' => '游客注册成功',
                        'data' => $data,
                    );
                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                    exit;
                } else {
                    echo false;
                }
            }
        }
    }

    //自动登陆
    public function autoLogin() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);

        $param['appid'] = isset($param['appid']) ? $param['appid'] : "";
        $param['sessionid'] = isset($param['sessionid']) ? $param['sessionid'] : "";
        $param['userid'] = isset($param['userid']) ? $param['userid'] : "";
        $param['ckid'] = isset($param['ckid']) ? $param['ckid'] : "";
        $param['channelid'] = isset($param['channelid']) ? $param['channelid'] : "";

        //后端对不为空的数据进行判断
        if ($param['appid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }

        if ($param['sessionid'] == "") {
            $result = array(
                'status' => 102,
                'msg' => 'sessionid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }

        if ($param['userid'] == "") {
            $result = array(
                'status' => 103,
                'msg' => 'userid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }

        if ($param['ckid'] == "") {
            $result = array(
                'status' => 104,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['channelid'] == "") {
            $result = array(
                'status' => 105,
                'msg' => '渠道id不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($param['appid']);
        if (!$checkAppid) {
            $result = array(
                'status' => 106,
                'msg' => 'appid不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $result = $this->UserRegister_model->querySessionid($param['sessionid']);
            if ($result) {
                $sidtime = $result['sidtime'];
                $time = time();
                $c = $time - $sidtime;
                if ($c / (60 * 60) > 24 * 7) {
                    //过期后生成新sessionid
                    $sessionid = $this->getSid();
                    $this->UserRegister_model->updSessionid($param['sessionid'], $sessionid);
                    $result = array(
                        'status' => 108,
                        'msg' => 'sessionid已经过期'
                    );
                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                } else {
                    $this->UserProfile_model->updateUserProfile($param['userid']);
                    //过期后生成新token
                    $newstoken = $this->genToken();
                    $this->UserRegister_model->updToken($result['token'], $newstoken);
                    $data['userid'] = $result['id'];
                    $data['username'] = $result['username'];
                    $data['phone'] = $result['phone'];
                    $data['email'] = $result['email'];
                    $data['ckid'] = $result['ckid'];
                    $data['sessionid'] = $result['sessionid'];
                    $data['token'] = $newstoken;
                    //日志文件格式处理（按照数据库字段顺序写文件，方便执行批量程序   记录登陆成功的返回信息
                    $str = "";
                    $temp = array();
                    $temp['userid'] = $data['userid'];
                    $temp['username'] = $data['username'];
                    $temp['phone'] = $data['phone'];
                    $temp['email'] = $data['email'];
                    $temp['ckid'] = $data['ckid'];
                    $temp['logintime'] = time();
                    foreach ($temp as $key => $value) {
                        $str = $str . ',' . $value;
                    }
                    //写日志
                    $this->filelog->fileWrite($str);
                    $result = array(
                        'status' => 200,
                        'msg' => '自动登陆成功',
                        'data' => $data,
                    );
                    $input = urldecode(json_encode($result));
                    $token = $this->des->encrypt($input);
                    echo $token;
                }
            } else {
                $result = array(
                    'status' => 108,
                    'msg' => '缓存登陆已过期，请输入用户名密码登陆'
                );
                $input = urldecode(json_encode($result));
                $token = $this->des->encrypt($input);
                echo $token;
            }
        }
    }

    //游客绑定用户名密码
    public function bindUserName() {
        $param = $this->input->get_post('param', TRUE);
        $param = $this->des->decrypt($param);
        $param = json_decode($param, TRUE);
        //后端对不为空的数据进行判断
        if ($param['userid'] == "") {
            $result = array(
                'status' => 101,
                'msg' => '用户id不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['ckid'] == "") {
            $result = array(
                'status' => 102,
                'msg' => 'ckid不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['username'] == "") {
            $result = array(
                'status' => 103,
                'msg' => '用户名不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if (!preg_match('/^[a-z\d_]{5,20}$/i', $param['username'])) {
            $result = array(
                'status' => 104,
                'msg' => '用户名不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['password'] == "") {
            $result = array(
                'status' => 105,
                'msg' => '密码不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        $userInfo = $this->UserRegister_model->bindUserName($param);
        if ($userInfo == 1) {
            $result = array(
                'status' => 106,
                'msg' => '该用户已经绑定过',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else if ($userInfo == 2) {
            $result = array(
                'status' => 107,
                'msg' => '查无此用户',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else if ($userInfo == 3) {
            $result = array(
                'status' => 108,
                'msg' => '用户名存在，请更换用户名在绑定',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $data['userid'] = $userInfo['id'];
            $data['username'] = $param['username'];
            $data['phone'] = $userInfo['phone'];
            $data['email'] = $userInfo['email'];
            $data['ckid'] = $userInfo['ckid'];
            $data['sessionid'] = $userInfo['sessionid'];
            $data['token'] = $userInfo['token'];
            $result = array(
                'status' => 200,
                'msg' => '绑定成功',
                'data' => $data,
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
        }
    }

    //邮箱绑定功能
    public function bindEmail() {
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
        if ($param['password'] == "") {
            $result = array(
                'status' => 104,
                'msg' => '密码不能为空',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }
        if ($param['email'] == "") {
            $result = array(
                'status' => 105,
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
                'status' => 106,
                'msg' => '您输入的电子邮件地址不合法',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        }

        $userInfo = $this->UserRegister_model->bindEmail($param);
        if ($userInfo == 1) {
            $result = array(
                'status' => 107,
                'msg' => '该用户已经绑定过邮箱',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else if ($userInfo == 2) {
            $result = array(
                'status' => 108,
                'msg' => '用户名密码不正确，请核对后在填',
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
            exit;
        } else {
            $data['userid'] = $userInfo['id'];
            $data['username'] = $userInfo['username'];
            $data['phone'] = $userInfo['phone'];
            $data['email'] = $param['email'];
            $data['ckid'] = $userInfo['ckid'];
            $data['sessionid'] = $userInfo['sessionid'];
            $data['token'] = $userInfo['token'];
            $result = array(
                'status' => 200,
                'msg' => '绑定邮箱成功',
                'data' => $data,
            );
            $input = urldecode(json_encode($result));
            $token = $this->des->encrypt($input);
            echo $token;
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

    //生成sid
    public function getSid($len = 32, $md5 = true) {
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
        $sid = '';
        for ($i = 0; $i < $len; $i++)
            $sid .= $chars[mt_rand(0, $numChars)];
        if ($md5) {
            $chunks = ceil(strlen($sid) / 32);
            $md5token = '';
            for ($i = 1; $i <= $chunks; $i++)
                $md5token .= md5(substr($sid, $i * 32 - 32, 32));
            $sid = substr($md5token, 0, $len);
        }
        return $sid;
    }

    //CP 校验token 
    public function checkToken() {
        $appid = $this->input->get_post('appid', TRUE);
        $token = $this->input->get_post('token', TRUE);
        if ($appid == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
            );
            $input = json_encode($result);
            echo $input;
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($appid);
        if (!$checkAppid) {
            $result = array(
                'status' => 102,
                'msg' => 'appid不合法',
            );
            $input = json_encode($result);
            echo $input;
            exit;
        }
        //后端对不为空的数据进行判断
        if ($token == "") {
            $result = array(
                'status' => 103,
                'msg' => 'token不能为空',
            );
            $input = json_encode($result);
            echo $input;
            exit;
        }

        $result = $this->UserRegister_model->checkToken($token);
        if ($result) {
            $tokentime = $result['tokentime'];
        } else {
            $result = array(
                'status' => 104,
                'msg' => '无效的token'
            );
            echo json_encode($result);
            exit;
        }
        $time = time();
        $c = $time - $tokentime;
        if ($c / (60 * 60) > 0.5) {
            //过期后生成新token
            $newstoken = $this->genToken();
            $this->UserRegister_model->updToken($token, $newstoken);
            $result = array(
                'status' => 105,
                'msg' => 'token已经过期'
            );
            echo json_encode($result);
            exit;
        } else {
            //登陆成功后改token
            $newstoken = $this->genToken();
            $this->UserRegister_model->updToken($token, $newstoken);
            $result = array(
                'status' => 200,
                'msg' => 'token验证成功',
                'userid' => $result['id'],
            );
            echo json_encode($result);
            exit;
        }
    }

}
