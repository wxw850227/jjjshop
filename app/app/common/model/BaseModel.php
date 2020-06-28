<?php

namespace app\common\model;

use app\common\exception\BaseException;
use think\facade\Env;
use think\Model;

/**
 * 模型基类
 */
class BaseModel extends Model
{
    //应用id
    public static $app_id;
    //根url
    public static $base_url;
    //别名
    protected $alias = '';
    //错误信息
    protected $error = '';

    // 定义全局的查询范围
    protected $globalScope = ['app_id'];

    /**
     * 模型基类初始化
     */
    public static function init()
    {
        parent::init();
        // 获取当前域名
        self::$base_url = base_url();
        // 后期静态绑定app_id
        self::bindAppId();
    }


    /**
     * 后期静态绑定类名称
     */
    private static function bindAppId()
    {
        if ($app = app('http')->getName()) {
            if($app != 'admin' && $app != 'job'){
                $callfunc = 'set' . ucfirst($app) . 'AppId';
                self::$callfunc();
            }
        }
    }

    /**
     * 设置app_id (store模块)
     */
    protected static function setShopAppId()
    {
        $session = session('jjjshop_store');
        self::$app_id = $session['app']['app_id'];
    }

    /**
     * 设置app_id (api模块)
     */
    protected static function setApiAppId()
    {
        self::$app_id = request()->param('app_id');
    }

    /**
     * 定义全局的查询范围
     */
    public function scopeApp_id($query)
    {
        if (self::$app_id > 0) {
            $query->where($query->getTable() . '.app_id', self::$app_id);
        }
    }

    /**
     * 设置默认的检索数据
     */
    protected function setQueryDefaultValue(&$query, $default = [])
    {
        $data = array_merge($default, $query);
        foreach ($query as $key => $val) {
            if (empty($val) && isset($default[$key])) {
                $data[$key] = $default[$key];
            }
        }
        return $data;
    }

    /**
     * 设置基础查询条件（用于简化基础alias和field）
     */
    public function setBaseQuery($alias = '', $join = [])
    {
        // 设置别名
        $aliasValue = $alias ?: $this->alias;
        $model = $this->alias($aliasValue)->field("{$aliasValue}.*");
        // join条件
        if (!empty($join)) : foreach ($join as $item):
            $model->join($item[0], "{$item[0]}.{$item[1]}={$aliasValue}."
                . (isset($item[2]) ? $item[2] : $item[1]));
        endforeach; endif;
        return $model;
    }

    /**
     * 批量更新数据(支持带where条件)
     */
    public function updateAll($data)
    {
        return $this->transaction(function () use ($data) {
            $result = [];
            foreach ($data as $key => $item) {
                $result[$key] = self::update($item['data'], $item['where']);
            }
            return $this->toCollection($result);
        });
    }

    public static function onBeforeUpdate(Model $model){
        if($model->createTime && $model[$model->createTime]){
            unset($model[$model->createTime]);
        }
        if ($model->updateTime && $model[$model->updateTime]) {
            $model[$model->updateTime] = $model->autoWriteTimestamp($model->updateTime);
        }
    }

    /**
     * 获取当前调用的模块名称
     */
    protected static function getCalledModule()
    {
        if (preg_match('/app\\\(\w+)/', get_called_class(), $class)) {
            return $class[1];
        }
        return false;
    }
    /**
     * 返回模型的错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}
