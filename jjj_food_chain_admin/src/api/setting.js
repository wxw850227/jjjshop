import request from '@/utils/request'

let SettingApi = {
  /*客服设置模板变量*/
  serviceDetail(data, errorback) {
    return request._get('/admin/setting.service/index', data, errorback);
  },
  /*保存客服设置*/
  editService(data, errorback) {
    return request._post('/admin/setting.service/index', data, errorback);
  },
}

export default SettingApi;
