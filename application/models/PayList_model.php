<?php
/**
 * Project:     乐恒互动支付系统系统
 * File:        PayList_model.php
 *
 * <pre>
 * 描述：支付方式列表
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨 <768216363@qq.com>
 * @copyright 2015 Joy4you paymentSDK, Inc.
 */
class PayList_model extends CI_Model {
    /**
     * 数据库表名
     * 
     * @var array
     */
    
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'paylist';
        parent::__construct();
    }
   
    /**
     * 获取支付列表
     * @param int $appid 游戏ID
     * @param int $channelid 渠道ID
     * @return array
     */
    function getPayList($appid, $channelid) {
        
        $sql = "select appid,channelid,paytype,payname from " . $this->db_name ." where appid='$appid' AND channelid='$channelid'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            $row = $query->result_array();
            return $row;
        } else {
            return FALSE;
        }
    }
}