<?php

namespace app\api\model\order;

use app\common\model\order\OrderRefund as OrderRefundModel;

/**
 * 售后单模型
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'update_time'
    ];

    /**
     * 追加字段
     */
    protected $append = [
        'state_text',   // 售后单状态文字描述
    ];

    /**
     * 售后单状态文字描述
     */
    public function getStateTextAttr($value, $data)
    {
        // 已完成
        if ($data['status'] == 20) {
            $text = [10 => '已同意退货并已退款', 20 => '已同意换货'];
            return $text[$data['type']];
        }
        // 已取消
        if ($data['status'] == 30) {
            return '已取消';
        }
        // 已拒绝
        if ($data['status'] == 10) {
            return $data['type'] == 10 ? '已拒绝退货退款' : '已拒绝换货';
        }
        // 进行中
        if ($data['status'] == 0) {
            if ($data['is_agree'] == 0) {
                return '等待审核中';
            }
            if ($data['type'] == 10) {
                return $data['is_user_send'] ? '已发货，待平台确认' : '已同意退货，请及时发货';
            }
        }
        return $value;
    }

    /**
     * 获取用户售后单列表
     */
    public function getList($user_id, $state = -1, $limit)
    {
        $model = $this;
        $state > -1 && $model = $this->where('status', '=', $state);

        return $model->with(['order_master', 'orderproduct.image'])
            ->where('user_id', '=', $user_id)
            ->order(['create_time' => 'desc'])
            ->paginate($limit, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 用户发货
     */
    public function delivery($data)
    {
        if (
            $this['type']['value'] != 10
            || $this['is_agree']['value'] != 10
            || $this['is_user_send'] != 0
        ) {
            $this->error = '当前售后单不合法，不允许该操作';
            return false;
        }
        if ($data['express_id'] <= 0) {
            $this->error = '请选择物流公司';
            return false;
        }
        if (empty($data['express_no'])) {
            $this->error = '请填写物流单号';
            return false;
        }
        return $this->save([
            'is_user_send' => 1,
            'send_time' => time(),
            'express_id' => (int)$data['express_id'],
            'express_no' => $data['express_no'],
        ]);
    }

    /**
     * 新增售后单记录
     */
    public function apply($user, $product, $data)
    {
        $this->startTrans();
        try {
            // 新增售后单记录
            $this->save([
                'order_product_id' => $data['order_product_id'],
                'order_id' => $product['order_id'],
                'user_id' => $user['user_id'],
                'type' => $data['type'],
                'apply_desc' => $data['content'],
                'is_agree' => 0,
                'status' => 0,
                'app_id' => self::$app_id,
            ]);
            // 记录凭证图片关系
            if (isset($data['images']) && !empty($data['images'])) {
                $this->saveImages($this['order_refund_id'], $data['images']);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 记录售后单图片
     */
    private function saveImages($order_refund_id, $images)
    {
        $images_ids = [];
        foreach (json_decode($images, true) as $val) {
            $images_ids[] = $val['file_id'];
        }
        // 生成评价图片数据
        $data = [];
        foreach ($images_ids as $image_id) {
            $data[] = [
                'order_refund_id' => $order_refund_id,
                'image_id' => $image_id,
                'app_id' => self::$app_id
            ];
        }
        return !empty($data) && (new OrderRefundImage)->saveAll($data);
    }

}