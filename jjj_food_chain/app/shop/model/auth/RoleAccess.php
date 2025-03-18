<?php

namespace app\shop\model\auth;

use app\common\model\shop\RoleAccess as RoleAccessModel;


/**
 * 角色模型
 */
class RoleAccess extends RoleAccessModel
{
    /**
     * 获取指定角色的所有权限id
     * @param int|array $role_id 角色id (支持数组)
     */
    public static function getAccessIds($role_id)
    {
        $roleIds = is_array($role_id) ? $role_id : [(int)$role_id];
        return (new self)->where('role_id', 'in', $roleIds)->column('access_id');
    }
}
