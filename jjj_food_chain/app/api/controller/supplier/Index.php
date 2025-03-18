<?php

namespace app\api\controller\supplier;

use app\api\controller\Controller;
use app\api\model\supplier\Supplier as SupplierModel;

/**
 * 门店
 */
class Index extends Controller
{
    //所有门店列表
    public function list()
    {
        $param = $this->postData();
        $SupplierModel = new SupplierModel;
        $list = $SupplierModel->supplierList($param);
        return $this->renderSuccess('', compact('list'));
    }

    //可选门店列表
    public function deliveryList()
    {
        $param = $this->postData();
        $SupplierModel = new SupplierModel;
        $list = $SupplierModel->deliveryList($param, $this->getUser());
        return $this->renderSuccess('', compact('list'));
    }

    //所有门店列表
    public function storeList($longitude = '', $latitude = '')
    {
        $list = SupplierModel::getStoreList($longitude = '', $latitude = '');
        return $this->renderSuccess('', compact('list'));
    }
}