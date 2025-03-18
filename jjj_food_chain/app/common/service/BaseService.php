<?php

namespace app\common\service;

/**
 * 服务基类
 * Interface BaseService
 * @package app\common\model
 */
Class BaseService
{
    public $error = '';

    /**
     * 返回模型的错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }
}