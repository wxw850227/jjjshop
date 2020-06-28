### Icon 图标

用于展示 icon，组件名：``uni-icon``，代码块： uIcon。

**使用方式：**

在 ``script`` 中引用组件 

```javascript
import uniIcon from "@/components/uni-icon/uni-icon.vue"
export default {
    components: {uniIcon}
}
```

在 ``template`` 中使用组件

```html
<uni-icon type="contact" size="30"></uni-icon>
```

实际效果参考：[https://github.com/dcloudio/uni-ui](https://github.com/dcloudio/uni-ui)

**Icon 属性说明：**

|属性名		|类型|默认值	|说明|
|---|----|---|---|
|type	|String	|-|图标图案，参考下表|
|color	|String	|-|图标颜色	|
|size	|Number	|24|图标大小|
|@click	|EventHandle|-|点击 Icon 触发事件|

**type 类型：**

<div>
  <link rel="stylesheet" type="text/css" href="https://img-cdn-qiniu.dcloud.net.cn/uniapp/doc/icon1.1.css"/>
  <ul class="icon-group">
  	<li class="icon-item"><span class="uni-icon uni-icon-contact"></span><span>contact</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-person"></span><span>person</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-personadd"></span><span>personadd</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-contact-filled"></span><span>contact-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-person-filled"></span><span>person-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-personadd-filled"></span><span>personadd-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-phone"></span><span>phone</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-email"></span><span>email</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-chatbubble"></span><span>chatbubble</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-chatboxes"></span><span>chatboxes</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-phone-filled"></span><span>phone-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-email-filled"></span><span>email-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-chatbubble-filled"></span><span>chatbubble-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-chatboxes-filled"></span><span>chatboxes-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-weibo"></span><span>weibo</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-weixin"></span><span>weixin</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-pengyouquan"></span><span>pengyouquan</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-chat"></span><span>chat</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-qq"></span><span>qq</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-videocam"></span><span>videocam</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-camera"></span><span>camera</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-mic"></span><span>mic</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-location"></span><span>location</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-mic-filled"></span><span>mic-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-location-filled"></span><span>location-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-micoff"></span><span>micoff</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-image"></span><span>image</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-map"></span><span>map</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-compose"></span><span>compose</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-trash"></span><span>trash</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-upload"></span><span>upload</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-download"></span><span>download</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-close"></span><span>close</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-redo"></span><span>redo</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-undo"></span><span>undo</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-refresh"></span><span>refresh</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-star"></span><span>star</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-plus"></span><span>plus</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-minus"></span><span>minus</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-circle"></span><span>circle</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-clear"></span><span>clear</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-refresh-filled"></span><span>refresh-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-star-filled"></span><span>star-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-plus-filled"></span><span>plus-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-minus-filled"></span><span>minus-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-circle-filled"></span><span>circle-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-checkbox-filled"></span><span>checkbox-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-closeempty"></span><span>closeempty</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-refreshempty"></span><span>refreshempty</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-reload"></span><span>reload</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-starhalf"></span><span>starhalf</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-spinner"></span><span>spinner</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-spinner-cycle"></span><span>spinner-cycle</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-search"></span><span>search</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-plusempty"></span><span>plusempty</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-forward"></span><span>forward</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-back"></span><span>back</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-checkmarkempty"></span><span>checkmarkempty</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-home"></span><span>home</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-navigate"></span><span>navigate</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-gear"></span><span>gear</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-paperplane"></span><span>paperplane</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-info"></span><span>info</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-help"></span><span>help</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-locked"></span><span>locked</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-more"></span><span>more</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-flag"></span><span>flag</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-home-filled"></span><span>home-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-gear-filled"></span><span>gear-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-info-filled"></span><span>info-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-help-filled"></span><span>help-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-more-filled"></span><span>more-filled</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-settings"></span><span>settings</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-list"></span><span>list</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-bars"></span><span>bars</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-loop"></span><span>loop</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-paperclip"></span><span>paperclip</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-eye"></span><span>eye</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowup"></span><span>arrowup</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowdown"></span><span>arrowdown</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowleft"></span><span>arrowleft</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowright"></span><span>arrowright</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowthinup"></span><span>arrowthinup</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowthindown"></span><span>arrowthindown</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowthinleft"></span><span>arrowthinleft</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-arrowthinright"></span><span>arrowthinright</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-pulldown"></span><span>pulldown</span></li>
  	<li class="icon-item"><span class="uni-icon uni-icon-scan"></span><span>scan</span></li>
  </ul>
</div>
