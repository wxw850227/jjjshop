<?php
/**
 * Snowflake 生成唯一ID算法，固定返回19位
 */

namespace app\common\service\order;

define('EPOCH', 1579533469598); // 当前时间（毫秒）
define('NUMWORKERBITS', 10);
define('NUMSEQUENCEBITS', 12);
define('MAXWORKERID', (-1 ^ (-1 << NUMWORKERBITS)));    // 集群ID + 机器ID， 10位，最多支持1024台机器
define('MAXSEQUENCE', (-1 ^ (-1 << NUMSEQUENCEBITS)));  // 序列，12位，每台机器每毫秒内最多产生4096个序列号

class Snowflake
{
    private $_lastTimestamp;
    private $_sequence = 0;
    private $_workerId = 1;

    public function __construct($workerId)
    {
        if (($workerId < 0) || ($workerId > MAXWORKERID)) {
            return null;
        }
        $this->_workerId = $workerId;
    }

    public function next()
    {
        $ts = $this->timestamp();
        if ($ts == $this->_lastTimestamp) {
            $this->_sequence = ($this->_sequence + 1) & MAXSEQUENCE;
            if ($this->_sequence == 0) {
                $ts = $this->waitNextMilli($ts);
            }
        } else {
            $this->_sequence = 0;
        }

        if ($ts < $this->_lastTimestamp) {
            return 0;
        }

        $this->_lastTimestamp = $ts;

        $return_pack = $this->pack();
        if (strlen($return_pack) < 19) $return_pack = str_pad($return_pack, 19, '0');
        return $return_pack;
    }

    private function pack()
    {
        return ($this->_lastTimestamp << (NUMWORKERBITS + NUMSEQUENCEBITS)) | ($this->_workerId << NUMSEQUENCEBITS) | $this->_sequence;
    }

    private function waitNextMilli($ts)
    {
        if ($ts = $this->_lastTimestamp) {
            sleep(0.1);
            $ts = $this->timestamp();
        }

        return $ts;
    }

    private function timestamp()
    {
        return $this->millitime() - EPOCH;
    }

    private function millitime()
    {
        $microtime = microtime();
        $comps = explode(' ', $microtime);
        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }
}