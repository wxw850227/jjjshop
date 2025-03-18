<?php

namespace app\common\model\shop;


use app\common\model\BaseModel;

/**
 * 应用模型
 */
class RoleAccess extends BaseModel
{
    protected $name = 'shop_role_access';
    protected $pk = 'id';

    /**
     * 权限
     * @return \think\model\relation\BelongsTo
     */
    public function shopAccess()
    {
        return $this->belongsTo('Access', 'access_id', 'access_id');
    }
}
