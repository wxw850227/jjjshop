<?php

namespace app\admin\model\admin;

use app\common\model\admin\User as UserModel;
use think\facade\Cache;

/**
 * 超管后台用户模型
 */
class User extends UserModel
{
    /**
     * 超管后台用户登录
     */
    public function login($data)
    {
        // 验证用户名密码是否正确
        if (!$user = self::where([
            'user_name' => $data['username'],
            'password' => salt_hash($data['password'])
        ])->find()
        ) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        // 保存登录状态
        $user['token'] = signToken($user['admin_user_id'], 'admin');
        Cache::tag('cache')->set('admin_token_' . $user['token'], $user['admin_user_id'], 86400 * 30);
        return $user;
    }

    /**
     * 超管用户信息
     */
    public static function detail($admin_user_id)
    {
        $model = new static();
        return $model::find($admin_user_id);
    }

    /**
     * 更新当前管理员信息
     */
    public function renew($data)
    {
        if ($data['pass'] !== $data['checkPass']) {
            $this->error = '确认密码不正确';
            return false;
        }
        return $this->save([
            'password' => salt_hash($data['pass']),
        ]);
    }

    /**
     * 获取用户信息
     */
    public static function getUser($data)
    {
        return (new static())->where(['admin_user_id' => $data['uid']])->find();
    }
}