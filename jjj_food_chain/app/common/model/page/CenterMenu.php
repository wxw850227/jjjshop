<?php

namespace app\common\model\page;

use think\facade\Request;
use app\common\model\BaseModel;
use think\facade\Cache;

/**
 * 个人中心菜单模型
 */
class CenterMenu extends BaseModel
{
    protected $name = 'center_menu';
    protected $pk = 'menu_id';

    /**
     * 详情
     */
    public static function detail($menu_id)
    {
        $detail = self::find($menu_id);
        if (strpos($detail['image_url'], 'http') !== 0) {
            $detail['image_url'] = base_url() . $detail['image_url'];
        }
        return $detail;
    }

    /**
     * 查询所有
     */
    public static function getAll()
    {
        $model = new static();
        if (!Cache::get('center_menu_' . $model::$app_id)) {
            $list = $model->where('status', '=', 1)->order(['sort' => 'asc'])->select();
            $listAll = $model->order(['sort' => 'asc'])->select();
            if (count($list) == 0 && count($listAll) == 0) {
                $sys_menus = $model->getSysMenu();
                $save_data = [];
                foreach ($sys_menus as $menu) {
                    $save_data[] = array_merge($sys_menus[$menu['sys_tag']], [
                        'app_id' => self::$app_id
                    ]);
                }
                $model->saveAll($save_data);
            }
            $list = $model->where('status', '=', 1)->order(['sort' => 'asc'])->select();
            if (count($list) == 0) {
                return [];
            }
            Cache::tag('cache')->set('center_menu_' . $model::$app_id, $list);
        }
        return Cache::get('center_menu_' . $model::$app_id);
    }

    /**
     * 系统菜单
     */
    public static function getSysMenu()
    {
        return [
            'address' => [
                'sys_tag' => 'address',
                'title' => '收货地址',
                'link_url' => '/pages/user/address/address',
                'image_url' => 'image/menu/address.png',
                'name' => '收货地址',
                'sort' => 1
            ],
            'setting' => [
                'sys_tag' => 'setting',
                'title' => '设置',
                'link_url' => '/pages/user/set/set',
                'image_url' => 'image/menu/setting.png',
                'name' => '设置',
                'sort' => 2
            ],
        ];

    }

}