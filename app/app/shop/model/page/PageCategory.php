<?php

namespace app\shop\model\page;

use app\common\model\page\PageCategory as PageCategoryModel;

/**
 * app分类页模板模型
 */
class PageCategory extends PageCategoryModel
{
    /**
     * 编辑记录
     */
    public function edit($data)
    {
        return $this->save($data);
    }

}
