<?php

namespace app\shop\controller\product\takeaway;

use app\shop\model\product\Product as ProductModel;
use app\shop\model\product\Category as CategoryModel;
use app\shop\service\ProductService;
use app\shop\controller\Controller;

/**
 * 商品管理控制器
 */
class Product extends Controller
{
    /**
     * 商品列表(全部)
     */
    public function index()
    {
        // 获取全部商品列表
        $model = new ProductModel;
        $list = $model->getList(array_merge(['status' => -1, 'product_type' => 0, 'shop_supplier_id' => $this->store['user']['shop_supplier_id']], $this->postData()));
        // 商品分类
        $category = CategoryModel::getCacheTree(0, 0, $this->store);
        // 数量
        $product_count = [
            'sell' => $model->getCount('sell', $this->store['user']['shop_supplier_id'], 0),
            'lower' => $model->getCount('lower', $this->store['user']['shop_supplier_id'], 0),
        ];
        return $this->renderSuccess('', compact('list', 'category', 'product_count'));
    }

    /**
     * 商品列表(在售)
     */
    public function lists()
    {
        // 获取全部商品列表
        $model = new ProductModel;
        $list = $model->getLists($this->postData());
        // 商品分类
        $catgory = CategoryModel::getCacheTree(0, 0, $this->store);
        return $this->renderSuccess('', compact('list', 'catgory'));
    }

    /**
     * 添加商品
     */
    public function add($scene = 'add')
    {
        // get请求
        if ($this->request->isGet()) {
            return $this->getBaseData();
        }
        //post请求
        $data = json_decode($this->postData()['params'], true);
        $model = new ProductModel;
        $data['product_type'] = 0;
        $data['shop_supplier_id'] = $this->store['user']['shop_supplier_id'];
        if ($model->add($data)) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 获取基础数据
     */
    public function getBaseData()
    {
        return $this->renderSuccess('', array_merge(ProductService::getEditData(0, $this->store), []));
    }

    /**
     * 商品编辑
     */
    public function edit($product_id, $scene = 'edit')
    {
        if ($this->request->isGet()) {
            $model = ProductModel::detail($product_id);
            return $this->renderSuccess('', array_merge(ProductService::getEditData(0, $this->store), compact('model')));
        }
        // 商品详情
        $model = ProductModel::detail($product_id);
        // 更新记录
        if ($model->edit(json_decode($this->postData()['params'], true))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态
     */
    public function state($product_id, $state)
    {
        // 商品详情
        $model = ProductModel::detail($product_id);
        if (!$model->setStatus($state)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     */
    public function delete($product_id)
    {
        // 商品详情
        $model = ProductModel::detail($product_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 同步商品到门店
     */
    public function transmit()
    {
        $model = new ProductModel;
        if (!$model->transmit($this->postData())) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 同步商品到店内
     */
    public function transmitStore()
    {
        $model = new ProductModel;
        if (!$model->transmitStore($this->postData())) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }
}
