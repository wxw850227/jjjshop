import request from '@/utils/request'

let MessageApi = {
  /*会员模板列表*/
  messageList(data, errorback) {
    return request._post('/shop/setting.message/index', data, errorback);
  },
  /*会员模板字段列表*/
  fieldList(data, errorback) {
    return request._post('/shop/setting.message/field', data, errorback);
  },
  /*会员模板字段设置保存*/
  saveSettings(data, errorback) {
    return request._post('/shop/setting.message/saveSettings', data, errorback);
  },
  /*会员模板设置状态修改*/
  updateSettingsStatus(data, errorback) {
    return request._post('/shop/setting.message/updateSettingsStatus', data, errorback);
  },
}

export default MessageApi;
