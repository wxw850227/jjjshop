<?php

namespace app\shop\controller\product\expand;

use app\shop\controller\Controller;
use app\shop\model\product\Spec as SpecModel;

/**
 * 商品规格控制器
 */
class Spec extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $model = new SpecModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加规则组
     */
    public function add($spec_name)
    {
        $model = new SpecModel();
        // 判断规格组是否存在
        if (!$specId = $model->getSpecIdByName($spec_name)) {
            // 新增规格
            if ($model->add($spec_name)) {
                return $this->renderSuccess('添加成功');
            }
            return $this->renderError($model->getError() ?: '添加失败');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑
     */
    public function edit($spec_id)
    {
        // 详情
        $model = SpecModel::detail($spec_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除商品
     */
    public function delete($spec_id)
    {
        $model = new SpecModel;
        if (!$model->setDelete($spec_id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
