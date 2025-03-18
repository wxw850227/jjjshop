<?php

namespace app\shop\model\shop;

use app\common\model\shop\Access as AccessModel;
use app\shop\model\auth\RoleAccess;
use app\shop\model\auth\UserRole;

/**
 * Class Access
 *  商家用户权限模型
 */
class Access extends AccessModel
{
    /**
     * 获取权限列表
     */
    public function getList($user_type = 0, $supplier = "")
    {
        $all = static::getAll(1, $user_type, $supplier);
        $res = $this->recursiveMenuArray($all, 0);
        return array_values($this->foo($res));
    }

    public function getListByUser($shop_user_id, $user_type, $supplier)
    {
        // 获取当前用户的角色集
        $roleIds = UserRole::getRoleIds($shop_user_id);
        // 根据已分配的权限
        $accessIds = RoleAccess::getAccessIds($roleIds);
        // 获取当前角色所有权限链接
        $menus_list = AccessModel::getAccessList($accessIds, $user_type, $supplier);
        // 格式化
        return $this->formatTreeData($menus_list, 0);
    }

    // 循环获取分类
    private function formatTreeData($all, $parent_id = 0)
    {
        $tree = array();
        foreach ($all as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                //父亲找到儿子
                $v['children'] = $this->formatTreeData($all, $v['access_id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 递归获取获取分类
     */
    private function recursiveMenuArray($data, $pid)
    {
        $re_data = [];
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $pid) {
                $re_data[$value['access_id']] = $value;
                $re_data[$value['access_id']]['children'] = $this->recursiveMenuArray($data, $value['access_id']);
            } else {
                continue;
            }
        }
        return $re_data;
    }

    /**
     * 格式化递归数组下标
     */
    private function foo(&$ar)
    {
        if (!is_array($ar)) return;
        foreach ($ar as $k => &$v) {
            if (is_array($v)) $this->foo($v);
            if ($k == 'children') $v = array_values($v);
        }
        return $ar;
    }
}