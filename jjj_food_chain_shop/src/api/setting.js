import request from '@/utils/request'

let SettingApi = {

  /*商城设置模板变量*/
  storeDetail(data, errorback) {
    return request._get('/shop/setting.store/index', data, errorback);
  },
  /*保存商城设置*/
  editStore(data, errorback) {
    return request._post('/shop/setting.store/index', data, errorback);
  },
  /*交易设置模板变量*/
  tradeDetail(data, errorback) {
    return request._get('/shop/setting.trade/index', data, errorback);
  },
  /*保存交易设置*/
  editTrade(data, errorback) {
    return request._post('/shop/setting.trade/index', data, errorback);
  },
  /*上传设置模板变量*/
  storageDetail(data, errorback) {
    return request._get('/shop/setting.storage/index', data, errorback);
  },
  /*保存上传设置*/
  editStorage(data, errorback) {
    return request._post('/shop/setting.storage/index', data, errorback);
  },
  /*打印设置模板变量*/
  printingDetail(data, errorback) {
    return request._get('/shop/setting.printing/index', data, errorback);
  },
  /*保存打印设置*/
  editPrinting(data, errorback) {
    return request._post('/shop/setting.printing/index', data, errorback);
  },
  /*打印设置模板变量*/
  clearDetail(data, errorback) {
    return request._get('/shop/setting.clear/index', data, errorback);
  },
  /*保存缓存*/
  editCache(data, errorback) {
    return request._post('/shop/setting.clear/index', data, errorback);
  },
  /*打印机列表*/
  printerList(data, errorback) {
    return request._post('/shop/setting.printer/index', data, errorback);
  },
  /*打印机类型*/
  printerType(data, errorback) {
    return request._get('/shop/setting.printer/add', data, errorback);
  },
  /*添加打印机*/
  addPrinter(data, errorback) {
    return request._post('/shop/setting.printer/add', data, errorback);
  },
  /*打印机详情*/
  printerDetail(data, errorback) {
    return request._get('/shop/setting.printer/edit', data, errorback);
  },
  /*修改打印机*/
  editPrinter(data, errorback) {
    return request._post('/shop/setting.printer/edit', data, errorback);
  },
  /*删除打印机*/
  deletePrinter(data, errorback) {
    return request._post('/shop/setting.printer/delete', data, errorback);
  },
  deliverDetail(data, errorback) {
    return request._get('/shop/setting.deliver/index', data, errorback);
  },
  editDeliver(data, errorback) {
    return request._post('/shop/setting.deliver/index', data, errorback);
  },
  /*地图设置*/
  mapDetail(data, errorback) {
    return request._get('/shop/setting.map/index', data, errorback);
  },
  /*地图设置*/
  editMap(data, errorback) {
    return request._post('/shop/setting.map/index', data, errorback);
  },
  /*协议设置*/
  protocolDetail(data, errorback) {
    return request._get('/shop/setting.protocol/index', data, errorback);
  },
  /*协议设置*/
  editProtocol(data, errorback) {
    return request._post('/shop/setting.protocol/index', data, errorback);
  },
}

export default SettingApi;
