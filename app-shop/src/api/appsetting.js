import request from '@/utils/request'

let AppSettingApi = {
  /*小程序-设置*/
  appwxDetail(data, errorback) {
    return request._post('/shop/appsettings.appwx/fetchData', data, errorback);
  },
  /*小程序-编辑*/
  editAppWx(data, errorback) {
    return request._post('/shop/appsettings.appwx/edit', data, errorback);
  },
  /*公众号*/
  appmpDetail(data, errorback) {
    return request._get('/shop/appsettings.appmp/index', data, errorback);
  },
  /*公众号*/
  editAppMp(data, errorback) {
    return request._post('/shop/appsettings.appmp/index', data, errorback);
  },
}

export default AppSettingApi;
