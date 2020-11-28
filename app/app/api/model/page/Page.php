<?php

namespace app\api\model\page;

use app\api\model\article\Article;
use app\api\model\product\Product as ProductModel;
use app\common\model\page\Page as PageModel;

/**
 * 首页模型
 */
class Page extends PageModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];

    /**
     * DIY页面详情
     */
    public static function getPageData($user, $page_id = null)
    {
        // 页面详情
        $detail = $page_id > 0 ? parent::detail($page_id) : parent::getHomePage();

        // 页面diy元素
        $items = $detail['page_data']['items'];
        // 页面顶部导航
        isset($detail['page_data']['page']) && $items['page'] = $detail['page_data']['page'];
        // 获取动态数据
        $model = new self;

        foreach ($items as $key => $item) {
            if ($item['type'] === 'window') {
                $items[$key]['data'] = array_values($item['data']);
            } else if ($item['type'] === 'product') {
                $items[$key]['data'] = $model->getProductList($user, $item);
            } else if ($item['type'] === 'article') {
                $items[$key]['data'] = $model->getArticleList($item);
            } else if ($item['type'] === 'special') {
                $items[$key]['data'] = $model->getSpecialList($item);
            }
        }
        return ['page' => $items['page'], 'items' => $items];
    }

    /**
     * 商品组件：获取商品列表
     */
    private function getProductList($user, $item)
    {
        // 获取商品数据
        $model = new ProductModel;
        if ($item['params']['source'] === 'choice') {
            // 数据来源：手动
            $productIds = array_column($item['data'], 'product_id');
            $productList = $model->getListByIdsFromApi($productIds, $user);
        } else {
            // 数据来源：自动
            $productList = $model->getList([
                'product_status' => 10,
                'category_id' => $item['params']['auto']['category'],
                'sortType' => $item['params']['auto']['productSort'],
                'list_rows' => $item['params']['auto']['showNum']
            ], $user);
        }
        if ($productList->isEmpty()) return [];
        // 格式化商品列表
        $data = [];
        foreach ($productList as $product) {
            $data[] = [
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'selling_point' => $product['selling_point'],
                'image' => $product['image'][0]['file_path'],
                'product_image' => $product['image'][0]['file_path'],
                'product_price' => $product['sku'][0]['product_price'],
                'line_price' => $product['sku'][0]['line_price'],
                'product_sales' => $product['product_sales'],
            ];
        }
        return $data;
    }

    /**
     * 文章组件：获取文章列表
     */
    private function getArticleList($item)
    {
        // 获取文章数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }

    /**
     * 头条快报：获取头条列表
     */
    private function getSpecialList($item)
    {
        // 获取头条数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }
}