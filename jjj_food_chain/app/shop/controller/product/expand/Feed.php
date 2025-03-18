<?php

namespace app\shop\controller\product\expand;

use app\shop\controller\Controller;
use app\shop\model\product\Feed as FeedModel;

/**
 * 加料库控制器
 */
class Feed extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $model = new FeedModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = new FeedModel();
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑
     */
    public function edit($feed_id)
    {
        // 详情
        $model = FeedModel::detail($feed_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除商品
     */
    public function delete($feed_id)
    {
        $model = new FeedModel;
        if (!$model->setDelete($feed_id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
}
