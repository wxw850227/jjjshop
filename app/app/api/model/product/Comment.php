<?php

namespace app\api\model\product;

use app\api\model\order\OrderProduct;
use app\common\model\product\Comment as CommentModel;
use app\common\exception\BaseException;

class Comment extends CommentModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'status',
        'sort',
        'order_id',
        'product_id',
        'order_product_id',
        'is_delete',
        'update_time'
    ];

    /**
     * 关联用户表
     */
    public function users()
    {
        return $this->belongsTo('app\\common\\model\\user\\User')->field(['user_id', 'nickName', 'avatarUrl']);
    }

    /**
     * 获取指定商品评价列表
     */
    public function getProductCommentList($product_id, $scoreType = -1, $limit = 15)
    {
        // 筛选条件
        $filter = [
            'product_id' => $product_id,
            'is_delete' => 0,
            'status' => 1,
        ];
        // 评分
        $scoreType > 0 && $filter['score'] = $scoreType;
        return $this->with(['OrderProduct', 'image.file', 'users'])
            ->where($filter)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($limit, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * 获取指定评分总数
     */
    public function getTotal($product_id)
    {
        return $this->field([
            'count(comment_id) AS `all`',
            'count(score = 10 OR NULL) AS `praise`',
            'count(score = 20 OR NULL) AS `review`',
            'count(score = 30 OR NULL) AS `negative`',
        ])->where([
            'product_id' => $product_id,
            'is_delete' => 0,
            'status' => 1
        ])->find();
    }

    /**
     * 验证订单是否允许评价
     */
    public function checkOrderAllowComment($order)
    {
        // 验证订单是否已完成
        if ($order['order_status']['value'] != 30) {
            $this->error = '该订单未完成，无法评价';
            return false;
        }
        // 验证订单是否已评价
        if ($order['is_comment'] == 1) {
            $this->error = '该订单已完成评价';
            return false;
        }
        return true;
    }

    /**
     * 根据已完成订单商品 添加评价
     */
    public function addForOrder($order, $productList, $formJsonData)
    {
        // 生成 formData
        $formData = $this->formatFormData($formJsonData);
        // 生成评价数据
        $data = $this->createCommentData($order['user_id'], $order['order_id'], $productList, $formData);
        if (empty($data)) {
            $this->error = '没有输入评价内容';
            return false;
        }
        return $this->transaction(function () use ($order, $productList, $formData, $data) {
            // 记录评价内容
            $result = $this->saveAll($data);
            // 记录评价图片
            $this->saveAllImages($result, $formData);
            // 更新订单评价状态
            $isComment = count($productList) === count($data);
            $this->updateOrderIsComment($order, $isComment, $result);
            return true;
        });
    }

    /**
     * 更新订单评价状态
     */
    private function updateOrderIsComment($order, $isComment, $commentList)
    {
        // 更新订单商品
        $orderProductData = [];
        foreach ($commentList as $comment) {
            $orderProductData[] = [
                'order_product_id' => $comment['order_product_id'],
                'is_comment' => 1
            ];
        }
        // 更新订单
        $isComment && $order->save(['is_comment' => 1]);
        return (new OrderProduct)->saveAll($orderProductData);
    }

    /**
     * 生成评价数据
     */
    private function createCommentData($user_id, $order_id, $productList, $formData)
    {
        $data = [];
        foreach ($productList as $product) {
            if (!isset($formData[$product['order_product_id']])) {
                throw new BaseException(['msg' => '提交的数据不合法']);
            }
            $item = $formData[$product['order_product_id']];
            !empty($item['content']) && $data[$product['order_product_id']] = [
                'score' => $item['score'],
                'content' => $item['content'],
                'is_picture' => !empty($item['image_list']),
                'sort' => 100,
                'status' => 0,
                'user_id' => $user_id,
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'order_product_id' => $item['order_product_id'],
                'app_id' => self::$app_id
            ];
        }
        return $data;
    }

    /**
     * 格式化 formData
     */
    private function formatFormData($formJsonData)
    {
        return array_column(json_decode($formJsonData, true), null, 'order_product_id');
    }

    /**
     * 记录评价图片
     */
    private function saveAllImages($commentList, $formData)
    {
        // 生成评价图片数据
        $imageData = [];
        foreach ($commentList as $comment) {
            $item = $formData[$comment['order_product_id']];
            foreach ($item['image_list'] as $imageId) {
                $imageData[] = [
                    'comment_id' => $comment['id'],
                    'image_id' => $imageId['file_id'],
                    'app_id' => self::$app_id
                ];
            }
        }
        $model = new CommentImage;
        return !empty($imageData) && $model->saveAll($imageData);
    }
}