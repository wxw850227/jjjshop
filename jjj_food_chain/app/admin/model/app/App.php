<?php

namespace app\admin\model\app;

use app\admin\model\page\Page as PageModel;
use app\admin\model\Shop as ShopUser;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\model\app\App as AppModel;
use app\admin\model\supplier\Supplier as SupplierModel;
use app\common\model\product\Category as CategoryModel;

class App extends AppModel
{
    /**
     * 获取小程序列表
     */
    public function getList($limit, $is_recycle = false)
    {
        return $this->alias('app')->field(['app.*,user.user_name'])
            ->join('shop_user user', 'user.app_id = app.app_id', 'left')
            ->where('user.is_super', '=', 1)
            ->where('user.user_type', '=', 0)
            ->where('app.is_delete', '=', 0)
            ->order(['create_time' => 'asc'])
            ->paginate($limit);
    }

    /**
     * 新增记录
     */
    public function add($data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (ShopUser::checkExist($data['user_name'])) {
            $this->error = '商家账号名已存在';
            return false;
        }
        if ($data['no_expire'] == 'true') {
            $data['expire_time'] = 0;
        } else {
            $data['expire_time'] = $data['expire_time'] ? strtotime($data['expire_time']) : 0;
        }
        $data['pay_type'] = json_encode(array_keys(OrderPayTypeEnum::data()));
        $this->startTrans();
        try {
            // 添加小程序记录
            $this->save($data);
            // 新增商家用户信息
            $ShopUser = new ShopUser;
            if (!$ShopUser->add($this['app_id'], $data)) {
                $this->error = $ShopUser->error;
                return false;
            }
            // 新增应用diy配置
            (new PageModel)->insertDefault($this['app_id']);
            //新增门店
            $SupplierModel = new SupplierModel;
            $SupplierModel->add($data, $this['app_id']);
            //新增特殊分类
            (new CategoryModel)->addSpecial($this['app_id'], $SupplierModel->shop_supplier_id);
            $ShopUser->save(['shop_supplier_id' => $SupplierModel->shop_supplier_id]);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 修改记录
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            $save_data = [
                'app_name' => $data['app_name'],
                'is_chain' => $data['is_chain']
            ];
            if ($data['no_expire'] == 'true') {
                $save_data['expire_time'] = 0;
            } else {
                $save_data['expire_time'] = strtotime($data['expire_time_text']);
            }
            $this->save($save_data);
            $user_data = [
                'user_name' => $data['user_name']
            ];
            if (!empty($data['password'])) {
                $user_data['password'] = salt_hash($data['password']);
            }
            $shop_user = (new ShopUser())->where('app_id', '=', $this['app_id'])
                ->where('is_super', '=', 1)
                ->where('user_type', '=', 0)->find();
            if ($shop_user['user_name'] != $data['user_name']) {
                if (ShopUser::checkExist($data['user_name'])) {
                    $this->error = '商家账号名已存在';
                    return false;
                }
            }
            $shop_user->save($user_data);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 移入移出回收站
     */
    public function recycle($is_recycle = true)
    {
        return $this->save(['is_recycle' => (int)$is_recycle]);
    }

    /**
     * 软删除
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

}