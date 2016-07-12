<?php

/**
 * Project:     乐恒互动支付系统SDK
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
class Payment extends CI_Controller {

    /**
     * HTTPS形式消息验证地址
     */
    var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    /**
     * HTTP形式消息验证地址
     */
    var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    public function __construct() {
        parent::__construct();
        $this->load->model('PayLog_model');
        $this->load->model('PayList_model');
        $this->load->model('Game_model');
        $this->load->model('Alipay_Result_model');
        $this->load->model('YinLianPay_Result_model');
        $this->load->model('Wxpay_Result_model');
        $this->load->model('SendRecord_model');
        //加载支付宝配置文件
        $this->config->load('alipay.config', TRUE);
        //加载银联配置文件
        $this->config->load('yinlian.config', TRUE);

        //引入支付宝类库
        include_once APPPATH . 'third_party/alipay/lib/alipay_core.function.php';
        include_once APPPATH . 'third_party/alipay/lib/alipay_rsa.function.php';
        //引入银联类库
        include_once APPPATH . 'third_party/yinlianpay/func/common.php';
        include_once APPPATH . 'third_party/yinlianpay/func/secureUtil.php';
        include_once APPPATH . 'third_party/yinlianpay/func/httpClient.php';
        //加载输出库
        $this->load->library('MyOutPut');
    }

    //获取支付列表
    public function getPayList() {
        $appid = $this->input->post_get('appid', TRUE);
        $channelid = $this->input->post_get('channelid', TRUE);

        //后端对不为空的数据进行判断
        if ($appid == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }

        if ($channelid == "") {
            $result = array(
                'status' => 102,
                'msg' => '渠道ID不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        $payListInfo = $this->PayList_model->getPayList($appid, $channelid);
        if ($payListInfo) {
            foreach ($payListInfo as $key => $value) {
                $data[$key]['paytype'] = $value['paytype'];
                $data[$key]['payname'] = $value['payname'];
            }

            $result = array(
                'status' => 200,
                'msg' => '获取支付列表成功',
                'data' => $data,
            );
            $this->myoutput->outputData($result);
            exit;
        } else {
            $result = array(
                'status' => 103,
                'msg' => '获取支付列表失败',
            );
            $this->myoutput->outputData($result);
            exit;
        }
    }

    //生成订单并去第三方下单
    public function createOrder() {
        $appid = $this->input->post_get('appid', TRUE);
        $channelid = $this->input->post_get('channelid', TRUE);
        $userid = $this->input->post_get('userid', TRUE);
        $paytype = $this->input->post_get('paytype', TRUE);
        $body = $this->input->post_get('body', TRUE);
        $goods_type = $this->input->post_get('goods_type', TRUE);
        $detail = $this->input->post_get('detail', TRUE);
        $fee_type = $this->input->post_get('fee_type', TRUE);
        $total_fee = $this->input->post_get('total_fee', TRUE);
        $attach = $this->input->post_get('attach', TRUE);
        $ckid = $this->input->post_get('ckid', TRUE);
        $sign = $this->input->post_get('sign', TRUE);

        //后端对不为空的数据进行判断
        if ($appid == "") {
            $result = array(
                'status' => 101,
                'msg' => 'appid不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }

        if ($channelid == "") {
            $result = array(
                'status' => 102,
                'msg' => '渠道ID不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        if ($userid == "") {
            $result = array(
                'status' => 103,
                'msg' => '用户ID不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        if (($paytype != 1 && $paytype != 2 && $paytype != 3) || $paytype == "") {
            $result = array(
                'status' => 104,
                'msg' => '支付类型不合法',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        if ($body == "") {
            $result = array(
                'status' => 105,
                'msg' => '商品名称不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        if ($total_fee == "") {
            $result = array(
                'status' => 106,
                'msg' => '商品价格不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        if ($ckid == "") {
            $result = array(
                'status' => 107,
                'msg' => 'ckid不能为空',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        //商户客户端和服务端签名校验
        $arr['appid'] = $appid;
        $arr['channelid'] = $channelid;
        $arr['userid'] = $userid;
        $arr['paytype'] = $paytype;
        $arr['body'] = $body;
        $arr['goods_type'] = $goods_type;
        $arr['detail'] = $detail;
        $arr['fee_type'] = $fee_type;
        $arr['total_fee'] = $total_fee;
        $arr['attach'] = $attach;
        $arr['ckid'] = $ckid;

        ksort($arr);
        $str = '';
        foreach ($arr as $k => $v) {
            $str .= $k . "=" . $v . '&';
        }
        $str .= 'key=' . $this->config->item('sign_key');
        $str = trim($str, '&');
        $arr['sign'] = md5($str);
        if ($arr['sign'] != $sign) {
            $result = array(
                'status' => 108,
                'msg' => '签名校验失败',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        //检查appid是否合法
        $checkAppid = $this->Game_model->checkAppid($appid);
        if (!$checkAppid) {
            $result = array(
                'status' => 109,
                'msg' => 'appid不合法',
            );
            $this->myoutput->outputData($result);
            exit;
        }
        //上面验证都通过后生成订单，然后根据支付类型去不同的第三方下单,并将本次记录写入数据库，默认订单状态是0，如果成功后，则将状态设置成1
        $out_trade_no = $ckid . time();
        //插入数据库，如果成功则根据支付类型去下单
        $arr['out_trade_no'] = $out_trade_no;
        $paylog = $this->PayLog_model->insertPayLog($arr);
        if ($paylog) {
            switch ($paytype) {
                //支付宝
                case 1:
                    $payInfo['appid'] = $appid;
                    $payInfo['out_trade_no'] = $out_trade_no;
                    $payInfo['subject'] = $body;
                    $payInfo['body'] = $detail;
                    $payInfo['goods_type'] = $goods_type;
                    $payInfo['fee_type'] = $fee_type;
                    $payInfo['total_fee'] = $total_fee * 1;
                    $payInfo['attach'] = $attach;
                    $data = self::_alipay($payInfo);
                    $result = array(
                        'status' => 200,
                        'msg' => '创建订单成功',
                        'data' => $data,
                    );
                    $this->myoutput->outputData($result);
                    break;
                //微信支付
                case 2:
                    $payInfo['out_trade_no'] = $out_trade_no;
                    $payInfo['body'] = $body;
                    $payInfo['detail'] = $detail;
                    $payInfo['fee_type'] = $fee_type;
                    //$payInfo['total_fee'] = self::_del0($total_fee);
                    $payInfo['total_fee'] = $total_fee * 100;
                    $payInfo['attach'] = $attach;
                    $data = self::_wxpay($payInfo);
                    $data['timestamp'] = time();
                    $result = array(
                        'status' => 200,
                        'msg' => '创建订单成功',
                        'data' => $data,
                    );
                    $this->myoutput->outputData($result);
                    break;
                //银联支付
                case 3:
                    $in_conf['yinlianpayLog'] = "yinlianpayLog";
                    $array = array($in_conf['yinlianpayLog']);
                    $this->load->library('FileLog', $array);
                    $payInfo['appid'] = $appid;
                    $payInfo['out_trade_no'] = $out_trade_no;
                    $payInfo['fee_type'] = $fee_type;
                    $payInfo['total_fee'] = $total_fee * 100;
                    $payInfo['attach'] = $attach;
                    $data = self::_yinlianpay($payInfo);
                    if ($data['respCode'] == '00') {
                        $result = array(
                            'status' => 200,
                            'msg' => '创建订单成功',
                            'data' => $data['tn'],
                        );
                        $this->filelog->fileWrite('创建订单成功' . '_' . $data['tn']);
                    } else {
                        $result = array(
                            'status' => 110,
                            'msg' => '下单失败',
                        );
                        $this->filelog->fileWrite('创建订单失败' . '_' . $data['respMsg']);
                    }
                    $this->myoutput->outputData($result);
                    break;
                default:
                    break;
            }
        }
    }

    //支付宝回调处理
    public function appNotifyUrl() {
        $in_conf['alipayLog'] = "alipayLog";
        $array = array($in_conf['alipayLog']);
        $this->load->library('FileLog', $array);
        //获取回调参数
        $param = $_POST;
        //先去查询是否是重复发送，如果状态已经修改，则不往下执行
        $status = $this->PayLog_model->queryOrderStatus($param['out_trade_no']);
        if ($status == 1) {
            echo "success";
            exit;
        }
        //无论成功失败都把记录存在数据库中，方便查问题
        $this->Alipay_Result_model->insertOrderInfo($param);
        $verify_result = self::_verifyNotify($param);
        if ($verify_result) {
            //记录一下成功日志
            $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . $this->input->post('trade_status', TRUE) . '_' . $this->input->post('trade_no', TRUE) . '_' . '校验成功');
            //logResult($this->input->post('out_trade_no', TRUE) . '_' . $this->input->post('trade_status', TRUE) . '_' . $this->input->post('trade_no', TRUE) . '_' . '校验成功');
            //商户订单号
            $out_trade_no = $this->input->post('out_trade_no', TRUE);
            //支付宝交易流水号
            $transaction_id = $this->input->post('trade_no', TRUE);
            //支付宝交易状态
            $transaction_status = $this->input->post('trade_status', TRUE);
            //交易金额
            $total_fee = $this->input->post('total_fee', TRUE);

            if ($transaction_status == 'TRADE_SUCCESS' || $transaction_status == 'TRADE_FINISHED') {
                //验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，并判断total_fee是否确实为该订单的实际金额（即商户订单创建时的金额），
                //同时需要校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email），
                //上述有任何一个验证不通过，则表明本次通知是异常通知，务必忽略。在上述验证通过后商户必须根据支付宝不同类型的业务通知，正确的进行不同的业务处理，
                //并且过滤重复的通知结果数据。在支付宝的业务通知中，只有交易通知状态为TRADE_SUCCESS或TRADE_FINISHED时，支付宝才会认定为买家付款成功。 
                //如果商户需要对同步返回的数据做验签，必须通过服务端的签名验签代码逻辑来实现。如果商户未正确处理业务通知，存在潜在的风险，商户自行承担因此而产生的所有损失。
                //查询商户订单信息
                $orderInfo = $this->PayLog_model->queryOrderInfo($out_trade_no);
                if ($orderInfo) {
                    if ($out_trade_no == $orderInfo->out_trade_no && $total_fee == ($orderInfo->total_fee) * 1) {
                        //把流水号插入数据库并且修改订单状态
                        $status = $this->PayLog_model->updateOrderInfo($out_trade_no, $transaction_id, $transaction_status);
                        if ($status) {
                            echo 'success';
                            $this->filelog->fileWrite('支付宝回调返回success');
                            $sendParam['appid'] = $orderInfo->appid;
                            $sendParam['userid'] = $orderInfo->uid;
                            $sendParam['paytype'] = $orderInfo->paytype;
                            $sendParam['status'] = '1';
                            $sendParam['out_trade_no'] = $orderInfo->out_trade_no;
                            $sendParam['transaction_id'] = $transaction_id;
                            $sendParam['create_time'] = $orderInfo->create_time;
                            $sendParam['channelid'] = $orderInfo->channelid;
                            $sendParam['body'] = $orderInfo->body;
                            $sendParam['total_fee'] = $orderInfo->total_fee;
                            $sendParam['attach'] = $orderInfo->attach;
                            $sign_string = '';
                            ksort($sendParam);
                            foreach ($sendParam as $key => $value) {
                                $sign_string .=urlencode($key) . '=' . urlencode($value) . '&';
                            }
                            //app_secrect
                            $gameInfo = $this->Game_model->queryGameInfo($orderInfo->appid);
                            $sign_string .= $gameInfo->app_secrect;
                            $sign = md5($sign_string);
                            $sendParam['sign'] = $sign;
                            $sendParam['url'] = $gameInfo->notify_url;
                            $result = self::_sendGoods($sendParam);
                            $this->SendRecord_model->insertSendRecord($sendParam);
                            if ($result == 'ok') {
                                $this->filelog->fileWrite('发货成功' . $result);
                            } else {
                                $this->filelog->fileWrite('发货失败：失败的单号是：' .  $orderInfo->out_trade_no);
                            }
                            //发货成功后记录
                            exit;
                        } else {
                            echo 'fail';
                            exit;
                        }
                    } else {
                        $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . '金额被非法篡改或者订单号被篡改');
                    }
                } else {
                    $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . '单号不存在');
                }
            } else {
                $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . '交易失败');
            }
        } else {
            //失败时候写日志并且插入数据库并且返回false
            $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . '校验失败');
            echo false;
        }
    }

    //银联回调处理
    public function wxNotifyUrl() {
        include_once APPPATH . 'third_party/wxpay/lib/WxPay.Api.php';
        include_once APPPATH . 'third_party/wxpay/lib/WxPay.Notify.php';
        $in_conf['wxpayLog'] = "wxpayLog";
        $array = array($in_conf['wxpayLog']);
        $this->load->library('FileLog', $array);
        $this->filelog->fileWrite("begin notify");
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (!$xml) {
            $this->filelog->fileWrite("xml数据异常");
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //先去查询是否是重复发送，如果状态已经修改，则不往下执行
        $status = $this->PayLog_model->queryOrderStatus($this->values['out_trade_no']);
        if ($status == 1) {
            $return['return_code'] = 'SUCCESS';
            $return['return_msg'] = 'OK';
            $xml = self::_toXml($return);
            echo $xml;
            exit;
        }
        //无论成功失败都把记录存在数据库中，方便查问题
        $this->Wxpay_Result_model->insertOrderInfo($this->values);

        //查询订单是否支付成功
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($this->values['transaction_id']);
        $result = WxPayApi::orderQuery($input);
        $this->filelog->fileWrite("query:" . json_encode($result));
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            //查询订单成功时
            $orderStatus = 1;
        } else {
            //查询订单失败时
            $orderStatus = 0;
            $return['return_code'] = 'FAIL';
            $return['return_msg'] = 'FAIL';
            $this->filelog->fileWrite("订单查询失败");
            $xml = self::_toXml($return);
            echo $xml;
            exit;
        }
        //验证签名
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = self::_toUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . WxPayConfig::KEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $sign = strtoupper($string);
        if ($orderStatus == 1 && $sign == $this->values['sign']) {
            $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . $this->input->post('return_code', TRUE) . '_' . $this->input->post('transaction_id', TRUE) . '_' . '校验成功');
            //商户订单号
            $out_trade_no = $this->values['out_trade_no'];
            //微信交易流水号
            $transaction_id = $this->values['transaction_id'];
            //微信交易状态
            $result_code = $this->values['result_code'];
            //交易金额
            $total_fee = $this->values['total_fee'];
            //交易状态
            $transaction_status = 'TRADE_SUCCESS';
            if ($result_code == 'SUCCESS') {
                $orderInfo = $this->PayLog_model->queryOrderInfo($out_trade_no);
                if ($orderInfo) {
                    if ($out_trade_no == $orderInfo->out_trade_no && $total_fee == ($orderInfo->total_fee) * 100) {
                        //把流水号插入数据库并且修改订单状态
                        $status = $this->PayLog_model->updateOrderInfo($out_trade_no, $transaction_id, $transaction_status);
                        if ($status) {
                            $return['return_code'] = 'SUCCESS';
                            $return['return_msg'] = 'OK';
                            $xml = self::_toXml($return);
                            echo $xml;
                            $this->filelog->fileWrite('微信回调返回success');
                            $sendParam['appid'] = $orderInfo->appid;
                            $sendParam['userid'] = $orderInfo->uid;
                            $sendParam['paytype'] = $orderInfo->paytype;
                            $sendParam['status'] = '1';
                            $sendParam['out_trade_no'] = $orderInfo->out_trade_no;
                            $sendParam['transaction_id'] = $transaction_id;
                            $sendParam['create_time'] = $orderInfo->create_time;
                            $sendParam['channelid'] = $orderInfo->channelid;
                            $sendParam['body'] = $orderInfo->body;
                            $sendParam['total_fee'] = $orderInfo->total_fee;
                            $sendParam['attach'] = $orderInfo->attach;
                            $sign_string = '';
                            ksort($sendParam);
                            foreach ($sendParam as $key => $value) {
                                $sign_string .=urlencode($key) . '=' . urlencode($value) . '&';
                            }
                            //获取$app_secrect
                            $gameInfo = $this->Game_model->queryGameInfo($orderInfo->appid);
                            $sign_string .= $gameInfo->app_secrect;
                            $sign = md5($sign_string);
                            $sendParam['sign'] = $sign;
                            $sendParam['url'] = $gameInfo->notify_url;
                            $result = self::_sendGoods($sendParam);
                            $this->SendRecord_model->insertSendRecord($sendParam);
                            if ($result == 'ok') {
                                $this->filelog->fileWrite('发货成功' . $result);
                            } else {
                                $this->filelog->fileWrite('发货失败：失败的单号是：' .  $orderInfo->out_trade_no);
                            }
                            exit;
                        } else {
                            $return['return_code'] = 'FAIL';
                            $return['return_msg'] = 'FAIL';
                            $xml = self::_toXml($return);
                            echo $xml;
                            exit;
                        }
                    } else {
                        $this->filelog->fileWrite($out_trade_no . '_' . '金额被非法篡改或者订单号被篡改');
                        $return['return_code'] = 'FAIL';
                        $return['return_msg'] = 'FAIL';
                        $this->filelog->fileWrite("订单查询失败");
                        $xml = self::_toXml($return);
                        echo $xml;
                        exit;
                    }
                } else {
                    $this->filelog->fileWrite($out_trade_no . '_' . '单号不存在');
                    $return['return_code'] = 'FAIL';
                    $return['return_msg'] = 'FAIL';
                    $this->filelog->fileWrite("订单查询失败");
                    $xml = self::_toXml($return);
                    echo $xml;
                    exit;
                }
            }
        } else {
            $this->filelog->fileWrite($this->input->post('out_trade_no', TRUE) . '_' . '校验失败');
            $return['return_code'] = 'FAIL';
            $return['return_msg'] = 'FAIL';
            $this->filelog->fileWrite("订单查询失败");
            $xml = self::_toXml($return);
            echo $xml;
            exit;
        }
    }

    //银联回调处理
    public function yinlianNotifyUrl() {
        $in_conf['yinlianpayLog'] = "yinlianpayLog";
        $array = array($in_conf['yinlianpayLog']);
        $this->load->library('FileLog', $array);
        //获取回调参数
        $param = $_POST;
        $this->filelog->fileWrite($param['orderId'] . '_' . $param['version'] . '_' . $param['encoding'] . '_' . $param['certId'] . '_' . $param['signature'] . '_' . $param['signMethod'] .
                '_' . $param['txnType'] . '_' . $param['txnSubType'] . '_' . $param['bizType'] . '_' . $param['accessType'] . '_' . $param['merId'] . '_' . $param['orderId'] .
                '_' . $param['txnTime'] . '_' . $param['txnAmt'] . '_' . $param['queryId'] . '_' . $param['respCode'] . '_' . $param['respMsg'] . '_' . '获取参数成功');
        //先去查询是否是重复发送，如果状态已经修改，则不往下执行
        $status = $this->PayLog_model->queryOrderStatus($param['orderId']);
        if ($status == 1) {
            echo "success";
            exit;
        }
        $this->YinLianPay_Result_model->insertOrderInfo($param);
        $verify_result = verify($_POST);
        if ($verify_result) {
            $this->filelog->fileWrite($param['orderId'] . '_' . $param['queryId'] . '_' . '校验成功');
            $out_trade_no = $param['orderId'];
            //银联交易流水号
            $transaction_id = $param['queryId'];
            //银联交易状态
            $transaction_status = 'TRADE_SUCCESS';
            //交易金额
            $total_fee = $this->input->post('txnAmt', TRUE);
            if ($param['respCode'] == '00') {
                $orderInfo = $this->PayLog_model->queryOrderInfo($out_trade_no);
                if ($orderInfo) {
                    if ($out_trade_no == $orderInfo->out_trade_no && $total_fee == ($orderInfo->total_fee) * 100) {
                        //把流水号插入数据库并且修改订单状态
                        $status = $this->PayLog_model->updateOrderInfo($out_trade_no, $transaction_id, $transaction_status);
                        if ($status) {
                            echo 'success';
                            $this->filelog->fileWrite('银联回调返回success');
                            $sendParam['appid'] = $orderInfo->appid;
                            $sendParam['userid'] = $orderInfo->uid;
                            $sendParam['paytype'] = $orderInfo->paytype;
                            $sendParam['status'] = '1';
                            $sendParam['out_trade_no'] = $orderInfo->out_trade_no;
                            $sendParam['transaction_id'] = $transaction_id;
                            $sendParam['create_time'] = $orderInfo->create_time;
                            $sendParam['channelid'] = $orderInfo->channelid;
                            $sendParam['body'] = $orderInfo->body;
                            $sendParam['total_fee'] = $orderInfo->total_fee;
                            $sendParam['attach'] = $orderInfo->attach;
                            $sign_string = '';
                            ksort($sendParam);
                            foreach ($sendParam as $key => $value) {
                                $sign_string .=urlencode($key) . '=' . urlencode($value) . '&';
                            }
                            //获取$app_secrect
                            $gameInfo = $this->Game_model->queryGameInfo($orderInfo->appid);
                            $sign_string .= $gameInfo->app_secrect;
                            $sign = md5($sign_string);
                            $sendParam['sign'] = $sign;
                            $sendParam['url'] = $gameInfo->notify_url;
                            $result = self::_sendGoods($sendParam);
                            $this->SendRecord_model->insertSendRecord($sendParam);
                            if ($result == 'ok') {
                                $this->filelog->fileWrite('发货成功' . $result);
                            } else {
                                $this->filelog->fileWrite('发货失败：失败的单号是：' .  $orderInfo->out_trade_no);
                            }
                            exit;
                        } else {
                            echo 'fail';
                            exit;
                        }
                    } else {
                        $this->filelog->fileWrite($out_trade_no . '_' . '金额被非法篡改或者订单号被篡改');
                    }
                } else {
                    $this->filelog->fileWrite($transaction_id . '_' . '单号不存在');
                }
            }
        } else {
            //失败时候写日志并且插入数据库并且返回false
            $this->filelog->fileWrite($this->input->post('orderId', TRUE) . '_' . '校验失败');
            echo false;
        }
    }

    //支付宝支付
    private function _alipay($payInfo) {
        //生成支付宝需要的签名
        $alipay_config = $this->config->item('alipay.config');
        $arr['service'] = "mobile.securitypay.pay";
        $arr['partner'] = $alipay_config['partner'];
        $arr['_input_charset'] = $alipay_config['input_charset'];
        $arr['notify_url'] = $alipay_config['notify_url'];
        $arr['seller_id'] = $alipay_config['seller_id'];
        //商品信息
        $arr['out_trade_no'] = $payInfo['out_trade_no'];
        $arr['subject'] = $payInfo['subject'];
        $arr['payment_type'] = "1";
        $arr['total_fee'] = $payInfo['total_fee'];
        $arr['body'] = $payInfo['body'];
        $arr['it_b_pay'] = "30m";
        $data = createLinkstring($arr);
        $private_key_path = $alipay_config['private_key_path'];
        $sign = rsaSign($data, $private_key_path);
        $result = $data . '"&sign="' . urlencode($sign) . '"&sign_type="RSA"';
        return $result;
    }

    //银联支付
    private function _yinlianpay($payInfo) {
        $in_conf['yinlianLog'] = "yinlianLog";
        $array = array($in_conf['yinlianLog']);
        $this->load->library('FileLog', $array);
        $yinlian_config = $this->config->item('yinlian.config');
        date_default_timezone_set("PRC");
        $params = array(
            'version' => '5.0.0', //版本号
            'encoding' => 'utf-8', //编码方式
            'certId' => getSignCertId(), //证书ID
            'txnType' => '01', //交易类型	 01：消费
            'txnSubType' => '01', //交易子类
            'bizType' => '000201', //业务类型(网关支付)
            'frontUrl' => $yinlian_config['SDK_FRONT_NOTIFY_URL'], //前台通知地址
            'backUrl' => $yinlian_config['SDK_BACK_NOTIFY_URL'], //后台通知地址
            'signMethod' => '01', //签名方法
            'channelType' => '08', //渠道类型，07-PC，08-手机
            'accessType' => '0', //接入类型
            'merId' => $yinlian_config['merId'], //商户代码，请改自己的测试商户号
            'orderId' => $payInfo['out_trade_no'], //商户订单号
            'txnTime' => date('YmdHis', time()), //订单发送时间
            'txnAmt' => $payInfo['total_fee'], //交易金额，单位分
            'currencyCode' => '156', //交易币种
            'defaultPayType' => '0001', //默认支付方式
            'reqReserved' => '0', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );
        //签名
        sign($params);
        $result = sendHttpRequest($params, $yinlian_config['SDK_App_Request_Url']);
        $result_arr = coverStringToArray($result);
        $isSuccess = verify($result_arr);
        if ($isSuccess == 1) {
            return $result_arr;
        } else {
            return false;
        }
    }

    //微信支付
    private function _wxpay($payInfo) {
        //生成支付宝需要的签名
        include_once APPPATH . 'third_party/wxpay/lib/WxPay.Api.php';
        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($payInfo['body']);
        $input->SetAttach($payInfo['attach']);
        $input->SetOut_trade_no($payInfo['out_trade_no']);
        $input->SetTotal_fee($payInfo['total_fee']);
        $input->SetTime_start(date("YmdHis"));
        //$input->SetTime_expire(date("YmdHis", time() + 100));
        $input->SetNotify_url("https://payment.joy4you.com/payment/wxNotifyUrl");
        $input->SetTrade_type("APP");
        $result = WxPayApi::unifiedOrder($input);
        return $result;
    }

    //支付成功后给游戏发货
    private function _sendGoods($param) {
        $data = array(
            'appid' => $param['appid'],
            'userid' => $param['userid'],
            'paytype' => $param['paytype'],
            'body' => $param['body'],
            'status' => $param['status'],
            'out_trade_no' => $param['out_trade_no'],
            'transaction_id' => $param['transaction_id'],
            'create_time' => $param['create_time'],
            'channelid' => $param['channelid'],
            'total_fee' => $param['total_fee'],
            'attach' => $param['attach'],
            'sign' => $param['sign'],
        );
        $postdata = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $param['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证HOST
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        /**
         * 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
         */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 运行cURL，请求网页
        $html = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);
        //$result = json_decode($html, TRUE);
        return $html;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息（支付宝）
     * @return 验证结果
     */
    private function _verifyNotify($param) {
        if (empty($param)) {//判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $isSign = self::_getSignVeryfy($param, $param["sign"]);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';
            if (!empty($param["notify_id"])) {
                $responseTxt = self::_getResponse($param["notify_id"]);
            }
            if (preg_match("/true$/i", $responseTxt) && $isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取返回时的签名验证结果(支付宝)
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    private function _getSignVeryfy($para_temp, $sign) {
        $alipay_config = $this->config->item('alipay.config');
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkVeryfy($para_sort);
        $isSgin = false;
        switch (strtoupper(trim($alipay_config['sign_type']))) {
            case "RSA" :
                $isSgin = rsaVerify($prestr, trim($alipay_config['ali_public_key_path']), $sign);
                break;
            default :
                $isSgin = false;
        }
        return $isSgin;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL（支付宝）
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    private function _getResponse($notify_id) {
        $alipay_config = $this->config->item('alipay.config');
        $transport = strtolower(trim($alipay_config['transport']));
        $partner = trim($alipay_config['partner']);
        $veryfy_url = '';
        if ($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        } else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = getHttpResponseGET($veryfy_url, $alipay_config['cacert']);

        return $responseTxt;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     * */
    private function _toXml($array) {
        if (!is_array($array) || count($array) <= 0) {
            throw new WxPayException("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml.="<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml.="<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 格式化参数格式化成url参数
     */
    private function _toUrlParams() {
        $buff = "";
        foreach ($this->values as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    //刪除多餘的0
    private function _del0($s) {

        for ($i = 0; $i < strlen($s); $i++) {
            $newTotal[$i] = substr($s, $i, 4);
            if (0 == substr($newTotal[$i], 0, 1)) {
                $total_fee[$i] = str_replace(0, "", $newTotal[$i]);
            } else {
                $total_fee = $newTotal[$i];
                break;
            }
        }
        return $total_fee;
    }
    
}
