<?php

namespace app\common\enum\settings;

use MyCLabs\Enum\Enum;
/**
 * 小票打印机类型 枚举类
 */
class PrinterTypeEnum extends Enum
{
    // 飞鹅打印机
    const FEI_E_YUN = 'FEI_E_YUN';

    // 365云打印
    const PRINT_CENTER = 'PRINT_CENTER';

    // 获取打印机类型名称
    public static function getTypeName()
    {
        return [
            self::FEI_E_YUN => '飞鹅打印机',
            self::PRINT_CENTER => '365云打印',
        ];
    }

}