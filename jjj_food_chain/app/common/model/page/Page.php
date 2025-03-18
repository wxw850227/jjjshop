<?php

namespace app\common\model\page;

use app\common\model\BaseModel;

/**
 * diy页面模型
 */
class Page extends BaseModel
{
    protected $pk = 'page_id';
    protected $name = 'page';

    /**
     * 页面标题栏默认数据
     * @return array
     */
    public function getDefaultPage()
    {
        static $defaultPage = [];
        if (!empty($defaultPage)) return $defaultPage;
        return [
            'type' => 'page',
            'name' => '页面设置',
            'params' => [
                'name' => '页面名称',
                'share_title' => '分享标题',
                'share_img' => self::$base_url . 'image/diy/logo.png',
            ],
        ];
    }

    /**
     * 页面diy元素默认数据
     * @return array[]
     */
    public function getDefaultItems()
    {
        return [
            'banner' => [
                'name' => '图片轮播',
                'type' => 'banner',
                'group' => 'media',
                'icon' => 'icon-lunbotu',
                'style' => [
                    'paddingTop' => 0,
                    'paddingBottom' => 0,
                    'paddingLeft' => 0,
                    'topRadio' => 0,
                    'bottomRadio' => 0,
                    'btnColor' => '#ffffff',
                    'background' => '#ffffff',
                    'btnShape' => 'round',//rectangle 长方形，round圆形, square正方形
                    'height' => 340,
                ],
                'data' => [
                    [
                        'imgUrl' => self::$base_url . 'image/diy/banner/01.png',
                        'linkUrl' => ''
                    ]
                ]
            ],
            'navBar' => [
                'name' => '导航组',
                'type' => 'navBar',
                'group' => 'media',
                'icon' => 'icon-mulu',
                'style' => [
                    "background" => "#ffffff",
                    "rowsNum" => "2",
                    "bgcolor" => "#f2f2f2",
                    "paddingTop" => 10,
                    "paddingBottom" => 10,
                    "paddingLeft" => 10,
                    "topRadio" => 8,
                    "bottomRadio" => 8,
                    "background1" => "#fff5cf",
                    "background2" => "#fff"
                ],
                'data' => [
                    [
                        'linkUrl' => '',
                        'imageUrl' => '',
                        'title' => '按钮标题',
                        'text' => '按钮文字1',
                        'color' => '#666666'
                    ],
                ]
            ],
            'blank' => [
                'name' => '辅助空白',
                'type' => 'blank',
                'group' => 'tools',
                'icon' => 'icon-kongbaiye',
                'style' => [
                    'height' => 20,
                    'background' => '#ffffff'
                ]
            ],
            'guide' => [
                'name' => '辅助线',
                'type' => 'guide',
                'group' => 'tools',
                'icon' => 'icon-fuzhuxian',
                'style' => [
                    'background' => '#f2f2f2',
                    'lineStyle' => 'solid',
                    'lineHeight' => 1,
                    'lineColor' => "#eeeeee",
                    'paddingTop' => 10,
                    'paddingLeft' => 10,
                    'paddingBottom' => 0,
                ]
            ],
            'window' => [
                'name' => '图片橱窗',
                'type' => 'window',
                'group' => 'media',
                'icon' => 'icon-tupian11',
                'style' => [
                    'paddingTop' => 0,
                    'paddingLeft' => 10,
                    'paddingBottom' => 10,
                    'background' => '#f2f2f2',
                    'layout' => 4
                ],
                'data' => [
                    [
                        'imgUrl' => self::$base_url . 'image/diy/window/01.jpg',
                        'linkUrl' => ''
                    ],
                    [
                        'imgUrl' => self::$base_url . 'image/diy/window/02.jpg',
                        'linkUrl' => ''
                    ],
                    [
                        'imgUrl' => self::$base_url . 'image/diy/window/03.jpg',
                        'linkUrl' => ''
                    ],
                    [
                        'imgUrl' => self::$base_url . 'image/diy/window/04.jpg',
                        'linkUrl' => ''
                    ]
                ],
                'dataNum' => 4
            ],
            'adNav' => [
                'name' => '广告导航',
                'type' => 'adNav',
                'group' => 'shop',
                'icon' => 'icon-tupian11',
                'style' => [
                    "bgcolor" => "#f2f2f2",
                    "bottomRadio" => 8,
                    "paddingLeft" => 10,
                    "topRadio" => 8,
                    "paddingTop" => 10
                ],
                // '手动选择' => 默认数据
                'data' => [
                    [
                        'imgUrl' => self::$base_url . 'image/diy/navbar/01.png',
                        'linkUrl' => 'pages/product/list/takeaway?orderType=takein',
                        "title" => "门店自取",
                        "text" => "下单免排队",
                        "titlecolor" => "#333",
                        "textcolor" => "#999"
                    ],
                    [
                        'imgUrl' => self::$base_url . 'image/diy/navbar/02.png',
                        "linkUrl" => "pages/product/list/takeaway?orderType=takeout",
                        "titlecolor" => "#333",
                        "textcolor" => "#999",
                        "title" => "外卖点单",
                        "text" => "无接触配送"

                    ]
                ]
            ],
        ];
    }

    /**
     * 格式化页面数据
     * @param $json
     * @return mixed
     */
    public function getPageDataAttr($json)
    {
        // 旧版数据转义
        $array = $this->_transferToNewData($json);
        // 合并默认数据
        return $this->_mergeDefaultData($array);
    }

    /**
     * 自动转换data为json格式
     * @param $value
     * @return false|string
     */
    public function setPageDataAttr($value)
    {
        return json_encode($value ?: ['items' => []]);
    }

    /**
     * diy页面详情
     * @param $page_id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($page_id)
    {
        return static::find($page_id);
    }

    /**
     * diy页面详情
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getHomePage()
    {
        return self::where('page_type', '10')->find();
    }

    /**
     * 旧版数据转义为新版格式
     * @param $json
     * @return array
     */
    private function _transferToNewData($json)
    {
        $array = json_decode($json, true);
        $items = $array['items'];
        if (isset($items['page'])) {
            unset($items['page']);
        }
        foreach ($items as &$item) {
            isset($item['data']) && $item['data'] = array_values($item['data']);
        }
        return [
            'page' => isset($array['page']) ? $array['page'] : $array['items']['page'],
            'items' => array_values(array_filter($items))
        ];
    }

    /**
     * 合并默认数据
     * @param $array
     * @return mixed
     */
    private function _mergeDefaultData($array)
    {
        $array['page'] = array_merge_multiple($this->getDefaultPage(), $array['page']);
        $defaultItems = $this->getDefaultItems();
        foreach ($array['items'] as &$item) {
            if (isset($defaultItems[$item['type']])) {
                array_key_exists('data', $item) && $defaultItems[$item['type']]['data'] = [];
                $item = array_merge_multiple($defaultItems[$item['type']], $item);
            }
        }
        return $array;
    }

}
