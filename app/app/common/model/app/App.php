<?php

namespace app\common\model\app;

use app\common\exception\BaseException;
use think\facade\Cache;
use app\common\model\BaseModel;

/**
 * 应用模型
 */
class App extends BaseModel
{
    //表名
    protected $name = 'app';
    //主键字段名
    protected $pk = 'app_id';

    /**
     * 获取应用信息
     */
    public static function detail($app_id)
    {
        return self::find($app_id);
    }


    /**
     * 从缓存中获取应用信息
     */
    public static function getAppCache($app_id = null)
    {
        if (is_null($app_id)) {
            $self = new static();
            $app_id = $self::$app_id;
        }
        if (!$data = Cache::get('app_' . $app_id)) {
            $data = self::detail($app_id);
            if (empty($data)) throw new BaseException(['msg' => '未找到当前应用信息']);
            Cache::tag('cache')->set('app_' . $app_id, $data);
        }
        return $data;
    }

}
