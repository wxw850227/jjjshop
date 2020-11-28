<?php

namespace app\common\model\app;

use app\common\exception\BaseException;
use think\facade\Cache;
use app\common\model\BaseModel;

/**
 * 微信公众号模型
 */
class AppMp extends BaseModel
{
    protected $name = 'app_mp';
    protected $pk = 'app_id';


    /**
     * 获取公众号信息
     */
    public static function detail($app_id = null)
    {
        if($app_id == null){
            $app_id = self::$app_id;
        }
        return self::find($app_id);
    }

    /**
     * 从缓存中获取公众号信息
     */
    public static function getAppMpCache($app_id = null)
    {
        if (is_null($app_id)) {
            $self = new static();
            $app_id = $self::$app_id;
        }
        if (!$data = Cache::get('app_mp_' . $app_id)) {
            $data = self::detail($app_id);
            if (empty($data)) throw new BaseException(['msg' => '未找到当前公众号信息']);
            Cache::tag('cache')->set('app_mp_' . $app_id, $data);
        }
        return $data;
    }

}
