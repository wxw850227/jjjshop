import request from '@/utils/request'

let AccessApi = {
  /*菜单列表*/
  accessList(data, errorback) {
    return request._post('/shop/access/index', data, errorback);
  },

  /*添加菜单*/
  addpAccess(data, errorback) {
    return request._post('/shop/access/add', data, errorback);
  },

  /*编辑菜单*/
  editAccess(data, errorback) {
    return request._post('/shop/access/edit', data, errorback);
  },
  
  /*删除菜单*/
  delAccess(data, errorback) {
    return request._post('/shop/access/delete', data, errorback);
  },

  /*修改状态*/
  status(data, errorback) {
    return request._post('/admin/access/status', data, errorback);
  },
}

export default AccessApi;
