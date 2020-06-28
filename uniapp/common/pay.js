/*
 * 支付
 */
export const pay = (result, self, success, fail) => {
	if (result.code === -10) {
		self.showError(result.msg);
		return false;
	}

	// 发起微信支付
	if (result.data.pay_type == 20) {
		//小程序支付
		// #ifdef  MP-WEIXIN	
		uni.requestPayment({
			provider: 'wxpay',
			timeStamp: result.data.payment.timeStamp,
			nonceStr: result.data.payment.nonceStr,
			package: 'prepay_id=' + result.data.payment.prepay_id,
			signType: 'MD5',
			paySign: result.data.payment.paySign,
			success: res => {
				paySuccess(result, self, success);
			},
			fail: res => {
				self.showError('订单未支付成功', () => {
					payError(result, fail);
				});
			},
		});
		// #endif
		//公众号支付
		// #ifdef  H5
		WeixinJSBridge.invoke('getBrandWCPayRequest', JSON.parse(result.data.payment),
			function(res) {
				if (res.err_msg == "get_brand_wcpay_request:ok") {
					paySuccess(result, self);
				} else if (res.err_msg == "get_brand_wcpay_request:cancel") {
					self.showSuccess('支付取消', () => {
						payError(result, fail);
					});
				} else {
					self.showError('订单未支付成功', () => {
						payError(result, fail);
					});
				}
			}
		);
		// #endif
	}
	// 余额支付
	if (result.data.pay_type == 10) {
		paySuccess(result, self, success);
	}
}

/*跳到支付成功页*/
function paySuccess(result, self, success) {
	if(success){
		success(result);
		return;
	}
	// #ifdef  MP-WEIXIN
	//小程序订阅消息
	const version = wx.getSystemInfoSync().SDKVersion;
	let temlIds = result.data.template_arr;
	if (temlIds && temlIds.length != 0 && self.compareVersion(version, '2.8.2') >= 0) {
		subscribeMessage(result, temlIds);
	} else {
		gotoSuccess(result);
	}
	// #endif
	// #ifndef  MP-WEIXIN
	gotoSuccess(result);
	// #endif
}
/*跳到支付成功页*/
function gotoSuccess(result) {
	uni.redirectTo({
		url: '/pages/order/pay-success/pay-success?order_id=' + result.data.order_id
	});
}

/*支付失败跳订单详情*/
function payError(result, fail) {
	if(fail){
		fail(result);
		return;
	}
	uni.navigateTo({
		url: '/pages/order/order-detail/order-detail?order_id=' + result.data.order_id
	});
}

/*小程序订阅消息*/
function subscribeMessage(result, temlIds) {
	wx.requestSubscribeMessage({
		tmplIds: temlIds,
		success: function(res) {
			gotoSuccess(result);
		},
		fail(err) {
			console.log(err);
			gotoSuccess(result);
		}
	});
}
