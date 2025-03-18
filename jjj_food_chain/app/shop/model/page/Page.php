<?php

namespace app\shop\model\page;

use app\common\model\page\Page as PageModel;
use app\shop\model\app\App;

/**
 * 微信小程序diy页面模型
 */
class Page extends PageModel
{
    /**
     * 获取列表
     */
    public function getList($params)
    {
        return $this->where(['is_delete' => 0])
            ->where(['page_type' => 20])
            ->order(['create_time' => 'desc'])
            ->hidden(['page_data'])
            ->paginate($params);
    }

    /**
     * 更新页面
     */
    public function edit($data)
    {
        // 删除app缓存
        App::deleteCache();
        // 保存数据
        return $this->save([
                'page_name' => $data['page']['params']['name'],
                'page_data' => $data
            ]) !== false;
    }
}
