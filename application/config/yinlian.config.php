<?php


// cvn2加密 1：加密 0:不加密
$config['SDK_CVN2_ENC'] = 0;
// 有效期加密 1:加密 0:不加密
$config['SDK_DATE_ENC'] = 0;
// 卡号加密 1：加密 0:不加密
$config['SDK_PAN_ENC'] = 0;
//商户号
$config['merId'] = '898111448161448';
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径
$config['SDK_SIGN_CERT_PATH'] = APPPATH.'third_party/yinlianpay/certs/PM_700000000000002_acp.pfx';

// 签名证书密码
$config['SDK_SIGN_CERT_PWD'] = '000000';

$config['SDK_VERIFY_CERT_PATH'] = APPPATH.'third_party/yinlianpay/certs/EbppRsaCert.cer';

// 密码加密证书（这条用不到的请随便配
$config['SDK_ENCRYPT_CERT_PATH'] = APPPATH.'third_party/yinlianpay/certs/verify_sign_acp.cer';

// 验签证书路径（请配到文件夹，不要配到具体文件
$config['SDK_VERIFY_CERT_DIR'] = APPPATH.'third_party/yinlianpay/certs/';

// 前台请求地址
$config['SDK_FRONT_TRANS_URL'] = 'https://gateway.95516.com/gateway/api/frontTransReq.do';

// 后台请求地址
$config['SDK_BACK_TRANS_URL'] = 'https://gateway.95516.com/gateway/api/backTransReq.do';

// 批量交易
$config['SDK_BATCH_TRANS_URL'] = 'https://gateway.95516.com/gateway/api/batchTrans.do';

//单笔查询请求地址
$config['SDK_SINGLE_QUERY_URL'] = 'https://gateway.95516.com/gateway/api/queryTrans.do';

//文件传输请求地址
$config['SDK_FILE_QUERY_URL'] = 'https://filedownload.95516.com/';

//有卡交易地址
$config['SDK_Card_Request_Url'] = 'https://gateway.95516.com/gateway/api/cardTransReq.do';

//App交易地址
$config['SDK_App_Request_Url'] = 'https://gateway.95516.com/gateway/api/appTransReq.do';


// 前台通知地址 (商户自行配置通知地址)
$config['SDK_FRONT_NOTIFY_URL'] = 'http://localhost:8085/upacp_sdk_php/demo/utf8/FrontReceive.php';
// 后台通知地址 (商户自行配置通知地址)
$config['SDK_BACK_NOTIFY_URL'] = 'https://payment.joy4you.com/payment/yinlianNotifyUrl';

	
?>