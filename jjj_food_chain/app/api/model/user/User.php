<?php

namespace app\api\model\user;

use app\api\model\settings\Setting as SettingModel;
use think\facade\Cache;
use app\common\exception\BaseException;
use app\common\model\user\User as UserModel;
use app\common\library\easywechat\AppWx;
use app\common\model\page\CenterMenu as CenterMenuModel;


/**
 * 用户模型类
 */
class User extends UserModel
{
    private $token;

    /**
     * 隐藏字段
     */
    protected $hidden = [
        'open_id',
        'is_delete',
        'create_time',
        'update_time'
    ];

    /**
     * 获取用户信息
     */
    public static function getUser($token)
    {
        $userId = Cache::get($token);
        return self::where(['user_id' => $userId])->with(['address', 'addressDefault'])->find();
    }

    /**
     * 用户登录
     */
    public function login($post)
    {
        // 微信登录 获取session_key
        $app = AppWx::getApp();
        $utils = $app->getUtils();
        $session = $utils->codeToSession($post['code']);
        $userInfo = $this->register($session);
        return $userInfo;
    }

    /**
     * 用户登录
     */
    public function userLogin($code)
    {
        // 微信登录 获取session_key
        $app = AppWx::getApp();
        $utils = $app->getUtils();
        $session = $utils->codeToSession($code);
        $userInfo = "";
        if (isset($session['unionid']) && !empty($session['unionid'])) {
            $userInfo = self::detailByUnionid($session['unionid']);
        }
        if (!$userInfo) {
            $userInfo = $this->where('open_id', '=', $session['openid'])
                ->where('is_delete', '=', 0)
                ->find();
        }
        if (!$userInfo) {
            $this->error = '用户不存在，请重新登录';
            return false;
        }
        $this->token = $this->token($session['openid']);
        // 记录缓存, 7天
        Cache::tag('cache')->set($this->token, $userInfo['user_id'], 86400 * 7);
        return $userInfo['user_id'];
    }

    /**
     * 用户登录
     */
    public function bindMobile($post)
    {
        try {
            $user_id = $post['user_id'];
            $user = self::detail($user_id);
            if (!$user) {
                $this->error = '授权失败，请重新授权';
                return false;
            }
            if ($user['mobile']) {
                // 生成token (session3rd)
                $this->token = $this->token($user_id);
                // 记录缓存, 7天
                Cache::tag('cache')->set($this->token, $user_id, 86400 * 7);
                return $user_id;
            }
            // 微信登录 获取session_key
            $app = AppWx::getApp();
            $session = AppWx::sessionKey($app, $post['code']);
            if (!$session) {
                $this->error = '授权失败，请重新授权';
                return false;
            }
            $iv = $post['iv'];
            $encrypted_data = $post['encrypted_data'];
            $utils = $app->getUtils();
            $result = $utils->decryptSession($session['session_key'], $iv, $encrypted_data);
            if (isset($result['phoneNumber']) && $result['phoneNumber']) {
                $this->startTrans();
                $this->where('user_id', '=', $user_id)
                    ->update([
                        'mobile' => $result['phoneNumber'],
                    ]);
                // 生成token (session3rd)
                $this->token = $this->token($user_id);
                // 记录缓存, 7天
                Cache::tag('cache')->set($this->token, $user_id, 86400 * 7);
                $this->commit();
                return $user_id;
            } else {
                $this->error = '登录失败';
                return false;
            }
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = '获取手机号失败，请重试';
            return false;
        }
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
     * 自动注册用户
     */
    private function register($decryptedData)
    {
        //通过unionid查询用户是否存在
        $user = null;
        if (isset($decryptedData['unionid']) && !empty($decryptedData['unionid'])) {
            $data['union_id'] = $decryptedData['unionid'];
            $user = self::detailByUnionid($decryptedData['unionid']);
        }
        if (!$user) {
            // 通过open_id查询用户是否已存在
            $user = self::detail(['open_id' => $decryptedData['openid']]);
        }
        if ($user) {
            if (isset($data['union_id'])) {
                $model = $user;
                // 只修改union_id
                $data = [
                    'union_id' => $data['union_id'],
                ];
            } else {
                return $user;
            }
        } else {
            $model = $this;
            $data['reg_source'] = 'wx';
        }
        $this->startTrans();
        try {
            // 保存/更新用户记录
            if (!$model->save(array_merge($data, [
                'open_id' => $decryptedData['openid'],
                'app_id' => self::$app_id
            ]))
            ) {
                throw new BaseException(['msg' => '用户注册失败']);
            }
            if (!$user) {
                $setting = SettingModel::getItem('store');
                //默认昵称
                $model->save(['nickName' => $setting['user_name'] . $model['user_id']]);
            }
            $this->commit();
            return $model;
        } catch (\Exception $e) {
            $this->rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }

    //修改个人资料
    public function updateInfo($data)
    {
        return $this->allowField(['avatarUrl', 'nickName'])->save($data);
    }

    /**
     * 个人中心菜单列表
     */
    public static function getMenus()
    {
        // 查询用户菜单
        $model = new CenterMenuModel();
        $user_menus = $model->getAll();
        foreach ($user_menus as $key => &$menus) {
            if (strpos($menus['image_url'], 'http') !== 0) {
                $menus['image_url'] = base_url() . $menus['image_url'];
            }
        }
        return $user_menus;
    }

    /**
     * 退出登录
     */
    public function logOut($token)
    {
        Cache::delete($token);
        return true;
    }
}
