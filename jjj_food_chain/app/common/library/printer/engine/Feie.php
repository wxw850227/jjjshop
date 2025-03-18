<?php

namespace app\common\library\printer\engine;

use app\common\library\printer\party\FeieHttpClient;

/**
 * 飞鹅打印机API引擎
 */
class Feie extends Basics
{
    // 接口IP或域名
    const IP = 'api.feieyun.cn';

    // 接口IP端口
    const PORT = 80;

    // 接口路径
    const PATH = '/Api/Open/';

    /**
     * 执行订单打印
     */
    public function printTicket($content)
    {
        // 构建请求参数
        $params = $this->getParams($content);
        // API请求：开始打印
        $client = new FeieHttpClient(self::IP, self::PORT);
        if (!$client->post(self::PATH, $params)) {
            $this->error = $client->getError();
            return false;
        }
        // 处理返回结果
        $result = json_decode($client->getContent());
        log_write($result);
        // 返回状态
        if ($result->ret != 0) {
            $this->error = $result->msg;
            return false;
        }
        return true;
    }

    /**
     * 构建Api请求参数
     */
    private function getParams(&$content)
    {
        $config = json_decode($this->config, true);
        $time = time();
        return [
            'user' => $config['USER'],
            'stime' => $time,
            'sig' => sha1("{$config['USER']}{$config['UKEY']}{$time}"),
            'apiname' => 'Open_printMsg',
            'sn' => $config['SN'],
            'content' => $content,
            'times' => $this->times    // 打印次数
        ];
    }

}