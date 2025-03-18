<?php

namespace app\api\model\user;

use app\api\model\plus\agent\Referee as RefereeModel;
use app\api\model\settings\Setting as SettingModel;
use think\facade\Cache;
use app\common\exception\BaseException;
use app\common\model\user\User as UserModel;
use app\common\model\user\Grade as GradeModel;
use app\api\model\plus\invitationgift\Partake;

/**
 * 公众号用户模型类
 */
class UserOpen extends UserModel
{
    private $token;

    /**
     * 隐藏字段
     */
    protected $hidden = [
        'open_id',
        'is_delete',
        'app_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 生成用户认证的token
     */
    private function token($user_id)
    {
        $app_id = self::$app_id;
        // 生成一个不会重复的随机字符串
        $guid = \getGuidV4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = 'token_salt';
        return md5("{$app_id}_{$timeStamp}_{$user_id}_{$guid}_{$salt}");
    }

    /**
     * 手机号密码用户登录
     */
    public function phoneLogin($data)
    {
        $user = $this->where('mobile', '=', $data['mobile'])
            ->where('password', '=', md5($data['password']))
            ->where('reg_source', '=', 'h5')
            ->where('is_delete', '=', 0)
            ->order('user_id desc')
            ->find();
        if (!$user) {
            $this->error = '手机号或密码错误';
            return false;
        } else {
            $user_id = $user['user_id'];
        }
        // 生成token (session3rd)
        $this->token = $this->token($user_id);
        // 记录缓存, 30天
        Cache::tag('cache')->set($this->token, $user_id, 86400 * 7);
        return $user_id;
    }

    /*
    *手机号注册
    */
    public function phoneRegister($data)
    {
        $setting = SettingModel::getItem('store');
        $user = $this->where('mobile', '=', $data['mobile'])
            ->where('is_delete', '=', 0)
            ->where('reg_source', '=', 'h5')
            ->find();
        if (!$user) {
            try {
                $this->startTrans();
                $this->save([
                    'mobile' => $data['mobile'],
                    'reg_source' => 'h5',
                    'app_id' => self::$app_id,
                    'password' => md5($data['password'])
                ]);
                //默认昵称
                $this->save(['nickName' => $setting['user_name'] . $this['user_id']]);
                $this->commit();
                // 生成token
                $this->token = $this->token($this['user_id']);
                // 记录缓存, 30天
                Cache::tag('cache')->set($this->token, $this['user_id'], 86400 * 7);
                return $this['user_id'];
            } catch (\Exception $e) {
                $this->rollback();
                throw new BaseException(['msg' => $e->getMessage()]);
            }
        } else {
            $this->error = '手机号已存在';
            return false;
        }
    }
}
