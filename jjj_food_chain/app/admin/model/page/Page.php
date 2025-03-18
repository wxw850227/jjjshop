<?php

namespace app\admin\model\page;

use app\common\model\page\Page as PageModel;

/**
 * 微信小程序diy页面模型
 */
class Page extends PageModel
{
    /**
     * 新增小程序首页diy默认设置
     */
    public function insertDefault($app_id)
    {
        return $this->save([
            'page_type' => 10,
            'page_name' => '首页',
            'page_data' => [
                'page' => [
                    'type' => 'page',
                    'name' => '页面设置',
                    'params' => [
                        'name' => '页面名称',
                        'share_title' => '分享标题',
                        'share_img' => self::$base_url . 'image/diy/logo.png',
                    ]
                ],
                'items' => [
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
                                "title" => "到店自取",
                                "text" => "门店免排队",
                                "titlecolor" => "#333",
                                "textcolor" => "#999",
                                'name' => '链接到 页面 自取'
                            ],
                            [
                                'imgUrl' => self::$base_url . 'image/diy/navbar/02.png',
                                "linkUrl" => "pages/product/list/takeaway?orderType=takeout",
                                "titlecolor" => "#333",
                                "textcolor" => "#999",
                                "title" => "外卖点单",
                                "text" => "在家享美味",
                                'name' => '链接到 页面 外卖'
                            ],
                            [
                                'imgUrl' => self::$base_url . 'image/diy/navbar/03.png',
                                "linkUrl" => "scanQrcode",
                                "titlecolor" => "#333",
                                "textcolor" => "#999",
                                "title" => "扫码点餐",
                                "text" => "下单更便捷",
                                'name' => '链接到 菜单 扫一扫'
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
                            "rowsNum" => "1",
                            "bgcolor" => "#f2f2f2",
                            "paddingTop" => 10,
                            "paddingBottom" => 10,
                            "paddingLeft" => 10,
                            "topRadio" => 8,
                            "bottomRadio" => 8,
                            "background1" => "#FFFFFF",
                            "background2" => "#FFFFFF"
                        ],
                        'data' => [
                            [
                                'linkUrl' => 'pages/product/list/store',
                                'imageUrl' => self::$base_url . 'image/diy/navbar/04.png',
                                'title' => '快餐模式',
                                'text' => '带你发掘更多美食',
                                'color' => '#666666',
                                'name' => '链接到 页面 快餐模式'
                            ],
                        ]
                    ],
                ],
            ],
            'app_id' => $app_id
        ]);
    }

}
