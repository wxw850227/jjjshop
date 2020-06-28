<?php

namespace app\shop\model\user;

use app\common\model\user\User as UserModel;

/**
 * 用户模型
 */
class User extends UserModel
{
    /**
     * 获取当前用户总数
     */
    public function getUserTotal($day = null)
    {
        $model = $this;
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $model = $model->where('create_time', '>=', $startTime)
                ->where('create_time', '<', $startTime + 86400);
        }
        return $model->where('is_delete', '=', '0')->count();
    }

    /**
     * 获取用户id
     * @return \think\Collection
     */
    public function getUsers($where = null)
    {
        // 获取用户列表
        return $this->where('is_delete', '=', '0')
            ->where($where)
            ->order(['user_id' => 'asc'])
            ->field(['user_id'])
            ->select();
    }

    /**
     * 获取用户列表
     */
    public function getList($params)
    {
        $model = $this;
        //检索：用户名
        if (!empty($params['nick_name'])) {
            $model = $model->where('nickName', 'like', '%' . trim($params['nick_name']) . '%');
        }
        //检索：注册时间
        if (!empty($params['value1'][0])) {
            $model = $model->whereTime('create_time', 'between', $params['value1']);
        }
        // 获取用户列表
        return $model->where('is_delete', '=', '0')
            ->order(['create_time' => 'desc'])
            ->hidden(['open_id', 'union_id'])
            ->paginate($params, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 软删除
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 新增记录
     */
    public function add($data)
    {
        return $this->save($data);
    }

    /**
     * 修改记录
     */
    public function edit($data)
    {
        $data['create_time'] = strtotime($data['create_time']);
        $data['update_time'] = time();
        return $this->save($data);
    }

    /**
     * 消减用户的实际消费金额
     */
    public function setDecUserExpend($userId, $expendMoney)
    {
        return $this->where(['user_id' => $userId])->dec('expend_money', $expendMoney)->update();
    }


}
