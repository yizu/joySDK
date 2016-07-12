<?php
/**
 * Project:     乐恒互动用户系统
 * File:        Game_model.php
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
class Game_model extends CI_Model {
    /**
     * 数据库表名
     * 
     * @var array
     */
    
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'game';
        parent::__construct();
    }
   
    /**
     * 查看appid是否合法
     * @param int $username 用户名
     * @param int $ckid 设备加密串
     * @return array
     */
    function checkAppid($appid) {
        
        $sql = "select appid from " . $this->db_name ." where appid='$appid'";
        $query = $this->db->query($sql);
        $this->db->last_query();
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['appid'];
        } else {
            return FALSE;
        }
    }
    
    /**
     * 通过appid查询game配置信息
     * @param int $appid 游戏唯一标示
     * @return array
     */
     function queryGameInfo($appid) {

        $this->db->where('appid', $appid);
        $query = $this->db->get($this->db_name);
        $row = $query->row();
        if ($row) {
            return $row;
        } else {
            return FALSE;
        }
        
    }
    
}