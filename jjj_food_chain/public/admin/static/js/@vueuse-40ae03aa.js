import{r as e,w as t,u as n,g as o,d as r,e as a,f as u,n as i,h as s,i as l}from"./@vue-5f6096d8.js";var c;const d="undefined"!=typeof window,f=e=>"boolean"==typeof e,p=e=>"number"==typeof e,v=e=>"string"==typeof e,m=()=>{},y=d&&(null==(c=null==window?void 0:window.navigator)?void 0:c.userAgent)&&/iP(ad|hone|od)/.test(window.navigator.userAgent);function O(e){return"function"==typeof e?e():n(e)}function w(e){return!!o()&&(r(e),!0)}function b(e,t=200,n={}){return function(e,t){return function(...n){return new Promise(((o,r)=>{Promise.resolve(e((()=>t.apply(this,n)),{fn:t,thisArg:this,args:n})).then(o).catch(r)}))}}(function(e,t={}){let n,o,r=m;const a=e=>{clearTimeout(e),r(),r=m};return u=>{const i=O(e),s=O(t.maxWait);return n&&a(n),i<=0||void 0!==s&&s<=0?(o&&(a(o),o=null),Promise.resolve(u())):new Promise(((e,l)=>{r=t.rejectOnCancel?l:e,s&&!o&&(o=setTimeout((()=>{n&&a(n),o=null,e(u())}),s)),n=setTimeout((()=>{o&&a(o),o=null,e(u())}),i)}))}}(t,n),e)}function g(n,o=200,r={}){const a=e(n.value),u=b((()=>{a.value=n.value}),o,r);return t(n,(()=>u())),a}function h(t,n,o={}){const{immediate:r=!0}=o,a=e(!1);let u=null;function i(){u&&(clearTimeout(u),u=null)}function l(){a.value=!1,i()}function c(...e){i(),a.value=!0,u=setTimeout((()=>{a.value=!1,u=null,t(...e)}),O(n))}return r&&(a.value=!0,d&&c()),w(l),{isPending:s(a),start:c,stop:l}}function I(e){var t;const n=O(e);return null!=(t=null==n?void 0:n.$el)?t:n}const E=d?window:void 0;function P(...e){let n,o,r,a;if(v(e[0])||Array.isArray(e[0])?([o,r,a]=e,n=E):[n,o,r,a]=e,!n)return m;Array.isArray(o)||(o=[o]),Array.isArray(r)||(r=[r]);const u=[],i=()=>{u.forEach((e=>e())),u.length=0},s=t((()=>[I(n),O(a)]),(([e,t])=>{i(),e&&u.push(...o.flatMap((n=>r.map((o=>((e,t,n,o)=>(e.addEventListener(t,n,o),()=>e.removeEventListener(t,n,o)))(e,n,o,t))))))}),{immediate:!0,flush:"post"}),l=()=>{s(),i()};return w(l),l}let A=!1;function T(e,t,n={}){const{window:o=E,ignore:r=[],capture:a=!0,detectIframe:u=!1}=n;if(!o)return;y&&!A&&(A=!0,Array.from(o.document.body.children).forEach((e=>e.addEventListener("click",m))));let i=!0;const s=e=>r.some((t=>{if("string"==typeof t)return Array.from(o.document.querySelectorAll(t)).some((t=>t===e.target||e.composedPath().includes(t)));{const n=I(t);return n&&(e.target===n||e.composedPath().includes(n))}})),l=[P(o,"click",(n=>{const o=I(e);o&&o!==n.target&&!n.composedPath().includes(o)&&(0===n.detail&&(i=!s(n)),i?t(n):i=!0)}),{passive:!0,capture:a}),P(o,"pointerdown",(t=>{const n=I(e);n&&(i=!t.composedPath().includes(n)&&!s(t))}),{passive:!0}),u&&P(o,"blur",(n=>{var r;const a=I(e);"IFRAME"!==(null==(r=o.document.activeElement)?void 0:r.tagName)||(null==a?void 0:a.contains(o.document.activeElement))||t(n)}))].filter(Boolean);return()=>l.forEach((e=>e()))}function j(t,n=!1){const o=e(),r=()=>o.value=Boolean(t());return r(),function(e,t=!0){a()?u(e):t?e():i(e)}(r,n),o}const Q="undefined"!=typeof globalThis?globalThis:"undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:{},C="__vueuse_ssr_handlers__";function S(n,o,{window:r=E,initialValue:a=""}={}){const u=e(a),i=l((()=>{var e;return I(o)||(null==(e=null==r?void 0:r.document)?void 0:e.documentElement)}));return t([i,()=>O(n)],(([e,t])=>{var n;if(e&&r){const o=null==(n=r.getComputedStyle(e).getPropertyValue(t))?void 0:n.trim();u.value=o||a}}),{immediate:!0}),t(u,(e=>{var t;(null==(t=i.value)?void 0:t.style)&&i.value.style.setProperty(O(n),e)})),u}Q[C]=Q[C]||{};var x,N,_=Object.getOwnPropertySymbols,k=Object.prototype.hasOwnProperty,B=Object.prototype.propertyIsEnumerable;function L(e,n,o={}){const r=o,{window:a=E}=r,u=((e,t)=>{var n={};for(var o in e)k.call(e,o)&&t.indexOf(o)<0&&(n[o]=e[o]);if(null!=e&&_)for(var o of _(e))t.indexOf(o)<0&&B.call(e,o)&&(n[o]=e[o]);return n})(r,["window"]);let i;const s=j((()=>a&&"ResizeObserver"in a)),l=()=>{i&&(i.disconnect(),i=void 0)},c=t((()=>I(e)),(e=>{l(),s.value&&a&&e&&(i=new ResizeObserver(n),i.observe(e,u))}),{immediate:!0,flush:"post"}),d=()=>{l(),c()};return w(d),{isSupported:s,stop:d}}(N=x||(x={})).UP="UP",N.RIGHT="RIGHT",N.DOWN="DOWN",N.LEFT="LEFT",N.NONE="NONE";var R=Object.defineProperty,F=Object.getOwnPropertySymbols,W=Object.prototype.hasOwnProperty,z=Object.prototype.propertyIsEnumerable,D=(e,t,n)=>t in e?R(e,t,{enumerable:!0,configurable:!0,writable:!0,value:n}):e[t]=n;((e,t)=>{for(var n in t||(t={}))W.call(t,n)&&D(e,n,t[n]);if(F)for(var n of F(t))z.call(t,n)&&D(e,n,t[n])})({linear:function(e){return e}},{easeInSine:[.12,0,.39,0],easeOutSine:[.61,1,.88,1],easeInOutSine:[.37,0,.63,1],easeInQuad:[.11,0,.5,0],easeOutQuad:[.5,1,.89,1],easeInOutQuad:[.45,0,.55,1],easeInCubic:[.32,0,.67,0],easeOutCubic:[.33,1,.68,1],easeInOutCubic:[.65,0,.35,1],easeInQuart:[.5,0,.75,0],easeOutQuart:[.25,1,.5,1],easeInOutQuart:[.76,0,.24,1],easeInQuint:[.64,0,.78,0],easeOutQuint:[.22,1,.36,1],easeInOutQuint:[.83,0,.17,1],easeInExpo:[.7,0,.84,0],easeOutExpo:[.16,1,.3,1],easeInOutExpo:[.87,0,.13,1],easeInCirc:[.55,0,1,.45],easeOutCirc:[0,.55,.45,1],easeInOutCirc:[.85,0,.15,1],easeInBack:[.36,0,.66,-.56],easeOutBack:[.34,1.56,.64,1],easeInOutBack:[.68,-.6,.32,1.6]});export{d as a,f as b,P as c,I as d,S as e,h as f,y as g,p as i,T as o,g as r,w as t,L as u};
