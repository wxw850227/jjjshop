const AgoraRtmClientModule = uni.requireNativePlugin('AgoraRtm');
var agoraRtm = {
	createInstance(params, callback) {
		this.callMethod('createInstance', {
		  appId: params.appid
		}, callback);
	},
	login(params, callback){
		this.callMethod('login', {
		  token: params.token,
		  uid: params.uid
		}, callback);
	},
	join(callback){
		this.callMethod('join', null, callback);
	},
	createChannel(params, callback) {
		this.callMethod('createChannel', {
		  channelId: params.channelId
		}, callback);
	},
	sendMessage(params, callback) {
		this.callMethod('sendMessage', {
		  message: params.message
		}, callback);
	},
	getChannelMemberCount(params, callback) {
		this.callMethod('getChannelMemberCount', {
		  channelIds: params.channelIds
		}, callback);
	},
	getMembers(callback){
		this.callMethod('getMembers', null, callback);
	},
	setupRemoteVideo(callback){
		this.callMethod('setupRemoteVideo', null, callback);
	},
	logout(){
		this.callMethod('logout', null);
	},
	release(params){
		this.callMethod('release', {
		  channelId: params.channelId
		});
	},
	callMethod(method, args, callback){
		return new Promise((resolve, reject) => {
			AgoraRtmClientModule.callMethod({
				method: method,
				args: args
			}, res => {
				console.log(res);
				if (res && res.code) {
				  console.log('-------rtm reject--------method='+method);
				  reject(res);
				} else {
				  console.log('-------rtm resolve--------method='+method);
				  resolve(res);
				  let resJson = JSON.parse(res);
				  callback && callback(resJson);
				}
			});
		});
	}
};
export default agoraRtm;
