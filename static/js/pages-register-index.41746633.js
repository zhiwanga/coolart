(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-register-index"],{"142d":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.login[data-v-35507bf5]{width:100%;height:100vh;padding:%?150?% %?40?% 0;box-sizing:border-box;background-color:#fff}.login .login-close[data-v-35507bf5]{width:%?50?%;position:absolute;top:%?120?%;left:%?50?%}.login .login-title[data-v-35507bf5]{font-size:%?60?%;color:#fff;letter-spacing:%?5?%}.login .login-phone[data-v-35507bf5]{width:100%;font-size:%?32?%;letter-spacing:%?8?%;color:#000;background-color:#fff;padding:%?10?% %?10?%;border-radius:%?10?%}.login .input-text[data-v-35507bf5]{margin-top:%?30?%;color:#666;letter-spacing:%?6?%;font-size:%?30?%}.login .title[data-v-35507bf5]{margin-top:%?16?%;margin-bottom:%?16?%}.login .input-texts[data-v-35507bf5]{margin-top:%?30?%;color:#f23636;letter-spacing:%?6?%;font-size:%?30?%;text-align:right;border-bottom:%?2?% solid #f23636;display:inline-block}.login .login-submit[data-v-35507bf5]{border-radius:%?30?%;background-color:#000;height:%?80?%;line-height:%?80?%;text-align:center;margin:%?80?% auto 0;font-size:%?32?%;color:#fff}.login .login-sub[data-v-35507bf5]{background-color:#000;color:#fff}.logo[data-v-35507bf5]{width:%?160?%;height:%?160?%;margin:0 auto;border-radius:%?20?%;overflow:hidden}.logo uni-image[data-v-35507bf5]{width:100%;height:100%}.name[data-v-35507bf5]{text-align:center;margin-top:%?20?%;margin-bottom:%?60?%}.box[data-v-35507bf5]{box-sizing:border-box;margin-bottom:33px;padding:27px 17px 32px;background:#f9f9f9;border:1px solid #f4f2f2;border-radius:11px;width:100%}.code_box[data-v-35507bf5]{display:flex;justify-content:space-between;margin-top:14px;padding:%?10?% %?10?%;background-color:#fff;border-radius:%?10?%}.code_box .ipt[data-v-35507bf5]{font-size:16px;color:grey}.code_box .code[data-v-35507bf5]{padding:2px 6px;background-color:grey;border-radius:4px;color:#e2d6d6}',""]),t.exports=e},"375d":function(t,e,i){"use strict";var n=i("b1c4"),o=i.n(n);o.a},"747d":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"login"},[n("v-uni-view",{staticClass:"logo"},[n("v-uni-image",{attrs:{src:i("940e"),mode:""}})],1),n("v-uni-view",{staticClass:"name"},[t._v("天琪数藏")]),n("v-uni-view",{staticClass:"box"},[n("v-uni-view",{staticClass:"title"},[t._v("手机号")]),n("v-uni-input",{staticClass:"login-phone",attrs:{type:"number",focus:!0,maxlength:"11",placeholder:"请输入手机号"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.getInput.apply(void 0,arguments)}},model:{value:t.phone,callback:function(e){t.phone=e},expression:"phone"}}),n("v-uni-view",{staticClass:"title"},[t._v("验证码")]),n("v-uni-view",{staticClass:"code_box"},[n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{"placeholder-style":"font-size:16px;",type:"text",value:"",placeholder:"短信验证码"},model:{value:t.code,callback:function(e){t.code=e},expression:"code"}})],1),n("v-uni-view",{staticClass:"code",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getCode.apply(void 0,arguments)}}},[t._v(t._s(t.codeTip))])],1),n("v-uni-view",{staticClass:"title"},[t._v("密码")]),n("v-uni-input",{staticClass:"login-phone",attrs:{type:"text",placeholder:"请输入密码"},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),n("v-uni-view",{staticClass:"title"},[t._v("邀请ID")]),n("v-uni-view",{staticClass:"code_box"},[n("v-uni-view",{staticClass:"ipt"},[n("v-uni-input",{attrs:{"placeholder-style":"font-size:16px;",type:"text",value:"",placeholder:"请输入邀请人ID"},model:{value:t.invite,callback:function(e){t.invite=e},expression:"invite"}})],1)],1)],1),n("v-uni-view",{staticClass:"login-submit",class:t.subType?"login-sub":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.loginSubmit.apply(void 0,arguments)}}},[t._v("注册")]),n("v-uni-view",{staticStyle:{display:"flex","justify-content":"space-between"}},[n("v-uni-view")],1)],1)},a=[]},"7b57":function(t,e,i){"use strict";function n(t){var e;return function(){var i=this,n=arguments;e&&clearTimeout(e),e=setTimeout((function(){t.apply(i,n)}),e)}}function o(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function a(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function s(t){var e=t.replace(/<img[^>]*>/gi,(function(t,e){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return e=e.replace(/style="[^"]+"/gi,(function(t,e){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),e=e.replace(/<br[^>]*\/>/gi,""),e=e.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),e}i("ac1f"),i("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r={debounce:n,checkIdCard:o,checkName:a,formatRichText:s};e.default=r},"940e":function(t,e,i){t.exports=i.p+"static/img/logo.427bfef0.png"},b1c4:function(t,e,i){var n=i("142d");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("4777a62b",n,!0,{sourceMap:!1,shadowMode:!1})},c4e4:function(t,e,i){"use strict";i.r(e);var n=i("ec89"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},d9aa:function(t,e,i){"use strict";i.r(e);var n=i("747d"),o=i("c4e4");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("375d");var s,r=i("f0c5"),c=Object(r["a"])(o["default"],n["b"],n["c"],!1,null,"35507bf5",null,!1,n["a"],s);e["default"]=c.exports},ec89:function(t,e,i){"use strict";var n=i("4ea4");i("c975"),i("d3b7"),i("ac1f"),i("1276"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(i("7b57")),a={data:function(){return{phone:null,subType:!0,codeTip:"获取验证码",inter:null,code:"",invite:"",password:""}},onLoad:function(t){console.log("lll",t),t.code&&(this.invite=t.code)},methods:{loginClose:function(){uni.switchTab({url:"../index/index"})},getCode:function(){var t=this;if(""==this.phone||!/^1(3|4|5|6|7|8|9)\d{9}$/.test(this.phone))return this.$showToast("请输入正确的手机号");if("获取验证码"==this.codeTip){var e=60;this.$request("post","Captcha/sendSmsCaptcha",{form:{mobile:this.phone,type:0}}).then((function(i){t.$showToast(i.message),t.inter=setInterval((function(){e-1<=0?(t.inter=clearInterval(t.inter),t.codeTip="获取验证码"):(e-=1,t.codeTip=e+"s后重新获取")}),1e3)})).catch((function(e){t.$showToast(e.message)}))}},register:function(){uni.navigateTo({url:"/pages/register/index"})},getRequest:function(){var t=window.location.href;console.log("kk",t);var e=new Object;if(-1!=t.indexOf("?")){var i=t.substr(1);strs=i.split("&");for(var n=0;n<strs.length;n++)e[strs[n].split("=")[0]]=unescape(strs[n].split("=")[1])}return e},getInput:function(t){t.detail.value.length>=11?this.subType=!1:this.subType=!0},loginSubmit:o.default.debounce((function(){var t=this;return this.subType?this.$showToast("请输入手机号"):""==this.code?this.$showToast("请输入验证码"):""==this.password?this.$showToast("请输入密码"):(uni.showLoading({title:"登录中"}),void this.$request("POST","Passport/registerNre",{phone:this.phone,password:this.password,smsCode:this.code,code:this.invite}).then((function(e){console.log("suc",e),t.$showToast(e.message);var i=e.data.phone.length;e.data.phone=e.data.phone.substring(0,3)+"****"+e.data.phone.substring(i-3,i),e.data.file_path?e.data.file_path=t.$config.ImgUrl+e.data.file_path:e.data.file_path="https://sc.zhongyuansc.net/uploads/headimg/headimg.png",uni.setStorageSync("userinfo",e.data),uni.setStorageSync("token",e.data.token),uni.setStorageSync("login",1),setTimeout((function(){uni.switchTab({url:"../index/index"})}),800)})).catch((function(e){t.$showToast(e.message)})).finally((function(){uni.hideLoading()})))}))},onUnload:function(){this.inter=clearInterval(this.inter)}};e.default=a}}]);