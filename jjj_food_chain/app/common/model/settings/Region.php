<?php

namespace app\common\model\settings;

use think\facade\Cache;
use app\common\library\helper;
use app\common\model\BaseModel;
/**
 * 地区模型
 */
class Region extends BaseModel
{
    protected $name = 'region';
    protected $pk = 'id';
    protected $createTime = false;
    protected $updateTime = false;

    /**
     * 类型自动转换
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'pid' => 'integer',
        'level' => 'integer',
    ];

    // 当前数据版本号
    private static $version = '1.2.3';

    // 县级市别名 (兼容微信端命名)
    private static $county = [
        '省直辖县级行政区划',
        '自治区直辖县级行政区划',
    ];

    /**
     * 根据id获取地区名称
     */
    public static function getNameById($id)
    {
        return $id > 0 ? self::getCacheAll()[$id]['name'] : '其他';
    }

    /**
     * 根据名称获取地区id
     */
    public static function getIdByName($name, $level = 0, $pid = 0)
    {
        // 兼容：微信端"省直辖县级行政区划"
        if (in_array($name, static::$county)) {
            $name = '直辖县级';
        }
        $data = self::getCacheAll();
        foreach ($data as $item) {
            if ($item['name'] == $name && $item['level'] == $level && $item['pid'] == $pid)
                return $item['id'];
        }
        return 0;
    }

    /**
     * 获取所有地区(树状结构)
     */
    public static function getCacheTree()
    {
        return static::getCacheData('tree');
    }

    /**
     * 获取所有地区列表
     */
    public static function getCacheAll()
    {
        return static::getCacheData('all');
    }

    /**
     * 获取所有地区的总数
     */
    public static function getCacheCounts()
    {
        return static::getCacheData('counts');
    }

    /**
     * 获取缓存中的数据(存入静态变量)
     */
    private static function getCacheData($item = null)
    {
        static $cacheData = [];
        if (empty($cacheData)) {
            $static = new static;
            $cacheData = $static->regionCache();
        }
        if (is_null($item)) {
            return $cacheData;
        }
        return $cacheData[$item];
    }

    /**
     * 获取地区缓存
     */
    private function regionCache()
    {
        // 缓存的数据
        $complete = Cache::get('region');
        // 如果存在缓存则返回缓存的数据，否则从数据库中查询
        // 条件1: 获取缓存数据
        // 条件2: 数据版本号要与当前一致
        if (
            !empty($complete)
            && isset($complete['version'])
            && $complete['version'] == self::$version
        ) {
            return $complete;
        }
        // 所有地区
        $allList = $tempList = $this->getAllList();
        // 已完成的数据
        $complete = [
            'all' => $allList,
            'tree' => $this->getTreeList($allList),
            'counts' => $this->getCount($allList),
            'version' => self::$version,
        ];
        // 写入缓存
        Cache::tag('cache')->set('region', $complete);
        return $complete;
    }

    private static function getCount($allList)
    {
        $counts = [
            'total' => count($allList),
            'province' => 0,
            'city' => 0,
            'region' => 0,
        ];
        $level = [1 => 'province', 2 => 'city', 3 => 'region'];
        foreach ($allList as $item) {
            $counts[$level[$item['level']]]++;
        }
        return $counts;
    }

    /**
     * 格式化为树状格式
     */
    private function getTreeList($allList)
    {
        $treeList = [];
        foreach ($allList as $pKey => $province) {
            if ($province['level'] == 1) {    // 省份
                $treeList[$province['id']] = $province;
                unset($allList[$pKey]);
                foreach ($allList as $cKey => $city) {
                    if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
                        $treeList[$province['id']]['city'][$city['id']] = $city;
                        unset($allList[$cKey]);
                        foreach ($allList as $rKey => $region) {
                            if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
                                $treeList[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
                                unset($allList[$rKey]);
                            }
                        }
                    }
                }
            }
        }
        return $treeList;
    }

    /**
     * 从数据库中获取所有地区
     */
    private function getAllList()
    {
        $list = self::withoutGlobalScope()
            ->field('id, pid, name, level')
            ->select()
            ->toArray();
        return helper::arrayColumn2Key($list, 'id');
    }
}
