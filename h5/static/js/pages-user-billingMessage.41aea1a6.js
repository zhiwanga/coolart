(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-billingMessage"],{"1da1":function(t,e,n){"use strict";function r(t,e,n,r,i,a,o){try{var s=t[a](o),c=s.value}catch(u){return void n(u)}s.done?e(c):Promise.resolve(c).then(r,i)}function i(t){return function(){var e=this,n=arguments;return new Promise((function(i,a){var o=t.apply(e,n);function s(t){r(o,i,a,s,c,"next",t)}function c(t){r(o,i,a,s,c,"throw",t)}s(void 0)}))}}n("d3b7"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=i},"242e":function(t,e,n){"use strict";n.r(e);var r=n("63d5"),i=n.n(r);for(var a in r)"default"!==a&&function(t){n.d(e,t,(function(){return r[t]}))}(a);e["default"]=i.a},"470c":function(t,e,n){"use strict";n.r(e);var r=n("5534"),i=n("242e");for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);n("d80a");var o,s=n("f0c5"),c=Object(s["a"])(i["default"],r["b"],r["c"],!1,null,"7973a7d9",null,!1,r["a"],o);e["default"]=c.exports},5534:function(t,e,n){"use strict";var r;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return r}));var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",[n("v-uni-view",{staticStyle:{width:"100vw",height:"64rpx","text-align":"center",position:"relative",top:"50%",transform:"translateY(50%)"}},[n("v-uni-text",{staticStyle:{"font-size":"32rpx"}},[t._v("请添加实名认证本人的银行卡")])],1),n("v-uni-view",{staticClass:"seting-tab"},[n("v-uni-view",{staticClass:"flex_ipt"},[n("v-uni-view",{staticClass:"txt"},[t._v("持卡人")]),n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{disabled:"true"},model:{value:t.bankUserName,callback:function(e){t.bankUserName=e},expression:"bankUserName"}})],1)],1),n("v-uni-view",{staticClass:"flex_ipt"},[n("v-uni-view",{staticClass:"txt"},[t._v("身份证号")]),n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{disabled:"true"},model:{value:t.idCardNumber,callback:function(e){t.idCardNumber=e},expression:"idCardNumber"}})],1)],1),n("v-uni-view",{staticClass:"flex_ipt"},[n("v-uni-view",{staticClass:"txt"},[t._v("银行卡号")]),n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{type:"text",placeholder:"请输入持卡人本人银行卡"},model:{value:t.bankNumber,callback:function(e){t.bankNumber=e},expression:"bankNumber"}})],1)],1),n("v-uni-view",{staticClass:"flex_ipt"},[n("v-uni-view",{staticClass:"txt"},[t._v("预留手机号")]),n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{type:"text",placeholder:"请输入预留手机号"},model:{value:t.mobile,callback:function(e){t.mobile=e},expression:"mobile"}})],1)],1),n("v-uni-view",{staticClass:"submit",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submit.apply(void 0,arguments)}}},[t._v("确认绑定")])],1),t.payFlag?n("v-uni-view",{staticClass:"back",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.payFlag=!1}}},[n("v-uni-view",{staticClass:"change",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[n("v-uni-view",{staticClass:"title"},[t._v("二级密码")]),n("v-uni-view",{staticClass:"ipt",staticStyle:{height:"60rpx"}},[n("v-uni-input",{attrs:{password:!0,maxlength:"20",placeholder:"请输入二级密码"},model:{value:t.paymentPwd,callback:function(e){t.paymentPwd=e},expression:"paymentPwd"}})],1),n("v-uni-view",{staticClass:"yes",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.checkPwd()}}},[t._v("确定")])],1)],1):t._e()],1)},a=[]},"5bb9":function(t,e,n){var r=n("de24");r.__esModule&&(r=r.default),"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var i=n("4f06").default;i("13cec6be",r,!0,{sourceMap:!1,shadowMode:!1})},"63d5":function(t,e,n){"use strict";var r=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("96cf");var i=r(n("1da1")),a=(r(n("743a")),n("f6d3")),o={data:function(){return{mobile:"",bankNumber:"",payFlag:!1,paymentPwd:"",bankUserName:"",idCardNumber:"",userinfo:""}},onShow:function(){this.getUserInfo()},methods:{getUserInfo:function(){var t=this;return(0,i.default)(regeneratorRuntime.mark((function e(){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=t,n.$request("post","user/userinfo").then((function(t){200==t.status&&(n.userinfo=t.data,n.bankUserName=n.userinfo.idname,n.idCardNumber=n.userinfo.idcar)}));case 2:case"end":return e.stop()}}),e)})))()},checkPwd:function(){var t=this;if(!this.paymentPwd||""==this.paymentPwd)return this.$showToast("填写二级密码");this.$request("post","user/collectionEdit",{cardno:this.bankNumber,mobile:this.mobile,cipcont:(0,a.encrypts)(this.paymentPwd)}).then((function(e){console.log("asd",e);var n=e.status;200==n?(t.$showToast("绑卡成功"),t.payFlag=!1,setTimeout((function(){uni.navigateBack({delta:1})}),200)):(t.paymentPwd=!1,t.$showToast(e.message))})).catch((function(e){t.$showToast(e.message)}))},submit:function(){return""!=this.mobile&&/^1(3|4|5|6|7|8|9)\d{9}$/.test(this.mobile)?""==this.bankNumber||""==this.mobile?this.$showToast("请完善银行卡收款信息"):void(this.userinfo.trade_pass?this.payFlag=!0:uni.navigateTo({url:"/pages/user/secondary"})):this.$showToast("请输入正确的手机号")}}};e.default=o},"96cf":function(t,e){!function(e){"use strict";var n,r=Object.prototype,i=r.hasOwnProperty,a="function"===typeof Symbol?Symbol:{},o=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",c=a.toStringTag||"@@toStringTag",u="object"===typeof t,l=e.regeneratorRuntime;if(l)u&&(t.exports=l);else{l=e.regeneratorRuntime=u?t.exports:{},l.wrap=y;var d="suspendedStart",h="suspendedYield",f="executing",p="completed",v={},g={};g[o]=function(){return this};var b=Object.getPrototypeOf,m=b&&b(b($([])));m&&m!==r&&i.call(m,o)&&(g=m);var w=E.prototype=k.prototype=Object.create(g);_.prototype=w.constructor=E,E.constructor=_,E[c]=_.displayName="GeneratorFunction",l.isGeneratorFunction=function(t){var e="function"===typeof t&&t.constructor;return!!e&&(e===_||"GeneratorFunction"===(e.displayName||e.name))},l.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,E):(t.__proto__=E,c in t||(t[c]="GeneratorFunction")),t.prototype=Object.create(w),t},l.awrap=function(t){return{__await:t}},L(C.prototype),C.prototype[s]=function(){return this},l.AsyncIterator=C,l.async=function(t,e,n,r){var i=new C(y(t,e,n,r));return l.isGeneratorFunction(e)?i:i.next().then((function(t){return t.done?t.value:i.next()}))},L(w),w[c]="Generator",w[o]=function(){return this},w.toString=function(){return"[object Generator]"},l.keys=function(t){var e=[];for(var n in t)e.push(n);return e.reverse(),function n(){while(e.length){var r=e.pop();if(r in t)return n.value=r,n.done=!1,n}return n.done=!0,n}},l.values=$,T.prototype={constructor:T,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=n,this.done=!1,this.delegate=null,this.method="next",this.arg=n,this.tryEntries.forEach(O),!t)for(var e in this)"t"===e.charAt(0)&&i.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=n)},stop:function(){this.done=!0;var t=this.tryEntries[0],e=t.completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function r(r,i){return s.type="throw",s.arg=t,e.next=r,i&&(e.method="next",e.arg=n),!!i}for(var a=this.tryEntries.length-1;a>=0;--a){var o=this.tryEntries[a],s=o.completion;if("root"===o.tryLoc)return r("end");if(o.tryLoc<=this.prev){var c=i.call(o,"catchLoc"),u=i.call(o,"finallyLoc");if(c&&u){if(this.prev<o.catchLoc)return r(o.catchLoc,!0);if(this.prev<o.finallyLoc)return r(o.finallyLoc)}else if(c){if(this.prev<o.catchLoc)return r(o.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<o.finallyLoc)return r(o.finallyLoc)}}}},abrupt:function(t,e){for(var n=this.tryEntries.length-1;n>=0;--n){var r=this.tryEntries[n];if(r.tryLoc<=this.prev&&i.call(r,"finallyLoc")&&this.prev<r.finallyLoc){var a=r;break}}a&&("break"===t||"continue"===t)&&a.tryLoc<=e&&e<=a.finallyLoc&&(a=null);var o=a?a.completion:{};return o.type=t,o.arg=e,a?(this.method="next",this.next=a.finallyLoc,v):this.complete(o)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),v},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var n=this.tryEntries[e];if(n.finallyLoc===t)return this.complete(n.completion,n.afterLoc),O(n),v}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var n=this.tryEntries[e];if(n.tryLoc===t){var r=n.completion;if("throw"===r.type){var i=r.arg;O(n)}return i}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,r){return this.delegate={iterator:$(t),resultName:e,nextLoc:r},"next"===this.method&&(this.arg=n),v}}}function y(t,e,n,r){var i=e&&e.prototype instanceof k?e:k,a=Object.create(i.prototype),o=new T(r||[]);return a._invoke=N(t,n,o),a}function x(t,e,n){try{return{type:"normal",arg:t.call(e,n)}}catch(r){return{type:"throw",arg:r}}}function k(){}function _(){}function E(){}function L(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function C(t){function e(n,r,a,o){var s=x(t[n],t,r);if("throw"!==s.type){var c=s.arg,u=c.value;return u&&"object"===typeof u&&i.call(u,"__await")?Promise.resolve(u.__await).then((function(t){e("next",t,a,o)}),(function(t){e("throw",t,a,o)})):Promise.resolve(u).then((function(t){c.value=t,a(c)}),(function(t){return e("throw",t,a,o)}))}o(s.arg)}var n;function r(t,r){function i(){return new Promise((function(n,i){e(t,r,n,i)}))}return n=n?n.then(i,i):i()}this._invoke=r}function N(t,e,n){var r=d;return function(i,a){if(r===f)throw new Error("Generator is already running");if(r===p){if("throw"===i)throw a;return F()}n.method=i,n.arg=a;while(1){var o=n.delegate;if(o){var s=P(o,n);if(s){if(s===v)continue;return s}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(r===d)throw r=p,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);r=f;var c=x(t,e,n);if("normal"===c.type){if(r=n.done?p:h,c.arg===v)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(r=p,n.method="throw",n.arg=c.arg)}}}function P(t,e){var r=t.iterator[e.method];if(r===n){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=n,P(t,e),"throw"===e.method))return v;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return v}var i=x(r,t.iterator,e.arg);if("throw"===i.type)return e.method="throw",e.arg=i.arg,e.delegate=null,v;var a=i.arg;return a?a.done?(e[t.resultName]=a.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=n),e.delegate=null,v):a:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,v)}function j(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function O(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function T(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(j,this),this.reset(!0)}function $(t){if(t){var e=t[o];if(e)return e.call(t);if("function"===typeof t.next)return t;if(!isNaN(t.length)){var r=-1,a=function e(){while(++r<t.length)if(i.call(t,r))return e.value=t[r],e.done=!1,e;return e.value=n,e.done=!0,e};return a.next=a}}return{next:F}}function F(){return{value:n,done:!0}}}(function(){return this||"object"===typeof self&&self}()||Function("return this")())},d80a:function(t,e,n){"use strict";var r=n("5bb9"),i=n.n(r);i.a},de24:function(t,e,n){var r=n("24fb");e=r(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-7973a7d9]{background-color:#fff}.back[data-v-7973a7d9]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-7973a7d9]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-7973a7d9]{text-align:center}.back .change .ipt[data-v-7973a7d9]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-7973a7d9]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-7973a7d9]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-7973a7d9]{width:100%;height:100%}.back .change .active[data-v-7973a7d9]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-7973a7d9]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.seting-tab[data-v-7973a7d9]{width:96%;margin:0 auto;margin-top:%?40?%;padding:%?20?%;box-sizing:border-box;-moz-box-shadow:0 1px 5px #d8d6d6;-webkit-box-shadow:0 1px 5px #d8d6d6;box-shadow:0 1px 5px #d8d6d6;border-radius:%?20?%}.seting-tab .van_tabs[data-v-7973a7d9]{display:flex;font-size:16px;color:#333}.seting-tab .van_tabs .van_tab[data-v-7973a7d9]{width:33.3%;height:%?100?%;line-height:%?100?%;text-align:center}.seting-tab .van_tabs .active[data-v-7973a7d9]{background-color:#f8f8f8}.seting-tab .upload[data-v-7973a7d9]{width:%?160?%;height:%?160?%;border:%?2?% solid #333;border-radius:%?14?%;margin:0 auto;margin-top:%?60?%;line-height:%?160?%;text-align:center;font-size:30px;color:#aaa}.seting-tab .uploadImgW[data-v-7973a7d9]{width:%?160?%;height:%?160?%;border-radius:%?14?%;margin:0 auto;margin-top:%?60?%;overflow:hidden}.seting-tab .flex_ipt[data-v-7973a7d9]{display:flex;padding:%?20?% %?10?%;border-bottom:%?2?% solid #f7f7f7}.seting-tab .flex_ipt .txt[data-v-7973a7d9]{color:#333;width:30%;margin-right:%?10?%}.seting-tab .flex_ipt .ipt[data-v-7973a7d9]{text-align:right;width:65%;color:#000}.seting-tab .submit[data-v-7973a7d9]{width:90%;height:%?70?%;line-height:%?70?%;border-radius:%?16?%;margin:0 auto;margin-top:%?40?%;text-align:center;background:#fdb428}body.?%PAGE?%[data-v-7973a7d9]{background-color:#fff}',""]),t.exports=e}}]);