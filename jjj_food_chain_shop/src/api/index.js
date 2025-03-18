import request from '@/utils/request'

let IndexApi = {

  /*基础配置*/
  base(data, errorback) {
    return request._post('/shop/index/base', data, errorback);
  },

  /*商城首页*/
  getCount(data, errorback) {
    return request._post('/shop/Index/index', data, errorback);
  },



}

export default IndexApi;
