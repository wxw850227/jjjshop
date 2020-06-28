<?php

namespace app\common\model\app;

use app\common\exception\BaseException;
use think\facade\Cache;
use app\common\model\BaseModel;

/**
 * 微信小程序模型
 */
class AppWx extends BaseModel
{
    //表名
    protected $name = 'app_wx';
    //主键字段名
    protected $pk = 'app_id';

    /**
     * 获取小程序信息
     */
    public static function detail($app_id = null)
    {
        return self::find(self::$app_id);
    }

    /**
     * 从缓存中获取小程序信息
     */
    public static function getAppWxCache($app_id = null)
    {
        if (is_null($app_id)) {
            $self = new static();
            $app_id = $self::$app_id;
        }
        if (!$data = Cache::get('app_wx_' . $app_id)) {
            $data = self::detail($app_id);
            if (empty($data)) throw new BaseException(['msg' => '未找到当前小程序信息']);
            Cache::set('app_wx_' . $app_id, $data);
        }
        return $data;
    }

}
