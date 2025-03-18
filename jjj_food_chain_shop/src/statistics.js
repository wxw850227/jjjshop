import request from '@/utils/request'

let StatisticsApi = {
  /*订单数据统计*/
  getOrderTotal(data, errorback) {
    return request._post('/supplier/statistics/sales/index', data, errorback);
  },
  /*订单时间段统计*/
  getOrderByDate(data, errorback) {
    return request._postBody('/supplier/statistics/sales/order', data, errorback);
  },
  /*商品时间段统计*/
  getProductByDate(data, errorback) {
    return request._postBody('/supplier/statistics/sales/product', data, errorback);
  },
  /*会员数据统计*/
  getUserTotal(data, errorback) {
    return request._post('/supplier/statistics/user/index', data, errorback);
  },
  /*成交占比*/
  getUserScale(data, errorback) {
    return request._post('/supplier/statistics/user/scale', data, errorback);
  },
  /*访问统计*/
  getVisit(data, errorback) {
    return request._postBody('/supplier/statistics/user/visit', data, errorback);
  },
}

export default StatisticsApi;
