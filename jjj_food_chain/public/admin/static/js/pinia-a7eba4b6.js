import{i as t}from"./vue-demi-71ba0ef2.js";import{ar as e,r as n,as as s,w as o,J as a,z as c,at as r,a9 as i,y as u,g as f,d as p,n as l,e as h,K as d,i as y}from"./@vue-5f6096d8.js";
/*!
  * pinia v2.0.33
  * (c) 2023 Eduardo San Martin Morote
  * @license MIT
  */let v;const b=t=>v=t,_=Symbol();function j(t){return t&&"object"==typeof t&&"[object Object]"===Object.prototype.toString.call(t)&&"function"!=typeof t.toJSON}var m,O;function $(){const o=e(!0),a=o.run((()=>n({})));let c=[],r=[];const i=s({install(t){b(i),i._a=t,t.provide(_,i),t.config.globalProperties.$pinia=i,r.forEach((t=>c.push(t))),r=[]},use(e){return this._a||t?c.push(e):r.push(e),this},_p:c,_a:null,_e:o,_s:new Map,state:a});return i}(O=m||(m={})).direct="direct",O.patchObject="patch object",O.patchFunction="patch function";const g=()=>{};function P(t,e,n,s=g){t.push(e);const o=()=>{const n=t.indexOf(e);n>-1&&(t.splice(n,1),s())};return!n&&f()&&p(o),o}function S(t,...e){t.slice().forEach((t=>{t(...e)}))}function w(t,e){t instanceof Map&&e instanceof Map&&e.forEach(((e,n)=>t.set(n,e))),t instanceof Set&&e instanceof Set&&e.forEach(t.add,t);for(const n in e){if(!e.hasOwnProperty(n))continue;const s=e[n],o=t[n];j(o)&&j(s)&&t.hasOwnProperty(n)&&!c(s)&&!r(s)?t[n]=w(o,s):t[n]=s}return t}const E=Symbol();const{assign:I}=Object;function M(t,u,f={},p,h,d){let y;const v=I({actions:{}},f),_={deep:!0};let O,$,M,x=s([]),A=s([]);const F=p.state.value[t];let J;function k(e){let n;O=$=!1,"function"==typeof e?(e(p.state.value[t]),n={type:m.patchFunction,storeId:t,events:M}):(w(p.state.value[t],e),n={type:m.patchObject,payload:e,storeId:t,events:M});const s=J=Symbol();l().then((()=>{J===s&&(O=!0)})),$=!0,S(x,n,p.state.value[t])}d||F||(p.state.value[t]={}),n({});const z=d?function(){const{state:t}=f,e=t?t():{};this.$patch((t=>{I(t,e)}))}:g;function K(e,n){return function(){b(p);const s=Array.from(arguments),o=[],a=[];let c;S(A,{args:s,name:e,store:q,after:function(t){o.push(t)},onError:function(t){a.push(t)}});try{c=n.apply(this&&this.$id===t?this:q,s)}catch(r){throw S(a,r),r}return c instanceof Promise?c.then((t=>(S(o,t),t))).catch((t=>(S(a,t),Promise.reject(t)))):(S(o,c),c)}}const N={_p:p,$id:t,$onAction:P.bind(null,A),$patch:k,$reset:z,$subscribe(e,n={}){const s=P(x,e,n.detached,(()=>a())),a=y.run((()=>o((()=>p.state.value[t]),(s=>{("sync"===n.flush?$:O)&&e({storeId:t,type:m.direct,events:M},s)}),I({},_,n))));return s},$dispose:function(){y.stop(),x=[],A=[],p._s.delete(t)}},q=a(N);p._s.set(t,q);const B=p._e.run((()=>(y=e(),y.run((()=>u())))));for(const e in B){const n=B[e];if(c(n)&&(!c(D=n)||!D.effect)||r(n))d||(!F||j(C=n)&&C.hasOwnProperty(E)||(c(n)?n.value=F[e]:w(n,F[e])),p.state.value[t][e]=n);else if("function"==typeof n){const t=K(e,n);B[e]=t,v.actions[e]=n}}var C,D;return I(q,B),I(i(q),B),Object.defineProperty(q,"$state",{get:()=>p.state.value[t],set:t=>{k((e=>{I(e,t)}))}}),p._p.forEach((t=>{I(q,y.run((()=>t({store:q,app:p._a,pinia:p,options:v}))))})),F&&d&&f.hydrate&&f.hydrate(q.$state,F),O=!0,$=!0,q}function x(t,e,n){let o,a;const c="function"==typeof e;function r(t,n){const r=h();(t=t||r&&u(_,null))&&b(t),(t=v)._s.has(o)||(c?M(o,e,a,t):function(t,e,n,o){const{state:a,actions:c,getters:r}=e,i=n.state.value[t];let u;u=M(t,(function(){i||(n.state.value[t]=a?a():{});const e=d(n.state.value[t]);return I(e,c,Object.keys(r||{}).reduce(((e,o)=>(e[o]=s(y((()=>{b(n);const e=n._s.get(t);return r[o].call(e,e)}))),e)),{}))}),e,n,0,!0)}(o,a,t));return t._s.get(o)}return"string"==typeof t?(o=t,a=c?n:e):(a=t,o=t.id),r.$id=o,r}export{$ as c,x as d};
