import request from '@/utils/request'
let OrderApi = {
    /*订单列表*/
    takeOrderlist(data, errorback) {
        return request._post('/shop/takeout.order/index', data, errorback);
    },
    storeOrderlist(data, errorback) {
        return request._post('/shop/store.order/index', data, errorback);
    },
    /*订单详情*/
    takeOrderdetail(data, errorback) {
        return request._post('/shop/takeout.order/detail', data, errorback);
    },
    storeOrderdetail(data, errorback) {
        return request._post('/shop/store.order/detail', data, errorback);
    },
    /*取消*/
    storeConfirm(data, errorback) {
        return request._post('/shop/store.Operate/orderCancel', data, errorback);
    },
    takeConfirm(data, errorback) {
        return request._post('/shop/takeout.Operate/orderCancel', data, errorback);
    },
    /*退款*/
    takeRefund(data, errorback) {
        return request._post('/shop/takeout.Operate/refund', data, errorback);
    },
    storeRefund(data, errorback) {
        return request._post('/shop/store.Operate/refund', data, errorback);
    },
    /*确认收货并退款*/
    takeReceipt(data, errorback) {
        return request._post('/shop/takeout.refund/receipt', data, errorback);
    },
    storeReceipt(data, errorback) {
        return request._post('/shop/store.refund/receipt', data, errorback);
    },
    /*核销*/
    takeExtract(data, errorback) {
        return request._post('/shop/takeout.operate/extract', data, errorback);
    },
    storeExtract(data, errorback) {
        return request._post('/shop/store.operate/extract', data, errorback);
    },
    sendDada(data, errorback) {
        return request._post('/shop/takeout.operate/sendOrder', data, errorback);
    },
    deliveryData(data, errorback) {
        return request._get('/shop/takeout.delivery/index', data, errorback);
    },
	storeWxDelivery(data, errorback) {
	    return request._post('/shop/store.order/wxDelivery', data, errorback);
	},
	takeoutWxDelivery(data, errorback) {
	    return request._post('/shop/takeout.order/wxDelivery', data, errorback);
	},
}

export default OrderApi;
