<?php

namespace app\shop\controller\file;

use app\JjjController;
use app\shop\model\file\UploadFile as UploadFileModel;
use app\shop\model\file\UploadGroup as UploadGroupModel;

class File extends JjjController
{
    /**
     * 文件库列表
     */
    public function lists($group_id, $pageSize, $type = '')
    {
        // 文件列表
        $file_list = (new UploadFileModel)->getlist(intval($group_id), $type, $pageSize, -1);
        return $this->renderSuccess('success', compact('file_list'));
    }

    /**
     * 类别列表
     */
    public function category($type = 'image')
    {
        // 分组列表
        $group_list = (new UploadGroupModel)->getList($type);
        return $this->renderSuccess('success', compact('group_list'));
    }

    /**
     * 新增分组
     */
    public function addGroup($group_name, $group_type = 'image')
    {
        $model = new UploadGroupModel;
        if ($model->add(compact('group_name', 'group_type'))) {
            $group_id = $model->getLastInsID();
            return $this->renderSuccess('添加成功', compact('group_id', 'group_name'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑分组
     */
    public function editGroup($group_id, $group_name)
    {
        $model = UploadGroupModel::detail($group_id);
        if ($model->edit(compact('group_name'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 删除分组
     */
    public function deleteGroup($group_id)
    {
        $model = UploadGroupModel::detail($group_id);
        if ($model->remove()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 批量删除文件
     */
    public function deleteFiles($fileIds)
    {
        $model = new UploadFileModel;
        if ($model->softDelete($fileIds)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }


    /**
     * 批量移动文件分组
     */
    public function moveFiles($group_id, $fileIds)
    {
        $model = new UploadFileModel;
        if ($model->moveGroup($group_id, $fileIds) !== false) {
            return $this->renderSuccess('移动成功');
        }
        return $this->renderError($model->getError() ?: '移动失败');
    }
}
