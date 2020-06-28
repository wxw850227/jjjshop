<?php

namespace app\shop\model\settings;

use app\common\model\settings\ReturnAddress as ReturnAddressModel;

/**
 * 退货地址模型
 */
class ReturnAddress extends ReturnAddressModel
{
    /**
     * 获取列表
     */
    public function getList($params)
    {
        return $this->order(['sort' => 'asc'])
            ->where('is_delete', '=', 0)
            ->paginate($params, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 获取全部收货地址
     */
    public function getAll()
    {
        return $this->order(['sort' => 'asc'])
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 添加新记录
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        return self::create($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data, $where)
    {
        return self::update($data, $where);
    }

    /**
     * 删除记录
     */
    public function del($wehre)
    {
        return self::update(['is_delete' => 1], $wehre);
    }

}