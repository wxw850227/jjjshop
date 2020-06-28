import request from '@/utils/request'
/*权限*/
let AuthApi = {
  /*获取当前角色权限*/
  getRoleList(data, errorback) {
    return request._post('/shop/auth.user/getRoleList', data, errorback);
  },
}
export default AuthApi;
