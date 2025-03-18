<?php

namespace app\admin\model;

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
        $all = static::getAll(-1);
        $res = $this->recursiveMenuArray($all, 0);
        return array_values($this->foo($res));

    }

    /**
     * 新增记录
     */
    public function add($data)
    {
        // 校验路径
        if(!$this->validate($data)){
            return false;
        }
        $data['access_id'] = time();
        $data['app_id'] = self::$app_id;
        return $this->save($data);
    }

    /**
     * 更新记录
     */
    public function edit($data)
    {
        if ($data['access_id'] == $data['parent_id']) {
            $this->error = '上级菜单不允许设置为当前菜单';
            return false;
        }
        // 判断上级角色是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopAccessIds($data['parent_id']);
            if (in_array($data['access_id'], $parentIds)) {
                $this->error = '上级菜单不允许设置为当前子菜单';
                return false;
            }
        }
        // 校验路径,不限制大小写
        if(strtolower($data['path']) !== strtolower($this['path'])){
            if(!$this->validate($data)){
                return false;
            }
        }

        $data['redirect_name'] = ($data['is_route'] == 1) ? $data['redirect_name'] : '';
        $data['icon'] = $data['icon'];
        return $this->save($data);
    }

    /**
     * 验证
     */
    private function validate($data){
         $count = $this->where(['path' => $data['path']])->count();
         if($count > 0){
             $this->error = '路径已存在，请重新更改';
             return false;
         }
         return true;
    }

    public function getChildCount($where)
    {
        return $this->where($where)->count();
    }


    /**
     * 删除权限
     */
    public function remove($access_id)
    {
        return $this->where('access_id', '=', $access_id)->delete();
    }
    /**
     * 删除插件
     */
    public function removePlus()
    {
        return $this->save([
            'plus_category_id' => 0
        ]);
    }

    /**
     * 获取所有上级id集
     */
    public function getTopAccessIds($access_id, &$all = null)
    {
        static $ids = [];
        is_null($all) && $all = $this->getAll();

        foreach ($all as $item) {
            if ($item['access_id'] == $access_id && $item['parent_id'] > 0) {
                $ids[] = $item['parent_id'];
                $this->getTopAccessIds($item['parent_id'], $all);
            }
        }

        return $ids;
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

    /**
     * 更改显示状态
     */
    public function status($status){
        return $this->save([
            'is_show' => $status
        ]);
    }
    /**
     * 更改门店菜单状态
     */
    public function supplier($status){
        return $this->save([
            'is_supplier' => $status
        ]);
    }
    /**
     * 获取所有插件
     */
    public static function getAllPlus(){
        $model = new static();
        $plus = $model->where('path', '=', '/plus/plus/index')->find();
        return $model->where('parent_id', '=', $plus['access_id'])
            ->where('plus_category_id', '=', 0)
            ->select();
    }

    /**
     * 保存插件分类
     * @param $data
     */
    public function addPlus($data){
        $model = new self();
        return $model->where('access_id', '=', $data['access_id'])->save([
            'plus_category_id' => $data['plus_category_id']
        ]);
    }
}