import request from '@/utils/request'

let StatisticsApi = {
  /*订单数据统计*/
  getOrderTotal(data, errorback) {
    return request._post('/shop/statistics.sales/index', data, errorback);
  },
  /*订单时间段统计*/
  getOrderByDate(data, errorback) {
    return request._post('/shop/statistics.sales/order', data, errorback);
  },
  /*商品时间段统计*/
  getProductByDate(data, errorback) {
    return request._post('/shop/statistics.sales/product', data, errorback);
  },
}

export default StatisticsApi;
