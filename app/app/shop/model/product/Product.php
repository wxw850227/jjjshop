<?php

namespace app\shop\model\product;

use app\common\model\product\Product as ProductModel;
use app\shop\service\product as ProductService;

/**
 * 商品模型
 */
class Product extends ProductModel
{
    /**
     * 添加商品
     */
    public function add(array $data)
    {
        if (!isset($data['image']) || empty($data['image'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['app_id'] = $data['sku']['app_id'] = self::$app_id;

        // 开启事务
        $this->startTrans();
        try {
            $data = $this->setProductPriceStock($data);
            // 添加商品
            $res = $this->save($data);
            // 商品规格
            $this->addProductSpec($data);

            // 商品图片
            $this->addProductImages($data['image']);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 添加商品图片
     */
    private function addProductImages($images)
    {
        $this->image()->delete();
        $data = array_map(function ($images) {
            return [
                'image_id' => isset($images['file_id']) ? $images['file_id'] : $images['image_id'],
                'app_id' => self::$app_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }

    /**
     * 编辑商品
     */
    public function edit($data)
    {
        if (!isset($data['image']) || empty($data['image'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        $data['spec_type'] = isset($data['spec_type']) ? $data['spec_type'] : $this['spec_type'];
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['app_id'] = self::$app_id;
        return $this->transaction(function () use ($data) {
            $data = $this->setProductPriceStock($data);
            // 保存商品
            $this->save($data);
            // 商品规格
            $this->addProductSpec($data, true);
            // 商品图片
            $this->addProductImages($data['image']);
            return true;
        });
    }

    /**
     * 设置商品一口价和库存
     */
    private function setProductPriceStock($data){
        if ($data['spec_type'] == '10') {
            // 单规格
            $data['product_price'] = $data['sku']['product_price'];
            $data['product_stock'] = $data['sku']['stock_num'];
        } else if ($data['spec_type'] == '20') {
            //存最低价
            $low_price = $data['sku'][0]['product_price'];
            $total_stock = 0;
            foreach($data['sku'] as $sku){
                $sku['app_id'] = self::$app_id;
                if($sku['product_price'] < $low_price){
                    $low_price = $sku['product_price'];
                }
                $total_stock += $sku['stock_num'];
            }
            $data['product_price'] = $low_price;
            $data['product_stock'] = $total_stock;
        }
        return $data;
    }

    /**
     * 添加商品规格
     */
    private function addProductSpec($data, $isUpdate = false)
    {
        // 更新模式: 先删除所有规格
        $model = new ProductSku;
        $isUpdate && $model->removeAll($this['product_id']);
        // 添加规格数据
        if ($data['spec_type'] == '10') {
            // 单规格
            $this->sku()->save($data['sku']);
        } else if ($data['spec_type'] == '20') {
            // 添加商品与规格关系记录
            $model->addProductSpecRel($this['product_id'], $data['spec_many']['spec_attr']);
            // 添加商品sku
            $model->addSkuList($this['product_id'], $data['spec_many']['spec_list']);
        }
    }

    /**
     * 修改商品状态
     */
    public function setStatus($state)
    {
        return $this->allowField(true)->save(['product_status' => $state ? 10 : 20]) !== false;
    }

    /**
     * 软删除
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取当前商品总数
     */
    public function getProductTotal($where = [])
    {
        return $this->where('is_delete', '=', 0)->where($where)->count();
    }

    /**
     * 获取商品告急数量总数
     */
    public function getProductStockTotal()
    {
        return $this->where('is_delete', '=', 0)->where('product_stock', '<', 20)->count();
    }

    public function getProductId($search)
    {
        $res = $this->where('product_name', 'like', '%' . trim($search) . '%')->select()->toArray();
        return array_column($res, 'product_id');
    }

}
