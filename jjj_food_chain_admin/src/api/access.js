import request from '@/utils/request'

let AccessApi = {
  /*菜单列表*/
  accessList(data, errorback) {
    return request._post('/admin/Access/index', data, errorback);
  },
  /*添加菜单*/
  addpAccess(data, errorback) {
    return request._post('/admin/Access/add', data, errorback);
  },
  /*编辑菜单*/
  editAccess(data, errorback) {
    return request._post('/admin/Access/edit', data, errorback);
  },
  /*删除菜单*/
  delAccess(data, errorback) {
    return request._post('/admin/Access/delete', data, errorback);
  },
  /*修改状态*/
  status(data, errorback) {
    return request._post('/admin/Access/status', data, errorback);
  },
  /*修改状态*/
  supplier(data, errorback) {
    return request._post('/admin/Access/supplier', data, errorback);
  },
}

export default AccessApi;
