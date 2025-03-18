<?php

namespace app\shop\model\product;

use think\facade\Cache;
use app\common\model\product\Category as CategoryModel;

/**
 * 商品分类模型
 */
class Category extends CategoryModel
{
    /**
     * 添加新记录
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        $this->deleteCache($data['type'], 0, $data['shop_supplier_id']);
        return $this->save($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        $this->deleteCache($this['type'], $this['is_special'], $this['shop_supplier_id']);
        !array_key_exists('image_id', $data) && $data['image_id'] = 0;
        return $this->save($data) !== false;
    }

    /**
     * 删除商品分类
     */
    public function remove($categoryId)
    {
        // 判断是否存在商品
        if ($productCount = (new Product)->getProductTotal(['category_id' => $categoryId])) {
            $this->error = '该分类下存在' . $productCount . '个商品，不允许删除';
            return false;
        }
        $this->deleteCache($this['type'], 0, $this['shop_supplier_id']);
        return $this->delete();
    }

    /**
     * 编辑记录
     */
    public function setStatus($data)
    {
        $this->deleteCache($this['type'], $this['is_special'], $this['shop_supplier_id']);
        return $this->save($data) !== false;
    }

    /**
     * 删除缓存
     */
    private function deleteCache($type, $is_special, $shop_supplier_id)
    {
        Cache::delete('category_' . $shop_supplier_id . '_' . static::$app_id . $type . $is_special);
        Cache::delete('category_api_' . $shop_supplier_id . '_' . static::$app_id . $type . $is_special);
        Cache::delete('category_cashier_' . $shop_supplier_id . '_' . static::$app_id . $type . $is_special);
        return true;
    }

}
