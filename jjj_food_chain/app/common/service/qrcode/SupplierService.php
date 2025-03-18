<?php

namespace app\common\service\qrcode;

use app\common\model\supplier\Supplier as SupplierModel;

/**
 * 二维码
 */
class SupplierService extends Base
{
    private $id;
    private $source;

    /**
     * 构造方法
     */
    public function __construct($id, $source)
    {
        parent::__construct();
        $this->id = $id;
        $this->source = $source;
    }

    /**
     * 获取小程序码
     */
    public function getImage()
    {
        $detail = SupplierModel::detail($this->id);
        // 保存目录
        $savePath = $this->getPosterPath($detail['app_id']);
        // 删除目录下的文件
        if (!$this->is_empty_dir($savePath)) {
            $this->deleteDir(substr($savePath, 0, -1));
        }
        mkdir($savePath, 0755, true);
        if ($this->source == 'wx') {
            // 下载小程序码
            $this->saveSupplierQrcodeToDir($detail['app_id'], 'pages/index/index', $savePath, $this->id);
        } else if ($this->source == 'mp' || $this->source == 'h5') {
            $this->saveSupplierMpQrcodeToDir('h5/pages/index/index', $savePath, $this->id, $detail['app_id']);
        }

        $zipNameUrl = $this->getZipPath($detail['app_id']);

        $zip = new \ZipArchive();
        if ($zip->open($zipNameUrl, \ZipArchive::OVERWRITE) !== TRUE) {
            //OVERWRITE 参数会覆写压缩包的文件 文件必须已经存在
            if ($zip->open($zipNameUrl, \ZipArchive::CREATE) !== true) {
                // 文件不存在则生成一个新的文件 用CREATE打开文件会追加内容至zip
                return '下载失败，文件夹不存在';
            }
        }

        $this->addFileToZip($savePath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法

        $zip->close(); //关闭处理的zip文件

        $zipName = $this->source . '.zip';
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipName);
        header('Content-Length: ' . filesize($zipNameUrl));
        ob_clean();
        flush();
        readfile($zipNameUrl);
        exit;
    }


    /**
     * 二维码文件路径
     */
    private function getPosterPath($app_id)
    {
        // 保存路径
        $tempPath = root_path('public') . 'temp' . '/' . $app_id . '/supplier-' . $this->id . '/' . $this->source . '/';
        return $tempPath;
    }

    /**
     * 二维码文件路径
     */
    private function getZipPath($app_id)
    {
        // 保存路径
        $tempPath = root_path('public') . 'temp' . '/' . $app_id . '/supplier-' . $this->id . '/' . $this->source . '.zip';
        return $tempPath;
    }

    /**
     * 删除当前目录及其目录下的所有目录和文件
     * @param string $path 待删除的目录
     * @note  $path路径结尾不要有斜杠/(例如:正确[$path='./static/image'],错误[$path='./static/image/'])
     */
    private function deleteDir($path)
    {
        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ($dir != '.' && $dir != '..') {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path . '/' . $dir;
                    if (is_dir($sonDir)) {
                        //递归删除
                        $this->deleteDir($sonDir);
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {
                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }

    /**
     * 打包文件夹
     */
    private function addFileToZip($path, $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($path . "/" . $filename)) {
                    $this->addFileToZip($path . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename);
                    $zip->renameName($path . "/" . $filename, $filename);
                }
            }
        }
        @closedir($handler);
    }

    private function is_empty_dir($fp)
    {
        if (!file_exists($fp)) {
            return false;
        }
        $H = @ opendir($fp);
        $i = 0;
        while ($_file = readdir($H)) {
            $i++;
        }
        closedir($H);
        if ($i > 2) {
            return false;
        } else {
            return true;
        }
    }
}