import request from '@/utils/request'

let SupplierApi = {
  /*供应商列表*/
  supplierList(data, errorback) {
    return request._post('/shop/supplier.supplier/index', data, errorback);
  },
  /*供应商编辑*/
  toEditSupplier(data, errorback) {
    return request._get('/shop/supplier.supplier/edit', data, errorback);
  },
  /*供应商设置*/
  setSupplier(data, errorback) {
    return request._post('/shop/supplier.supplier/setting', data, errorback);
  },
  /*供应商设置*/
  getsetSupplier(data, errorback) {
    return request._get('/shop/supplier.supplier/setting', data, errorback);
  },
  /*供应商编辑*/
  editSupplier(data, errorback) {
    return request._post('/shop/supplier.supplier/edit', data, errorback);
  }
}
export default SupplierApi;
