<?php


namespace app\common\model\store;

use app\common\model\BaseModel;

/**
 * 门店区域模型
 */
class TableArea extends BaseModel
{
    protected $pk = 'area_id';
    protected $name = 'table_area';

    /**
     * 关联门店
     */
    public function supplier()
    {
        return $this->BelongsTo('app\\common\\model\\supplier\\Supplier', 'shop_supplier_id', 'shop_supplier_id');
    }

    /**
     * 桌位详情
     */
    public static function detail($where)
    {
        $filter = is_array($where) ? $where : ['area_id' => $where];
        return static::where($filter)->find();
    }

    /**
     * 获取所有列表
     */
    public static function getAllList($shop_supplier_id)
    {
        return (new self)->where('shop_supplier_id', '=', $shop_supplier_id)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }
}