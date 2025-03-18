<?php

namespace app\shop\model\app;

use app\common\model\app\AppWx as AppWxModel;
use think\facade\Cache;

/**
 * 微信小程序模型
 */
class AppWx extends AppWxModel
{
    /**
     * 更新小程序设置
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            // 删除app缓存
            self::deleteCache();
            $where['app_id'] = self::$app_id;
            $count = $this->getCount($where);
            // 更新小程序设置
            if ($count > 0) {
                self::update($data, $where);
            }
            if ($count == 0) {
                $data['app_id'] = self::$app_id;
                self::create($data);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    public function getCount($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 删除app缓存
     */
    public static function deleteCache()
    {
        return Cache::delete('app_wx_' . self::$app_id);
    }

}
