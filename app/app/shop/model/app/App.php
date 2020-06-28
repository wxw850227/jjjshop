<?php

namespace app\shop\model\app;

use app\common\model\app\App as AppModel;
use think\facade\Cache;

/**
 * 应用模型
 */
class App extends AppModel
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

            $count = $this->count($where);
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

    public function count($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 删除app缓存
     */
    public static function deleteCache()
    {
        return Cache::delete('app_' . self::$app_id);
    }

}
