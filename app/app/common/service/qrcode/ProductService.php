<?php

namespace app\common\service\qrcode;

use Grafika\Color;
use Grafika\Grafika;

class ProductService extends Base
{
    // 商品信息
    private $product;

    // 用户id
    private $user_id;

    // 商品类型：10商城商品
    private $productType;

    // 来源，微信小程序，公众号
    private $source;

    // 小程序码链接
    private $pages = [
        10 => 'pages/product/detail/detail',
    ];

    /**
     * 构造方法
     */
    public function __construct($product, $user,$source, $productType = 10)
    {
        parent::__construct();
        // 商品信息
        $this->product = $product;
        // 当前用户id
        $this->user_id = $user ? $user['user_id'] : 0;
        // 商品类型：10商城商品
        $this->productType = $productType;
        //来源
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        // 判断海报图文件存在则直接返回url
        if (file_exists($this->getPosterPath())) {
            return $this->getPosterUrl();
        }
        // 小程序id
        $appId = $this->product['app_id'];
        // 商品海报背景图
        $backdrop = __DIR__ . '/resource/product_bg.png';
        // 下载商品首图
        $productUrl = $this->saveTempImage($appId, $this->product['image'][0]['file_path'], 'product');
        // 小程序码参数
        $scene = "gid:{$this->product['product_id']},uid:" . ($this->user_id ?: '');
        // 下载小程序码
        $qrcode = $this->saveQrcode($appId, $scene, $this->pages[$this->productType]);

        // 拼接海报图
        return $this->savePoster($backdrop, $productUrl, $qrcode);
    }

    /**
     * 拼接海报图
     */
    private function savePoster($backdrop, $productUrl, $qrcode)
    {
        // 实例化图像编辑器
        $editor = Grafika::createEditor(['Gd']);
        // 字体文件路径
        $fontPath = Grafika::fontsDir() . '/' . 'st-heiti-light.ttc';
        // 打开海报背景图
        $editor->open($backdropImage, $backdrop);
        // 打开商品图片
        $editor->open($productImage, $productUrl);
        // 重设商品图片宽高
        $editor->resizeExact($productImage, 690, 690);
        // 商品图片添加到背景图
        $editor->blend($backdropImage, $productImage, 'normal', 1.0, 'top-left', 30, 30);
        // 商品名称处理换行
        $fontSize = 30;
        $productName = $this->wrapText($fontSize, 0, $fontPath, $this->product['product_name'], 680, 2);
        // 写入商品名称
        $editor->text($backdropImage, $productName, $fontSize, 30, 750, new Color('#333333'), $fontPath);
        // 写入商品价格
        $priceType = [10 => 'product_price', 20 => 'sharing_price'];
        $editor->text($backdropImage, $this->product['sku'][0][$priceType[$this->productType]], 38, 62, 964, new Color('#ff4444'));
        // 打开小程序码
        $editor->open($qrcodeImage, $qrcode);
        // 重设小程序码宽高
        $editor->resizeExact($qrcodeImage, 140, 140);
        // 小程序码添加到背景图
        $editor->blend($backdropImage, $qrcodeImage, 'normal', 1.0, 'top-left', 570, 914);

        // 保存图片
        $editor->save($backdropImage, $this->getPosterPath());
        return $this->getPosterUrl();
    }

    /**
     * 处理文字超出长度自动换行
     */
    private function wrapText($fontsize, $angle, $fontface, $string, $width, $max_line = null)
    {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        $letter = [];
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'UTF-8');
        }
        $line_count = 0;
        foreach ($letter as $l) {
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $content . ' ' . $l);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $line_count++;
                if ($max_line && $line_count >= $max_line) {
                    $content = mb_substr($content, 0, -1, 'UTF-8') . "...";
                    break;
                }
                $content .= "\n";
            }
            $content .= $l;
        }
        return $content;
    }

    /**
     * 海报图文件路径
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = root_path('public') . 'temp' . '/' . $this->product['app_id'] . '/' . $this->source. '/';
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath . $this->getPosterName();
    }

    /**
     * 海报图文件名称
     */
    private function getPosterName()
    {
        return 'product_' . md5("{$this->user_id}_{$this->productType}_{$this->product['product_id']}") . '.png';
    }

    /**
     * 海报图url
     */
    private function getPosterUrl()
    {
        return \base_url() . 'temp/' . $this->product['app_id'] . '/' .$this->source . '/' . $this->getPosterName() . '?t=' . time();
    }

}