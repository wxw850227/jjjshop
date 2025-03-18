<?php

namespace app\common\enum\user\grade;

use MyCLabs\Enum\Enum;

/**
 * 会员等级变更记录表 -> 变更类型
 */
class ChangeTypeEnum extends Enum
{
    // 后台管理员设置
    const ADMIN_USER = 10;

    // 自动升级
    const AUTO_UPGRADE = 20;

}