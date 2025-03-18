import request from '@/utils/request'

let DataApi = {
  /*用户接口*/
  getUser(data, errorback) {
    return request._post('/shop/data.user/lists', data, errorback);
  },
}
export default DataApi;
