<?php

namespace app\admin\controller;

use app\admin\model\settings\MessageField as MessageFieldModel;
use app\admin\model\settings\Message as MessageModel;

class Message extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $list = MessageModel::getAll();
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = new MessageModel;
        // 新增记录
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 更新权限
     */
    public function edit()
    {
        // 权限详情
        $data = $this->postData();
        $model = MessageModel::detail($data['message_id']);

        // 更新记录
        if ($model->edit($data)) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }

    /**
     * 删除小程序
     */
    public function delete($message_id)
    {
        // 小程序详情
        $model = MessageModel::detail($message_id);
        if (!$model->setDelete()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 消息字段列表
     */
    public function field($message_id)
    {
        $list = MessageFieldModel::getAll($message_id);
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 保存消息字段
     */
    public function saveField()
    {
        $model = new MessageFieldModel;
        // 新增记录
        if ($model->add($this->postData())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError('添加失败');
    }
}