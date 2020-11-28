<?php

namespace app\api\model\user;

use app\api\model\plus\agent\Referee as RefereeModel;
use app\common\model\user\Grade as GradeModel;
use think\facade\Cache;
use app\common\exception\BaseException;
use app\common\model\user\User as UserModel;

/**
 * 公众号用户模型类
 */
class UserMp extends UserModel
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
     * 用户登录
     */
    public function login($userInfo, $referee_id = null)
    {
        $userInfo = $userInfo['original'];
        // 自动注册用户
        $user_id = $this->register($userInfo, $referee_id);
        // 生成token (session3rd)
        $this->token = $this->token($userInfo['openid']);
        // 记录缓存, 7天
        Cache::set($this->token, $user_id, 86400 * 7);
        return $user_id;
    }

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
    private function token($openid)
    {
        return md5($openid . 'token_salt');
    }

    /**
     * 自动注册用户
     */
    private function register($userInfo, $referee_id = null)
    {
        $data = [];
        $user = self::detail(['mpopen_id' => $userInfo['openid']])?:$this;
        if($user){
            $model = $user;
        }else{
            $model = $this;
            $data['reg_source'] = 'mp';
        }

        $data['mpopen_id'] = $userInfo['openid'];

        // 用户昵称
        $data['nickName'] = $userInfo['nickname'];
        $data['avatarUrl'] = $userInfo['headimgurl'];
        $data['gender'] = $userInfo['sex'];
        $data['province'] = $userInfo['province'];
        $data['country'] = $userInfo['country'];
        $data['city'] = $userInfo['city'];

        try {
            $this->startTrans();
            // 保存/更新用户记录
            if (!$model->save(array_merge($data, [
                'app_id' => self::$app_id
            ]))
            ) {
                throw new BaseException(['msg' => '用户注册失败']);
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return $model['user_id'];
    }

}
