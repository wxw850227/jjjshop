const AgoraRtcEngineModule = uni.requireNativePlugin('AgoraView');
var agoraRtc = {
	create(params, callback) {
		console.log('appid==='+params.appid);
		this.callMethod('create', {
		  config: {
			appId: params.appid,
			areaCode: 0,
			logConfig: 0
		  },
		  appType: 14
		}, callback);
	},
	enableVideo(callback) {
		this.callMethod('enableVideo', null, callback);
	},
	setBeautyEffectOptions(beautyOptions) {
		this.callMethod('setBeautyEffectOptions', {
			enabled: true,
			options: beautyOptions
		});
	},
	setVideoEncoderConfiguration(config, callback) {
		this.callMethod('setVideoEncoderConfiguration', {
			config: config
		}, callback);
	},
	startPreview(callback) {
		this.callMethod('startPreview', null, callback);
	},
	switchCamera(callback) {
		this.callMethod('switchCamera', null, callback);
	},
	enableVirtualBackground(config, callback){
		this.callMethod('enableVirtualBackground', {
			enabled: config.enabled,
			config: config
		}, callback);
	},
	stopPreview(callback) {
		this.callMethod('stopPreview', null, callback);
	},
	setChannelProfile(profile, callback){
		this.callMethod('setChannelProfile', {
			profile: profile,
		}, callback);
	},
	setClientRole(role, callback){
		this.callMethod('setClientRole', {
			role: role,
		}, callback);
	},
	joinChannel(params, callback){
		this.callMethod('joinChannel', {
			token: params.token,
			channelName: params.channelName,
			optionalInfo: null,
			optionalUid: params.uid,
			options: null
		}, callback);
	},
	stopAudioMixing(callback){
		this.callMethod('stopAudioMixing', null, callback);
	},
	startAudioMixing(params, callback){
		this.callMethod('startAudioMixing', {
			filePath: params.filePath,
			loopback: params.loopback,
			replace: params.replace,
			cycle: params.cycle,
			startPos: params.startPos
		}, callback);
	},
	leaveChannel(callback){
		this.callMethod('leaveChannel', null, callback);
	},
	disableAudio(callback){
		this.callMethod('disableAudio', null, callback);
	},
	enableAudio(callback){
		this.callMethod('enableAudio', null, callback);
	},
	destroy(){
		this.callMethod('destroy', null);
	},
	callMethod(method, args, callback){
		return new Promise((resolve, reject) => {
			AgoraRtcEngineModule.callMethod({
				method: method,
				args: args
			}, res => {
				console.log(res);
				if (res && res.code) {
				  console.log('-------rtc reject--------method='+method);
				  reject(res);
				} else {
				  console.log('-------rtc resolve--------method='+method);
				  resolve(res);
				  callback && callback(res);
				}
			});
		});
	}
};
export default agoraRtc;
