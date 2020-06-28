import request from '@/utils/request'

let ProductApi = {

    /*分类管理*/
    catList(data, errorback) {
        return request._post('/shop/product.category/index', data, errorback);
    },
    /*分类添加*/
    catAdd(data, errorback) {
        return request._post('/shop/product.category/add', data, errorback);
    },
    /*分类删除*/
    catDel(data, errorback) {
        return request._post('/shop/product.category/delete', data, errorback);
    },
    /*分类修改*/
    catEdit(data, errorback) {
        return request._post('/shop/product.category/edit', data, errorback);
    },
    /*产品列表*/
    productList(data, errorback) {
        return request._post('/shop/product.product/index', data, errorback);
    },
    /*产品列表*/
    productLists(data, errorback) {
        return request._post('/shop/product.product/lists', data, errorback);
    },
    /*新增产品*/
    addProduct(data, errorback) {
        return request._post('/shop/product.product/add', data, errorback);
    },
    /*产品基础数据*/
    getBaseData(data, errorback) {
        return request._post('/shop/product.product/getBaseData', data, errorback);
    },
    /*删除产品*/
    delProduct(data, errorback) {
        return request._post('/shop/product.product/delete', data, errorback);
    },
    /*产品基础数据*/
    getEditData(data, errorback) {
        return request._post('/shop/product.product/getEditData', data, errorback);
    },
    /*新增产品*/
    editProduct(data, errorback) {
        return request._post('/shop/product.product/edit', data, errorback);
    },
    /*新增规格组*/
    addSpec(data, errorback) {
        return request._post('/shop/product.spec/addSpec', data, errorback);
    },
    /*新增规格值*/
    addSpecValue(data, errorback) {
        return request._post('/shop/product.spec/addSpecValue', data, errorback);
    },
    /*商品列表不带分页*/
    getList(data, errorback) {
        return request._post('/shop/data.product/lists', data, errorback);
    },
    /*商品列表不带分页*/
    getActiveProductList(data, errorback) {
        return request._post('/shop/plus.fans.product/lists', data, errorback);
    },
    /*商品评论列表*/
    getCommentList(data, errorback) {
        return request._post('/shop/product.comment/index', data, errorback);
    },
    /*获取评论详情*/
    getComment(data, errorback) {
        return request._post('/shop/product.comment/detail', data, errorback);
    },
    /*获取评论详情*/
    editComment(data, errorback) {
        return request._post('/shop/product.comment/edit', data, errorback);
    },
    /*删除评论*/
    delComment(data, errorback) {
        return request._post('/shop/product.comment/delete', data, errorback);
    },
    /*得到分类图片*/
    cateImage(data, errorback) {
        return request._post('/shop/product.category/image', data, errorback);
    },

}

export default ProductApi;
