<?php

namespace app\common\library\printer\engine;


class PrintCenter extends Basics
{
    // API地址
    const API = 'http://open.printcenter.cn:8080/addOrder';

    /**
     * 执行订单打印
     */
    public function printTicket($content)
    {
        $config = json_decode($this->config, true);
        // 构建请求参数
        $context = stream_context_create([
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded ",
                'method' => 'POST',
                'content' => http_build_query([
                    'deviceNo' => $config['deviceNo'],
                    'key' => $config['key'],
                    'printContent' => $content,
                    'times' => $this->times
                ]),
            ]
        ]);
        // API请求：开始打印
        $result = file_get_contents(self::API, false, $context);
        // 处理返回结果
        $result = json_decode($result);
        log_write($result);
        // 返回状态
        if ($result->responseCode != 0) {
            $this->error = $result->msg;
            return false;
        }
        return true;
    }

}