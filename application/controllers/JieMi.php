<?php

/**
 * Project:     des解密工具
 * File:        Jiemi.php
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
class JieMi extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    //解密页面
    public function jiemipage() {
        $this->load->view('jiemi');
    }
    
    public function ajaxJiami() {
        $key = $this->input->get_post('key', TRUE);
        $mingwen = $this->input->get_post('mingwen', TRUE);
        $array = array($key, $key);
        $this->load->library('DES', $array);
        $param = $this->des->encrypt($mingwen);
        echo $param;
        
    }
    
    public function ajaxJiemi() {
        $key = $this->input->get_post('key', TRUE);
        $miwen = $this->input->get_post('miwen', TRUE);
        $array = array($key, $key);
        $this->load->library('DES', $array);
        $param = $this->des->decrypt($miwen);
        echo $param;
        
    }
    
}
