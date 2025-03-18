<?php

namespace app\shop\controller\setting;

use app\shop\controller\Controller;
use app\shop\model\settings\Printer as PrinterModel;

/**
 * 打印机控制器
 */
class Printer extends Controller
{
    /**
     * 打印机列表
     */
    public function index()
    {
        $model = new PrinterModel;
        $list = $model->getList($this->postData(),$this->store['user']['shop_supplier_id']);
        return $this->renderSuccess('', compact('list'));
    }


    /**
     * 打印机类型
     */
    public function type()
    {
        $model = new PrinterModel;
        $printerType = $model::getPrinterTypeList();
        return $this->renderSuccess('', compact('printerType'));
    }


    /**
     * 添加打印机
     */
    public function add()
    {
        if($this->request->isGet()){
            return  $this->type();
        }
        // 新增记录
        $model = new PrinterModel;
        $data = $this->postData();
        $data['shop_supplier_id'] = $this->store['user']['shop_supplier_id'];
        if ($model->add($data)) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 打印机详情
     */
    public function detail($printer_id)
    {
        $detail = PrinterModel::detail($printer_id);
        $detail['printer_config'] = json_decode($detail['printer_config'], true);
        $printerType = $detail::getPrinterTypeList();
        return $this->renderSuccess('', compact('detail', 'printerType'));

    }

    public function edit($printer_id)
    {
        if($this->request->isGet()){
            return $this->detail($printer_id);
        }
        $model = PrinterModel::detail($printer_id);
        // 更新记录
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除记录
     */
    public function delete($printer_id)
    {
        $model = PrinterModel::detail($printer_id);
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?:'删除失败');
    }

}
