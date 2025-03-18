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
			package: result.data.payment.package,
			signType: result.data.payment.signType,
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
		if (self.isWeixin()) {
			// WeixinJSBridge.invoke('getBrandWCPayRequest', JSON.parse(result.data.payment),
			WeixinJSBridge.invoke('getBrandWCPayRequest', result.data.payment,
				function(res) {
					if (res.err_msg == "get_brand_wcpay_request:ok") {
						paySuccess(result, self, success);
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
		} else {
			window.location.href = result.data.payment.h5_url + '&redirect_url=' + result.data.return_Url;
			return;
		}
		// #endif
		// #ifdef  APP-PLUS
		//微信支付
		wxAppPay(result, self, success, fail);
		return;
		// #endif
	}
	// 余额支付
	if (result.data.pay_type == 10) {
		paySuccess(result, self, success);
	}
	// 支付宝支付
	if (result.data.pay_type == 30) {
		// #ifndef  H5
		aliAppPay(result, self, success, fail);
		// #endif
		// #ifdef  H5
		uni.navigateTo({
			url: '/pages/order/alipay-h5/alipay-h5?order_id=' + result.data.order_id + '&order_type=' +
				result.data.order_type,
		});
		// #endif
	}
}

/*跳到支付成功页*/
function paySuccess(result, self, success) {
	if (success) {
		success(result);
		return;
	}
	gotoSuccess(result);
}
/*跳到支付成功页*/
function gotoSuccess(result) {
	uni.reLaunch({
		url: '/pages/order/pay-success/pay-success?order_id=' + result.data.order_id
	});

}

/*支付失败跳订单详情*/
function payError(result, fail) {
	if (fail) {
		fail(result);
		return;
	}
	if(result.data.order_type == 50){
		return
	}
	uni.redirectTo({
		url: '/pages/order/order-detail?order_id=' + result.data.order_id
	});
}

function wxAppPay(result, self, success, fail) {
	// 获取支付通道  
	plus.payment.getChannels(function(channels) {
		self.channel = channels[0];
		console.log(self.channel);
		uni.requestPayment({
			provider: 'wxpay',
			orderInfo: result.data.payment,
			success(res) {
				paySuccess(result, self, success);
			},
			fail(error) {
				console.log(error);
				self.showError('订单未支付成功', () => {
					payError(result, fail);
				});
			},
		});
	}, function(e) {
		console.log("获取支付通道失败：" + e.message);
	});
}

function aliAppPay(result, self, success, fail) {
	console.log(result.data.payment);
	uni.requestPayment({
		provider: 'alipay',
		orderInfo: result.data.payment,
		success(res) {
			paySuccess(result, self, success);
		},
		fail(error) {
			console.log(error);
			self.showError('订单未支付成功', () => {
				payError(result, fail);
			});
		},
	});
}
