<?php
/**
 * Project:     日志类
 * File:        FileLog.php
 *
 * <pre>
 * 描述：日志操作类
 * </pre>
 *
 * @category   PHP
 * @package    Include
 * @subpackage File
 * @author     liyang <768216362@qq.com>
 * @license    BSD Licence
 */

class FileLog
{
    /**
     * 错误代码 0、正常，1、目录创建失败，2、写入内容为空，3、文件写入失败，4、清理过期日志失败
     * @var integer
     */
    private $_errorno = 0;

    /**
     * 日志文件路径及名称
     * @var string
     */
    private $_logfile = '';

    /**
     * 日志主目录
     * @var string
     */
    private $_dir = '';

    /**
     * 默认保留几个月的日志
     * @var integer
     */
    private $_keepmonth = 2;

    /**
     * 构造函数（日志目录的检查、创建和删除过期日志。记日志的策略先按事务分开目录，每个事的日志目录下按年、月再分目录，月的目录中存储每天一个的日志文件）
     * @param string $task 记日志的事务名字，用来区分日志路径
     */
    public function __construct($param)
    {
        $this->_task = $param[0];
        $this->_dir = APPPATH . DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR;
        $dir = ($this->_dir) . $this->_task . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');

        //检查目录是否存在，若不存在则创建之
        if (!is_dir($dir)) {
            if (false === mkdir($dir, 0777, true)) {
                $this->_errorno = 1;
            }
        }
        $this->_logfile = $dir . DIRECTORY_SEPARATOR . $this->_task . date('Ymd') . '.log';
    }


    /**
     * 写日志
     * @param string $str 记录内容
     * @return boolean
     */
    public function fileWrite($str)
    {
        //检查$str是否为空
        $str = chop($str);
        if (empty($str)) {
            $this->_errorno = 2;
            return false;
        }
        
        //每天的日志写一个文件，所以都是追加模式
        $handle = fopen($this->_logfile, 'ab');
        //加个换行用
        $res = fwrite($handle, $str."\n");
        fclose($handle);

        if ($res === false) {
            $this->_errorno = 3;
            return false;
        }

        return true;
    }

    /**
     * 删除过期日志
     * @return null
     */
    public function clearExpireLog()
    {
        $keep = $this->_keepmonth;
        $yearnow = date('Y');
        $monthnow = date('m');
        //年初的月再加上 12
        $monthdelete = ($monthnow <= $keep) ? ($monthnow + 12 - $keep) : ($monthnow - $keep);
        //不是年初的月由于数学操作会变成一位数，还得再变成2位数，方便后面找目录
        $monthdelete = sprintf('%02d', $monthdelete);
        $yeardelete = ($monthnow <= $keep) ? ($yearnow - 1) :$yearnow;

        $this->fileWrite(date('Y-m-d H:i:s').' Deleting log.');

        //加上 GLOB_ONLYDIR 仅返回路径，不要文件
        //这里 glob 的模式其实是完整的物理路径 D:\soufun\comment\data\log\*\*\*，允许这样多级用星号通配，则可一次性遍历出 D:\soufun\comment\data\log 下全部再3级的子目录，如 D:\soufun\comment\data\log\add_audit\2008\11 或者 D:\soufun\comment\data\log\rsync_audit\2008\12
        foreach (glob($this->_dir.'*'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $logthis) {
            //截掉最后 7 位的年和月数，再加上要删除的年月数
            $dir2delete = substr($logthis, 0, -7).$yeardelete.DIRECTORY_SEPARATOR.$monthdelete;

            //再比较，凡是早于要删除的年月数的文件夹，一律删除
            if (($logthis <= $dir2delete ) && is_dir($logthis)) {
                $files = glob($logthis.DIRECTORY_SEPARATOR.'*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                $msg = date('Y-m-d H:i:s').' '.$logthis;
                if (rmdir($logthis)) {
                    $msg .= ' deleted.';
                } else {
                    $msg .= ' failed.';
                    $this->_errorno = 4;
                }

                $this->fileWrite($msg);
            }
        }

        $msg = date('Y-m-d H:i:s').' Done.';

        $this->fileWrite($msg);
    }

    /**
     * 返回错误代码
     * @return integer
     */
    public function errorno()
    {
        return $this->_errorno;
    }
}

/* End of file FileLog.php */
