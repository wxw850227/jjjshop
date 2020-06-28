<?php

namespace app\shop\controller\page;

use app\shop\controller\Controller;
use app\shop\model\page\Page as PageModel;
use app\shop\model\page\PageCategory as PageCategoryModel;

/**
 * 应用页面管理
 */
class Page extends Controller
{
    /**
     * 页面列表
     */
    public function index()
    {
        $model = new PageModel;
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 新增页面
     */
    public function add()
    {
        $model = new PageModel;
        if ($this->request->isGet()) {
            return $this->renderSuccess('', [
                'defaultData' => $model->getDefaultItems(),
                'jsonData' => ['page' => $model->getDefaultPage(), 'items' => []],
                'opts' => [
                    'catgory' => [],
                ]
            ]);
        }
        // 接收post数据
        $post = $this->postData();
        if (!$model->add($post['Parmens'])) {
            return $this->renderError('添加失败');
        }
        return $this->renderSuccess('添加成功');
    }

    /**
     * 编辑页面
     */
    public function edit($page_id = null)
    {
        $model = $page_id > 0 ? PageModel::detail($page_id) : PageModel::getHomePage();
        if ($this->request->isGet()) {
            $jsonData = $model['page_data'];
            jsonRecursive($jsonData);
            $defaultData = $model->getDefaultItems();
            return $this->renderSuccess('', [
                'defaultData' => $defaultData,
                'jsonData' => $jsonData,
                'opts' => [
                    'catgory' => [],
                ]
            ]);
        }

        // 接收post数据
        $post = $this->postData('Parmens');

        if (!$model->edit($post)) {
            return $this->renderError('更新失败');
        }
        return $this->renderSuccess('更新成功');
    }

    /**
     * 删除页面
     */
    public function delete($page_id)
    {
        // 帮助详情
        $model = PageModel::detail($page_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 分类模板
     */
    public function category()
    {
        $model = PageCategoryModel::detail();
        if ($this->request->isPost()) {
            if ($model->edit($this->postData())) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->renderSuccess('', compact('model'));
    }

    /**
     * 页面链接
     */
    public function links()
    {
        return $this->renderSuccess('links');
    }

}
