<?php

namespace app\shop\service\order;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * 订单导出服务类
 */
class ExportService
{
    /**
     * 订单导出
     */
    public function orderList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //列宽
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('P')->setWidth(30);

        //设置工作表标题名称
        $sheet->setTitle('订单明细');

        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '商品信息');
        $sheet->setCellValue('C1', '订单总额');
        $sheet->setCellValue('D1', '包装费');
        $sheet->setCellValue('E1', '实付款金额');
        $sheet->setCellValue('F1', '支付方式');
        $sheet->setCellValue('G1', '下单时间');
        $sheet->setCellValue('H1', '买家');
        $sheet->setCellValue('I1', '买家留言');
        $sheet->setCellValue('J1', '配送方式');
        $sheet->setCellValue('K1', '自提联系电话');
        $sheet->setCellValue('L1', '收货人姓名');
        $sheet->setCellValue('M1', '联系电话');
        $sheet->setCellValue('N1', '收货人地址');
        $sheet->setCellValue('O1', '付款状态');
        $sheet->setCellValue('P1', '付款时间');
        $sheet->setCellValue('Q1', '核销时间');
        $sheet->setCellValue('R1', '订单状态');
        $sheet->setCellValue('S1', '微信支付交易号');
        $sheet->setCellValue('T1', '订单来源');
        $sheet->setCellValue('U1', '退款金额');

        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $address = $order['address'];
            $sheet->setCellValue('A' . ($index + 2), "\t" . $order['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $this->filterProductInfo($order));
            $sheet->setCellValue('C' . ($index + 2), $order['order_price']);
            $sheet->setCellValue('D' . ($index + 2), $order['bag_price']);
            $sheet->setCellValue('E' . ($index + 2), $order['pay_price']);
            $sheet->setCellValue('F' . ($index + 2), $order['pay_type']['text']);
            $sheet->setCellValue('G' . ($index + 2), $order['create_time']);
            $sheet->setCellValue('H' . ($index + 2), $order['user'] ? $order['user']['nickName'] : '');
            $sheet->setCellValue('I' . ($index + 2), $order['buy_remark']);
            $sheet->setCellValue('J' . ($index + 2), $order['delivery_type']['text']);
            $sheet->setCellValue('K' . ($index + 2), !empty($order['extract']) ? "\t" . $order['extract']['phone'] . "\t" : '');
            $sheet->setCellValue('L' . ($index + 2), $order['address'] ? $order['address']['name'] : '');
            $sheet->setCellValue('M' . ($index + 2), "\t" . ($order['address'] ? $order['address']['phone'] : '') . "\t");
            $sheet->setCellValue('N' . ($index + 2), $address ? $address->getFullAddress() : '');
            $sheet->setCellValue('O' . ($index + 2), $order['pay_status']['text']);
            $sheet->setCellValue('P' . ($index + 2), $this->filterTime($order['pay_time']));
            $sheet->setCellValue('Q' . ($index + 2), $this->filterTime($order['receipt_time']));
            $sheet->setCellValue('R' . ($index + 2), $order['order_status']['text']);
            $sheet->setCellValue('S' . ($index + 2), $order['transaction_id']);
            $sheet->setCellValue('T' . ($index + 2), $order['order_source_text']);
            $sheet->setCellValue('U' . ($index + 2), $order['refund_money']);
            $index++;
        }

        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '订单') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 订单导出
     */
    public function deliverList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //列宽
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(10);

        //设置工作表标题名称
        $sheet->setTitle('订单明细');

        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '订单金额');
        $sheet->setCellValue('C1', '订单状态');
        $sheet->setCellValue('D1', '配送方式');
        $sheet->setCellValue('E1', '配送费');
        $sheet->setCellValue('F1', '配送状态');
        $sheet->setCellValue('G1', '配送时间');
        $sheet->setCellValue('H1', '送达时间');
        $sheet->setCellValue('I1', '收货人姓名');
        $sheet->setCellValue('J1', '联系电话');
        $sheet->setCellValue('L1', '收货人地址');
        $sheet->setCellValue('L1', '配送员');
        $sheet->setCellValue('M1', '配送员电话');

        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $address = $order['address'];
            $sheet->setCellValue('A' . ($index + 2), "\t" . $order['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $order['orders']['order_price']);
            $sheet->setCellValue('C' . ($index + 2), $order['orders']['order_status']['text']);
            $sheet->setCellValue('D' . ($index + 2), $order['deliver_source_text']);
            $sheet->setCellValue('E' . ($index + 2), $order['price']);
            $sheet->setCellValue('F' . ($index + 2), $order['deliver_status_text']);
            $sheet->setCellValue('G' . ($index + 2), $order['create_time']);
            $sheet->setCellValue('H' . ($index + 2), $this->filterTime($order['deliver_time']));
            $sheet->setCellValue('I' . ($index + 2), $order['orders']['address']['name']);
            $sheet->setCellValue('J' . ($index + 2), "\t" . $order['orders']['address']['phone'] . "\t");
            $sheet->setCellValue('K' . ($index + 2), $address ? $address->getFullAddress() : '');
            $sheet->setCellValue('L' . ($index + 2), $order['linkman']);
            $sheet->setCellValue('M' . ($index + 2), $order['phone']);
            $index++;
        }

        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '订单配送信息') . '-' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 积分订单导出
     */
    public function pointsList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //列宽
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('P')->setWidth(30);

        //设置工作表标题名称
        $sheet->setTitle('积分订单明细');

        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '商品信息');
        $sheet->setCellValue('C1', '订单总额');
        $sheet->setCellValue('D1', '兑换积分');
        $sheet->setCellValue('E1', '配送费');
        $sheet->setCellValue('F1', '支付方式');
        $sheet->setCellValue('G1', '下单时间');
        $sheet->setCellValue('H1', '买家');
        $sheet->setCellValue('I1', '配送方式');
        $sheet->setCellValue('J1', '自提门店');
        $sheet->setCellValue('K1', '门店电话');
        $sheet->setCellValue('L1', '门店地址');
        $sheet->setCellValue('M1', '收货人姓名');
        $sheet->setCellValue('N1', '联系电话');
        $sheet->setCellValue('O1', '收货人地址');
        $sheet->setCellValue('P1', '付款状态');
        $sheet->setCellValue('Q1', '付款时间');
        $sheet->setCellValue('R1', '核销时间');
        $sheet->setCellValue('S1', '订单状态');
        $sheet->setCellValue('T1', '支付交易号');

        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $address = $order['address'];
            $sheet->setCellValue('A' . ($index + 2), "\t" . $order['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $order['product_name']);
            $sheet->setCellValue('C' . ($index + 2), $order['pay_price']);
            $sheet->setCellValue('D' . ($index + 2), $order['points_num']);
            $sheet->setCellValue('E' . ($index + 2), $order['express_price']);
            $sheet->setCellValue('F' . ($index + 2), $order['pay_type']['text']);
            $sheet->setCellValue('G' . ($index + 2), $order['create_time']);
            $sheet->setCellValue('H' . ($index + 2), $order['user']['nickName']);
            $sheet->setCellValue('I' . ($index + 2), $order['delivery_type']['text']);
            $sheet->setCellValue('J' . ($index + 2), $order['store'] ? $order['store']['name'] : '');
            $sheet->setCellValue('K' . ($index + 2), $order['store'] ? "\t" . $order['store']['link_phone'] . "\t" : '');
            $sheet->setCellValue('L' . ($index + 2), $order['store'] ? $order['store']['address'] : '');
            $sheet->setCellValue('M' . ($index + 2), $order['address'] ? $order['address']['name'] : '');
            $sheet->setCellValue('N' . ($index + 2), $order['address'] ? "\t" . $order['address']['phone'] . "\t" : '');
            $sheet->setCellValue('O' . ($index + 2), $order['address'] ? $order['address']['detail'] : '');
            $sheet->setCellValue('P' . ($index + 2), $order['pay_status']['text']);
            $sheet->setCellValue('Q' . ($index + 2), $this->filterTime($order['pay_time']));
            $sheet->setCellValue('R' . ($index + 2), $this->filterTime($order['receipt_time']));
            $sheet->setCellValue('S' . ($index + 2), $order['state_text']);
            $sheet->setCellValue('T' . ($index + 2), $order['transaction_id']);
            $index++;
        }

        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '积分订单') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 格式化商品信息
     */
    private function filterProductInfo($order)
    {
        $content = '';
        foreach ($order['product'] as $key => $product) {
            $content .= ($key + 1) . ".商品名称：{$product['product_name']}\n";
            !empty($product['product_attr']) && $content .= "　商品规格：{$product['product_attr']}\n";
            $content .= "　购买数量：{$product['total_num']}\n";
            $content .= "　商品总价：{$product['total_price']}元\n\n";
        }
        return $content;
    }

    /**
     * 订单导出
     */
    public function financeOrderList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //列宽
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        //设置工作表标题名称
        $sheet->setTitle('订单明细');
        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '订单来源');
        $sheet->setCellValue('C1', '应收金额');
        $sheet->setCellValue('D1', '优惠金额');
        $sheet->setCellValue('E1', '实付金额');
        $sheet->setCellValue('F1', '预计收入');
        $sheet->setCellValue('G1', '订单状态');
        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $sheet->setCellValue('A' . ($index + 2), "\t" . $order['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $order['order_type_text']);
            $sheet->setCellValue('C' . ($index + 2), $order['order_price']);
            $sheet->setCellValue('D' . ($index + 2), $order['order_price'] - $order['pay_price']);
            $sheet->setCellValue('E' . ($index + 2), $order['pay_price']);
            $sheet->setCellValue('F' . ($index + 2), $order['pay_price'] - $order['refund_money']);
            $sheet->setCellValue('G' . ($index + 2), $order['order_status']['text']);
            $index++;
        }
        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '订单') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 订单导出
     */
    public function recordOrderList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //列宽
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        //设置工作表标题名称
        $sheet->setTitle('订单交易明细');
        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '订单类型');
        $sheet->setCellValue('C1', '总金额');
        $sheet->setCellValue('D1', '优惠金额');
        $sheet->setCellValue('E1', '实付金额');
        $sheet->setCellValue('F1', '实际到账');
        $sheet->setCellValue('G1', '支付方式');
        $sheet->setCellValue('H1', '订单状态');
        $sheet->setCellValue('I1', '下单时间');
        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $sheet->setCellValue('A' . ($index + 2), "\t" . $order['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $order['order_type_text']);
            $sheet->setCellValue('C' . ($index + 2), $order['order_price']);
            $sheet->setCellValue('D' . ($index + 2), $order['order_price'] - $order['pay_price']);
            $sheet->setCellValue('E' . ($index + 2), $order['pay_price']);
            $sheet->setCellValue('F' . ($index + 2), $order['pay_price'] - $order['refund_money']);
            $sheet->setCellValue('G' . ($index + 2), $order['pay_type']['text']);
            $sheet->setCellValue('H' . ($index + 2), $order['order_status']['text']);
            $sheet->setCellValue('I' . ($index + 2), $order['create_time']);
            $index++;
        }
        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '订单') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 订单导出
     */
    public function ProductRank($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //列宽
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        //设置工作表标题名称
        $sheet->setTitle('商品销量明细');
        $sheet->setCellValue('A1', '排名');
        $sheet->setCellValue('B1', '商品名称');
        $sheet->setCellValue('C1', '商品价格');
        $sheet->setCellValue('D1', '销量');
        $sheet->setCellValue('E1', '销售额(元)');

        //填充数据
        $index = 0;
        foreach ($list as $key => $item) {
            $sheet->setCellValue('A' . ($index + 2), "\t" . ($key + 1) . "\t");
            $sheet->setCellValue('B' . ($index + 2), $item['product_name']);
            $sheet->setCellValue('C' . ($index + 2), $item['product_price']);
            $sheet->setCellValue('D' . ($index + 2), $item['total_num']);
            $sheet->setCellValue('E' . ($index + 2), $item['total_price']);
            $index++;
        }
        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '订单') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 订单导出
     */
    public function groupOrder($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //列宽
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);

        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        //设置工作表标题名称
        $sheet->setTitle('团购订单明细');
        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '团购名称');
        $sheet->setCellValue('C1', '团购价格');
        $sheet->setCellValue('D1', '团购数量');
        $sheet->setCellValue('E1', '订单总额');
        $sheet->setCellValue('F1', '实付款金额');
        $sheet->setCellValue('G1', '支付方式');
        $sheet->setCellValue('H1', '下单时间');
        $sheet->setCellValue('I1', '买家');
        $sheet->setCellValue('J1', '买家电话');
        $sheet->setCellValue('K1', '付款时间');
        $sheet->setCellValue('L1', '核销时间');
        $sheet->setCellValue('M1', '订单状态');
        $sheet->setCellValue('N1', '微信支付交易号');

        //填充数据
        $index = 0;
        foreach ($list as $key => $item) {
            $sheet->setCellValue('A' . ($index + 2), "\t" . $item['order_no'] . "\t");
            $sheet->setCellValue('B' . ($index + 2), $item['product'][0]['group_name']);
            $sheet->setCellValue('C' . ($index + 2), $item['product'][0]['group_price']);
            $sheet->setCellValue('D' . ($index + 2), $item['product'][0]['total_num']);
            $sheet->setCellValue('E' . ($index + 2), $item['total_price']);
            $sheet->setCellValue('F' . ($index + 2), $item['pay_price']);
            $sheet->setCellValue('G' . ($index + 2), $item['pay_type']['text']);
            $sheet->setCellValue('H' . ($index + 2), $item['create_time']);
            $sheet->setCellValue('I' . ($index + 2), $item['user']['nickName']);
            $sheet->setCellValue('J' . ($index + 2), $item['user']['mobile']);
            $sheet->setCellValue('K' . ($index + 2), $this->filterTime($item['pay_time']));
            $sheet->setCellValue('L' . ($index + 2), $this->filterTime($item['settled_time']));
            $sheet->setCellValue('M' . ($index + 2), $item['order_status']['text']);
            $sheet->setCellValue('N' . ($index + 2), $item['transaction_id']);
            $index++;
        }
        //保存文件
        $writer = new Xlsx($spreadsheet);
        $filename = iconv("UTF-8", "GB2312//IGNORE", '团购订单明细') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 余额提现订单导出
     */
    public function userCashList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //列宽
        $sheet->getColumnDimension('I')->setWidth(50);

        //设置工作表标题名称
        $sheet->setTitle('余额提现明细');

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', '用户ID');
        $sheet->setCellValue('C1', '微信昵称');
        $sheet->setCellValue('D1', '手机号');
        $sheet->setCellValue('E1', '提现金额');
        $sheet->setCellValue('F1', '实际到账');
        $sheet->setCellValue('G1', '提现比例');
        $sheet->setCellValue('H1', '提现方式');
        $sheet->setCellValue('I1', '提现信息');
        $sheet->setCellValue('J1', '审核状态');
        $sheet->setCellValue('K1', '申请时间');
        $sheet->setCellValue('L1', '审核时间');
        //填充数据
        $index = 0;
        foreach ($list as $cash) {
            $sheet->setCellValue('A' . ($index + 2), $cash['id']);
            $sheet->setCellValue('B' . ($index + 2), $cash['user_id']);
            $sheet->setCellValue('C' . ($index + 2), $cash['nickName']);
            $sheet->setCellValue('D' . ($index + 2), "\t" . $cash['mobile'] . "\t");
            $sheet->setCellValue('E' . ($index + 2), $cash['money']);
            $sheet->setCellValue('F' . ($index + 2), $cash['real_money']);
            $sheet->setCellValue('G' . ($index + 2), $cash['cash_ratio'] . '%');
            $sheet->setCellValue('H' . ($index + 2), $cash['pay_type']['text']);
            $sheet->setCellValue('I' . ($index + 2), $this->cashInfo($cash));
            $sheet->setCellValue('J' . ($index + 2), $cash['apply_status']['text']);
            $sheet->setCellValue('K' . ($index + 2), $cash['create_time']);
            $sheet->setCellValue('L' . ($index + 2), $cash['audit_time']);
            $index++;
        }
        //保存文件
        $filename = iconv("UTF-8", "GB2312//IGNORE", '余额提现明细') . '-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * 格式化提现信息
     */
    private function cashInfo($cash)
    {
        $content = '';
        if ($cash['pay_type']['value'] == 20) {
            $content .= "支付宝姓名：{$cash['alipay_name']}\n";
            $content .= "  支付宝账号：{$cash['alipay_account']}\n";
        } elseif ($cash['pay_type']['value'] == 30) {
            $content .= "银行名称：{$cash['bank_name']}\n";
            $content .= "  开户名：{$cash['bank_account']}\n";
            $content .= "  银行卡号：{$cash['bank_card']}\n";
        }
        return $content;
    }

    /**
     * 日期值过滤
     */
    private function filterTime($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

}