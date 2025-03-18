import request from '@/utils/request'

let UserApi = {

    /*用户登录*/
    login(data, errorback) {
        return request._post('/shop/passport/login', data, errorback);
    },
    /*退出登录*/
    loginOut(data, errorback) {
        return request._post('/shop/passport/logout', data, errorback);
    },
    /*修改用户*/
    edituser(data, errorback) {
        return request._post('/shop/user.user/edit', data, errorback);
    },
    /*充值*/
    userRecharge(data, errorback) {
        return request._post('/shop/user.user/recharge', data, errorback);
    },
    /*删除用户*/
    deleteuser(data, errorback) {
        return request._post('/shop/user.user/delete', data, errorback);
    },
    /*用户列表*/
    userlist(data, errorback) {
        return request._post('/shop/user.user/index', data, errorback);
    },
    /*用户余额*/
    BalanceLog(data, errorback) {
        return request._post('/shop/user.Balance/log', data, errorback);
    },
    /*修改密码*/
    EditPass(data, errorback) {
        return request._post('/shop/passport/editPass', data, errorback);
    },
}

export default UserApi;
