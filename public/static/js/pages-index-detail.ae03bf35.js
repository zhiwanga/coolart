(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-detail"],{"0740":function(t,e,i){"use strict";i.r(e);var n=i("2b3e"),a=i("bd27");for(var r in a)"default"!==r&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("5df3");var o,s=i("f0c5"),d=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"68d8dfe2",null,!1,n["a"],o);e["default"]=d.exports},"0ccb":function(t,e,i){var n=i("50c4"),a=i("1148"),r=i("1d80"),o=Math.ceil,s=function(t){return function(e,i,s){var d,c,u=String(r(e)),l=u.length,f=void 0===s?" ":String(s),h=n(i);return h<=l||""==f?u:(d=h-l,c=a.call(f,o(d/f.length)),c.length>d&&(c=c.slice(0,d)),t?u+c:c+u)}};t.exports={start:s(!1),end:s(!0)}},1148:function(t,e,i){"use strict";var n=i("a691"),a=i("1d80");t.exports="".repeat||function(t){var e=String(a(this)),i="",r=n(t);if(r<0||r==1/0)throw RangeError("Wrong number of repetitions");for(;r>0;(r>>>=1)&&(e+=e))1&r&&(i+=e);return i}},"1da1":function(t,e,i){"use strict";function n(t,e,i,n,a,r,o){try{var s=t[r](o),d=s.value}catch(c){return void i(c)}s.done?e(d):Promise.resolve(d).then(n,a)}function a(t){return function(){var e=this,i=arguments;return new Promise((function(a,r){var o=t.apply(e,i);function s(t){n(o,a,r,s,d,"next",t)}function d(t){n(o,a,r,s,d,"throw",t)}s(void 0)}))}}i("d3b7"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=a},"2b3e":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"detail"},[n("v-uni-image",{staticClass:"back-btn",attrs:{src:i("97fe"),mode:"widthFix"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goback.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"F1 detail-image"},[n("v-uni-image",{staticClass:"F1-bg",attrs:{src:i("ae6d"),mode:"widthFix"}}),n("v-uni-image",{staticClass:"F1-goods-img detail-img",attrs:{src:t.detailListArr.file_path,mode:"widthFix"}}),n("v-uni-text",{staticClass:"F1-goods-name"},[t._v(t._s(t.detailListArr.goods_name))]),n("v-uni-text",{staticClass:"F1-goods-bottom"},[t._v("AC235687#12/20")])],1),n("v-uni-view",{staticClass:"list"},[n("v-uni-image",{staticClass:"list-bg",attrs:{src:i("83fa"),mode:"heightFix"}}),n("v-uni-view",{staticClass:"list-item"},[n("v-uni-view",{staticClass:"icon-left"},[n("v-uni-view",{staticClass:"list-text"},[t._v("收藏者")])],1),n("v-uni-text",{staticClass:"icon-right"},[t._v("***")])],1),n("v-uni-view",{staticClass:"list-item"},[n("v-uni-view",{staticClass:"icon-left"},[n("v-uni-view",{staticClass:"list-text"},[t._v("生成时间")])],1),n("v-uni-text",{staticClass:"icon-right"},[t._v("2022.06.09 18:40:34")])],1),n("v-uni-view",{staticClass:"list-item"},[n("v-uni-view",{staticClass:"icon-left"},[n("v-uni-view",{staticClass:"list-text"},[t._v("藏品**值")])],1),n("v-uni-text",{staticClass:"icon-right"},[t._v("***")])],1),n("v-uni-view",{staticClass:"list-item"},[n("v-uni-view",{staticClass:"icon-left"},[n("v-uni-view",{staticClass:"list-text"})],1),n("v-uni-text",{staticClass:"icon-right"},[t._v("作品源自:***********")])],1)],1),n("v-uni-view",{staticClass:"story-title"},[t._v("藏品故事")]),n("v-uni-view",{staticClass:"story"},[n("v-uni-view",{staticClass:"story-content"},[n("v-uni-rich-text",{attrs:{nodes:t.detailListArr.content}})],1)],1),t.detailType?n("v-uni-view",{staticClass:"submit"},[1==t.type?n("v-uni-view",{staticClass:"sub-price"},[t._v("￥"+t._s(t.detailListArr.goods_price_min))]):n("v-uni-view",{staticClass:"sub-price"},[t._v("￥"+t._s(t.detailListArr.price))]),t.userinfo.userId?[t.showIcon&&t.detailListArr.stock_num>0?n("v-uni-view",{staticClass:"sub-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.buy(t.detailListArr.goods_id)}}},[t._v("立即购买")]):t._e(),t.showIcon&&0==t.detailListArr.stock_num?n("v-uni-view",{staticClass:"sub-btn"},[t._v("已售罄")]):t._e(),t.showIcon?t._e():n("v-uni-view",{staticClass:"sub-btn timeIcon"},[n("v-uni-view",{staticClass:"icon"},[n("v-uni-image",{attrs:{src:i("3ffc"),mode:""}})],1),n("v-uni-view",[t._v(t._s(t.startTime))])],1)]:[n("v-uni-view",{staticClass:"sub-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.login.apply(void 0,arguments)}}},[t._v("暂未登录")])]],2):t._e(),t.showCode?n("v-uni-view",{staticClass:"back",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showCode=!1}}},[n("v-uni-view",{staticClass:"change",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[n("v-uni-view",{staticClass:"title"},[t._v("填写验证码")]),n("v-uni-input",{staticClass:"ipt",attrs:{type:"text",value:"",placeholder:"请输入验证码"},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}}),n("v-uni-view",{staticClass:"yes",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submitCode.apply(void 0,arguments)}}},[t._v("确定")])],1)],1):t._e(),t.payType?n("v-uni-view",{staticClass:"back",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.payType=!1}}},[n("v-uni-view",{staticClass:"change",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[n("v-uni-view",{staticClass:"title"},[t._v("选择支付方式")]),t._l(t.payArr,(function(e,i){return n("v-uni-view",{key:i,staticClass:"item",class:i==t.payIndex?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeType(i)}}},[n("v-uni-view",{staticClass:"img"},[n("v-uni-image",{attrs:{src:e.image,mode:""}})],1),n("v-uni-view",[t._v(t._s(e.name))])],1)})),t.idcar?n("v-uni-view",{staticStyle:{"margin-top":"10rpx",color:"#939291"}},[t._v("已选择银行卡")]):t._e(),n("v-uni-view",{staticClass:"yes",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submit.apply(void 0,arguments)}}},[t._v("确定")])],2)],1):t._e(),n("v-uni-form",{attrs:{id:"alipaysubmit",name:"alipaysubmit",action:"支付宝地址",method:"POST"}})],1)},r=[]},"34bf":function(t,e,i){var n=i("f262");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("0e483a12",n,!0,{sourceMap:!1,shadowMode:!1})},"3ffc":function(t,e,i){t.exports=i.p+"static/img/shijian.097b12e1.png"},"4d14":function(t,e,i){"use strict";function n(t){var e;return function(){var i=this,n=arguments;e&&clearTimeout(e),e=setTimeout((function(){t.apply(i,n)}),e)}}function a(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function r(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function o(t){var e=t.replace(/<img[^>]*>/gi,(function(t,e){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return e=e.replace(/style="[^"]+"/gi,(function(t,e){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),e=e.replace(/<br[^>]*\/>/gi,""),e=e.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),e}i("ac1f"),i("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s={debounce:n,checkIdCard:a,checkName:r,formatRichText:o};e.default=s},"4d90":function(t,e,i){"use strict";var n=i("23e7"),a=i("0ccb").start,r=i("9a0c");n({target:"String",proto:!0,forced:r},{padStart:function(t){return a(this,t,arguments.length>1?arguments[1]:void 0)}})},"5df3":function(t,e,i){"use strict";var n=i("34bf"),a=i.n(n);a.a},"5eb3":function(t,e,i){t.exports=i.p+"static/img/story.a6dd6950.png"},"83fa":function(t,e,i){t.exports=i.p+"static/img/img12.4bde8af9.png"},8852:function(t,e,i){t.exports=i.p+"static/img/story_03.8252b782.png"},"96cf":function(t,e){!function(e){"use strict";var i,n=Object.prototype,a=n.hasOwnProperty,r="function"===typeof Symbol?Symbol:{},o=r.iterator||"@@iterator",s=r.asyncIterator||"@@asyncIterator",d=r.toStringTag||"@@toStringTag",c="object"===typeof t,u=e.regeneratorRuntime;if(u)c&&(t.exports=u);else{u=e.regeneratorRuntime=c?t.exports:{},u.wrap=b;var l="suspendedStart",f="suspendedYield",h="executing",g="completed",p={},v={};v[o]=function(){return this};var m=Object.getPrototypeOf,w=m&&m(m(P([])));w&&w!==n&&a.call(w,o)&&(v=w);var y=_.prototype=k.prototype=Object.create(v);A.prototype=y.constructor=_,_.constructor=A,_[d]=A.displayName="GeneratorFunction",u.isGeneratorFunction=function(t){var e="function"===typeof t&&t.constructor;return!!e&&(e===A||"GeneratorFunction"===(e.displayName||e.name))},u.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,_):(t.__proto__=_,d in t||(t[d]="GeneratorFunction")),t.prototype=Object.create(y),t},u.awrap=function(t){return{__await:t}},T(C.prototype),C.prototype[s]=function(){return this},u.AsyncIterator=C,u.async=function(t,e,i,n){var a=new C(b(t,e,i,n));return u.isGeneratorFunction(e)?a:a.next().then((function(t){return t.done?t.value:a.next()}))},T(y),y[d]="Generator",y[o]=function(){return this},y.toString=function(){return"[object Generator]"},u.keys=function(t){var e=[];for(var i in t)e.push(i);return e.reverse(),function i(){while(e.length){var n=e.pop();if(n in t)return i.value=n,i.done=!1,i}return i.done=!0,i}},u.values=P,S.prototype={constructor:S,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=i,this.done=!1,this.delegate=null,this.method="next",this.arg=i,this.tryEntries.forEach(R),!t)for(var e in this)"t"===e.charAt(0)&&a.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=i)},stop:function(){this.done=!0;var t=this.tryEntries[0],e=t.completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function n(n,a){return s.type="throw",s.arg=t,e.next=n,a&&(e.method="next",e.arg=i),!!a}for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r],s=o.completion;if("root"===o.tryLoc)return n("end");if(o.tryLoc<=this.prev){var d=a.call(o,"catchLoc"),c=a.call(o,"finallyLoc");if(d&&c){if(this.prev<o.catchLoc)return n(o.catchLoc,!0);if(this.prev<o.finallyLoc)return n(o.finallyLoc)}else if(d){if(this.prev<o.catchLoc)return n(o.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<o.finallyLoc)return n(o.finallyLoc)}}}},abrupt:function(t,e){for(var i=this.tryEntries.length-1;i>=0;--i){var n=this.tryEntries[i];if(n.tryLoc<=this.prev&&a.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var r=n;break}}r&&("break"===t||"continue"===t)&&r.tryLoc<=e&&e<=r.finallyLoc&&(r=null);var o=r?r.completion:{};return o.type=t,o.arg=e,r?(this.method="next",this.next=r.finallyLoc,p):this.complete(o)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),p},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var i=this.tryEntries[e];if(i.finallyLoc===t)return this.complete(i.completion,i.afterLoc),R(i),p}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var i=this.tryEntries[e];if(i.tryLoc===t){var n=i.completion;if("throw"===n.type){var a=n.arg;R(i)}return a}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,n){return this.delegate={iterator:P(t),resultName:e,nextLoc:n},"next"===this.method&&(this.arg=i),p}}}function b(t,e,i,n){var a=e&&e.prototype instanceof k?e:k,r=Object.create(a.prototype),o=new S(n||[]);return r._invoke=L(t,i,o),r}function x(t,e,i){try{return{type:"normal",arg:t.call(e,i)}}catch(n){return{type:"throw",arg:n}}}function k(){}function A(){}function _(){}function T(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function C(t){function e(i,n,r,o){var s=x(t[i],t,n);if("throw"!==s.type){var d=s.arg,c=d.value;return c&&"object"===typeof c&&a.call(c,"__await")?Promise.resolve(c.__await).then((function(t){e("next",t,r,o)}),(function(t){e("throw",t,r,o)})):Promise.resolve(c).then((function(t){d.value=t,r(d)}),(function(t){return e("throw",t,r,o)}))}o(s.arg)}var i;function n(t,n){function a(){return new Promise((function(i,a){e(t,n,i,a)}))}return i=i?i.then(a,a):a()}this._invoke=n}function L(t,e,i){var n=l;return function(a,r){if(n===h)throw new Error("Generator is already running");if(n===g){if("throw"===a)throw r;return j()}i.method=a,i.arg=r;while(1){var o=i.delegate;if(o){var s=E(o,i);if(s){if(s===p)continue;return s}}if("next"===i.method)i.sent=i._sent=i.arg;else if("throw"===i.method){if(n===l)throw n=g,i.arg;i.dispatchException(i.arg)}else"return"===i.method&&i.abrupt("return",i.arg);n=h;var d=x(t,e,i);if("normal"===d.type){if(n=i.done?g:f,d.arg===p)continue;return{value:d.arg,done:i.done}}"throw"===d.type&&(n=g,i.method="throw",i.arg=d.arg)}}}function E(t,e){var n=t.iterator[e.method];if(n===i){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=i,E(t,e),"throw"===e.method))return p;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return p}var a=x(n,t.iterator,e.arg);if("throw"===a.type)return e.method="throw",e.arg=a.arg,e.delegate=null,p;var r=a.arg;return r?r.done?(e[t.resultName]=r.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=i),e.delegate=null,p):r:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,p)}function I(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function R(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function S(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(I,this),this.reset(!0)}function P(t){if(t){var e=t[o];if(e)return e.call(t);if("function"===typeof t.next)return t;if(!isNaN(t.length)){var n=-1,r=function e(){while(++n<t.length)if(a.call(t,n))return e.value=t[n],e.done=!1,e;return e.value=i,e.done=!0,e};return r.next=r}}return{next:j}}function j(){return{value:i,done:!0}}}(function(){return this||"object"===typeof self&&self}()||Function("return this")())},"97fe":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAApCAYAAAAxmNlDAAAACXBIWXMAAAsTAAALEwEAmpwYAAABJUlEQVRIibXXMUoDQRTG8X92cwEhXWy9gJVgZZXKMoW4pefSRiyUnEEQQu6gjYTYeQFB/CxkYR2yu/PmvTxY2P1m+TEzDDPMRBKBdQp8AlskRTxTSff6qy9Jl1Hoo/7XqnIOfQo8AMsk30T3VJLuJNXR6K2kWhIHQUvgLNQKZ6MW2ITmwmY0By5Cx+BidAh2oX2wG90HD6FVLprCYWgXDkVbuI5GW7iJRiVRAWfJJv0C3AA/nhOgAt6T7By48qAASDqS9JpMxbeka89UtC/H0Xj3IxRPgzB8XxiC9zW48aFGFz72QzGeM6w+vPHCRbhlCZlw09q04FY4Gy+Bs/BSeBT3wEP4YiK5b01z4Bk46WRP3jsIwAdwAbx1snVEj9uaAQ2wA1a/TaCC80ztV/MAAAAASUVORK5CYII="},"9a0c":function(t,e,i){var n=i("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(n)},ae6d:function(t,e,i){t.exports=i.p+"static/img/img11.ce83959c.png"},bd27:function(t,e,i){"use strict";i.r(e);var n=i("c18d"),a=i.n(n);for(var r in n)"default"!==r&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a},c18d:function(t,e,i){"use strict";var n=i("4ea4");i("99af"),i("d3b7"),i("e25e"),i("ac1f"),i("25f0"),i("4d90"),i("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("96cf");var a,r,o,s,d=n(i("1da1")),c=n(i("4d14")),u=n(i("bb56")),l={data:function(){return{detailImg:i("8852"),storyImg:i("5eb3"),noticeText:"数字藏品为虚拟数字商品，而非实物，仅限实名认证为年满18周岁的中国大陆用户购买。数字藏品的版权由发行方或原创者拥有，除另行取得版权拥有者书面同意外，用户不得将数字藏品用于任何商业用途。本商品一经售出，不支持退换。本商品源文件不支持本地下载。请勿对数字藏品进行炒作、场外交易、欺诈，或以任何其他非法方式进行使用。",detailListArr:{file_path:"",goods_name:"",stock_total:"",content:"",author:"",goods_address:"",logo:"",startTime:"",goods_price_min:"",price:"",goods_id:""},detailType:!0,payType:!1,payArr:[{type:"jdpay",name:"京东支付",image:"/static/jd.jpg"},{type:"jdpay",name:"银行卡支付",image:"/static/jd.jpg"}],payIndex:0,showIcon:!1,startTime:"",timer:null,userinfo:"",type:1,twoPay:!1,newResult:!0,idcar:"",showCode:!1,code:"",requestNum:""}},mounted:function(){},onLoad:function(t){var e=this;console.log(t),2==t.type?(this.type=2,this.$request("get","order/details",{transactionId:t.id}).then((function(t){console.log("res",t),t.data.content=c.default.formatRichText(t.data.content),e.detailListArr=t.data,e.showIcon=!0,e.twoPay=!0})).catch((function(t){console.log("err",t)}))):(uni.showLoading({title:"加载中..."}),this.getDetailList(t.id),1==t.type&&(this.detailType=!1)),this.userinfo=uni.getStorageSync("userinfo")},methods:{detailList:function(){},getDetailList:function(t){var e=this;return(0,d.default)(regeneratorRuntime.mark((function i(){return regeneratorRuntime.wrap((function(i){while(1)switch(i.prev=i.next){case 0:e.$request("get","Goods/goodsDetails",{goodsid:t}).then((function(t){console.log("ress",t),t.data.goodsdetails.content=c.default.formatRichText(t.data.goodsdetails.content),e.detailListArr=t.data.goodsdetails;var i=new Date(t.data.goodsdetails.startTime.replace(/-/g,"/")).getTime(),n=(new Date).getTime();if(n>i)return e.showIcon=!0;console.log("ppp",i,i-n),e.timer=setInterval((function(){var t=(new Date).getTime(),n=i-t;e.showIcon=!1,uni.hideLoading(),n-1>1?(n-=1,e.startTime=e.formateSeconds(n)):(e.showIcon=!0,clearInterval(e.timer))}),1e3)})).catch((function(t){console.log("err",t)}));case 1:case"end":return i.stop()}}),i)})))()},login:function(){uni.navigateTo({url:"/pages/login/index"})},formateSeconds:function(t){var e=parseInt(t/1e3),i=0,n=0,a="";return e>60&&(i=parseInt(e/60),e=parseInt(e%60),i>60&&(n=parseInt(i/60),i=parseInt(i%60))),a="".concat(n.toString().padStart(2,"0"),":").concat(i.toString().padStart(2,"0"),":").concat(e.toString().padStart(2,"0")),a},submitCode:function(){var t=this;if(""==this.code)return this.$showToast("请输入验证码");uni.showLoading({}),uni.request({url:"".concat(u.default.URL,"jdPay/quickPay"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{code:this.code,bank_id:this.idcar,requestNum:this.requestNum},success:function(e){if(uni.hideLoading(),500==e.data.status)return t.newResult=!0,t.$showToast(e.data.message);t.$showToast(e.data.message),t.code="",t.showCode=!1}})},goback:function(){uni.navigateBack({delta:1})},submit:function(){var t=this;if(2==this.type)if(0==this.payIndex)uni.request({url:"".concat(u.default.URL,"order/transaction"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsId:this.detailListArr.goods_id,isBox:0,pay_type:this.payArr[this.payIndex].type,transactionId:this.detailListArr.transaction_id},success:function(e){if(500==e.data.status)return t.newResult=!0,t.$showToast(e.data.message);console.log("dd",e);var i=e.data;t.newResult=!0,console.log("asd",i),location.href=i}});else if(""!=this.idcar){if(uni.showLoading({}),console.log("asd",this.idcar),!this.newResult)return;this.newResult=!1,uni.request({url:"".concat(u.default.URL,"order/transactionBank"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsId:this.detailListArr.goods_id,isBox:0,pay_type:this.payArr[this.payIndex].type,transactionId:this.detailListArr.transaction_id,bank_id:this.idcar},success:function(e){if(uni.hideLoading(),500==e.data.status)return t.newResult=!0,t.$showToast(e.data.message);console.log("dd",e),t.requestNum=e.data.data.requestNum,t.newResult=!0,t.payType=!1,t.showCode=!0,t.$showToast("短信发送成功")}})}else this.$showToast("请选择银行卡"),setTimeout((function(){uni.navigateTo({url:"/pages/user/bankList?type=0"})}),1e3);else if(0==this.payIndex)uni.request({url:"".concat(u.default.URL,"checkout/buy"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsId:this.detailListArr.goods_id,isBox:0,pay_type:this.payArr[this.payIndex].type},success:function(e){if(500==e.data.status)return t.newResult=!0,t.$showToast(e.data.message);console.log("dd",e);var i=e.data;t.newResult=!0,console.log("asd",i),location.href=i}});else if(""!=this.idcar){if(uni.showLoading({}),console.log("asd",this.idcar),!this.newResult)return;this.newResult=!1,uni.request({url:"".concat(u.default.URL,"checkout/buyBank"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsId:this.detailListArr.goods_id,isBox:0,pay_type:this.payArr[this.payIndex].type,bank_id:this.idcar},success:function(e){if(uni.hideLoading(),500==e.data.status)return t.newResult=!0,t.$showToast(e.data.message);console.log("dd",e),t.requestNum=e.data.data.requestNum,t.newResult=!0,t.payType=!1,t.showCode=!0,t.$showToast("短信发送成功")}})}else this.$showToast("请选择银行卡"),setTimeout((function(){uni.navigateTo({url:"/pages/user/bankList?type=0"})}),1e3)},createControls:function(){s=new OrbitControls(o,a.domElement),s.autoRotate=!0,s.autoRotateSpeed=5},leadModel:function(){var t=this,e=new GLTFLoader;e.load(t.detailListArr.images,(function(t){r.add(t.scene),uni.hideLoading()}))},initThree:function(){console.log("打印场景API",THREE.Scene),r=new THREE.Scene;var t=new THREE.AmbientLight(16777215);r.add(t);var e=window.innerWidth,i=window.innerHeight,n=(i=360,e/i);o=new THREE.PerspectiveCamera(45,n,1,1e4),o.position.set(0,0,4),o.lookAt(new THREE.Vector3(0,0,0)),a=new THREE.WebGLRenderer({antialias:!0}),a.outputEncoding=THREE.sRGBEncoding,a.setSize(e,i);var s=document.getElementById("threeView");s.appendChild(a.domElement),a.render(r,o),this.render()},render:function(){var t=this;requestAnimationFrame((function(){s.update(),t.render()})),a.render(r,o)},changeType:function(t){this.payIndex=t},buy:function(t){if(!this.userinfo.idcar)return this.$showToast("请完成实名认证");this.payType=!0}},onUnload:function(){clearInterval(this.timer)}};e.default=l},f262:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-68d8dfe2]{background-color:#18191b}.detail[data-v-68d8dfe2]{width:100%;padding:0 0 %?120?%;box-sizing:border-box}.detail .back-btn[data-v-68d8dfe2]{width:%?25?%;height:%?25?%;position:absolute;left:%?40?%;top:%?60?%;z-index:1000}.detail .detail-image[data-v-68d8dfe2]{width:100%;text-align:center;-webkit-transform:translateZ(0);transform:translateZ(0)}.detail .detail-image .detail-img[data-v-68d8dfe2]{-webkit-animation:detail-data-v-68d8dfe2 20s infinite;animation:detail-data-v-68d8dfe2 20s infinite;-webkit-transform:translateZ(%?2?%);transform:translateZ(%?2?%)}.detail .F1[data-v-68d8dfe2]{width:100%;position:relative}.detail .F1 .F1-bg[data-v-68d8dfe2]{width:100%;position:relative;z-index:1}.detail .F1 .F1-goods-img[data-v-68d8dfe2]{width:50%;position:absolute;z-index:2;left:25%;top:25%}.detail .F1 .F1-goods-name[data-v-68d8dfe2]{width:60%;left:20%;display:block;position:absolute;bottom:%?120?%;z-index:3;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#fff;font-size:%?40?%}.detail .F1 .F1-goods-bottom[data-v-68d8dfe2]{width:40%;left:30%;display:block;position:absolute;bottom:%?50?%;padding:%?5?% %?20?%;box-sizing:border-box;border-radius:%?10?%;z-index:3;text-align:center;background:linear-gradient(90deg,#ebc696,#f4dfb7);color:#8b5704}.detail .list[data-v-68d8dfe2]{width:90%;margin:%?20?% auto;background:#36393c;border-radius:%?20?%;position:relative}.detail .list .list-bg[data-v-68d8dfe2]{display:block;height:%?150?%;position:absolute;right:%?120?%;top:%?80?%}.detail .list .list-item[data-v-68d8dfe2]{display:flex;justify-content:space-between;align-items:center;margin-bottom:%?20?%;padding:%?30?% %?30?%;border-bottom:%?1?% solid #434548;color:#fff;font-size:15px;color:#9d9d9d}.detail .list .list-item[data-v-68d8dfe2]:last-of-type{border:none}.detail .list .icon-right[data-v-68d8dfe2]{color:#fff}.detail .story-title[data-v-68d8dfe2]{width:100%;padding:0 %?50?%;font-size:17px;font-weight:700;margin-top:%?50?%;color:#fff}.detail .story[data-v-68d8dfe2]{width:100%;border-radius:%?30?%;margin-top:%?20?%;color:#fff;padding:%?30?% %?50?%;margin-bottom:%?30?%;box-sizing:border-box}.detail .story .story-author[data-v-68d8dfe2]{display:flex;align-items:center;justify-content:space-between;padding:%?30?% %?20?% %?20?%;color:#a3a3a3;font-size:%?35?%}.detail .story .story-author .story-author-text[data-v-68d8dfe2]{color:#fff}.detail .submit[data-v-68d8dfe2]{position:fixed;bottom:0;left:0;width:100%;display:flex;justify-content:space-between;align-items:center;padding:%?20?% %?30?%;background:#181818;color:#fff;font-size:%?50?%;font-weight:700;box-sizing:border-box}.detail .submit .sub-price[data-v-68d8dfe2]{letter-spacing:%?5?%;color:#fecdad;margin-left:%?50?%}.detail .submit .sub-btn[data-v-68d8dfe2]{font-size:%?30?%;background:#f8cdb5;color:#000;border-radius:%?50?%;padding:%?20?% %?60?%;text-align:center}.detail .submit .timeIcon[data-v-68d8dfe2]{display:flex;align-items:center}.detail .submit .timeIcon .icon[data-v-68d8dfe2]{width:%?36?%;height:%?36?%;margin-right:%?6?%}.detail .submit .timeIcon .icon uni-image[data-v-68d8dfe2]{width:100%;height:100%}@-webkit-keyframes detail-data-v-68d8dfe2{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}@keyframes detail-data-v-68d8dfe2{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}.back[data-v-68d8dfe2]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-68d8dfe2]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-68d8dfe2]{text-align:center}.back .change .ipt[data-v-68d8dfe2]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-68d8dfe2]{display:flex;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-68d8dfe2]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-68d8dfe2]{width:100%;height:100%}.back .change .active[data-v-68d8dfe2]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-68d8dfe2]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}body.?%PAGE?%[data-v-68d8dfe2]{background-color:#18191b}',""]),t.exports=e}}]);