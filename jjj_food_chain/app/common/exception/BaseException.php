<?php

namespace app\common\exception;

use think\Exception;

/**
 * 自定义异常类的基类
 */
class BaseException extends Exception
{
    public $code = 0;
    public $message = 'invalid parameters';

    /**
     * 构造函数，接收一个关联数组
     */
    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->message = $params['msg'];
        }
    }
}

