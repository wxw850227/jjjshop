<?php

namespace app\shop\controller\settings;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

use think\facade\Cache;

/**
 * 缓存清除
 */
class Clear extends Controller
{

    public function index()
    {
        $data = $this->postData();
        $this->rmCache($data['keys']);
        return $this->renderSuccess('操作成功');
    }

    /**
     * 商城设置进来请求的接口
     */
    public function fetchData()
    {
        $cacheList = $this->getItems();
        return $this->renderSuccess('', compact('cacheList'));
    }

    /**
     * 数据缓存项目
     */
    private function getItems()
    {
        $app_id = $this->store['app']['app_id'];
        return [
            'category' => [
                'type' => 'cache',
                'key' => 'category_' . $app_id,
                'name' => '商品分类'
            ],
            'setting' => [
                'type' => 'cache',
                'key' => 'setting_' . $app_id,
                'name' => '商城设置'
            ],
            'app' => [
                'type' => 'cache',
                'key' => 'app_' . $app_id,
                'name' => '应用设置'
            ],
            'temp' => [
                'type' => 'file',
                'name' => '临时图片',
                'dirPath' => [
                    'temp' => root_path('public') . '/temp/' . $app_id . '/',
                    'runtime' => root_path('runtime') . '/image/' . $app_id . '/'
                ]
            ],
        ];
    }


    /**
     * 删除缓存
     */
    private function rmCache($keys)
    {
        $app_id = $this->store['app']['app_id'];
        $cacheList = $this->getItems();
        $keys = array_intersect(array_keys($cacheList), $keys);
        foreach ($keys as $key) {
            $item = $cacheList[$key];
            if ($item['type'] === 'cache') {
                Cache::has($item['key']) && Cache::delete($item['key']);
                //如果是app，则多删除
                if($item['key'] == 'app'){
                    Cache::has('app_wx_' . $app_id) && Cache::delete('app_wx_' . $app_id);
                }
            } elseif ($item['type'] === 'file') {
                $this->deltree($item['dirPath']);
            }
        }
    }

    /**
     * 删除目录下所有文件
     */
    private function deltree($dirPath)
    {
        if (is_array($dirPath)) {
            foreach ($dirPath as $path)
                $this->deleteFolder($path);
        } else {
            return $this->deleteFolder($dirPath);
        }
        return true;
    }

    /**
     * 递归删除指定目录下所有文件
     */
    private function deleteFolder($path)
    {
        if (!is_dir($path))
            return false;
        // 扫描一个文件夹内的所有文件夹和文件
        foreach (scandir($path) as $val) {
            // 排除目录中的.和..
            if (!in_array($val, ['.', '..'])) {
                // 如果是目录则递归子目录，继续操作
                if (is_dir($path . $val)) {
                    // 子目录中操作删除文件夹和文件
                    $this->deleteFolder($path . $val . '/');
                    // 目录清空后删除空文件夹
                    rmdir($path . $val . '/');
                } else {
                    // 如果是文件直接删除
                    unlink($path . $val);
                }
            }
        }
        return true;
    }


}
