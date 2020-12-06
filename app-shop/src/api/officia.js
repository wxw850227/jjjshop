import request from '@/utils/request'

let OfficiaApi = {
    /*获取数据*/
    getData(data, errorback) {
        return request._get('/shop/plus.officia/index', data, errorback);
    },
    /*保存数据*/
    saveData(data, errorback) {
        return request._post('/shop/plus.officia/index', data, errorback);
    },
}
export default OfficiaApi;
