<?php

namespace app\shop\controller\product;

use app\shop\controller\Controller;
use app\shop\model\product\Spec as SpecModel;
use app\shop\model\product\SpecValue as SpecValueModel;

/**
 * 商品规格控制器
 */
class Spec extends Controller
{

    /**
     * 添加规则组
     */
    public function addSpec($spec_name, $spec_value)
    {
        $specModel = new SpecModel();
        $specValueModel = new SpecValueModel();
        // 判断规格组是否存在
        if (!$specId = $specModel->getSpecIdByName($spec_name)) {
            // 新增规格组and规则值
            if ($specModel->add($spec_name)
                && $specValueModel->add($specModel['spec_id'], $spec_value))
                return $this->renderSuccess('', [
                    'spec_id' => (int)$specModel['spec_id'],
                    'spec_value_id' => (int)$specValueModel['spec_value_id'],
                ]);
            return $this->renderError();
        }
        // 判断规格值是否存在
        if ($specValueId = $specValueModel->getSpecValueIdByName($specId, $spec_value)) {
            return $this->renderSuccess('',  [
                'spec_id' => (int)$specId,
                'spec_value_id' => (int)$specValueId,
            ]);
        }
        // 添加规则值
        if ($specValueModel->add($specId, $spec_value)){
            return $this->renderSuccess('', [
                'spec_id' => (int)$specId,
                'spec_value_id' => (int)$specValueModel['spec_value_id'],
            ]);
        }

        return $this->renderError();
    }

    /**
     * 添加规格值
     */
    public function addSpecValue($spec_id, $spec_value)
    {
        $specValueModel = new SpecValueModel();
        // 判断规格值是否存在
        if ($specValueId = $specValueModel->getSpecValueIdByName($spec_id, $spec_value)) {
            return $this->renderSuccess('',  [
                'spec_value_id' => (int)$specValueId,
            ]);
        }
        // 添加规则值
        if ($specValueModel->add($spec_id, $spec_value))
            return $this->renderSuccess('', [
                'spec_value_id' => (int)$specValueModel['spec_value_id'],
            ]);
        return $this->renderError();
    }

}
