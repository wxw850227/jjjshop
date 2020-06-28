<?php

namespace app\shop\model\shop;

use app\common\model\shop\Access as AccessModel;

/**
 * Class Access
 *  商家用户权限模型
 * @package app\admin\model
 */
class Access extends AccessModel
{
    /**
     * 获取权限列表
     */
    public function getList()
    {
        $all = static::getAll();
        $res = $this->recursiveMenuArray($all, 0);
        return array_values($this->foo($res));

    }

    /**
     * 递归获取获取分类
     */
    public function recursiveMenuArray($data, $pid)
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
    public function foo(&$ar)
    {
        if (!is_array($ar)) return;
        foreach ($ar as $k => &$v) {
            if (is_array($v)) $this->foo($v);
            if ($k == 'children') $v = array_values($v);
        }
        return $ar;
    }


}