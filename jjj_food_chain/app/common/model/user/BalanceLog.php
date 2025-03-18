<?php

namespace app\common\model\user;

use app\common\model\BaseModel;
use app\common\enum\user\balanceLog\BalanceLogSceneEnum;

/**
 * 用户余额变动明细模型
 */
class BalanceLog extends BaseModel
{
    protected $name = 'user_balance_log';
    protected $updateTime = false;

    /**
     * 获取当前模型属性
     */
    public static function getAttributes()
    {
        return [
            // 充值方式
            'scene' => BalanceLogSceneEnum::data(),
        ];
    }

    /**
     * 关联会员记录表
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\user\\User");
    }

    /**
     * 余额变动场景
     */
    public function getSceneAttr($value)
    {
        return ['text' => BalanceLogSceneEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 新增记录
     */
    public static function add($scene, $data, $describeParam)
    {
        $model = new static;
        $model->save(array_merge([
            'scene' => $scene,
            'describe' => vsprintf(BalanceLogSceneEnum::data()[$scene]['describe'], $describeParam),
            'app_id' => $model::$app_id
        ], $data));
    }

}