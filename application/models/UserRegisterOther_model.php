<?php

/**
 * Project:     乐恒互动用户系统
 * File:        UserRegisterOther_model.php
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
class UserRegisterOther_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    function __construct() {
        $this->load->database('joy_user_1');
        $this->db_name = 'user_register_other';
        parent::__construct();
    }

    /**
     * 注册，插入用户基本设备信息
     * @param int $param 设备信息数组
     * @return array
     */
    function insertUserOtherInfo($param) {
        $data = array(
            'uid' => $param['uid'],
            'sex' => $param['sex'],
            'nickname' => $param['nickname'],
            'birthday'=>$param['birthday'],
            'from'=>$param['from'],
            'chid'=>$param['chid'],
            'sdkverison'=>$param['sdkverison'],
            'sdkjsversion'=>$param['sdkjsversion'],
            'systemhardware'=>$param['systemhardware'],
            'telecomoper'=> $param['telecomoper'],
            'network'=>$param['network'],
            'screenwidth'=>$param['screenwidth'],
            'screenhight'=>$param['screenhight'],
            'density'=>$param['density'],
            'channelid'=>$param['channelid'],
            'cpuhardware'=>$param['cpuhardware'],
            'memory'=>$param['memory'],
            'dt'=>$param['dt'],
            'dm'=>$param['dm'],
            'osv'=>$param['osv'],
            'mac'=>$param['mac'],
            'imei'=>$param['imei'],
            'srl'=>$param['srl'],
            'pkg'=>$param['pkg'],
            'bn'=>$param['bn'],
            'idfa'=>$param['idfa'],
            'registertime'=>time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    
    /**
     * 注册，插入用户基本设备信息
     * @param int $param 设备信息数组
     * @return array
     */
    function insertTouristOtherInfo($param) {
        $data = array(
            'uid' => $param['uid'],
            'sex' => $param['sex'],
            'nickname' => $param['nickname'],
            'birthday'=>$param['birthday'],
            'from'=>$param['from'],
            'chid'=>$param['chid'],
            'sdkverison'=>$param['sdkverison'],
            'sdkjsversion'=>$param['sdkjsversion'],
            'systemhardware'=>$param['systemhardware'],
            'telecomoper'=> $param['telecomoper'],
            'network'=>$param['network'],
            'screenwidth'=>$param['screenwidth'],
            'screenhight'=>$param['screenhight'],
            'density'=>$param['density'],
            'channelid'=>$param['channelid'],
            'cpuhardware'=>$param['cpuhardware'],
            'memory'=>$param['memory'],
            'dt'=>$param['dt'],
            'dm'=>$param['dm'],
            'osv'=>$param['osv'],
            'mac'=>$param['mac'],
            'imei'=>$param['imei'],
            'srl'=>$param['srl'],
            'pkg'=>$param['pkg'],
            'bn'=>$param['bn'],
            'idfa'=>$param['idfa'],
            'registertime'=>time(),
        );
        $result = $this->db->insert($this->db_name, $data);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }

    /**
     * 获取7天内注册数据结合
     * @return array
     */
    function getRegisterCountData() {
        $sql = "select date_format(dtEventTime,'%Y-%m-%d') as date, COUNT('vopenid') as sum from " . $this->db_name .
                " where DateDiff(dtEventTime,now()) < 7 group by date_format(dtEventTime,'%Y-%m-%d') order by dtEventTime desc limit 7;";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                //拼接走势图需要的数据格式
                //$tempStr =  '[' . ($key+1) . ',' . $row['sum'] . ']' ;
                $temp[] = array($key, $row['sum']);
                $result[$key] = array($temp[$key][0], $temp[$key][1]);
            }
            return $result;
        } else {
            return FALSE;
        }
    }

}
