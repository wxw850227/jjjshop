<?php

namespace app\common\model\product;

use app\common\model\BaseModel;

/**
 * 评论模型
 */
class Comment extends BaseModel
{
    //表名
    protected $name = 'comment';
    //主键字段名
    protected $px = 'comment_id';

    /**
     * 所属订单
     */
    public function orderMaster()
    {
        return $this->belongsTo('app\\common\\model\\order\\Order');
    }

    /**
     * 订单商品
     */
    public function OrderProduct()
    {
        return $this->belongsTo('app\\common\\model\\order\\OrderProduct');
    }

    /**
     * 商品
     */
    public function product()
    {
        return $this->belongsTo('app\\common\\model\\product\\Product', 'product_id', 'product_id');
    }

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo('app\\common\\model\\user\\User', 'user_id', 'user_id');
    }

    /**
     * 关联评价图片表
     */
    public function image()
    {
        return $this->hasMany('app\\common\\model\\product\\CommentImage', 'comment_id', 'comment_id')->order(['id' => 'asc']);
    }

    /**
     * 评价详情
     */
    public function detail($comment_id)
    {
        return $this->where('comment_id', '=', $comment_id)->with(['user','image.file', 'orderMaster', 'product.image.file'])->find();
    }

    /**
     * 更新记录
     */
    public function edit($data)
    {
        return $this->where('comment_id', '=', $data['comment_id'])->save([
            'status' => $data['status']
        ]);
    }

    /**
     * 获取评价列表
     */
    public function getList($params)
    {
        $model = $this;
        if (isset($params['score']) && $params['score'] > 0) {
            $model = $model->where('score', '=', $params['score']);
        }
        if (isset($params['status']) && $params['status'] > -1) {
            $model = $model->where('status', '=', $params['status']);
        }

        return $model->with(['user', 'orderMaster', 'product.image.file'])
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($params, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * @param $where array 查询条件
     */
    public function getStatusNum($where)
    {
        return $this->where($where)->count();
    }

}