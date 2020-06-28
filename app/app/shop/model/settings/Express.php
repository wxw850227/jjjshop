<?php

namespace app\shop\model\settings;

use app\common\model\settings\Express as ExpressModel;

/**
 * 物流公司模型
 */
class Express extends ExpressModel
{

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
        return self::destroy($wehre);
    }
}