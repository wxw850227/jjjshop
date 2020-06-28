<?php
use app\common\exception\ExceptionHandler;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandler::class,
];
