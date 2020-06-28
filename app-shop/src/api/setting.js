import request from '@/utils/request'

let SettingApi = {

  /*商城设置模板变量*/
  storeDetail(data, errorback) {
    return request._post('/shop/settings.store/fetchData', data, errorback);
  },
  /*保存商城设置*/
  editStore(data, errorback) {
    return request._post('/shop/settings.store/index', data, errorback);
  },
  /*交易设置模板变量*/
  tradeDetail(data, errorback) {
    return request._post('/shop/settings.trade/fetchData', data, errorback);
  },
  /*保存交易设置*/
  editTrade(data, errorback) {
    return request._post('/shop/settings.trade/index', data, errorback);
  },
  /*上传设置模板变量*/
  storageDetail(data, errorback) {
    return request._post('/shop/settings.storage/fetchData', data, errorback);
  },
  /*保存上传设置*/
  editStorage(data, errorback) {
    return request._post('/shop/settings.storage/index', data, errorback);
  },
  /*清理缓存-取数据*/
  clearDetail(data, errorback) {
    return request._post('/shop/settings.clear/fetchData', data, errorback);
  },
  /*保存缓存*/
  editCache(data, errorback) {
    return request._post('/shop/settings.clear/index', data, errorback);
  },
  /*保存缓存*/
  editCache(data, errorback) {
    return request._post('/shop/settings.clear/index', data, errorback);
  },
  /*物流公司列表*/
  expressList(data, errorback) {
    return request._post('/shop/settings.express/index', data, errorback);
  },
  /*添加物流公司*/
  addExpress(data, errorback) {
    return request._post('/shop/settings.express/add', data, errorback);
  },
  /*物流公司详情*/
  expressDetail(data, errorback) {
    return request._post('/shop/settings.express/detail', data, errorback);
  },
  /*修改物流公司*/
  editExpress(data, errorback) {
    return request._post('/shop/settings.express/edit', data, errorback);
  },
  /*删除物流公司*/
  deleteExpress(data, errorback) {
    return request._post('/shop/settings.express/delete', data, errorback);
  },
  /*退货地址列表*/
  addressList(data, errorback) {
    return request._post('/shop/settings.address/index', data, errorback);
  },
  /*添加退货地址*/
  addAddress(data, errorback) {
    return request._post('/shop/settings.address/add', data, errorback);
  },
  /*退货地址详情*/
  addressDetail(data, errorback) {
    return request._post('/shop/settings.address/detail', data, errorback);
  },
  /*修改退货地址*/
  editAddress(data, errorback) {
    return request._post('/shop/settings.address/edit', data, errorback);
  },
  /*删除退货地址*/
  deleteAddress(data, errorback) {
    return request._post('/shop/settings.address/delete', data, errorback);
  },
  /*运费模板列表*/
  deliveryList(data, errorback) {
    return request._post('/shop/settings.delivery/index', data, errorback);
  },
  /*配送区域*/
  deliveryArea(data, errorback) {
    return request._post('/shop/settings.delivery/area', data, errorback);
  },
  /*添加运费模板*/
  addDelivery(data, errorback) {
    return request._post('/shop/settings.delivery/add', data, errorback);
  },
  /*运费模板详情*/
  deliveryDetail(data, errorback) {
    return request._post('/shop/settings.delivery/detail', data, errorback);
  },
  /*修改运费模板*/
  editDelivery(data, errorback) {
    return request._post('/shop/settings.delivery/edit', data, errorback);
  },
  /*删除运费模板*/
  deleteDelivery(data, errorback) {
    return request._post('/shop/settings.delivery/delete', data, errorback);
  },
  /*物流公司编码表*/
  getCompany(data, errorback) {
    return request._post('/shop/settings.express/companyList', data, errorback);
  },
  /*短信通知模板变量*/
  smsDetail(data, errorback) {
    return request._post('/shop/settings.sms/fetchData', data, errorback);
  },
  /*修改短信设置*/
  editSms(data, errorback) {
    return request._post('/shop/settings.sms/index', data, errorback);
  }

}

export default SettingApi;
