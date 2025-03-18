<?php

namespace app\common\model\settings;

use think\facade\Request;
use app\common\enum\settings\PrinterTypeEnum;
use app\common\model\BaseModel;

/**
 * 打印机模型
 */
class Printer extends BaseModel
{
    protected $name = 'printer';
    protected $pk = 'printer_id';

    /**
     * 获取打印机类型列表
     */
    public static function getPrinterTypeList()
    {
        static $printerTypeEnum = [];
        if (empty($printerTypeEnum)) {
            $printerTypeEnum = PrinterTypeEnum::getTypeName();
        }
        return $printerTypeEnum;
    }

    /**
     * 打印机类型名称
     */
    public function getPrinterTypeAttr($value)
    {
        $printerType = self::getPrinterTypeList();
        return ['value' => $value, 'text' => $printerType[$value]];
    }

    /**
     * 自动转换printer_config为array格式
     */
    public function getPrinterConfigAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 自动转换printer_config为json格式
     */
    public function setPrinterConfigAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取全部
     */
    public static function getAll($shop_supplier_id = 0)
    {
        $where = [];
        if ($shop_supplier_id) {
            $where['shop_supplier_id'] = $shop_supplier_id;
        }
        return (new static)->where('is_delete', '=', 0)
            ->where($where)
            ->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     */
    public function getList($limit = 10, $shop_supplier_id = 0)
    {
        $where = [];
        if ($shop_supplier_id) {
            $where['shop_supplier_id'] = $shop_supplier_id;
        }
        return $this->where('is_delete', '=', 0)
            ->where($where)
            ->order(['sort' => 'asc'])
            ->paginate($limit, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 物流公司详情
     */
    public static function detail($printer_id)
    {
        return self::find($printer_id);
    }

}
