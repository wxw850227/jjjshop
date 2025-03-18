import request from '@/utils/request'

let CollectionApi = {
  /*获取数据*/
  getData(data, errorback) {
    return request._get('/shop/plus.collection/index', data, errorback);
  },
  /*保存数据*/
  saveData(data, errorback) {
    return request._post('/shop/plus.collection/index', data, errorback);
  },
}
export default CollectionApi;
