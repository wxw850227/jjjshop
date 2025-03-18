<?php


namespace app\common\model\store;

use app\common\model\BaseModel;

/**
 * 门店类型模型
 */
class TableType extends BaseModel
{
    protected $pk = 'type_id';
    protected $name = 'table_type';

    /**
     * 关联门店
     */
    public function supplier()
    {
        return $this->BelongsTo('app\\common\\model\\supplier\\Supplier', 'shop_supplier_id', 'shop_supplier_id');
    }

    /**
     * 桌位类型详情
     */
    public static function detail($where)
    {
        $filter = is_array($where) ? $where : ['type_id' => $where];
        return static::where($filter)->find();
    }

    /**
     * 获取所有门店列表
     */
    public static function getAllList($shop_supplier_id)
    {
        return (new self)->where('shop_supplier_id', '=', $shop_supplier_id)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }
}