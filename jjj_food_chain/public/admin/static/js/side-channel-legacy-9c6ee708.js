!function(){function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t(e)}System.register(["./get-intrinsic-legacy-ae4be0ce.js","./call-bind-legacy-19c53985.js","./object-inspect-legacy-a2c406c5.js"],(function(e,n){"use strict";var o,r,u;return{setters:[function(t){o=t.g},function(t){r=t.a},function(t){u=t.o}],execute:function(){var n=o,i=r,f=u,c=n("%TypeError%"),a=n("%WeakMap%",!0),p=n("%Map%",!0),y=i("WeakMap.prototype.get",!0),s=i("WeakMap.prototype.set",!0),l=i("WeakMap.prototype.has",!0),b=i("Map.prototype.get",!0),x=i("Map.prototype.set",!0),g=i("Map.prototype.has",!0),m=function(t,e){for(var n,o=t;null!==(n=o.next);o=n)if(n.key===e)return o.next=n.next,n.next=t.next,t.next=n,n};e("s",(function(){var e,n,o,r={assert:function(t){if(!r.has(t))throw new c("Side channel does not contain "+f(t))},get:function(r){if(a&&r&&("object"===t(r)||"function"==typeof r)){if(e)return y(e,r)}else if(p){if(n)return b(n,r)}else if(o)return function(t,e){var n=m(t,e);return n&&n.value}(o,r)},has:function(r){if(a&&r&&("object"===t(r)||"function"==typeof r)){if(e)return l(e,r)}else if(p){if(n)return g(n,r)}else if(o)return function(t,e){return!!m(t,e)}(o,r);return!1},set:function(r,u){a&&r&&("object"===t(r)||"function"==typeof r)?(e||(e=new a),s(e,r,u)):p?(n||(n=new p),x(n,r,u)):(o||(o={key:{},next:null}),function(t,e,n){var o=m(t,e);o?o.value=n:t.next={key:e,next:t.next,value:n}}(o,r,u))}};return r}))}}}))}();
