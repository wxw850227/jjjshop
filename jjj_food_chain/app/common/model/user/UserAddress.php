<?php

namespace app\common\model\user;

use app\common\model\BaseModel;
use app\common\model\settings\Region;

class UserAddress extends BaseModel
{
    protected $name = 'user_address';
    protected $pk = 'address_id';
}