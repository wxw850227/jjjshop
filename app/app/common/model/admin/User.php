<?php
namespace app\common\model\admin;

use app\common\model\BaseModel;

/**
 * 超管后台用户模型
 */
class User extends BaseModel
{
    //表名
    protected $name = 'admin_user';
    //主键字段名
    protected $pk = 'admin_user_id';
}