<?php

namespace app\common\model\product;

use app\common\model\BaseModel;

/**
 * 规格/属性(组)模型
 */
class Spec extends BaseModel
{
    protected $name = 'spec';
    protected $pk = 'spec_id';
    protected $updateTime = false;

    //更新规格
    public function updateSpec($data)
    {
        if ($data) {
            $addData = [];
            foreach ($data as $item) {
                if ($item['spec_name']) {
                    $isExit = $this->where('spec_name', '=', $item['spec_name'])->count();
                    if ($isExit == 0) {
                        $addData[] = [
                            'spec_name' => $item['spec_name'],
                            'app_id' => self::$app_id
                        ];
                    }
                }
            }
            $addData && $this->saveAll($addData);
        }
    }
    /**
     * 获取列表数据
     */
    public function getAllList()
    {
        $model = $this;
        return $model
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }
    /**
     * 详情
     */
    public static function detail($spec_id)
    {
        return self::find($spec_id);
    }
}
