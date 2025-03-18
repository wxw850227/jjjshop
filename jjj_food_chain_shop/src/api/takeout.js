import request from '@/utils/request'

let TakeOutApi = {
  /*店内概况*/
  takeSurvey(data, errorback) {
    return request._post('/shop/takeout.survey/index', data, errorback);
  },
  /*配送管理*/
  deliverList(data, errorback) {
    return request._post('/shop/takeout.Deliver/index', data, errorback);
  },
  /*确认送达*/
  verify(data, errorback) {
    return request._post('/shop/takeout.Deliver/verify', data, errorback);
  },
  /*取消配送*/
  cancel(data, errorback) {
    return request._post('/shop/takeout.Deliver/cancel', data, errorback);
  },
  /*取消配送*/
  detail(data, errorback) {
    return request._post('/shop/takeout.Deliver/detail', data, errorback);
  },
  /*导出*/
  export(data, errorback) {
    return request._post('/shop/takeout.Deliver/export', data, errorback);
  },
}

export default TakeOutApi;
