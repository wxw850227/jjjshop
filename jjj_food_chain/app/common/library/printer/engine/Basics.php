<?php

namespace app\common\library\printer\engine;

/**
 * 小票打印机驱动基类
 */
abstract class Basics
{
    protected $config;  // 打印机配置
    protected $times;   // 打印联数(次数)
    protected $error;   // 错误信息

    /**
     * 构造函数
     */
    public function __construct($config, $times)
    {
        $this->config = $config;
        $this->times = $times;
    }

    /**
     * 执行打印请求
     */
    abstract protected function printTicket($content);

    /**
     * 返回错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 创建打印的内容
     */
    private function setContentText()
    {
        return '';
    }


}